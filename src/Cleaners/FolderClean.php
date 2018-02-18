<?php
/**
 * Created by PhpStorm.
 * User: patrykwalus
 * Date: 17.02.2018
 * Time: 19:57
 */

namespace App\Cleaners;

use App\Core\Project;

class FolderClean implements CleanInterface
{

    protected $folderName;

    public function __construct($folderName)
    {
        $this->folderName = $folderName;
    }

    public function clean()
    {
        Project::deleteFolder($this->folderName);
    }
}