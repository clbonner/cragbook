<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/route_update.php 
 * Returns JSON data so the user can manipulate the 
 * route order for a crag, then submit it back to update the database.
 */

require_once("../include/config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // send JSON data for routes at crag
    if (isset($_GET["cragid"])) {
        $sql = "SELECT * FROM routes WHERE cragid=" .$_GET["cragid"] ." ORDER by orderid ASC;";
        if (!$result = $db->query($sql)) {
            $error = $db->error ."\n";
            echo $error;
            exit;
        }
        
        $routes = [];
        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }
        
        // send routes as JSON
        echo json_encode($routes);
    }
    
    // update route order for crag
    else {
        $routes = urldecode($_SERVER["QUERY_STRING"]);
        $routes = json_decode($routes, true);
        
        // update routes in database
        foreach ($routes as $route) {
            $sql = "UPDATE routes SET orderid=" .$route["orderid"] ." WHERE routeid=" .$route["routeid"] .";";
            if(!$db->query($sql)){
                echo "{\"error\" : \"" .$db->error ."\"}";
                exit;
            }
        }
        
        // notify success
        echo "{\"status\":\"SUCCESS\"}";
    }
}

$db->close();
    
?>