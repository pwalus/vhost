#! /usr/bin/env php
<?php
use Acme\Config;
use Acme\createNewHostCommand;
use Acme\Host;
use Symfony\Component\Console\Application;

require 'vendor/autoload.php';

$app = new Application('Create new Virtual Host', '1.0');
$app->add(new NewVirtualHostCommand());
$app->run();