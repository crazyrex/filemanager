<?php namespace Simexis\Filemanager\controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

/**
 * Class FolderController
 * @package Simexis\Filemanager\controllers
 */
class FolderController extends Controller {

    protected $file_location;

    function __construct()
    {
        if (Session::get('sfm_type') == "Images")
            $this->file_location = Config::get('sfm.images_dir');
        else
            $this->file_location = Config::get('sfm.files_dir');
    }


    /**
     * Get list of folders as json to populate treeview
     *
     * @return mixed
     */
    public function getFolders()
    {
        $directories = File::directories(base_path($this->file_location));
        $final_array = [];

        foreach ($directories as $directory)
        {
            if (basename($directory) != "thumbs")
            {
                $final_array[] = basename($directory);
            }
        }

        return View::make("filemanager::tree")
            ->with('dirs', $final_array);
    }


    /**
     * Add a new folder
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = Str::slug(Input::get('name'));

        $path = base_path($this->file_location);

        if (!File::exists($path . "/" . $folder_name))
        {
            File::makeDirectory($path . "/" . $folder_name, $mode = 0777, true, true);
            return "OK";
        } else
        {
            return "A folder with this name already exists!";
        }

    }

}
