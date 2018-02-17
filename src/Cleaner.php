<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 18:42
 */

declare(strict_types=1);

namespace App;

use App\Cleaners\ConfigClean;
use App\Cleaners\HostClean;
use App\Cleaners\SymlinkClean;

class Cleaner
{
    const HOST_FILE = 1;

    const CONFIG_FILE = 2;

    const SYMLINK = 3;

    const FOLDER = 4;

    protected static $hostName;

    protected static $cleanData = [];

    public static function clean()
    {
        Manager::logInfo("Cleaning...");
        foreach (self::createObjects() as $cleaner) {
            $cleaner->clean();
        }
    }

    protected static function createObjects(): array
    {
        $cleanObjects = [];
        if (in_array(self::HOST_FILE, self::$cleanData)) {
            $cleanObjects[] = new HostClean(self::$hostName);
        }

        if (in_array(self::CONFIG_FILE, self::$cleanData)) {
            $cleanObjects[] = new ConfigClean(self::$hostName);
        }

        if (in_array(self::SYMLINK, self::$cleanData)) {
            $cleanObjects[] = new SymlinkClean(self::$hostName);
        }

//        if (in_array(self::FOLDER, self::$cleanData)) {
//            $cleanObjects = new HostClean();
//        }

        return $cleanObjects;
    }

    public static function setCleanData(int $cleanData)
    {
        self::$cleanData[] = $cleanData;
    }

    public static function setHostName(string $hostName)
    {
        self::$hostName = $hostName;
    }

}