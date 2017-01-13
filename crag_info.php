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

else
{
    $db = db_connect();
    
    // get crag details
    $sql = "SELECT * FROM crags WHERE cragid = ". $_GET["cragid"] .";";
    $result = $db->query($sql);
    $crag = $result->fetch_assoc();
        
    // get routes according to filter
    if ($_GET["filter"] == "british") {
        // filter routes by british adjective grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $BritishAdjFilter . $OrderByGrade . $BritishAdj . $ElseAsc;
    }
    elseif ($_GET["filter"] == "french") {
        // filter routes by french grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $frenchGradeFilter . $OrderByGrade . $frenchGrade . $ElseAsc;
    }
    elseif ($_GET["filter"] == "font") {
        // filter routes by font grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $fontGradeFilter . $OrderByGrade . $fontGrade . $ElseAsc;
    }
    elseif ($_GET["filter"] == "yds") {
        // filter routes by YDS grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $ydsGradeFilter . $OrderByGrade . $ydsGrade . $ElseAsc;
    }
    elseif ($_GET["filter"] == "uiaa") {
        // filter routes by UIAA grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $uiaaGradeFilter . $OrderByGrade . $uiaaGrade . $ElseAsc;
    }
    elseif ($_GET["filter"] == "vgrade") {
        // filter routes by v grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $vGradeFilter . $OrderByGrade . $vGrade . $ElseAsc;
    }
    elseif ($_GET["sort"] == "grade") {
        // get routes and sort by grade
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ";
        $sql .= $OrderByGrade . $BritishAdj . $FrenchNum . $FontNum . $ElseAsc;
    }
    else {
        // get routes sorted by orderid (default)
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
    }
    
    $routelist = $db->query($sql);
    
    // get area info
    $sql = "SELECT * FROM areas WHERE areaid = ". $crag["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Could not get area details. query = " .$sql);
    else
        $area = $result->fetch_assoc();

    // check if any routes were found
    if ($routelist->num_rows > 0) {
        // store routes
        $routes = [];
        while($row = $routelist->fetch_assoc())
            array_push($routes, $row);
        
        // show crag info page 
        view("crag_info.php", ["crag" => $crag, "routes" => $routes, "area" => $area]);
    }
    else {
        // show crag info page with no routes
        view("crag_info.php", ["crag" => $crag, "routes" => 0, "area" => $area]);
    }
    
    $db->close();
}
    
?>