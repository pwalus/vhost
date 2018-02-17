<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 14:02
 */
declare(strict_types=1);

namespace App\Core\Servers;

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

        $config = preg_replace(
            '/&&hostname&&/',
            $this->hostName,
            file_get_contents($this->getConfigDependsOnType())
        );

        if (file_put_contents($availableSrc, $config) === false) {
            throw new Exception('Something bad happened. Cannot create config file');
        }

        $this->createSymlink($availableSrc, $enabledSrc);
    }

    /**
     * @param string $availableSrc
     * @param string $enabledSrc
     * @throws Exception
     */
    protected function checkIfCanCreateConfig(string $availableSrc, string $enabledSrc)
    {
        if (file_exists($availableSrc)) {
            throw new Exception(sprintf('Cannot create config file %s, already exists!', $availableSrc));
        }
        if (file_exists($enabledSrc)) {
            throw new Exception(sprintf('Cannot create config file %s, already exists!', $enabledSrc));
        }
    }

    /**
     * @param string $availableSrc
     * @param string $enabledSrc
     * @throws Exception
     */
    protected function createSymlink(string $availableSrc, string $enabledSrc)
    {
        if (symlink($availableSrc, $enabledSrc) === false) {
            throw new Exception('Cannot create symlink for config file');
        }
    }

}