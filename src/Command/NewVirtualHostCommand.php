<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 11:58
 */
declare(strict_types=1);

namespace App\Command;

use App\Core\MagentoVHost;
use App\Core\ServerFactory;
use App\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewVirtualHostCommand extends Command
{

    public function configure()
    {
        $this->setName('create')
            ->setDescription('Create new virtual host.')
            ->addArgument('serverType', InputArgument::REQUIRED, 'Type of server')
            ->addArgument('hostType', InputArgument::REQUIRED, 'Type of virtual host')
            ->addArgument('hostName', InputArgument::REQUIRED, 'Name of virtual host');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Manager::setStream($input, $output);
        $serverType = Manager::getInput()->getArgument('serverType');
        $hostType = Manager::getInput()->getArgument('hostType');
        $hostName = Manager::getInput()->getArgument('hostName');

        $server = ServerFactory::createServer($serverType, $hostType, $hostName);
        $server->createVirtualHost();
    }

}