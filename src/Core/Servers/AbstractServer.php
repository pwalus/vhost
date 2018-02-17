<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 15:37
 */
declare(strict_types=1);

namespace App\Core\Servers;

use App\Core\HostFile;
use App\Manager;
use Exception;

abstract class AbstractServer
{
    const RELATIVE_PATH = '../../../resource/';

    protected $configFiles = [];

    protected $hostType;

    protected $hostName;

    public function __construct(string $hostType, string $hostName)
    {
        $this->hostType = $hostType;
        $this->hostName = $hostName;
    }

    public function createVirtualHost()
    {
        try {
            HostFile::addLineToHostFile($this->hostName);
            $this->createConfig();
        } catch (Exception $exception) {
            Manager::logError($exception->getMessage());
            die();
        }
    }


    protected function getConfigDependsOnType(): string
    {
        if (isset($this->configFiles[$this->hostType])) {
            return self::RELATIVE_PATH . $this->configFiles[$this->hostType];
        }

        throw new \InvalidArgumentException(sprintf('There is not defined host type as %s', $this->hostType));
    }

    protected abstract function createConfig();

}