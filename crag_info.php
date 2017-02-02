<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crag_info.php 
 * Controller for displaying crag information page
 */

require_once(__DIR__ ."/include/config.php");
$db = db_connect();

// show all crags if no cragid supplied
if (!isset($_GET["cragid"]))
{
    view("all_crags.php", []);
    exit;
}

// return ajax request for routes when filter is given
elseif (isset($_GET["filter"])) {
    
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
    elseif ($_GET["filter"] == "all") {
        $sql = "SELECT * FROM routes WHERE cragid = ". $_GET["cragid"] ." ORDER BY orderid ASC;";
    }
    else {
        // return nothing if incorrect filter given
        exit;
    }
    
    // get routes
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error);
    elseif ($result->num_rows > 0) {
        $routes = [];
        while($row = $result->fetch_assoc())
            array_push($routes, $row);
    
        // build table
        $table = "<table>";
        $table .= "<tr>";
        $table .= "<th></th>";
        $table .= "<th>Name</th>";
        $table .= "<th>Grade</th>";
        $table .= "<th>Stars</th>";
        $table .= "<th>Length</th>";
        $table .= "<th>Sector</th>";
        $table .= "</tr>";
        
        // show editing options if user logged in
        if (isset($_SESSION["userid"])) {
            foreach ($routes as $route) {
                $table .= "<tr>";
                $table .= "<td>";
                $table .= "<button class=\"fa fa-edit btn-edit\" onclick=\"window.location.assign('" .SITEURL ."/admin/route.php?action=edit&routeid=" .$route["routeid"] ."')\"></button>";
                $table .= "<button class=\"fa fa-trash-o btn-edit\" onclick=\"window.location.assign('" .SITEURL ."/admin/route.php?action=delete&routeid=" .$route["routeid"] ."')\"></button>";
                $table .= "<button class=\"fa fa-info btn-border\" onclick=\"getRouteInfo(" .$route["routeid"] .")\"></button></td>";
                $table .= "<td><a href=\"" .SITEURL ."/admin/route.php?action=edit&routeid=" .$route["routeid"] ."\">" .$route["name"] ."</a></td>";
                $table .= "<td>" .$route["grade"] ."</td>";
                $table .= "<td>" .$route["stars"] ."</td>";
                $table .= "<td>" .$route["length"] ."m</td>";
                $table .= "<td>" .$route["sector"] ."</td>";
                $table .= "</tr>";
            }
        }
        
        // not logged in
        else {
            foreach ($routes as $route) {
                $table .= "<tr>";
                $table .= "<td>";
                $table .= "<button class=\"fa fa-info btn-border margin-side-5\" onclick=\"getRouteInfo(" .$route["routeid"] .")\"></button></td>";
                $table .= "<td>" .$route["name"] ."</td>";
                $table .= "<td>" .$route["grade"] ."</td>";
                $table .= "<td>" .$route["stars"] ."</td>";
                $table .= "<td>" .$route["length"] ."m</td>";
                $table .= "<td>" .$route["sector"] ."</td>";
                $table .= "</tr>";
            }
        }
        
        $table .= "</table>";
    }
    else
        $table = "<p>No routes</p>";
    
    // send table
    echo $table;
}

// show crag info page
elseif (isset($_GET["cragid"])) {
    
    // get crag info
    $sql = "SELECT * FROM crags WHERE cragid = ". $_GET["cragid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error);
    else
        $crag = $result->fetch_assoc();
    
    // get area info
    $sql = "SELECT name,areaid FROM areas WHERE areaid = ". $crag["areaid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error);
    else
        $area = $result->fetch_assoc();
    
    // show crag page
    view("crag_info.php", ["crag" => $crag, "area" => $area]);
}
    
$db->close();
    
?>