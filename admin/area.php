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

// add an area
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add")
{
    // set session data
    set_data("add", NULL);
    
    // show area form for adding a crag
    $button = "Add";
    $returnurl = SITEURL ."/areas.php";
    
    view("area_form.php", ["button" => $button, "returnurl" => $returnurl]);
}

// edit an existing area
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit")
{
    // get area details
    $result = $db->query("SELECT * FROM areas WHERE areaid=" .$_GET["areaid"]);
    if ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    else
        error("Area details could not be found. :( <p>areaid = " .$_GET["areaid"] ."</p><p> query = " .$sql ."</p>");
    
    // set session data
    set_data("edit", $_GET["areaid"]);
    
    // show form
    $button = "Update";
    $returnurl = SITEURL ."/crags.php?areaid=" .$_GET["areaid"];
    
    view("area_form.php", ["button" => $button, "area" => $area, "returnurl" => $returnurl]);
}

// show delete confirmation
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete")
{
    // get area details
    $result = $db->query("SELECT * FROM areas WHERE areaid=" .$_GET["areaid"] .";");
    if ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    else
        error("Cannot find area.");
    
    // set session data
    set_data("delete", $_GET["areaid"]);
    
    // show form
    $message = "Are you sure you want to delete <b>" .$area["name"] ."</b> and all associated crags and routes?";
    $controller = "area.php";
    $returnurl = SITEURL ."/crags.php?areaid=" .$_GET["areaid"];
    
    view("delete_form.php", ["message" => $message, "controller" => $controller, "returnurl" => $returnurl]);
}

// remove area from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete")
{
    // get area details
    $sql = "SELECT * FROM areas WHERE areaid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Area not found when getting area details. cragid = " .$_SESSION["id"] ." query = " .$sql);

    $area = $result->fetch_assoc();
    
    // get crags for area
    $crags = [];
    $sql = "SELECT * FROM crags WHERE areaid=" .$area["areaid"];
    if ($result = $db->query($sql)) {
        while($row = $result->fetch_assoc())
            array_push($crags, $row);
    }
    else
        error("Crag(s) not found for area. areaid = " .$area["areaid"] ." query = " .$sql);
    
    // delete crags and routes
    foreach ($crags as $crag) {
        $sql = "DELETE FROM crags WHERE cragid=" .$crag["cragid"] .";";
        $result = $db->query($sql);
        $sql = "DELETE FROM routes WHERE cragid=" .$crag["cragid"] .";";
        $result = $db->query($sql);
    }
    
    // delete area
    $sql = "DELETE FROM areas WHERE areaid=" .$area["areaid"] .";";
    if (!$result = $db->query($sql))
        error("Could not delete area. areaid = " .$area["areaid"] ." query = " .$sql);
    
    // return to area page
    header("Location: " .SITEURL ."/areas.php";

    clear_data();
}

// add or update an area
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    // security checks
    $name = sec_check($_POST["name"]);
    $description = sec_check($_POST["description"]);
    
    // add or update database
    if ($_SESSION["action"] == "add")
        $sql = "INSERT INTO areas (name,description) VALUES (\"" .$name ."\",\"" .$description ."\");";
    elseif ($_SESSION["action"] == "edit") {
        $sql = "UPDATE areas SET name=\"" .$name ."\",description=\"" .$description 
            ."\" WHERE areaid=" .$_SESSION["id"] .";";
    }
    
    $result = $db->query($sql);
    
    if ($result == FALSE)
        error("Area details could not be added/updated. :( <p>id = " .$_SESSION["id"] ."</p><p> query = " .$sql ."</p>");
    
    // get area details
    $sql = "SELECT * FROM areas WHERE name=\"" .$name ."\";";
    $result = $db->query($sql);
    
    if ($result->num_rows == 1)
        $area = $result->fetch_assoc();
    else
        error("Area details could not be retrieved. :( <p>id = " .$_SESSION["id"] ."</p><p> query = " .$sql ."</p>");
    
    // return to area page
    header("Location: " .SITEURL ."/crags.php?areaid=" .$area["areaid"]);
    
    // clear session data
    clear_data();
}

$db->close();
    
?>