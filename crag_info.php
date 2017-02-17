<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crag_info.php 
 * Controller for displaying crag information page
 */

require_once(__DIR__ ."/include/config.php");
$db = db_connect();

// show all crags if no cragid supplied
if (!isset($_GET["cragid"]))
{
    view("all_crags.php", []);
    exit;
}

// show crag info page
elseif (isset($_GET["cragid"])) {
    
    // get crag info
    $sql = "SELECT * FROM crags WHERE cragid = ". $_GET["cragid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error);
    else
        $crag = $result->fetch_assoc();
    
    // get area info
    $sql = "SELECT name,areaid FROM areas WHERE areaid = ". $crag["areaid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crag_info.php: " .$db->error);
    else
        $area = $result->fetch_assoc();
    
    // show crag page
    view("crag_info.php", ["crag" => $crag, "area" => $area]);
}
    
$db->close();
    
?>