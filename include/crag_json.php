<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/route_update.php 
 * Returns JSON data for crags so they can be populated using
 * Google Maps API.
 */

require_once("../include/config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["areaid"])) {
        $sql = "SELECT * FROM crags WHERE areaid=" .$_GET["areaid"] .";";
        if (!$result = $db->query($sql)) {
            $error = $db->error ."\n";
            echo $error;
            exit;
        }
        
        $crags = [];
        while ($crag = $result->fetch_assoc()) {
            array_push($crags, $crag);
        }
        
        // send crags as JSON
        echo json_encode($crags);
    }
}

$db->close();
    
?>