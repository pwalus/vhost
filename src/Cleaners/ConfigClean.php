<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 19:57
 */

namespace App\Cleaners;

use App\Core\Servers\Nginx;

class ConfigClean implements CleanInterface
{

    protected $hostName;

    public function __construct($hostName)
    {
        $this->hostName = $hostName;
    }

    public function clean()
    {
        unlink(Nginx::CONFIG_AVAILABLE_PATH . $this->hostName. '.conf');
    }
}