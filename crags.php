<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crags.php 
 * Controller for displaying crags in a given area.
 */
 
require_once("include/config.php");
$db = db_connect();

// get all crags if no areaid
if ($_GET["areaid"] == NULL) {
    $sql = "SELECT cragid,name FROM crags ORDER BY name ASC";
    
    if (!$result = $db->query($sql))
        error("Error in crags.php: " .$db->error());
    elseif ($result->num_rows == 0)
        $crags = 0;
    else {
        while($crag = $result->fetch_assoc())
            $crags[$crag["cragid"]] = $crag["name"];
    }
    
    view("all_crags.php", ["crags" => $crags]);
}

// get crags for areaid
else {
    $sql = "SELECT * FROM areas WHERE areaid = ". $_GET["areaid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crags.php: " .$db->error());
    
    $area = $result->fetch_assoc();
    
    $sql = "SELECT cragid,name FROM crags WHERE areaid = ". $_GET["areaid"] ." ORDER BY name ASC;";
    
    if (!$result = $db->query($sql))
        error("Error in crags.php: " .$db->error());
    elseif ($result->num_rows > 0) {
        $crags = [];
        while($row = $result->fetch_assoc()) 
            array_push($crags, $row);
    }
    
    // just show area info if no crags found
    else {
        view("crags.php", ["area" => $area, "crags" => 0, "routes" => 0]);
        
        $db->close();
        exit;
    }
    
    // get route list for area according to filter (default by name)
    foreach($crags as $crag) {
        $values = $values . $crag["cragid"] . ",";
    }
    
    if($_GET["filter"] == "british") {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
        $sql .= $BritishAdjFilter . $OrderByGrade . $BritishAdj . $ElseAsc;
        $sql = str_replace(",)", ")", $sql);
    }
    elseif($_GET["filter"] == "french") {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
        $sql .= $frenchGradeFilter . $OrderByGrade . $frenchGrade . $ElseAsc;
        $sql = str_replace(",)", ")", $sql);
    }
    elseif($_GET["filter"] == "font") {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
        $sql .= $fontGradeFilter . $OrderByGrade . $fontGrade . $ElseAsc;
        $sql = str_replace(",)", ")", $sql);
    }
    elseif($_GET["filter"] == "yds") {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
        $sql .= $ydsGradeFilter . $OrderByGrade . $ydsGrade . $ElseAsc;
        $sql = str_replace(",)", ")", $sql);
    }
    elseif($_GET["filter"] == "uiaa") {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
        $sql .= $uiaaGradeFilter . $OrderByGrade . $uiaaGrade . $ElseAsc;
        $sql = str_replace(",)", ")", $sql);
    }
    elseif($_GET["filter"] == "vgrade") {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ";
        $sql .= $vGradeFilter . $OrderByGrade . $vGrade . $ElseAsc;
        $sql = str_replace(",)", ")", $sql);
    }
    else {
        $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ORDER BY name ASC;";
        $sql = str_replace(",)", ")", $sql);
    }
    
    if (!$result = $db->query($sql))
        error("Error in crags.php: " .$db->error());
    
    // show routes
    $routes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc())
            array_push($routes, $row);
    }
    
    // no routes found
    else
        $routes = 0;
    
    view("crags.php", ["crags" => $crags, "area" => $area, "routes" => $routes ]);
}

$db->close();
    
?>