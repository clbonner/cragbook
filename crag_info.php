<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crag_info.php 
 * Controller for displaying crag information page
 */

require_once("include/config.php");

// show all crags if no cragid supplied
if ($_GET["cragid"] == NULL)
{
    view("all_crags.php", []);
    exit;
}

// get details for crag
else
{
    $db = db_connect();
    
    $sql = "SELECT * FROM crags WHERE cragid = ". $_GET["cragid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error());
    
    $crag = $result->fetch_assoc();
        
    // get routes according to filter (default orderid)
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
    else {
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
    }
    
    // get routes
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error());
    elseif ($result->num_rows > 0) {
        $routes = [];
        while($row = $result->fetch_assoc())
            array_push($routes, $row);
    }
    else
        $routes = 0;
    
    // get area info
    $sql = "SELECT * FROM areas WHERE areaid = ". $crag["areaid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error());
    else
        $area = $result->fetch_assoc();

    // show crag page
    view("crag_info.php", ["crag" => $crag, "routes" => $routes, "area" => $area]);
    
    $db->close();
}
    
?>