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

// edit home page
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get site settings
    $sql = "SELECT * FROM site;";
    $result = $db->query($sql);
    
    while ($row = $result->fetch_assoc())
        $site[$row["setting"]] = $row["value"];
    
    // show form
    view("home_form.php", ["site" => $site]);
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // security check
    $text = sec_check($_POST["text"]);
    
    // update database
    $result = $db->query("UPDATE site SET value = \"" .$text ."\" WHERE setting = \"home_text\";" );
    
    // return to home page
    require(SITEROOT ."index.php");
}

$db->close();
    
?>