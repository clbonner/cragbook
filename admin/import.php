<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2020 Christopher L Bonner
 *
 * admin/import.php
 * Controller for bulk importing routes to a crag.
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    move_uploaded_file($_FILES["csvfile"]["tmp_name"], "import.csv");
    $fp = fopen("import.csv", "r");

    // remove exisiting routes from database
    $sql = "DELETE FROM routes WHERE cragid=" .$_POST["importcrag"];
    if (!$result = $db->query($sql))
        error("Error in admin/import.php. QUERY: " .$sql ." ERROR: ".$db->error);

    // ignore header line of CSV file
    fgetcsv($fp);

    // import each route in to the database
    // 0=orderid 1=name 2=grade 3=length etc...
    while ($route = fgetcsv($fp)) {
        $sql = "INSERT INTO routes (cragid,orderid,name,grade,length,stars,
          firstascent,sector,seriousness,description,discipline,private) VALUES ("
          .$_POST["importcrag"] .","
          .$route[0] .",\""
          .$route[1] ."\",\""
          .$route[2] ."\","
          .$route[3] .",\""
          .$route[4] ."\",\""
          .$route[5] ."\",\""
          .$route[6] ."\","
          .$route[7] .",\""
          .$route[8] ."\","
          .$route[9] .","
          .$route[10] .");";
          
        // execute query
        if (!$result = $db->query($sql))
            error("Error in admin/import.php. QUERY: " .$sql ." ERROR: ".$db->error);
    }

    fclose($fp);
    header("Location: " .SITEURL ."/crag.php?cragid=" .$_POST["importcrag"]);
}

$db->close();
