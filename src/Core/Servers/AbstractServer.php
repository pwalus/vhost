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

    protected $folderName;

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

    public function getConfigFiles(): array
    {
        return $this->configFiles;
    }

    public function setHostType(string $hostType)
    {
        $this->hostType = $hostType;
    }

    public function setHostName(string $hostName)
    {
        $this->hostName = $hostName;
    }

    public function setFolderName(string $folderName)
    {
        $this->folderName = $folderName;
    }

    protected abstract function createConfig();

}