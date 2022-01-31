<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crag.php 
 * Controller for displaying crag information page
 */

require_once(__DIR__ ."/include/config.php");
$db = db_connect();

// get crag info
is_valid_num($_GET["cragid"]);
$sql = "SELECT areaid FROM crags WHERE cragid = ". $_GET["cragid"] .";";

if (!$result = $db->query($sql))
    error("Error retrieving crag info.");
else
    $crag = $result->fetch_assoc();

// get area info
$sql = "SELECT name,areaid FROM areas WHERE areaid = ". $crag["areaid"] .";";

if (!$result = $db->query($sql))
    error("Error retrieving area info.");
else
    $area = $result->fetch_assoc();

// show crag page
view("crag.php", ["area" => $area]);

$db->close();

?>