<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Helper to get packages from the package manager and from the package
 * configuration.
 *
 * @author Roelof Roos
 */
trait DepsListTrait
{
    /**
     * Returns the root dir of the application. Caches response value. Does NOT
     * contain a trailing slash.
     * @return string Absolute path to the root.
     */
    private function getRootDir() : string
    {
        // Store the root staticly, to save some ops
        static $rootDir = null;

        // Return chached value, if any
        if ($rootDir !== null) {
            return $rootDir;
        }

        // Get the response from Yarn about all installed packages in the
        // application root.
        $kernel = $this->getContainer()->get('kernel');
        $rootDir = $kernel->getRootDir();

        // Find out if the Kernel's root dir is actually the root dir.
        if (!is_dir($rootDir . '/doc') && is_dir($rootDir . '/../doc')) {
            $rootDir = dirname($rootDir);
        }

        // Remove all symlinks to the given file.
        $rootDir = realpath($rootDir);

        return $rootDir;
    }

    /**
     * Check if yarn is installed by running `yarn -v`
     *
     * @param  OutputInterface $output Output to report process status to
     * @return bool                    True if Yarn is installed.
     */
    private function isYarnInstalled(OutputInterface $output) : bool
    {
        // Run Yarn, if it errors it's probably not installed
        $helper = $this->getHelper('process');
        $process = ProcessBuilder::create(['yarn', '-v'])->getProcess();

        $helper->run($output, $process);

        // If it managed to run, let everyone know.
        return $process->isSuccessful();
    }

    /**
     * Returns an array of installed packages, with the package names as keys
     * and their versions as values.
     *
     * @param  OutputInterface $output Output to report process status to
     * @return array                   List of packages, with versions.
     */
    private function getListFromYarn(OutputInterface $output) : ?array
    {
        $rootDir = $this->getRootDir();

        // Change to the root of the app
        chdir($rootDir);

        // Get the list from Yarn
        $helper = $this->getHelper('process');
        $process = ProcessBuilder::create([
            'yarn',
            'list',
            '--depth=0',
            '--pure-lockfile',
            '--silent',
            '--json'
        ])->getProcess();

        $helper->run($output, $process);

        // Return null if we didn't get any packages.
        if (!$process->isSuccessful()) {
            return null;
        }

        // Read all lines, Yarn likes outputting a lot on occasion
        $treeData = null;
        $responseLines = explode("\n", $process->getOutput());

        // Look for the line containing valid JSON, that contains data of type
        // 'tree'
        foreach ($responseLines as $line) {
            $line = trim($line);

            $lineJson = json_decode($line, true);
            if (!$lineJson || json_last_error() !== \JSON_ERROR_NONE) {
                continue;
            }

            if (($lineJson['type'] ?? 'none') !== 'tree') {
                continue;
            }

            $treeData = $lineJson['data'] ?? [];
            break;
        }

        // If we don't have a tree, return
        if (empty($treeData)) {
            return null;
        }

        // Get the tree, or null (shouldn't be null though)
        $trees = $treeData['trees'] ?? [];

        // Abort on null
        if (empty($trees)) {
            return null;
        }

        $outData = [];

        // Names are returned as '[name]@[version]', which should be easy.
        // However, NPMJS has introduced namespaces in the form of
        // '@[vendor]/[name]', which means we need to stitch that back together
        // should we encounter one.
        foreach ($trees as $treeObj) {
            $bits = explode('@', $treeObj['name']);
            $version = array_pop($bits);
            $name = implode('@', $bits);
            $outData[$name] = $version;
        }

        // All done :)
        return $outData;
    }

    /**
     * Read Nodejs packages from the Package.json file
     *
     * @return array
     */
    private function getPackageDependencies(string $packageFile) : array
    {
        $rootDir = $this->getRootDir();

        $packagePath = $rootDir . $packageFile;

        if (!file_exists($packagePath)) {
            return [];
        }

        $contents = file_get_contents($packagePath);
        $contents = json_decode($contents, true);

        if (empty($contents) || json_last_error() !== \JSON_ERROR_NONE) {
            return [];
        }

        $results = [];

        foreach (($contents['devDependencies'] ?? []) as $package => $version) {
            $results[$package] = [
                'name' => $package,
                'requested' => $version,
                'dev-only' => true
            ];
        }

        foreach (($contents['dependencies'] ?? []) as $package => $version) {
            $results[strtolower($package)] = [
                'name' => $package,
                'requested' => $version,
                'dev-only' => false
            ];
        }

        return $results;
    }

    /**
     * Returns an enriched list of packages, with installed and requested
     * versions. Uses the package.json file and yarn list.
     *
     * @param  OutputInterface $output
     * @return array
     */
    protected function getDependencies(OutputInterface $output, string $packageFile) : array
    {
        $yarnPackages = $this->getListFromYarn($output);
        $npmPackages = $this->getPackageDependencies($packageFile);

        $result = [];

        foreach ($npmPackages as $package => $info) {
            $result[$package] = array_merge(
                $info,
                ['installed' => $yarnPackages[$package] ?? null]
            );
        }

        return $result;
    }

    /**
     * Returns the name of te plugin, without vendor name, if any.
     *
     * @param  string $name
     * @return string
     */
    public function getCleanNpmName(string $name) : string
    {
        if ($name[0] === '@') {
            $bits = explode('/', $name, 2);
            if (isset($bits[1])) {
                return trim($bits[1]);
            }
        }
        return trim($name);
    }
}
