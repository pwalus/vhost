<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 16:01
 */
declare(strict_types=1);

namespace App\Core;

use App\Cleaner;
use App\Manager;
use Exception;

final class HostFile
{
    const HOST_FILE = '/etc/hosts';

    /**
     * @param string $hostName
     * @throws Exception
     */
    public static function addLineToHostFile(string $hostName)
    {
        Manager::logComment("Checking if can add line to host file...");
        self::checkIfCanAddLineToHost($hostName);

        Manager::logComment(sprintf("Adding new line to host file..."));
        $line = "127.0.0.1\t" . $hostName . "\n";
        if (file_put_contents(self::HOST_FILE, $line, FILE_APPEND) === false) {
            throw new Exception('Something bad happened. Cannot append line to host file');
        }

        Cleaner::setCleanData(Cleaner::HOST_FILE);
    }

    /**
     * @param string $hostName
     * @throws Exception
     */
    protected static function checkIfCanAddLineToHost(string $hostName)
    {
        if (! file_exists(self::HOST_FILE)) {
            throw new Exception('Cannot locate host file');
        }

        $hostContent = self::getContent();

        if ((bool)preg_match(sprintf('/%s/', $hostName), $hostContent)) {
            throw new Exception(sprintf('Cannot add %s to host file, already exists!', $hostName));
        }

        if (! is_writable(self::HOST_FILE)) {
            throw new Exception('Host file is not writable! Run command with sudo');
        }
    }

    /**
     * @return bool|string
     */
    protected static function getContent(): string
    {
        return file_get_contents(self::HOST_FILE);
    }

    public static function deleteLineFromHostFile(string $hostName)
    {
        $line = "127.0.0.1\t" . $hostName . "\n";
        $hostContent = self::getContent();
        $replacement = str_replace($line, '', $hostContent);
        if (! empty($replacement)) {
            file_put_contents(self::HOST_FILE, $replacement);
        }
    }

}