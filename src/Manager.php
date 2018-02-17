<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 12:26
 */
declare(strict_types=1);

namespace App;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Manager
{
    /**
     * @var InputInterface;
     */
    protected static $input;
    /**
     * @var OutputInterface;
     */
    protected static $output;

    public static final function setStream(InputInterface $input, OutputInterface $output)
    {
        self::$input = $input;
        self::$output = $output;
    }

    public static function logError(string $message)
    {
        self::$output->writeln(sprintf("<error>%s</error>", $message));
    }

    public static function logInfo(string $message)
    {
        self::$output->writeln(sprintf("<info>%s</info>", $message));
    }

    public static function logComment(string $message)
    {
        self::$output->writeln(sprintf("<comment>%s</comment>", $message));
    }

    /**
     * @return InputInterface
     */
    public static function getInput(): InputInterface
    {
        return self::$input;
    }

    /**
     * @return OutputInterface
     */
    public static function getOutput(): OutputInterface
    {
        return self::$output;
    }
}