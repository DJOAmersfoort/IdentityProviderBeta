<?php

namespace AppBundle\Command;

use AppBundle\Command\DepsListTrait;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Updater for the documentation concerning dependencies. Will ask Yarn for a
 * JSON list of packages, and then parse the dependencies markdown and update
 * the markdown file accordingly.
 *
 * @author Roelof Roos
 */
class DepsListCommand extends ContainerAwareCommand
{
    use DepsListTrait;

    /**
     * The type of heading used for dependencies
     *
     * @var string
     */
    const TITLE_CLASS = 'h2';

    /**
     * Path from app root to the file containing the dependencies.
     *
     * @var string
     */
    const DOC_FILENAME = '/doc/dependencies.md';

    /**
     * Path to the package.json file for the app. Absolute from the package
     * root.
     *
     * @var string
     */
    const PACKAGE_FILE = '/package.json';

    /**
     * Set name, description, aliases and options
     */
    protected function configure() : void
    {
        $this
            ->setName('deps:list')
            ->setDescription('Lists all installed depedencies, with requested and installed version')
            ->addOption('dry-run', 'r', InputOption::VALUE_NONE, 'Don\'t write changes')
            ->addOption('print-markdown', 'p', InputOption::VALUE_NONE, 'Print the rendered markdown');
    }

    protected function listPackages(OutputInterface $output, array $packages) : void
    {
        $table = new Table($output);
        $table->setHeaders(['Name', 'Wanted', 'Installed', 'Dev only?']);

        foreach ($packages as $package) {
            $table->addRow([
                $package['name'],
                $package['requested'],
                $package['installed'] ?? 'N/A',
                $package['dev-only'] ? 'Yes' : 'No'
            ]);
        }

        $table->render();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isYarnInstalled($output)) {
            $output->writeln([
                '<error>Failed to check if Yarn was installed</>',
                'Make sure you have the yarn binary installed on your path'
            ]);
            return false;
        }

        $output->writeln('Fetching dependencies from Yarn and the package file...');

        $list = $this->getDependencies($output, self::PACKAGE_FILE);

        $output->writeln(sprintf(
            'Retrieved <info>%d</> packages',
            count($list)
        ));

        if (count($list) > 0) {
            $this->listPackages($output, $list);
        }

        // Print that we're done
        $output->writeln('<info>Command completed.</>');
    }
}
