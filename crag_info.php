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
    $result = $db->query($sql);
    $crag = $result->fetch_assoc();
        
    // get routes according to filter
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
    elseif ($_GET["sort"] == "grade") {
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $OrderByGrade . $BritishAdj . $FrenchNum . $FontNum . $ElseAsc;
    }
    else {
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
    }
    
    $routelist = $db->query($sql);
    
    // get area info
    $sql = "SELECT * FROM areas WHERE areaid = ". $crag["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Could not get area details. query = " .$sql);
    else
        $area = $result->fetch_assoc();

    // show routes
    if ($routelist->num_rows > 0) {
        $routes = [];
        while($row = $routelist->fetch_assoc())
            array_push($routes, $row);
        
        view("crag_info.php", ["crag" => $crag, "routes" => $routes, "area" => $area]);
    }
    
    // if no routes were found
    else {
        view("crag_info.php", ["crag" => $crag, "routes" => 0, "area" => $area]);
    }
    
    $db->close();
}
    
?>