<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 13:45
 */
declare(strict_types=1);

namespace App\Core;

use App\Core\Servers\AbstractServer;
use App\Core\Servers\Nginx;

abstract class ServerFactory
{

    protected static $servers = [
        'nginx' => Nginx::class,
    ];

    public static function createServer(string $serverType): AbstractServer
    {
        if (isset(self::$servers[$serverType])) {
            return new self::$servers[$serverType]();
        }

        throw new \InvalidArgumentException(sprintf('There is not defined server as %s', $serverType));
    }

    public static function getServers(): array
    {
        return self::$servers;
    }

}