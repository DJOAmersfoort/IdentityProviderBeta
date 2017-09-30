<?php

namespace AppBundle\Command;

use AppBundle\Command\DepsListTrait;
use AppBundle\Entity\User;
use League\HTMLToMarkdown\HtmlConverter;
// BOO! The Parsedown developer bastards don't care about other developers!
use Parsedown as Markdown;
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
class DepsDocsCommand extends ContainerAwareCommand
{
    use DepsListTrait;

    /**
     * The type of heading used for dependencies
     *
     * @var string
     */
    const TITLE_CLASS = 'h2';

    /**
     * The prefix used for markdown titles. Make sure it'll result in the same
     * HTML tag as specified in the TITLE_CLASS constant.
     * @var string
     */
    const MARKDOWN_TITLE_PREFIX = '##';

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
     * Template for new dependencies
     *
     * @var string
     */
    const DEPENDENCY_TEMPLATE = <<<'MARKDOWN'
%s %%1$s

- **Full name:** [`%%2$s`][%%4$s]
- **Requested:** `%%3$s`

<!-- TODO: Write description for %%1$s -->
%%1$s is installed because it's in the package.json

[%%4$s]: http://npmjs.com/package/%%2$s

MARKDOWN;

    /**
     * Set name, description, aliases and options
     */
    protected function configure() : void
    {
        $this
            ->setName('deps:update-docs')
            ->setDescription('Updates the dependencies documentation in the doc/ directory.')
            ->addOption('dry-run', 'r', InputOption::VALUE_NONE, 'Don\'t write changes')
            ->addOption('print-markdown', 'p', InputOption::VALUE_NONE, 'Print the rendered markdown');
    }

    /**
     * Converts Markdown to a DOMDocument, via HTML.
     * @param  string       $contents
     * @return DOMDocument
     */
    private function getMarkdownTree(string $contents) : \DOMDocument
    {
        $pd = new Markdown;
        $html = $pd->text($contents);

        $doc = new \DOMDocument;

        try {
            libxml_use_internal_errors(true);
            $doc->loadHTML($html, LIBXML_NOWARNING);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors(false);
        }

        return $doc;
    }

    /**
     * Converts HTML to Markdown
     *
     * @param  DOMDocument $doc
     * @return string
     */
    private function getMarkdownFile(\DOMDocument $doc) : string
    {
        $contents = $doc->saveHTML();

        $htmlConv = new HtmlConverter([
            'strip_tags' => true,
            'header_style' => 'atx'
        ]);

        return $htmlConv->convert($contents);
    }

    /**
     * Returns a list of packages that are present in the Markdown file and in
     * the document.
     *
     * @param  OutputInterface $output
     * @param  array           $packages
     * @param  DOMDocument     $doc
     * @return array
     */
    private function getPackagesInMarkdown(OutputInterface $output, array $packages, \DOMDocument $doc) : array
    {
        $titles = $doc->getElementsByTagName(self::TITLE_CLASS);

        $packageTitles = [];
        foreach ($titles as $title) {
            $titleText = (string) $title->nodeValue;
            $titleText = trim($titleText);
            if (array_key_exists(strtolower($titleText), $packages)) {
                $packageTitles[strtolower($titleText)] = $titleText;
            }

            $output->writeln(sprintf(
                'Found <info>%s</> present in current heading list',
                $titleText
            ), OutputInterface::VERBOSITY_VERY_VERBOSE);
        }

        return $packageTitles;
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

        $docFile = $this->getRootDir() . self::DOC_FILENAME;
        $contents = file_get_contents($docFile);

        $packages = $this->getPackageDependencies(self::PACKAGE_FILE);
        $doc = $this->getMarkdownTree($contents);
        $describedPackages = $this->getPackagesInMarkdown($output, $packages, $doc);

        $depTemplate = sprintf(self::DEPENDENCY_TEMPLATE, self::MARKDOWN_TITLE_PREFIX);

        foreach ($packages as $packageName => $package) {
            if (in_array($packageName, $describedPackages)) {
                continue;
            }

            $cleanName = strtolower($package['name']);
            $cleanName = preg_replace('/[^a-z0-9\/]+/', '-', $cleanName);
            $cleanName = str_replace('/', '__', $cleanName);
            $cleanName = trim($cleanName, '-');

            $displayName = $this->getCleanNpmName($package['name']);
            $displayName = ucfirst($displayName);

            $output->writeln(sprintf(
                'Adding <info>%s</> with link <comment>%s</> to file',
                $package['name'],
                $cleanName
            ), OutputInterface::VERBOSITY_VERY_VERBOSE);

            $contents .= PHP_EOL . sprintf(
                $depTemplate,
                $displayName,
                $package['name'],
                $package['requested'],
                $cleanName
            );
        }

        $contents = rtrim($contents) . PHP_EOL;

        if ($input->getOption('print-markdown')) {
            $output->writeln([
                '<comment>Start of Markdown dump</>',
                str_repeat('=', 32),
                "\n",
                $contents,
                "\n",
                str_repeat('=', 32)
            ]);
        }

        if (!$input->getOption('dry-run')) {
            $output->writeln(sprintf(
                'Writing changes to <info>%s</>',
                $docFile
            ));
            file_put_contents($docFile, $contents);
        }

        // Print that we're done
        $output->writeln('<info>Command completed.</>');
    }
}
