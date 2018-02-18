<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 11:58
 */
declare(strict_types=1);

namespace App\Command;

use App\Core\ServerFactory;
use App\Core\Servers\AbstractServer;
use App\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class NewVirtualHostCommand extends Command
{

    public function configure()
    {
        $this->setName('create')->setDescription('Create new virtual host.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Manager::setStream($input, $output);
        $serverType = $this->getServerType($input, $output);
        $server = ServerFactory::createServer($serverType);

        $hostType = $this->getHostType($server, $input, $output);
        $hostName = $this->getHostName($input, $output);
        $folderName = $this->getFolderName($hostName, $input, $output);

        $server->setHostType($hostType);
        $server->setHostName($hostName);
        $server->setFolderName($folderName);
        $server->createVirtualHost();

        Manager::logInfo('New Virtual Host configuration created successfully! Open in browser: ' . $hostName);
    }

    protected function getServerType(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            '<info>Please choose server type: </info>',
            array_keys(ServerFactory::getServers()),
            0
        );

        return $helper->ask($input, $output, $question);
    }

    protected function getHostType(AbstractServer $server, InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            '<info>Please choose host type: </info>',
            array_keys($server->getConfigFiles()),
            0
        );

        return $helper->ask($input, $output, $question);
    }

    protected function getHostName(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please enter the name of the host: </info>');

        return $helper->ask($input, $output, $question);
    }

    protected function getFolderName(string $hostName, InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $question = new Question(
            '<info>Please enter the name of the project folder: </info><comment>[' . $hostName . ']</comment> ',
            $hostName
        );

        return $helper->ask($input, $output, $question);
    }

}