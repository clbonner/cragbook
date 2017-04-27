<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/area_json.php 
 * Returns JSON data for areas in database.
 */

require_once(__DIR__ ."/config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    // get area
    if (isset($_GET["areaid"])) {
        
        if (!is_numeric($_GET["areaid"])) exit;
    
        if (isset($_SESSION["userid"]))
            $sql = "SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] .";";
        else
            $sql = "SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] ." AND public=1;";
        
        if (!$result = $db->query($sql)) {
            exit("Error in area_json.php: " .$db->error);
        }
        
        $areas = $result->fetch_assoc();
    }
    
    // get all areas in database   
    else {
        
        if (isset($_SESSION["userid"]))
            $sql = "SELECT * FROM areas ORDER BY name ASC;";
        else
            $sql = "SELECT * FROM areas WHERE public=1 ORDER BY name ASC;";
        
        if (!$result = $db->query($sql)) {
            exit("Error in area_json.php: " .$db->error);
        }
        
        $areas = [];
        while ($area = $result->fetch_assoc()) {
            $area["description"] = htmlspecialchars_decode($area["description"]);
            array_push($areas, $area);
        }
    }
    
    echo json_encode($areas);
}

$db->close();
    
?>