<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 15:37
 */
declare(strict_types=1);

namespace App\Core\Servers;

use App\Cleaner;
use App\Core\HostFile;
use App\Manager;
use Exception;

abstract class AbstractServer
{
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
            Manager::logInfo(sprintf("Hostname: %s created", $this->hostName));
        } catch (Exception $exception) {
            Manager::logError($exception->getMessage());
            Cleaner::clean();
            die();
        }
    }

    protected function getConfigDependsOnType(): string
    {
        if (isset($this->configFiles[$this->hostType])) {
            $src = PROJECT_PATH . 'resource/' . $this->configFiles[$this->hostType];
            if (file_exists($src)) {
                return $src;
            }

            throw new \InvalidArgumentException(sprintf('Cannot find file %s', $src));
        }

        throw new \InvalidArgumentException(sprintf('There is not defined host type as %s', $this->hostType));
    }

    protected abstract function createConfig();

}