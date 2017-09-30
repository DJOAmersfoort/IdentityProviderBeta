<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IdpUpdateCommand extends ContainerAwareCommand
{
    /**
     * The help text for this command, with a '{command}' placeholder.
     * @var string
     */
    const HELP_TEXT = <<<EOF
The {command} command updates the database with any changes and new users from
the DJO IDP Backend. This includes locking accounts of former members of the DJO
and creating accounts for users that have newly registered at the DJO.
EOF;

    protected function configure()
    {
        $this
            ->setName('idp:update')
            ->setDescription('Updates the users in the Identity Provider')
            ->addOption('dry-run', 'r', InputOption::VALUE_NONE, 'Don\'t write changes');

        $helpText = str_replace('{command}', $this->getName(), self::HELP_TEXT);
        $helpText = wordwrap(str_replace("\n", ' ', $helpText), 60);
        $helpText = str_replace(
            $this->getName(),
            "<info>{$this->getName()}</>",
            $helpText
        );
        $this->setHelp($helpText);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $backendHelper = $container->get('app.backend');
        $rep = $em->getRepository(User::class);

        // TODO Retrieve all users from backend
        $users = [];

        // Retrieve all locally stored users
        $localUsers = $rep->findAll();

        // TODO Compare users locally to users in response. Deactivate missing
        foreach ($localUsers as $user) {
            // TODO get remote id
            // TODO check if ID exists in list
            // TODO delete user if doesn't exist.
        }

        foreach ($users as $user) {
            // TODO update each user that still exists, retrieve extensive info

            // TODO write changes to a UserChange entity, that the user will
            // be presented with after login
        }

        // Save info, if this is not a dry-run
        if (!$input->getOption('dry-run')) {
            $em->flush();
        }

        // Print that we're done
        $output->writeln('<info>Command completed.</>');
    }
}
