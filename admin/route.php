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
        error("Error in admin/route.php: " .$db->error);
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
        error("Error in admin/route.php: " .$db->error);
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
        error("Error in admin/route.php: " .$db->error);
    elseif ($result->num_rows == 1)
        $route = $result->fetch_assoc();
    
    // get crag info
    $sql = "SELECT * FROM crags WHERE cragid=" .$route["cragid"] .";";
    if (!$result = $db->query($sql))
        error("Error in admin/route.php: " .$db->error);
    elseif ($result->num_rows == 1)
        $crag = $result->fetch_assoc();
    
    // remove route
    $sql = "DELETE FROM routes WHERE routeid=" .$_SESSION["id"] .";";
    if (!$result = $db->query($sql))
        error("Error in admin/route.php: " .$db->error);
    
    // return to crag page
    header("Location: " .SITEURL ."/crag.php?cragid=" .$crag["cragid"]);
    
    clear_data();
}

// update route database
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["action"] == "add" || $_SESSION["action"] == "edit")
{
    $name = sec_check($_POST["name"]);
    $description = sec_check($_POST["description"]);
    $grade = sec_check($_POST["grade"]);
    $stars = sec_check($_POST["stars"]);
    $length = sec_check($_POST["length"]);
    $sector = sec_check($_POST["sector"]);
    $fascent = sec_check($_POST["fascent"]);
    $discipline = sec_check($_POST["discipline"]);
    $seriousness = sec_check($_POST["seriousness"]);
    if (!is_numeric($discipline)) error("Invalid entry for discipline.");
    if (!is_numeric($seriousness)) $seriousness = 0;
    if ($_POST["private"] == "on") $private = 1;
    else $private = 0;
    
    var_dump($private);
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
        error("Error in admin/route.php: " .$db->error);
    
    // get cragid
    if ($_SESSION["action"] == "edit") {
        $sql = "SELECT * FROM routes WHERE routeid=" .$_SESSION["id"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/route.php: " .$db->error);
        else
            $route = $result->fetch_assoc();
        
        $cragid = $route["cragid"];
    }
    else
        $cragid = $_SESSION["id"];
    
    // get crag details
    $sql = "SELECT * FROM crags WHERE cragid=" .$cragid .";";
    if (!$result = $db->query($sql))
        error("Error in admin/route.php: " .$db->error);
    else
        $crag = $result->fetch_assoc();
    
    // return to crag page
    header("Location: " .SITEURL ."/crag.php?cragid=" .$crag["cragid"]);

    clear_data();
}

$db->close();
    
?>