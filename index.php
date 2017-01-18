<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * index.php 
 * Controller for displaying the home page.
 */
    
require_once("include/config.php");
$db = db_connect();

$sql = "SELECT value FROM site WHERE setting = \"home_text\";";

if (!$result = $db->query($sql))
        error("Error in index.php: " .$db->error);

$home_text = $result->fetch_row();

view("home.php", ["home_text" => $home_text[0] ]);

$db->close();
    
?>