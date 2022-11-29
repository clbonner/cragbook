<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/route.php 
 * Controller for adding, editing and deleting routes from the database
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// show new route form
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add") {
    set_data("add", $_GET["cragid"]);
    $returnurl = SITEURL ."/crag.php?cragid=" .$_GET["cragid"];
    
    view("route_form.php", ["button" => "Add", "returnurl" => $returnurl]);
}

// show edit route form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit") {
    $sql = "SELECT * FROM routes WHERE routeid = " .$_GET["routeid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving route.");
    elseif ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    
    set_data("edit", $_GET["routeid"]);
    $returnurl = SITEURL ."/crag.php?cragid=" .$route["cragid"];

    view("route_form.php", ["button" => "Save", "route" => $route, "returnurl" => $returnurl]);
}

// show delete confirmation
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete") {
    $sql = "SELECT * FROM routes WHERE routeid=" .$_GET["routeid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving route.");
    elseif ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    
    set_data("delete", $_GET["routeid"]);
    
    $message = "Are you sure you want to delete the route <b>" .$route["name"] ."</b>?";
    $returnurl = SITEURL ."/crag.php?cragid=" .$route["cragid"];
    $controller = "route.php";
    
    view("delete_form.php", ["message" => $message, "returnurl" => $returnurl,
        "controller" => $controller]);
}

// remove route from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete") {
    // get route info
    $sql = "SELECT * FROM routes WHERE routeid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving route.");
    elseif ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    
    // get crag info
    $sql = "SELECT * FROM crags WHERE cragid=" .$route["cragid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving crag.");
    elseif ($result->num_rows == 1)
        $crag = $result->fetch_assoc();
    
    // remove route
    $sql = "DELETE FROM routes WHERE routeid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error deleting routes.");
    
    // return to crag page
    header("Location: " .SITEURL ."/crag.php?cragid=" .$crag["cragid"]);
    
    clear_data();
}

// update route database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    $name = $db->escape_string($_POST["name"]);
    $description = $db->escape_string($_POST["description"]);
    $grade = $db->escape_string($_POST["grade"]);
    $stars = $db->escape_string($_POST["stars"]);
    $length = $db->escape_string($_POST["length"]);
    $sector = $db->escape_string($_POST["sector"]);
    $fascent = $db->escape_string($_POST["fascent"]);
    $discipline = $db->escape_string($_POST["discipline"]);
    $seriousness = $db->escape_string($_POST["seriousness"]);
    if (!is_numeric($discipline)) error("Invalid entry for discipline.");
    if (!is_numeric($seriousness)) $seriousness = 0;
    if ($_POST["private"] == "on") $private = 1;
    else $private = 0;

    // add/update database
    if ($_SESSION["action"] == "add") {
        $sql = "INSERT INTO routes (cragid,name,description,grade,stars,length,sector,firstascent,discipline,seriousness,private) VALUES (" .$_SESSION["id"] .",\"" .$name 
            ."\",\"" .$description ."\",\"" .$grade ."\",\"" .$stars ."\"," .$length .",\"" .$sector ."\",\"" .$fascent ."\"," .$discipline ."," .$seriousness ."," .$private .");";
    }
    elseif ($_SESSION["action"] == "edit") {
        $sql = "UPDATE routes SET name=\"" .$name. "\",description=\"" .$description 
            ."\",grade=\"" .$grade ."\",stars=\"" .$stars ."\",length=\"" .$length 
            ."\",sector=\"" .$sector ."\",firstascent=\"" .$fascent ."\",discipline=" .$discipline .",seriousness=" 
            .$seriousness .",private=" .$private ." WHERE routeid = " .$_SESSION["id"] .";";
    }
    if (!$result = $db->query($sql))
        error("Error saving route.");
    
    // get cragid
    if ($_SESSION["action"] == "edit") {
        $sql = "SELECT * FROM routes WHERE routeid=" .$_SESSION["id"] .";";
        if (!$result = $db->query($sql))
            error("Error retrieving route.");
        else
            $route = $result->fetch_assoc();
        
        $cragid = $route["cragid"];
    }
    else
        $cragid = $_SESSION["id"];
    
    // get crag details
    $sql = "SELECT * FROM crags WHERE cragid=" .$cragid .";";
    if (!$result = $db->query($sql))
        error("Error retrieving crag.");
    else
        $crag = $result->fetch_assoc();
    
    // return to crag page
    header("Location: " .SITEURL ."/crag.php?cragid=" .$crag["cragid"]);

    clear_data();
}

$db->close();
    
?>