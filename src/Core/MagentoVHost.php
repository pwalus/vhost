<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 12:06
 */

declare(strict_types=1);

namespace App\Core;

use App\Manager;
use Exception;

class MagentoVHost
{
    private $name;

    CONST CONFIG_AVAILABLE_PATH = "/usr/local/etc/nginx/sites-available/";

    CONST CONFIG_ENABLED_PATH = "/usr/local/etc/nginx/sites-enabled/";

    const HOST_FILE = '/etc/hosts';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addHost()
    {
        try {
            $this->addLineToHostFile();
            $this->createConfig();
        } catch (Exception $exception) {
            Manager::logError($exception->getMessage());
            die();
        }
    }

    /**
     * @throws Exception
     */
    protected function addLineToHostFile()
    {
        $this->checkIfCanAddLineToHost();

        $line = "127.0.0.1\t" . $this->name . "\n";
        if (file_put_contents(self::HOST_FILE, $line, FILE_APPEND) === false) {
            throw new Exception('Something bad happened. Cannot append line to host file');
        }
    }

    /**
     * @throws Exception
     */
    protected function checkIfCanAddLineToHost()
    {
        $hostContent = file_get_contents(self::HOST_FILE);

        if ((bool)preg_match(sprintf('/%s/', $this->getName()), $hostContent)) {
            throw new Exception(sprintf('Cannot add %s to host file, already exists!', $this->getName()));
        }

        if (! is_writable(self::HOST_FILE)) {
            throw new Exception('Host file is not writable! Run command with sudo');
        }
    }

    /**
     * @throws Exception
     */
    protected function createConfig()
    {
        $availableSrc = self::CONFIG_AVAILABLE_PATH . $this->name . '.conf';
        $enabledSrc = self::CONFIG_ENABLED_PATH . $this->name . '.conf';

        $this->checkIfCanCreateConfig($availableSrc, $enabledSrc);

        $config = preg_replace(
            '/&&hostname&&/',
            $this->name,
            file_get_contents('/Users/patrykwalus/Commands/vhost/resource/magento.conf')
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

    public function getName(): string
    {
        return $this->name;
    }

}