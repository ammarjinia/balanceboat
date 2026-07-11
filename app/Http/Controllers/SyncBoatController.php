<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\DB;

class SyncBoatController extends Controller {
    public $centers = array();

    public function __construct() {
        // Set new limit
        ini_set('max_execution_time', -1);
    }

    /**
     * Display a listing of the resource - Experiences.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // Specify the root directory you want to start scanning
        $rootDirectory = 'bbuploads';
        
        // Call the function to start scanning
        $centers = $this->scanFilesInDirectory($rootDirectory);
        
        foreach ($centers as $dir=>$files) {
            list($base, $expId) = explode("bbuploads/",$dir);   
            
            if ($expId) {
                echo "<h3>Image of @$expId Synchronization started...</h3>";
                //$resExp = DB::select("select `name` from `experiences` where `id` = '$expId'");
                foreach ($files as $image) {
                    if ($image) {
                        $src = base_path("public/bbuploads/".$expId."/".$image);
                        $dest = "experiences/".date("Y/m/d")."/".urlencode($image);
                        if (file_exists($src)) {
                            
                            if (substr($image, 0, 1) !== '.') {
                                echo '<p>'.$src.'</p>';
                        
                                $resMove = Storage::disk('s3')->put($dest, file_get_contents($src));
                                // Check the result
                                if (@$resMove) {
                                    // The file was moved successfully than
                                    DB::insert('INSERT INTO `experience_image_gallery`(`experience_id`,`image_title`,`image_url`) VALUES (?, ?, ?)', [$expId, basename($image), $dest]);
                                    
                                    // Remove the local file
                                    unlink($src);
                                    
                                }
                            } else {
                                unlink($src);
                            }
                        }
                    }
                }
                echo "<h3>Image of @$expId Synchronization completed successfully.</h3>";
                
                // Remove the dir
                $srcDir = base_path("public/bbuploads/".$expId);
                if (file_exists(@$srcDir)) {
                    rmdir($srcDir);
                }
            }
        }
        echo 'Page will be refresh after 30 sec...';
        echo '<meta http-equiv="refresh" content="30">';
       // echo "<center><h3>Synchronization of images completed successfully.</h3></center>";
        
    }
    
    public function scanFilesInDirectory($directory) {
        //$centers[$directory] = array();
        // Get the list of files and directories in the specified directory
        $files = scandir($directory);
        /*$filteredFiles = array_filter($files, function($file) {
            return substr($file, 0, 1) !== '.';
        });*/
        $dir=0;
        // Loop through each file/directory
        foreach ($files as $key=>$file) {
            // Exclude current and parent directory entries
            if ($file != '.' && $file != '..') {
                // Build the full path to the file/directory
                $filePath = $directory . '/' . $file;
                // Check if it's a file or a directory
                if (is_file($filePath)) {
                    $this->centers[$directory][] = $file;
                    // If it's a file, you can perform actions on it
                } elseif (is_dir($filePath)) {
                    list($base, $expId) = explode("bbuploads/",$filePath);
                    
                    $objExp = DB::select('select count(`id`) as cnt from `experiences` where `id` = ?', [$expId]);
                    if ($objExp[0]->cnt == 0) {
                        echo  "Do not exist experience of the Id: ".$expId."<br />";
                        continue;
                    }
                    if ($dir > 4) {
                        break;
                    }
                    // If it's a directory, recursively scan its contents
                    $this->scanFilesInDirectory($filePath);
                    $dir++;
                }
            }
        }
        return $this->centers;
    }
}