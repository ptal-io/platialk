<?php

    require('db.php');

    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $user = intval($_POST['user']);
        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $user . ".csv");
        

        $row = 1;
        if (($handle = fopen("uploads/".$user.".csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 1) {
                    $id = intval($data[0]);
                    $cat = trim($data[1]);
                    $lat = floatval($data[2]);
                    $lng = floatval($data[3]);
                    $query = "INSERT INTO points (id, pid, cat, geom) VALUES (".$user.",".$id.",'".$cat."',st_setsrid(st_makepoint(".$lng.",".$lat."),4326));";
                    pg_query($query) or die(pg_last_error());
                }
                $row++;
            }
            fclose($handle);
        }
        sleep(2);
        echo $user;
    }

?>