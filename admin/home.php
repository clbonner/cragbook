<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * edit_home.php 
 * Controller for editing the home page title and text.
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// edit home page text
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM site;";
    if (!$result = $db->query($sql))
        error("Error in admin/home.php: " .$db->error);
    else {
        while ($row = $result->fetch_assoc())
            $site[$row["setting"]] = $row["value"];
    }
    
    view("home_form.php", ["site" => $site]);
    
    $db->close();
}

// update home page text
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $db->escape_string($_POST["text"]);
    
    $sql = "UPDATE site SET value = \"" .$text ."\" WHERE setting = \"home_text\";";
    if (!$result = $db->query($sql))
        error("Error in home.php: " .$db->error);
    
    require(__DIR__ ."/../index.php");
}

?>