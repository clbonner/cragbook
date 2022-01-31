<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/area.php 
 * Controller for adding, editing and deleting areas from the database
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// show add area form
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add")
{
    set_data("add", NULL);
    $button = "Add";
    $returnurl = SITEURL ."/all_areas.php";
    
    view("area_form.php", ["button" => $button, "returnurl" => $returnurl]);
}

// show edit area form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit")
{
    $sql = "SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving that area.");
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    
    set_data("edit", $_GET["areaid"]);
    
    $button = "Save";
    $returnurl = SITEURL ."/area.php?areaid=" .$_GET["areaid"];
    
    view("area_form.php", ["button" => $button, "area" => $area, "returnurl" => $returnurl]);
}

// show delete confirmation
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete")
{
    $sql = "SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving area.");
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    
    set_data("delete", $_GET["areaid"]);
    
    $message = "Are you sure you want to delete <b>" .$area["name"] ."</b> and all associated crags and routes?";
    $controller = "area.php";
    $returnurl = SITEURL ."/area.php?areaid=" .$_GET["areaid"];
    
    view("delete_form.php", ["message" => $message, "controller" => $controller, "returnurl" => $returnurl]);
}

// remove area from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete")
{
    // get area and crag details
    $sql = "SELECT * FROM areas WHERE areaid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving area.");
    else
        $area = $result->fetch_assoc();
    
    
    $sql = "SELECT * FROM crags WHERE areaid=" .$area["areaid"];
    if (!$result = $db->query($sql)) 
        error("Error retrieving crags.");
    else {
        $crags = [];
        while($row = $result->fetch_assoc())
            array_push($crags, $row);
    }
    
    // remove crags and routes
    foreach ($crags as $crag) {
        $sql = "DELETE FROM crags WHERE cragid=" .$crag["cragid"] .";";
        if (!$result = $db->query($sql))
            error("Error deleting crags.");
        
        $sql = "DELETE FROM routes WHERE cragid=" .$crag["cragid"] .";";
        if (!$result = $db->query($sql))
            error("Error deleting routes.");
    }
    
    // remove area
    $sql = "DELETE FROM areas WHERE areaid=" .$area["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Error deleting area.");
    
    // return to area page
    header("Location: " .SITEURL ."/all_areas.php");

    clear_data();
}

// add or update an area
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    $name = $db->escape_string($_POST["name"]);
    $description = $db->escape_string($_POST["description"]);
    $location = $_POST["location"];
    if ($_POST["public"] == "on") $public = 1;
    else $public = 0;
    
    // add/update area details
    if ($_SESSION["action"] == "add")
        $sql = "INSERT INTO areas (name,description,location,public) VALUES (\"" .$name ."\",\"" .$description ."\",\"" .$location ."\"," .$public .");";
    elseif ($_SESSION["action"] == "edit") {
        $sql = "UPDATE areas SET name=\"" .$name ."\",description=\"" .$description 
            ."\",location=\"" .$location ."\",public=" .$public ." WHERE areaid=" .$_SESSION["id"] .";";
    }
    if (!$result = $db->query($sql))
        error("Error saving area.");
    
    // get area details
    $sql = "SELECT * FROM areas WHERE name=\"" .$name ."\";";
    if (!$result = $db->query($sql))
        error("Error retrieving area.");
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();

    // return to area page
    header("Location: " .SITEURL ."/area.php?areaid=" .$area["areaid"]);
    
    clear_data();
}

$db->close();
    
?>