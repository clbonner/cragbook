<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/route_sort.php 
 * Shows the route sorting page where the user can change the order of
 * routes at a particular crag.
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// get details and show route sorting page
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"];
    if (!$result = $db->query($sql))
        error("Error retrieving crag.");
    else
        $crag = $result->fetch_assoc();
    
    $returnurl = SITEURL ."/crag.php?cragid=" .$crag["cragid"];
    
    view("route_sort.php", ["crag" => $crag, "returnurl" => $returnurl]);
}

$db->close();
    
?>