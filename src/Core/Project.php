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

final class Project
{
    private static $src;

    /**
     * @param string $folderName
     * @throws Exception
     */
    public static function createFolder(string $folderName)
    {
        self::$src = PROJECT_PATH . $folderName;

        Manager::logComment("Checking if can create project folder...");
        self::checkIfCanCreateFolder();

        Manager::logComment(sprintf("Creating new folder for project..."));
        if (mkdir(self::$src) === false) {
            throw new Exception('Something bad happened. Cannot create project folder');
        }

        Cleaner::setCleanData(Cleaner::FOLDER);
    }

    /**
     * @throws Exception
     */
    protected static function checkIfCanCreateFolder()
    {
        if (file_exists(self::$src)) {
            throw new Exception(sprintf('Already exists: %', self::$src));
        }

        if (! is_writable(self::$src)) {
            throw new Exception('Cannot access project folder! Run command with sudo');
        }
    }

    public static function deleteFolder(string $folderName)
    {
        $src = PROJECT_PATH . $folderName;
        Manager::logComment(sprintf("Removing %s", $src));
        if (self::$src == $src) {
            rmdir($src);
        }
    }

}