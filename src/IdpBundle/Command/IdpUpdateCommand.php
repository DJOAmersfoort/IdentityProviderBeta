<?php

namespace IdpBundle\Command;

use IdpBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IdpUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('idp:update')
            ->setDescription('Updates the users in the Identity Provider')
            ->addOption('dry-run', 'r', InputOption::VALUE_NONE, 'Don\'t write changes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
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
