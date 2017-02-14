<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/route_json.php 
 * Returns JSON data so the user can manipulate the 
 * route order for a crag, then submit it back to update the database.
 */
 
require_once(__DIR__ ."/config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    // send JSON data for routes at crag
    if (isset($_GET["cragid"])) {
        
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
        
        if (!$result = $db->query($sql)) {
            exit("Error in route_json.php: " .$db->error);
        }
        
        $routes = [];
        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }
        
        // send routes as JSON
        echo json_encode($routes);
    }
    
    // get individual route
    elseif (isset($_GET["routeid"])) {
        
        $sql = "SELECT * FROM routes WHERE routeid = ". $_GET["routeid"];
        
        if (!$result = $db->query($sql)) {
            exit("Error in route_json.php: " .$db->error);
        }
        elseif ($result->num_rows == 1)
            $route = $result->fetch_assoc();
        else 
            $route = "";
    
        // remove html special chars from description
        $route["description"] = htmlspecialchars_decode($route["description"]);
        
        // send route as JSON
        echo json_encode($route);
    }
}

// update route order for crag
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    login_check();
    
    $routes = urldecode($_POST["routes"]);
    $routes = json_decode($routes, true);
    
    // update routes in database
    foreach ($routes as $route) {
        $sql = "UPDATE routes SET orderid=" .$route["orderid"] ." WHERE routeid=" .$route["routeid"] .";";
        
        if(!$db->query($sql)){
            exit("Error in route_json.php: " .$db->error);
        }
    }
}

$db->close();
    
?>