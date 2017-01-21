<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/route_update.php 
 * Returns JSON data so the user can manipulate the 
 * route order for a crag, then submit it back to update the database.
 */

require_once("../include/config.php");

// check user is logged in before we start
if (!isset($_SESSION["userid"]))
    exit;

$db = db_connect();

// get details and show route sorting page
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"];
    if (!$result = $db->query($sql))
        error("Error in admin/route_sort.php: " .$db->error);
    else
        $crag = $result->fetch_assoc();
    
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$crag["cragid"];
    
    view("route_sort.php", ["crag" => $crag, "returnurl" => $returnurl]);
}

$db->close();
    
?>