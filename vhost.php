#! /usr/bin/env php
<?php

use App\Command\NewVirtualHostCommand;
use Symfony\Component\Console\Application;

require 'vendor/autoload.php';

try {
    $app = new Application('Create new Virtual Host', '1.0');
    $app->add(new NewVirtualHostCommand());
    $app->run();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
