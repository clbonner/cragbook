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

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (isset($_GET["cragid"])) {
        // get routes
        $sql = "SELECT * FROM routes WHERE cragid=" .$_GET["cragid"] ." ORDER by orderid ASC;";
        if (!$result = $db->query($sql)) {
            $errors .= $db->error ."\n";
            echo $errors;
            exit;
        }
        
        // store routes in array
        $routes = [];
        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }
        
        // send routes as JSON
        echo json_encode($routes);
    }
    else {
        // decode sent data to array
        $routes = urldecode($_SERVER["QUERY_STRING"]);
        $routes = json_decode($routes, true);
        
        // update routes in database
        foreach ($routes as $route) {
            $sql = "UPDATE routes SET orderid=" .$route["orderid"] ." WHERE routeid=" .$route["routeid"] .";";
            if(!$db->query($sql)){
                $error = $db->error;
                exit;
            }
        }
        
        // notify
        echo "{\"status\":\"SUCCESS\"}";
    }
}

$db->close();
    
?>