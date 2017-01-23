<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/prefs.php 
 * Controller for updating user and site preferences
 */

require_once("../include/config.php");

// check user is logged in before we start
if (!isset($_SESSION["userid"]))
    exit;

$db = db_connect();

// show preferences form
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $sql = "SELECT * FROM users WHERE userid=" .$_SESSION["userid"];
    if (!$result = $db->query($sql))
        error("Error in admin/prefs.php: " .$db->error);
    else
        $user = $result->fetch_assoc();
    
    view("prefs.php", ["user" => $user]);
}

// update preferences
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $button = "Back";
    $returnurl = SITEURL ."/admin/prefs.php";
    
    // get user details
    $sql = "SELECT * FROM users WHERE userid=" .$_SESSION["userid"];
    if (!$result = $db->query($sql))
        error("Error in admin/prefs.php: " .$db->error);
    else
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
    elseif (empty($displayname)) {
        view("info.php", ["message" => "You must provide a display name.", "button" => $button, "returnurl" => $returnurl]);
        exit;
    }
    elseif (!empty($oldpass)) {
        // check new password isn't blank
        if (empty($newpass)) {
            view("info.php", ["message" => "Your password cannot be blank.", "button" => $button, "returnurl" => $returnurl]);
            exit;
        }
            
        // check password matches current one
        if (password_verify($oldpass, $user["password"])) {
            if ($newpass == $confirmpass) {
                // update password in database
                $sql = "UPDATE users SET password=\"" .password_hash($newpass, PASSWORD_DEFAULT) ."\" WHERE userid=" .$_SESSION["userid"] .";";
                if (!$db->query($sql))
                    error("Error updating password: " .$db->error);
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
        error("Error updating user details:" .$db->error);
        
    info("Preferences updated.");
}

$db->close();
    
?>