<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/route.php 
 * Controller for adding, editing and deleting routes from the database
 */

require_once("../include/config.php");
$db = db_connect();

// add a new route
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "add")
{
    // set session data and show form
    set_data("add", $_GET["cragid"]);
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$_GET["cragid"];
    
    view("route_form.php", ["button" => "Add", "returnurl" => $returnurl]);
}

// edit existing route
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "edit")
{
    // get route details
    $sql = "SELECT * FROM routes WHERE routeid = " .$_GET["routeid"] .";";
    $result = $db->query($sql);
    
    if ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    else
        error("Route not found. routeid = " .$_GET["routeid"] ." query = " .$sql);
    
    // set session data and show form
    set_data("edit", $_GET["routeid"]);
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$route["cragid"];

    view("route_form.php", ["button" => "Save", "route" => $route, "returnurl" => $returnurl]);
}

// delete confirmation
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "delete")
{
    // get route info
    $sql = "SELECT * FROM routes WHERE routeid=" .$_GET["routeid"] .";";
    $result = $db->query($sql);
    
    if ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    else
        error("Route not found. routeid = " .$_GET["routeid"] ." query = " .$sql);
    
    set_data("delete", $_GET["routeid"]);
    
    // show confirmation
    $message = "Are you sure you want to delete the route <b>" .$route["name"] ."</b>?";
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$route["cragid"];
    $controller = "route.php";
    
    view("delete_form.php", ["message" => $message, "returnurl" => $returnurl,
        "controller" => $controller]);
}

// remove route from database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "delete")
{
    // get route info
    $sql = "SELECT * FROM routes WHERE routeid=" .$_SESSION["id"] .";";
    $result = $db->query($sql);
    
    if ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    else
        error("Route not found. routeid = " .$_SESSION["id"] ." query = " .$sql);
    
    // get crag info
    $sql = "SELECT * FROM crags WHERE cragid=" .$route["cragid"] .";";
    $result = $db->query($sql);
    
    if ($result->num_rows == 1)
        $crag = $result->fetch_assoc();
    else
        error("Crag not found before attempting to delete route. cragid = " .$route["cragid"] ." query = " .$sql);
    
    // delete route
    $sql = "DELETE FROM routes WHERE routeid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Cannot delete route. routeid = " .$_SESSION["id"] ." query = " .$sql);
    
    // return to crag page
    header("Location: " .SITEURL ."/crag_info.php?cragid=" .$crag["cragid"];
    
    clear_data();
}

// update route database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    // security checks
    $name = sec_check($_POST["name"]);
    $description = sec_check($_POST["description"]);
    $grade = sec_check($_POST["grade"]);
    $stars = sec_check($_POST["stars"]);
    $length = sec_check($_POST["length"]);
    $sector = sec_check($_POST["sector"]);
    
    // add/update database
    if ($_SESSION["action"] == "add")
    {
        // get last route at crag
        $sql = "SELECT * FROM routes WHERE cragid=" .$_SESSION["id"] ." ORDER BY orderid DESC LIMIT 1;";
        $result = $db->query($sql);
        if ($result->num_rows == 0)
            $orderid = 1;
        elseif ($result != FALSE) {
            $last = $result->fetch_assoc();
            $orderid = ++$last["orderid"];
        }
            
        $sql = "INSERT INTO routes (cragid,name,description,grade,stars,length,sector,orderid) VALUES (" .$_SESSION["id"] .",\"" .$name 
            ."\",\"" .$description ."\",\"" .$grade ."\",\"" .$stars ."\"," .$length .",\"" .$sector ."\"," .$orderid .");";
    }
    elseif ($_SESSION["action"] == "edit")
    {
        $sql = "UPDATE routes SET name=\"" .$name. "\",description=\"" .$description 
            ."\",grade=\"" .$grade ."\",stars=\"" .$stars ."\",length=\"" .$length 
            ."\",sector=\"" .$sector ."\" WHERE routeid = " .$_SESSION["id"] .";";
    }
    $result = $db->query($sql);
    
    if ($result == FALSE)
        error("Route details could not be added/updated. :( <p>id = " .$_SESSION["id"] ."</p><p> query = " .$sql ."</p>");
    
    // get cragid
    if ($_SESSION["action"] == "edit") {
        $sql = "SELECT * FROM routes WHERE routeid=" .$_SESSION["id"] .";";
        if ($result = $db->query($sql))
            $route = $result->fetch_assoc();
        else
            error("Cannot get route info. :( <p>id = " .$_SESSION["id"] ."</p><p> query = " .$sql ."</p>");
        
        $cragid = $route["cragid"];
    }
    else
        $cragid = $_SESSION["id"];
    
    // get crag details
    $sql = "SELECT * FROM crags WHERE cragid=" .$cragid .";";
        if ($result = $db->query($sql))
            $crag = $result->fetch_assoc();
        else
            error("Cannot get crag info. :( <p>id = " .$route["cragid"] ."</p><p> query = " .$sql ."</p>");

    // return to crag page
    header("Location: " .SITEURL ."/crag_info.php?cragid=" .$crag["cragid"];

    clear_data();
}

$db->close();
    
?>