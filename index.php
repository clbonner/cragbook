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

// get site preferences
$title = $db->query("SELECT value FROM site WHERE setting = \"home_title\";");
$text = $db->query("SELECT value FROM site WHERE setting = \"home_text\";");

$home_title = $title->fetch_row();
$home_text = $text->fetch_row();

// show home page
view("home.php", ["home_title" => $home_title[0], "home_text" => $home_text[0] ]);
    
?>