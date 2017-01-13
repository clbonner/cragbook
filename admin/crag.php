<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/crag.php 
 * Controller for adding, editing and deleting crags from the database
 */

require_once("../include/config.php");
$db = db_connect();

// show new crag form
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add")
{
    set_data("add", $_GET["areaid"]);
    $returnurl = SITEURL ."/crags.php?areaid=" .$_GET["areaid"];
    
    view("crag_form.php", ["button" => "Add", "returnurl" => $returnurl]);
}

// show edit crag form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit")
{
    $sql = "SELECT * FROM crags WHERE cragid = " .$_GET["cragid"] .";";
    $crag_details = $db->query($sql);
    
    if ($crag_details->num_rows == 1)
        $crag = $crag_details->fetch_assoc();
    else
        error("Crag not found. cragid = " .$_GET["cragid"] ." query = " .$sql);
    
    set_data("edit", $_GET["cragid"]);
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$_GET["cragid"];
    
    view("crag_form.php", ["button" => "Save", "crag" => $crag, "returnurl" => $returnurl]);
}

// show crag delete form
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete")
{
    $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"] .";";
    $crag_details = $db->query($sql);
    
    if ($crag_details->num_rows == 1)
        $crag = $crag_details->fetch_assoc();
    else
        error("Crag not found. cragid = " .$_GET["cragid"] ." query = " .$sql);
    
    set_data("delete", $_GET["cragid"]);
    
    $message = "Are you sure you want to delete <b>" .$crag["name"] ."</b> and all associated routes?";
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$crag["cragid"];
    $controller = "crag.php";
    
    view("delete_form.php", ["message" => $message, "returnurl" => $returnurl,
        "controller" => $controller]);
}

// remove crag from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete")
{
    // get crag details
    $sql = "SELECT * FROM crags WHERE cragid=" .$_SESSION["id"] .";";
    $crag_details = $db->query($sql);
    
    if ($crag_details == FALSE)
        error("Crag not found when getting crag details. cragid = " .$_SESSION["id"] ." query = " .$sql);

    $crag = $crag_details->fetch_assoc();
    
    // get area details
    $sql = "SELECT * FROM areas WHERE areaid=" .$crag["areaid"];
    $area_details = $db->query($sql);
    
    if ($area_details->num_rows == 1)
        $area = $area_details->fetch_assoc();
    else
        error("Area not found when getting area details. areaid = " .$crag["areaid"] ." query = " .$sql);
    
    // remove crag and routes from database
    $sql = "DELETE FROM crags WHERE cragid=" .$_SESSION["id"] .";";
    $delete_crag = $db->query($sql);
    $sql = "DELETE FROM routes WHERE cragid=" .$_SESSION["id"] .";";
    $delete_routes = $db->query($sql);
    
    // return to the area page
    header("Location: " .SITEURL ."/crags.php?areaid=" .$area["areaid"]);
    
    clear_data();
}

// add or update existing crag
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    $name = sec_check($_POST["name"]);
    $description = sec_check($_POST["description"]);
    $access = sec_check($_POST["access"]);
    $policy = sec_check($_POST["policy"]);
    $location = sec_check($_POST["location"]);
    $approach = sec_check($_POST["approach"]);
    
    // add new crag
    if ($_SESSION["action"] == "add")
    {
        $sql = "INSERT INTO crags (areaid,name,description,access,policy,location,approach) VALUES (\"" .$_SESSION["id"] ."\",\"" .$name 
            ."\",\"" .$description ."\",\"" .$access ."\",\"" .$policy ."\",\"" .$location ."\",\"" .$approach ."\");";
    }

    // update crag details
    elseif ($_SESSION["action"] == "edit")
    {
        $sql = "UPDATE crags SET name=\"" .$name. "\",description=\"" .$description 
            ."\",access=\"" .$access ."\",policy=\"" .$policy ."\",location=\"" .$location 
            ."\",approach=\"" .$approach ."\" WHERE cragid = " .$_SESSION["id"] .";";
    }
    
    $result = $db->query($sql);
    
    if ($result == FALSE)
        error("Crag details could not be added/updated. :( <p>id = " .$_SESSION["id"] ."</p><p> query = " .$sql ."</p>");
    
    // get cragid if newly added
    if ($_SESSION["action"] == "add") {
        $sql = "SELECT * FROM crags WHERE name=\"" .$name ."\" AND areaid=" .$_SESSION["id"] .";";
        $result = $db->query($sql);
        
        if ($result->num_rows == 1)
            $crag = $result->fetch_assoc();
        else
            error("Crag details could not be retrieved. :( <p>id = " .$_SESSION["id"] ."</p><p> query = " .$sql ."</p>");
    }
    elseif ($_SESSION["action"] == "edit")
        $crag["cragid"] = $_SESSION["id"];
    
    // return to crag page
    header("Location: " .SITEURL ."/crag_info.php?cragid=" .$crag["cragid"]);
    
    clear_data();
}

$db->close();
    
?>