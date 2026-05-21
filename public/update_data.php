<?php
ini_set("display_errors","on");
$servername = "localhost";
$username = "u809584917_boat";
$password = "Vww!inY/6l";
$db = "u809584917_boat";

try {
    $conn = mysqli_connect($servername, $username, $password, $db);
    /* change character set to utf8mb4 */
mysqli_set_charset($conn, "utf8mb4");
    //echo "Connected successfully"; 
} catch (exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
// exit;
$message = '';
$existRecord = fopen("exist_records.txt", "w") or die("Unable to open file!");
if(isset($_POST["upload"]))
{
    if($_FILES['file']['name'])
    {
        $filename = explode(".", $_FILES['file']['name']);
        if(end($filename) == "csv")
        {mysqli_query($conn, "SET SESSION sql_mode = ''");

            $handle = fopen($_FILES['file']['tmp_name'], "r");
            $i = 0;
            $upCount = 0;
            while($data = fgetcsv($handle))
            {
                if ($i>0) {
                    
                    if ($_POST["data"] == "reviews") {
                    
                        $listing_id = mysqli_real_escape_string($conn, $data[0]);
                        //$query1 = "select id from listings where name = '".mysqli_real_escape_string($conn, trim($data[0]))."' limit 1";
                        //$qr = mysqli_query($conn, $query1);
                        //$rs = mysqli_fetch_row($qr); 
                        $query = " INSERT INTO `listing_comments`(`listing_id`,`comment_author`,`comment_author_email`,`comment_author_city`,`comment_author_country`,`comment_content`,`comment_approved`,`rating`) 
                        VALUES ('".mysqli_real_escape_string($conn, $data[0])."',
                        '".mysqli_real_escape_string($conn, $data[1])."',
                        '".mysqli_real_escape_string($conn, $data[2])."',
                        '".mysqli_real_escape_string($conn, $data[3])."',
                        '".mysqli_real_escape_string($conn, $data[4])."',
                        '".mysqli_real_escape_string($conn, $data[5])."',
                        '".mysqli_real_escape_string($conn, 1)."',
                        '".mysqli_real_escape_string($conn, $data[7])."')";
                        echo $query;
                        // mysqli_query($conn, $query);
                    
                    } else if ($_POST["data"] == "retreats") {
                        $query1 = "select id from listings where name = '".mysqli_real_escape_string($conn, trim($data[0]))."' limit 1";
                        $qr = mysqli_query($conn, $query1);
                        $rs = mysqli_fetch_row($qr); 
                        $listing_id = (@$rs[0]) ? $rs[0] : trim($data[0]);
                        $start_date = ($data[2]) ? ((stristr(trim($data[2]),"ever")) ? $data[2] : date("Y-m-d",strtotime($data[2]))) : $data[2];
                        $is_recurring = ($data[2]) ? ((stristr(trim($data[2]),"ever")) ? 'yes' : 'no') : 'no';
                        $start_time = $data[3];
                        $query = " INSERT INTO `listing_retreats`(`listing_id`,`name`,`start_date`,`start_time`,`location`,`country`,`duration`,`price`, `currency`, `about`, `is_recurring`) 
                        VALUES ('".mysqli_real_escape_string($conn, $listing_id)."',
                        '".mysqli_real_escape_string($conn, $data[1])."',
                        '".mysqli_real_escape_string($conn, $start_date)."',
                        '".mysqli_real_escape_string($conn, $start_time)."',
                        '".mysqli_real_escape_string($conn, $data[4])."',
                        '".mysqli_real_escape_string($conn, $data[5])."',
                        '".mysqli_real_escape_string($conn, $data[6])."',
                        '".mysqli_real_escape_string($conn, $data[7])."',
                        '".mysqli_real_escape_string($conn, $data[8])."',
                        '".mysqli_real_escape_string($conn, $data[9])."',
                        '".mysqli_real_escape_string($conn, $is_recurring)."')";
                        echo $query;
                        // mysqli_query($conn, $query);
                        
                    } else if ($_POST["data"] == "insert-exp-main") {
                        $qryExp = mysqli_query($conn, "select `id` from `experiences` where `id` = '".mysqli_real_escape_string($conn, $data[0])."' limit 1");
                        $resExp = mysqli_fetch_row($qryExp);
                        if ($resExp) {
                            $txt = mysqli_real_escape_string($conn, $data[0])."\n";
                            fwrite($existRecord, $txt);
                            continue;
                        }
                        $centerId = null;
                        if ($data[1]) {
                            // Find Centre
                            $qryCentre = mysqli_query($conn, "select `id` from `centers` where `name` = '".mysqli_real_escape_string($conn, $data[1])."' limit 1");
                            $resCentre = mysqli_fetch_row($qryCentre);
                            
                            if ($resCentre) {
                                $centerId = (@$resCentre[0]) ? $resCentre[0] : trim($data[1]);
                            } else {
                                $qryInsCentre = 'insert into centers (`name`) VALUES ("'.mysqli_real_escape_string($conn, $data[1]).'")';
                                $resInsCentre = mysqli_query($conn, $qryInsCentre);
                                $centerId = mysqli_insert_id($conn);
                            }
                        }
                        
                        $query = ' INSERT INTO `experiences`(`id`,`name`,`center_id`,`duration`,`currency`,`avg_price`, `experience_overview`, `styles_taught`, `experience_details`, `food_overview`, `schedule`, `what_is_included`, `what_is_not_included`, `how_to_get_here`, `booking_info`, `cancellation_policy`) 
                        VALUES ("'.mysqli_real_escape_string($conn, $data[0]).'",
                        "'.mysqli_real_escape_string($conn, $data[2]).'",
                        "'.mysqli_real_escape_string($conn, $centerId).'",
                        "'.mysqli_real_escape_string($conn, $data[3]).'",
                        "'.mysqli_real_escape_string($conn, $data[4]).'",
                        "'.mysqli_real_escape_string($conn, $data[5]).'",
                        "'.mysqli_real_escape_string($conn, $data[6]).'",
                        "'.mysqli_real_escape_string($conn, $data[7]).'",
                        "'.mysqli_real_escape_string($conn, $data[8]).'",
                        "'.mysqli_real_escape_string($conn, $data[9]).'",
                        "'.mysqli_real_escape_string($conn, $data[10]).'",
                        "'.mysqli_real_escape_string($conn, $data[11]).'",
                        "'.mysqli_real_escape_string($conn, $data[12]).'",
                        "'.mysqli_real_escape_string($conn, $data[13]).'",
                        "'.mysqli_real_escape_string($conn, $data[14]).'",
                        "'.mysqli_real_escape_string($conn, $data[15]).'")';
                        mysqli_query($conn, $query);
                        $upCount++;
                    }else if ($_POST["data"] == "insert-exp") {
                        
                        $centerId = null;
                        $qryExp = mysqli_query($conn, "select `id` from `experiences` where `id` = '".mysqli_real_escape_string($conn, $data[0])."' limit 1");
                        $resExp = mysqli_num_rows($qryExp);
                        if ($resExp > 0) {
                            $expId = mysqli_real_escape_string($conn, $data[0]);
                            
                            $txt = mysqli_real_escape_string($conn, $data[0])."\n";
                            fwrite($existRecord, $txt);
                        } else {
                            if ($data[3]) {
                                // Find Centre
                                $qryCentre = mysqli_query($conn, "select `id` from `centers` where `name` = '".mysqli_real_escape_string($conn, $data[3])."' limit 1");
                                $resCentre = mysqli_fetch_row($qryCentre);
                                
                                if ($resCentre) {
                                    $centerId = (@$resCentre[0]) ? $resCentre[0] : trim($data[3]);
                                } else {
                                    $qryInsCentre = 'insert into centers (`name`, `location`) VALUES ("'.mysqli_real_escape_string($conn, $data[3]).'","'.mysqli_real_escape_string($conn, $data[10]).'")';
                                    $resInsCentre = mysqli_query($conn, $qryInsCentre);
                                    $centerId = mysqli_insert_id($conn);
                                    
                                    $qryInsCentCat = "insert into center_locations (`center_id`, `location_id`) VALUES ($centerId, 373)";
                                    $resInsCentCat = mysqli_query($conn, $qryInsCentCat);
                                }
                            }
                            
                            $query = " INSERT INTO `experiences`(`id`,`name`,`slug`,`center_id`,`meta_title`,`meta_description`) 
                            VALUES ('".mysqli_real_escape_string($conn, $data[0])."',
                            '".mysqli_real_escape_string($conn, $data[1])."',
                            '".mysqli_real_escape_string($conn, $data[2])."',
                            '".mysqli_real_escape_string($conn, $centerId)."',
                            '".mysqli_real_escape_string($conn, $data[4])."',
                            '".mysqli_real_escape_string($conn, $data[5])."')";
                            mysqli_query($conn, $query);
                        
                            if ($data[6]) {
                                
                                // Find Cat
                                $qryCat = mysqli_query($conn, "select `id` from `category` where `name` = '".mysqli_real_escape_string($conn, $data[6])."' limit 1");
                                $resCat = mysqli_fetch_row($qryCat);
                                
                                if ($resCat) {
                                    $catId = (@$resCat[0]) ? $resCat[0] : trim($data[6]);
                                } else {
                                    $qryInsCat = 'insert into category (`name`) VALUES ("'.mysqli_real_escape_string($conn, $data[6]).'")';
                                    $resInsCat = mysqli_query($conn, $qryInsCat);
                                    $catId = mysqli_insert_id($conn);
                                }
                                $query1 = "insert into experience_category (experience_id, category_id) VALUES (".mysqli_real_escape_string($conn, $data[0]).",".$catId.")";
                                mysqli_query($conn, $query1);
                            }
                            
                            if ($data[7]) {
                                // Find Cat
                                $qryCat = mysqli_query($conn, "select `id` from `category` where `name` = '".mysqli_real_escape_string($conn, $data[7])."' limit 1");
                                $resCat = mysqli_fetch_row($qryCat);
                                
                                if ($resCat) {
                                    $catId = (@$resCat[0]) ? $resCat[0] : trim($data[7]);
                                } else {
                                    $qryInsCat = 'insert into category (`name`) VALUES ("'.mysqli_real_escape_string($conn, $data[7]).'")';
                                    $resInsCat = mysqli_query($conn, $qryInsCat);
                                    $catId = mysqli_insert_id($conn);
                                }
                                $query1 = "insert into experience_category (experience_id, category_id) VALUES (".mysqli_real_escape_string($conn, $data[0]).",".$catId.")";
                                mysqli_query($conn, $query1);
                            }
                            
                            if ($data[8]) {
                                // Find Cat
                                $qryCat = mysqli_query($conn, "select `id` from `category` where `name` = '".mysqli_real_escape_string($conn, $data[8])."' limit 1");
                                $resCat = mysqli_fetch_row($qryCat);
                                
                                if ($resCat) {
                                    $catId = (@$resCat[0]) ? $resCat[0] : trim($data[8]);
                                } else {
                                    $qryInsCat = 'insert into category (`name`, `type`) VALUES ("'.mysqli_real_escape_string($conn, $data[8]).'", 1)';
                                    $resInsCat = mysqli_query($conn, $qryInsCat);
                                    $catId = mysqli_insert_id($conn);
                                }
                                $query1 = "insert into experience_category (experience_id, category_id) VALUES (".mysqli_real_escape_string($conn, $data[0]).",".$catId.")";
                                mysqli_query($conn, $query1);
                            }
                            
                            if ($data[9]) {
                                // Find Cat
                                $qryCat = mysqli_query($conn, "select `id` from `category` where `name` = '".mysqli_real_escape_string($conn, $data[9])."' limit 1");
                                $resCat = mysqli_fetch_row($qryCat);
                                
                                if ($resCat) {
                                    $catId = (@$resCat[0]) ? $resCat[0] : trim($data[9]);
                                } else {
                                    $qryInsCat = 'insert into category (`name`, `type`) VALUES ("'.mysqli_real_escape_string($conn, $data[9]).'", 1)';
                                    $resInsCat = mysqli_query($conn, $qryInsCat);
                                    $catId = mysqli_insert_id($conn);
                                }
                                $query1 = "insert into experience_category (experience_id, category_id) VALUES (".mysqli_real_escape_string($conn, $data[0]).",".$catId.")";
                                mysqli_query($conn, $query1);
                            }
                        }
                        $upCount++;
                        
                    } else if ($_POST["data"] == "insert-exp-images") {
                        $query = " INSERT INTO `experience_image_gallery`(`experience_id`,`image_title`,`image_url`) 
                        VALUES ('".mysqli_real_escape_string($conn, $data[0])."',
                        '".mysqli_real_escape_string($conn, $data[1])."',
                        '".mysqli_real_escape_string($conn, str_replace("https://balanceboatblob.blob.core.windows.net/uploads/",'', $data[2]))."')";
                        mysqli_query($conn, $query);
                        $upCount++;
                        
                    } else if ($_POST["data"] == "update-exp") {
                        $exp_id = mysqli_real_escape_string($conn, $data[0]);
                        
                        //echo $query = "Update `experiences` set `name` = '".mysqli_real_escape_string($conn, $data[1])."', `meta_title` = '".mysqli_real_escape_string($conn, $data[2])."',`meta_description` = '".mysqli_real_escape_string($conn, $data[3])."', `experience_overview` = '".mysqli_real_escape_string($conn, $data[4])."' where `id` = '".$exp_id."'";
                        if (@$data[2] > 0) {
                          //$query = "Update `experiences` set `avg_price` = '".mysqli_real_escape_string($conn, $data[2])."', `currency` = '".mysqli_real_escape_string($conn, $data[3])."'  where `id` = '".$exp_id."'";
                        }
                        //$query = "Update `experiences` set `duration` = '".mysqli_real_escape_string($conn, $data[1])."', `avg_price` = '".mysqli_real_escape_string($conn, $data[2])."', `currency` = '".mysqli_real_escape_string($conn, $data[3])."', `experience_overview` = '".mysqli_real_escape_string($conn, $data[4])."', `styles_taught` = '".mysqli_real_escape_string($conn, $data[5])."', `experience_details`  = '".mysqli_real_escape_string($conn, $data[6])."', `food_overview`  = '".mysqli_real_escape_string($conn, $data[7])."', `what_is_included`  = '".mysqli_real_escape_string($conn, $data[8])."', `what_is_not_included`  = '".mysqli_real_escape_string($conn, $data[9])."', `how_to_get_here`  = '".mysqli_real_escape_string($conn, $data[10])."', `booking_info`  = '".mysqli_real_escape_string($conn, $data[11])."', `cancellation_policy`  = '".mysqli_real_escape_string($conn, $data[12])."'  where `id` = '".$exp_id."'";
                        //$query = "Update `experiences` set `duration` = '".mysqli_real_escape_string($conn, $data[1])."', `experience_overview` = '".mysqli_real_escape_string($conn, $data[2])."', `styles_taught` = '".mysqli_real_escape_string($conn, $data[3])."', `food_overview`  = '".mysqli_real_escape_string($conn, $data[4])."', `how_to_get_here`  = '".mysqli_real_escape_string($conn, $data[5])."', `booking_info`  = '".mysqli_real_escape_string($conn, $data[6])."', `cancellation_policy`  = '".mysqli_real_escape_string($conn, $data[7])."' where `id` = '".$exp_id."'";
                        //$query = "Update `experiences` set `banner_image_url` = '".$data[1]."', `banner_image_title` = '".basename($data[1])."' where `id` = '".$exp_id."'";
                        $query = 'Update `experiences` set `tags` = "'.$data[1].'" where `id` = "'.$exp_id.'"';
                        mysqli_query($conn, $query);
                        $upCount++;
                        
                    }  else if ($_POST["data"] == "insert-centres") {
                        if ($data[6]) {
                            // Find COuntry
                            $qryCat = mysqli_query($conn, "select `id` from `category` where `name` = '".$data[6]."' limit 1");
                            $resCat = mysqli_fetch_row($qryCat);
                            
                            if ($resCat) {
                                $catId = (@$resCat[0]) ? $resCat[0] : trim($data[6]);
                            } else {
                                $qryInsCat = 'insert into category (`name`, `type`) VALUES ("'.mysqli_real_escape_string($conn, $data[8]).'", 1)';
                                $resInsCat = mysqli_query($conn, $qryInsCat);
                                $catId = mysqli_insert_id($conn);
                            }
                        }
                        $query = " INSERT INTO `centers`(`id`,`name`,`slug`,`meta_title`,`meta_description`, `location`, `country`) 
                        VALUES ('".mysqli_real_escape_string($conn, $data[0])."',
                        '".mysqli_real_escape_string($conn, $data[1])."',
                        '".mysqli_real_escape_string($conn, $data[2])."',
                        '".mysqli_real_escape_string($conn, $data[3])."',
                        '".mysqli_real_escape_string($conn, $data[4])."',
                        '".mysqli_real_escape_string($conn, $data[5])."',
                        '".mysqli_real_escape_string($conn, $catId)."')";
                        mysqli_query($conn, $query);
                        
                        $qryInsCentCat = 'insert into center_locations (`center_id`, `location_id`) VALUES ("'.mysqli_real_escape_string($conn, $data[0]).'", '.$catId.')';
                        $resInsCentCat = mysqli_query($conn, $qryInsCentCat);
                        
                        $upCount++;
                    } else if ($_POST["data"] == "update-centre") {
                        $centre_id = mysqli_real_escape_string($conn, $data[0]);
                        // echo $query = "Update `centers` set `bg_id` = '".mysqli_real_escape_string($conn, $data[1])."' where `id` = '".$centre_id."'";exit;
                        echo $query = "Update `centers` set 
                            `name` = '".mysqli_real_escape_string($conn, $data[1])."',
                            `address_of_center` = '".mysqli_real_escape_string($conn, $data[2])."',
                            `website` = '".mysqli_real_escape_string($conn, $data[3])."',
                            `email_address` = '".mysqli_real_escape_string($conn, $data[4])."',
                            `contact_number` = '".mysqli_real_escape_string($conn, $data[5])."',
                            `facebook_url` = '".mysqli_real_escape_string($conn, $data[6])."',
                            `instagram_url` = '".mysqli_real_escape_string($conn, $data[7])."',
                            `meta_title` = '".mysqli_real_escape_string($conn, $data[8])."',
                            `meta_description` = '".mysqli_real_escape_string($conn, $data[9])."',
                            `about_center` = '".mysqli_real_escape_string($conn, $data[10])."',
                            `tags` = '".mysqli_real_escape_string($conn, $data[11])."' 
                            where `id` = '".$centre_id."'";
                        //mysqli_query($conn, $query);
                       // exit;
                         echo "<br />";
                    } else if ($_POST["data"] == "update-users") {
                        $user_id = mysqli_real_escape_string($conn, $data[0]);
                        $query = "Update `users` set `email` = '".mysqli_real_escape_string($conn, $data[1])."' where `id` = '".$user_id."'";
                        echo $query;
                        // mysqli_query($conn, $query);
                    } else if ($_POST["data"] == "update-blogs") {
                        
                        //$query1 = "select id from category where name = '".mysqli_real_escape_string($conn, trim($data[1]))."' limit 1";
                        //$qr = mysqli_query($conn, $query1);
                        //$rs = mysqli_fetch_row($qr); 
                        //$category_id = (@$rs[0]) ? $rs[0] : trim($data[0]);
                        echo $query = 'Update `blogs` set `meta_title` = "'.mysqli_real_escape_string($conn, $data[1]).'", `meta_description` = "'.mysqli_real_escape_string($conn, $data[2]).'", `meta_keywords` = "'.mysqli_real_escape_string($conn, $data[3]).'" where name = "'.mysqli_real_escape_string($conn, trim($data[0])).'"';
                        mysqli_query($conn, $query);
                        echo "<br />";
                        $upCount++;
                        
                    } else if ($_POST["data"] == "listing-category") {
                        
                        $query = "insert into listing_category (listing_id, category_id)
                            VALUES (".mysqli_real_escape_string($conn, $data[0]).",
                            (select id from category where name='$data[1]'))";
                        mysqli_query($conn, $query);
                    } else if ($_POST["data"] == "insert-centre-images-bg") {
                        $query1 = "insert into center_image_gallery (`center_id`, `bg_exp_id`, `image_title`, `image_url`) VALUES ('".mysqli_real_escape_string($conn, $data[0])."', '".mysqli_real_escape_string($conn, $data[1])."', '".mysqli_real_escape_string($conn, $data[2])."','".mysqli_real_escape_string($conn, $data[3])."')";
                        mysqli_query($conn, $query1);
                        $upCount++;
                    }
                    
                    // echo $i."-".mysqli_affected_rows($conn)."-".$query."<br />";
                }
                $i++;
            }
            fclose($handle);
            echo "<h4>Total Count:".$upCount."</h4>";
        } else {
            $message = '<label class="text-danger">Please Select CSV File only</label>';
        }
    } else {
        $message = '<label class="text-danger">Please Select File</label>';
    }
}

if(isset($_GET["updation"]))
{
 $message = '<label class="text-success">Listing Updation Done</label>';
}
?>
<!DOCTYPE html>
<html>
 <head>
     <meta charset="UTF-8">
  <title>Update Mysql Database through Upload CSV File using PHP</title>
 </head>
 <body>
  <br />
  <div class="container">
   <h2 align="center">Update Mysql Database through Upload CSV File using PHP</a></h2>
   <br />
   <form method="post" enctype='multipart/form-data'>
    <p><label>Please Select File(Only CSV Formate)</label>
    <input type="file" name="file" /></p>
    <br />
    <select id="data" name="data">
        <option value="">Select Data</option>
        <option value="reviews">Reviews</option>
        <option value="retreats">Retreats</option>
        <option value="insert-exp">Insert Experience</option>
        <option value="insert-exp-main">Insert Experience Main</option>
        <option value="insert-exp-images">Insert Experience Images</option>
        <option value="update-exp">Update Exps</option>
        <option value="update-users">Update Users</option>
        <option value="update-blogs">Update Blogs</option>
        <option value="listing-category">Insert Listing Category</option>
        <option value="insert-centres">Insert Centres</option>
        <option value="update-centre">Update centre</option>
        <option value="insert-centre-images-bg">Insert centre Images from BG</option>
    </select>
    <input type="submit" name="upload" class="btn btn-info" value="Upload" />
   </form>
   <br />
  </div>
 </body>
</html>
