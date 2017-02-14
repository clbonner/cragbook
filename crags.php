<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crags.php 
 * Controller for displaying crags in a given area.
 */
 
require_once(__DIR__ ."/include/config.php");
$db = db_connect();

// show all crags when no areaid
if (!isset($_GET["areaid"])) {
    view("all_crags.php", []);
}

// return ajax request showing table of routes for area
elseif (isset($_GET["filter"])) {
    
    // get crags for area
    $sql = "SELECT cragid,name FROM crags WHERE areaid = ". $_GET["areaid"] ." ORDER BY name ASC;";
    
    if (!$result = $db->query($sql))
        ajax_err("Error in crags.php: " .$db->error);
    elseif ($result->num_rows > 0) {
        
        // store crags in array
        $crags = [];
        while($row = $result->fetch_assoc()) 
            array_push($crags, $row);

        // get cragid's for area
        foreach($crags as $crag) {
            $values = $values . $crag["cragid"] . ",";
        }
        $values[strlen($values) - 1] = " ";
        
        // get route list for area according to filter
        if($_GET["filter"] == "british") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
            $sql .= $BritishAdjFilter . $OrderByGrade . $BritishAdj . $ElseAsc;
        }
        elseif($_GET["filter"] == "french") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
            $sql .= $frenchGradeFilter . "ORDER BY grade ASC";
        }
        elseif($_GET["filter"] == "font") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
            $sql .= $fontGradeFilter . "ORDER BY grade ASC";
        }
        elseif($_GET["filter"] == "yds") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
            $sql .= $ydsGradeFilter . "ORDER BY grade ASC";
        }
        elseif($_GET["filter"] == "uiaa") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
            $sql .= $uiaaGradeFilter . $OrderByGrade . $uiaaGrade . $ElseAsc;
        }
        elseif($_GET["filter"] == "vgrade") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
            $sql .= $vGradeFilter . "ORDER BY grade ASC";
        }
        elseif ($_GET["filter"] == "all") {
            $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ORDER BY name ASC;";
        }
        else {
            // don't return anything if incorrect filter
            exit;
        }
    
        if (!$result = $db->query($sql)) {
            ajax_err("Error in crags.php: " .$db->error);
        }
        elseif ($result->num_rows > 0) {
            
            // put routes in array
            $routes = [];
            while ($route = $result->fetch_assoc()) {
                array_push($routes, $route);
            }
        
            // build table    
            $table = "<table>";
            $table .= "<tr>";
            $table .= "<th></th>";
            $table .= "<th>Name</th>";
            $table .= "<th>Grade</th>";
            $table .= "<th>Stars</th>";
            $table .= "<th>Crag</th>";
            $table .= "</tr>";
            
            foreach ($routes as $route) {
                $table .= "<tr>";
                $table .= "<td><div>";
                $table .= "<button class=\"fa fa-info btn-border margin-side-5\" onclick=\"getRouteInfo(" .$route["routeid"] .")\"></button></div></td>";
                $table .= "<td>" .$route["name"] ."</td>";
                $table .= "<td>" .$route["grade"] ."</td>";
                $table .= "<td>" .$route["stars"] ."</td>";
                $table .= "<td>";
                
                foreach($crags as $crag) {
                    if($crag["cragid"] ==  $route["cragid"])
                        $table .= "<a href=\"" .SITEURL ."/crag_info.php?cragid=" .$crag["cragid"] ."\">" .$crag["name"] ."</a>";
                }
                
                $table .= "</td>";
                $table .= "</tr>";
            }
            
            $table .= "</table>";
        }
        else
            $table = "<p>No routes</p>";
    }
    else
        $table = "<p>No routes</p>";
    
    // send routes table
    echo $table;
}

// get area info for areaid
elseif (isset($_GET["areaid"])) {
    $sql = "SELECT * FROM areas WHERE areaid = ". $_GET["areaid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crags.php: " .$db->error);
    
    $area = $result->fetch_assoc();
    
    view("crags.php", ["area" => $area]);
}

$db->close();
    
?>