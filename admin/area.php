<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/area.php 
 * Controller for adding, editing and deleting areas from the database
 */

require_once("../include/config.php");
$db = db_connect();

// show add area form
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add")
{
    set_data("add", NULL);
    $button = "Add";
    $returnurl = SITEURL ."/areas.php";
    
    view("area_form.php", ["button" => $button, "returnurl" => $returnurl]);
}

// show edit area form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit")
{
    $sql = "SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Error in admin/area.php: " .$db->error);
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    
    set_data("edit", $_GET["areaid"]);
    
    $button = "Update";
    $returnurl = SITEURL ."/crags.php?areaid=" .$_GET["areaid"];
    
    view("area_form.php", ["button" => $button, "area" => $area, "returnurl" => $returnurl]);
}

// show delete confirmation
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete")
{
    $sql = "SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Error in admin/area.php: " .$db->error);
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    
    set_data("delete", $_GET["areaid"]);
    
    $message = "Are you sure you want to delete <b>" .$area["name"] ."</b> and all associated crags and routes?";
    $controller = "area.php";
    $returnurl = SITEURL ."/crags.php?areaid=" .$_GET["areaid"];
    
    view("delete_form.php", ["message" => $message, "controller" => $controller, "returnurl" => $returnurl]);
}

// remove area from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete")
{
    // get area and crag details
    $sql = "SELECT * FROM areas WHERE areaid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error in admin/area.php: " .$db->error);
    else
        $area = $result->fetch_assoc();
    
    
    $sql = "SELECT * FROM crags WHERE areaid=" .$area["areaid"];
    if (!$result = $db->query($sql)) 
        error("Error in admin/area.php: " .$db->error);
    else {
        $crags = [];
        while($row = $result->fetch_assoc())
            array_push($crags, $row);
    }
    
    // remove crags and routes
    foreach ($crags as $crag) {
        $sql = "DELETE FROM crags WHERE cragid=" .$crag["cragid"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/area.php: " .$db->error);
        
        $sql = "DELETE FROM routes WHERE cragid=" .$crag["cragid"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/area.php: " .$db->error);
    }
    
    // remove area
    $sql = "DELETE FROM areas WHERE areaid=" .$area["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Error in admin/area.php: " .$db->error);
    
    // return to area page
    header("Location: " .SITEURL ."/areas.php");

    clear_data();
}

// add or update an area
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    $name = sec_check($_POST["name"]);
    $description = sec_check($_POST["description"]);
    $location = $_POST["location"];
    
    // add/update area details
    if ($_SESSION["action"] == "add")
        $sql = "INSERT INTO areas (name,description,location) VALUES (\"" .$name ."\",\"" .$description ."\",\"" .$location ."\");";
    elseif ($_SESSION["action"] == "edit") {
        $sql = "UPDATE areas SET name=\"" .$name ."\",description=\"" .$description 
            ."\",location=\"" .$location ."\" WHERE areaid=" .$_SESSION["id"] .";";
    }
    if (!$result = $db->query($sql))
        error("Error in admin/area.php: " .$db->error);
    
    // get area details
    $sql = "SELECT * FROM areas WHERE name=\"" .$name ."\";";
    if (!$result = $db->query($sql))
        error("Error in admin/area.php: " .$db->error);
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();

    // return to area page
    header("Location: " .SITEURL ."/crags.php?areaid=" .$area["areaid"]);
    
    clear_data();
}

$db->close();
    
?>