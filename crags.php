<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * crags.php 
 * Controller for displaying crags in a given area.
 */
 
require_once(__DIR__ ."/include/config.php");
$db = db_connect();

// show all crags when no areaid
if (!isset($_GET["areaid"])) {
    view("all_crags.php", []);
}

// get area info for areaid
elseif (isset($_GET["areaid"])) {
    $sql = "SELECT * FROM areas WHERE areaid = ". $_GET["areaid"] .";";
    
    if (!$result = $db->query($sql))
        error("Error in crags.php: " .$db->error);
    
    $area = $result->fetch_assoc();
    
    view("crags.php", ["area" => $area]);
}

$db->close();
    
?>