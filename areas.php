<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * areas.php 
 * Controller for listing climbing areas
 */

require_once("include/config.php");
$db = db_connect(); 

// get list of areas
$sql = "SELECT areaid,name FROM areas ORDER BY name ASC;";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while($area = $result->fetch_assoc()) 
        $areas[$area["areaid"]] = $area["name"];
}
else
    $areas = 0;

view("areas.php", ["areas" => $areas]);

$db->close();

?>