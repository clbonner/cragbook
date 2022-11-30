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
    if (isset($_GET["areaid"])) {
        is_valid_num($_GET["areaid"]);
        
        if (isset($_SESSION["userid"]))
            $sql = "SELECT cragid,name FROM crags WHERE areaid = ". $_GET["areaid"] ." ORDER BY name ASC;";
        else
            $sql = "SELECT cragid,name FROM crags WHERE areaid = ". $_GET["areaid"] ." AND public=1 ORDER BY name ASC;";
        
        if (!$result = $db->query($sql))
            ajax_err("Error retrieving crags.");
        elseif ($result->num_rows > 0) {
        
            // store crags in array
            $crags = [];
            while($row = $result->fetch_assoc()) 
                array_push($crags, $row);

            // get cragid's for area
            $values = "";
            foreach($crags as $crag) {
                $values = $values . $crag["cragid"] . ",";
            }
            $values[strlen($values) - 1] = " "; // remove comma
            
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ORDER BY orderid;";
            
            if (!$result = $db->query($sql)) {
                ajax_err("Error retrieving routes.");
            }
            
            $routes = [];
            while ($route = $result->fetch_assoc()) {
                array_push($routes, $route);
            }
            
            if (!isset($_SESSION["userid"])) {
                for ($i = 0; $i < sizeof($routes); $i++) {
                    if ($routes[$i]["private"] == 1) {
                        $routes[$i]["description"] = "";
                    }
                }
            }
        }
        else
            $routes = "";
        
        // send routes as JSON
        echo json_encode($routes);
    }
    
    // send JSON data for routes at crag
    if (isset($_GET["cragid"])) {
        is_valid_num($_GET["cragid"]);

        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
        
        if (!$result = $db->query($sql)) {
            exit("Error retrieving routes.");
        }
        
        $routes = [];
        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }
        
        if (!isset($_SESSION["userid"])) {
            foreach ($routes as $key => $value) {
                if ($routes[$key]["private"] == 1) {
                    $routes[$key]["description"] = "";
                }
            }
        }
        
        // send routes as JSON
        echo json_encode($routes);
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
            exit("Error saving route.");
        }
    }
}

$db->close();
    
?>