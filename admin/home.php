<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * edit_home.php 
 * Controller for editing the home page title and text.
 */

require_once("../include/config.php");
$db = db_connect();

// edit home page text
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM site;";
    $result = $db->query($sql);
    
    while ($row = $result->fetch_assoc())
        $site[$row["setting"]] = $row["value"];
    
    view("home_form.php", ["site" => $site]);
}

// update home page text
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = sec_check($_POST["text"]);
    
    $result = $db->query("UPDATE site SET value = \"" .$text ."\" WHERE setting = \"home_text\";" );
    
    require(SITEROOT ."index.php");
}

$db->close();
    
?>