<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 19:57
 */

namespace App\Cleaners;

use App\Core\HostFile;

class HostClean implements CleanInterface
{

    protected $hostName;

    public function __construct($hostName)
    {
        $this->hostName = $hostName;
    }

    public function clean()
    {
        HostFile::deleteLineFromHostFile($this->hostName);
    }
}