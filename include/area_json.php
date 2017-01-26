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
    // get all areas in database   
    $sql = "SELECT * FROM areas ORDER BY name ASC;";
    if (!$result = $db->query($sql)) {
        $error = $db->error ."\n";
        echo $error;
        exit;
    }
    
    $areas = [];
    while ($area = $result->fetch_assoc()) {
        $area["description"] = htmlspecialchars_decode($area["description"]);
        array_push($areas, $area);
    }
    
    // send areas as JSON
    echo json_encode($areas);
}

$db->close();
    
?>