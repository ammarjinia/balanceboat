<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller {
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
        $rootDirectory = 'bguploads';

        echo DB::connection('bgmysql')->select("select * from `listings` where id = '872'");exit;
        // Call the function to start scanning
        $centers = $this->scanFilesInDirectory($rootDirectory);
        
        foreach ($centers as $dir=>$files) {
            list($base, $listingId) = explode("bguploads/",$dir);   
            
            if ($listingId) {
                
                echo "<h3>Image of @$listingId Synchronization started...</h3>";
                foreach ($files as $image) {
                    if ($image) {
                        $src = base_path("public/bguploads/".$listingId."/".$image);
                        $dest = "listings/".date("Y/m/d")."/".$image;
                        if (file_exists($src)) {
                            
                            if (substr($image, 0, 1) !== '.') {
                                echo '<p>'.$src.'</p>';
                        
                                $resMove = Storage::disk('azure_bg')->put($dest, file_get_contents($src));
                                // Check the result
                                if (@$resMove) {
                                    // The file was moved successfully than
                                    DB::connection('bgmysql')->insert('INSERT INTO `listing_image_gallery`(`listing_id`,`image_title`,`image_url`) VALUES (?, ?, ?)', [$listingId, basename($image), $dest]);
                                    
                                    // Remove the local file
                                    unlink($src);
                                    
                                }
                            } else {
                                unlink($src);
                            }
                        }
                    }
                }
                echo "<h3>Image of @$listingId Synchronization completed successfully.</h3>";
                // Remove the dir
                $srcDir = base_path("public/bguploads/".$listingId);
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
                    if ($dir > 2) {
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

    public function syncListingToCentre() {
        $objCenters = \App\Centers::select("id","name","bg_id")->whereNull("bg_id")->get();
        if ($objCenters) {
            foreach ($objCenters as $center) {
                $bgListing = DB::connection('bgmysql')->select("select * from `listings` where name = ?", [$center->name]);
                if ($bgListing) {
                    $center->bg_id = $bgListing[0]->id;
                    $center->save();
                    echo "<p>Center ID: ".$center->id." synced with BG Listing ID: ".$bgListing[0]->id."</p>";
                } else {
                    echo "<p style='color:red;'>No BG Listing found for Center ID: ".$center->id." Name: ".$center->name."</p>";
                }
            }
        }
    }
}