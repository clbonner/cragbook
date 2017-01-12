<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/prefs.php 
 * Controller for updating user and site preferences
 */

require_once("../include/config.php");
$db = db_connect();

// show preferences form
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    // get user details
    $sql = "SELECT * FROM users WHERE userid=" .$_SESSION["userid"];
    $result = $db->query($sql);
    
    $user = $result->fetch_assoc();
    
    view("prefs.php", ["user" => $user]);
}

// update preferences
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // info view parameters
    $button = "Back";
    $returnurl = SITEURL ."/admin/prefs.php";
    
    // get user details
    $sql = "SELECT * FROM users WHERE userid=" .$_SESSION["userid"];
    $result = $db->query($sql);
    
    $user = $result->fetch_assoc();
    
    // security checks
    $username = sec_check($_POST["username"]);
    $displayname = sec_check($_POST["displayname"]);
    $oldpass = sec_check($_POST["oldpass"]);
    $newpass = sec_check($_POST["newpass"]);
    $confirmpass = sec_check($_POST["confirmpass"]);
    
    // validate submission
    if (empty($username)) {
        view("info.php", ["message" => "You must provide a username.", "button" => $button, "returnurl" => $returnurl]);
        exit;
    }
    if (empty($displayname)) {
        view("info.php", ["message" => "You must provide a display name.", "button" => $button, "returnurl" => $returnurl]);
        exit;
    }
    
    // check passwords
    if (!empty($oldpass)) {
        // check new password isn't blank
        if (empty($newpass)) {
            view("info.php", ["message" => "Your password cannot be blank.", "button" => $button, "returnurl" => $returnurl]);
            exit;
        }
            
        // check password matches current one
        if (password_verify($oldpass, $user["password"])) {
            if ($newpass == $confirmpass) {
                //update password in database
                $sql = "UPDATE users SET password=\"" .password_hash($newpass, PASSWORD_DEFAULT) ."\" WHERE userid=" .$_SESSION["userid"] .";";
                if (!$db->query($sql))
                    error("Error updating password.");
            }
            else {
                view("info.php",["message" => "New passwords do not match!", "button" => $button, "returnurl" => $returnurl]);
                exit;
            }
        }
        else {
            view("info.php",["message" => "Old password incorrect.", "button" => $button, "returnurl" => $returnurl]);
            exit;
        }
    }
    
    // update username/displayname
    $sql = "UPDATE users SET username=\"" .$username ."\", displayname=\"" .$displayname ."\" WHERE userid=" .$_SESSION["userid"] .";";
    if (!$db->query($sql))
        error("Error updating user details.");
        
    info("Preferences updated.");
}

$db->close();
    
?>