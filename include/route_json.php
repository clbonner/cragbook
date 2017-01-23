<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/route_json.php 
 * Returns JSON data so the user can manipulate the 
 * route order for a crag, then submit it back to update the database.
 */

require_once("../include/config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // send JSON data for routes at crag
    if (isset($_GET["cragid"])) {
        
        // check for a filter
        if (isset($_GET["filter"])) {
            if ($_GET["filter"] == "british") {
                $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
                $sql .= $BritishAdjFilter . $OrderByGrade . $BritishAdj . $ElseAsc;
            }
            elseif ($_GET["filter"] == "french") {
                $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
                $sql .= $frenchGradeFilter . $OrderByGrade . $frenchGrade . $ElseAsc;
            }
            elseif ($_GET["filter"] == "font") {
                $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
                $sql .= $fontGradeFilter . $OrderByGrade . $fontGrade . $ElseAsc;
            }
            elseif ($_GET["filter"] == "yds") {
                $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
                $sql .= $ydsGradeFilter . $OrderByGrade . $ydsGrade . $ElseAsc;
            }
            elseif ($_GET["filter"] == "uiaa") {
                $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
                $sql .= $uiaaGradeFilter . $OrderByGrade . $uiaaGrade . $ElseAsc;
            }
            elseif ($_GET["filter"] == "vgrade") {
                $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
                $sql .= $vGradeFilter . $OrderByGrade . $vGrade . $ElseAsc;
            }
        }
        
        // default order
        else
            $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
        
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