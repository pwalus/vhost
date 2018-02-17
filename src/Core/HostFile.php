<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 16:01
 */
declare(strict_types=1);

namespace App\Core;

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
        self::checkIfCanAddLineToHost($hostName);

        $line = "127.0.0.1\t" . $hostName . "\n";
        if (file_put_contents(self::HOST_FILE, $line, FILE_APPEND) === false) {
            throw new Exception('Something bad happened. Cannot append line to host file');
        }
    }

    /**
     * @param string $hostName
     * @throws Exception
     */
    protected static function checkIfCanAddLineToHost(string $hostName)
    {
        $hostContent = file_get_contents(self::HOST_FILE);

        if ((bool)preg_match(sprintf('/%s/', $hostName), $hostContent)) {
            throw new Exception(sprintf('Cannot add %s to host file, already exists!', $hostName));
        }

        if (! is_writable(self::HOST_FILE)) {
            throw new Exception('Host file is not writable! Run command with sudo');
        }
    }

}