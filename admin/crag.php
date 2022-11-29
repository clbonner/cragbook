<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/crag.php 
 * Controller for adding, editing and deleting crags from the database
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// show new crag form
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add")
{
    set_data("add", $_GET["areaid"]);
    $returnurl = SITEURL ."/area.php?areaid=" .$_GET["areaid"];
    
    view("crag_form.php", ["button" => "Add", "returnurl" => $returnurl]);
}

// show edit crag form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit")
{
    $sql = "SELECT * FROM crags WHERE cragid = " .$_GET["cragid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving crag.");
    elseif ($result->num_rows == 1)
        $crag = $result->fetch_assoc();
    
    set_data("edit", $_GET["cragid"]);
    $returnurl = SITEURL ."/crag.php?cragid=" .$_GET["cragid"];
    
    view("crag_form.php", ["button" => "Save", "crag" => $crag, "returnurl" => $returnurl]);
}

// show crag delete form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete")
{
    $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving crag.");
    elseif ($result->num_rows == 1)
        $crag = $result->fetch_assoc();
    
    set_data("delete", $_GET["cragid"]);
    
    $message = "Are you sure you want to delete <b>" .$crag["name"] ."</b> and all associated routes?";
    $returnurl = SITEURL ."/crag.php?cragid=" .$crag["cragid"];
    $controller = "crag.php";
    
    view("delete_form.php", ["message" => $message, "returnurl" => $returnurl,
        "controller" => $controller]);
}

// remove crag from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete")
{
    // get crag details
    $sql = "SELECT * FROM crags WHERE cragid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error retrieving crag.");
    else
        $crag = $result->fetch_assoc();
    
    // get area details
    $sql = "SELECT * FROM areas WHERE areaid=" .$crag["areaid"];
    if (!$result = $db->query($sql))
        error("Error retrieving area.");
    elseif ($result->num_rows == 1)
        $area = $result->fetch_assoc();

    // remove crag and routes from database
    $sql = "DELETE FROM crags WHERE cragid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error deleting crag.");
    
    $sql = "DELETE FROM routes WHERE cragid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error deleting routes.");
    
    // return to the area page
    header("Location: " .SITEURL ."/area.php?areaid=" .$area["areaid"]);
    
    clear_data();
}

// add or update existing crag
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    $name = $db->escape_string($_POST["name"]);
    $description = $db->escape_string($_POST["description"]);
    $access = $db->escape_string($_POST["access"]);
    $policy = $db->escape_string($_POST["policy"]);
    $location = $db->escape_string($_POST["location"]);
    $approach = $db->escape_string($_POST["approach"]);
    if ($_POST["public"] == "on") $public = 1;
    else $public = 0;
    if ($_POST["hide_descriptions"] == "on") $hide_descriptions = 1;
    if ($_POST["show_descriptions"] == "on") $show_descriptions = 1;
    
    // add new crag
    if ($_SESSION["action"] == "add") {
        $sql = "INSERT INTO crags (areaid,name,description,access,policy,location,approach,public) VALUES (\"" .$_SESSION["id"] ."\",\"" .$name 
            ."\",\"" .$description ."\",\"" .$access ."\",\"" .$policy ."\",\"" .$location ."\",\"" .$approach ."\"," .$public .");";
    }

    // update crag details
    elseif ($_SESSION["action"] == "edit") {
        $sql = "UPDATE crags SET name=\"" .$name. "\",description=\"" .$description 
            ."\",access=\"" .$access ."\",policy=\"" .$policy ."\",location=\"" .$location 
            ."\",approach=\"" .$approach ."\",public=" .$public ." WHERE cragid = " .$_SESSION["id"] .";";
    }
    
    if (!$result = $db->query($sql))
        error("Error saving crag.");
    
    // get cragid if newly added
    if ($_SESSION["action"] == "add") {
        $sql = "SELECT * FROM crags WHERE name=\"" .$name ."\" AND areaid=" .$_SESSION["id"] .";";
        if (!$result = $db->query($sql))
            error("Error retrieving crag.");
        elseif ($result->num_rows == 1)
            $crag = $result->fetch_assoc();
    }
    elseif ($_SESSION["action"] == "edit") {
        $crag["cragid"] = $_SESSION["id"];
        
        // update hide/show descriptions
        if ($hide_descriptions == 1 && $show_descriptions == 0) {
            $sql = "UPDATE routes SET private=1 WHERE cragid=" .$crag["cragid"];
            if (!$result = $db->query($sql))
                error("Error in admin/crag.php: " .$db->error);
        }
        else if ($show_descriptions == 1 && $hide_descriptions == 0) {
            $sql = "UPDATE routes SET private=0 WHERE cragid=" .$crag["cragid"];
            if (!$result = $db->query($sql))
                error("Error in admin/crag.php: " .$db->error);
        }
    }
    
    // return to crag page
    header("Location: " .SITEURL ."/crag.php?cragid=" .$crag["cragid"]);
    
    clear_data();
}

$db->close();
    
?>