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
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    // get crag info
    $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"];
    if (!$result = $db->query($sql))
        error("Cannot retrieve crag details. (route_sort.php) query = " .$sql);
    
    $crag = $result->fetch_assoc();
    $returnurl = SITEURL ."/crag_info.php?cragid=" .$crag["cragid"];
    
    // show form
    view("route_sort.php", ["crag" => $crag, "returnurl" => $returnurl]);
}

$db->close();
    
?>