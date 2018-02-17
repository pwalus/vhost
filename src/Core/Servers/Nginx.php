<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 14:02
 */
declare(strict_types=1);

namespace App\Core\Servers;

use App\Cleaner;
use App\Manager;
use Exception;

class Nginx extends AbstractServer
{
    const CONFIG_AVAILABLE_PATH = '/usr/local/etc/nginx/sites-available/';

    const CONFIG_ENABLED_PATH = '/usr/local/etc/nginx/sites-enabled/';

    protected $configFiles = [
        'magento' => 'magento.conf',
        'magento2' => 'magento.conf',
        'pimcore' => 'pimcore.conf',
        'symfony' => 'symfony.conf',
    ];

    /**
     * @throws Exception
     */
    protected function createConfig()
    {
        $availableSrc = self::CONFIG_AVAILABLE_PATH . $this->hostName . '.conf';
        $enabledSrc = self::CONFIG_ENABLED_PATH . $this->hostName . '.conf';

        $this->checkIfCanCreateConfig($availableSrc, $enabledSrc);
        $this->createConfigFile($availableSrc);
        $this->createSymlink($availableSrc, $enabledSrc);
    }

    /**
     * @param string $availableSrc
     * @param string $enabledSrc
     * @throws Exception
     */
    protected function checkIfCanCreateConfig(string $availableSrc, string $enabledSrc)
    {
        Manager::logComment("Checking if can create new config file...");
        if (file_exists($availableSrc)) {
            throw new Exception(sprintf('Cannot create config file %s, already exists!', $availableSrc));
        }
        if (file_exists($enabledSrc)) {
            throw new Exception(sprintf('Cannot create config file %s, already exists!', $enabledSrc));
        }
    }

    /**
     * @param $availableSrc
     * @throws Exception
     */
    protected function createConfigFile($availableSrc)
    {
        Manager::logComment("Creating new config file...");

        $config = preg_replace(
            '/&&hostname&&/',
            $this->hostName,
            file_get_contents($this->getConfigDependsOnType())
        );

        if (file_put_contents($availableSrc, $config) === false) {
            throw new Exception('Something bad happened. Cannot create config file');
        }

        Cleaner::setCleanData(Cleaner::CONFIG_FILE);
    }

    /**
     * @param string $availableSrc
     * @param string $enabledSrc
     * @throws Exception
     */
    protected function createSymlink(string $availableSrc, string $enabledSrc)
    {
        Manager::logComment("Creating symlink...");
        if (symlink($availableSrc, $enabledSrc) === false) {
            throw new Exception('Cannot create symlink for config file');
        }

        Cleaner::setCleanData(Cleaner::SYMLINK);
    }

}