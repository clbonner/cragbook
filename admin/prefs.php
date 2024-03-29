<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/prefs.php
 * Controller for updating user and site preferences
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// show preferences form
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $crags = [];

    // get user details
    $sql = "SELECT * FROM users WHERE userid=" .$_SESSION["userid"];
    if (!$result = $db->query($sql))
        error("Error retrieving user.");
    else
        $user = $result->fetch_assoc();

    // get list of crags
    $sql = "SELECT name,cragid FROM crags ORDER BY name ASC";
    if (!$result = $db->query($sql))
        error("Error retrieving crags.");
    elseif ($result->num_rows !== NULL)
        while ($crag = $result->fetch_assoc()) {
          array_push($crags, $crag);
        }

    view("prefs.php", ["user" => $user, "crags" => $crags]);
}

// update preferences
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $button = "Back";
    $returnurl = SITEURL ."/admin/prefs.php";

    // get user details
    $sql = "SELECT * FROM users WHERE userid=" .$_SESSION["userid"];
    if (!$result = $db->query($sql))
        error("Error retrieving user.");
    else
        $user = $result->fetch_assoc();

    // security checks
    $username = $db->escape_string($_POST["username"]);
    $displayname = $db->escape_string($_POST["displayname"]);
    $oldpass = $db->escape_string($_POST["oldpass"]);
    $newpass = $db->escape_string($_POST["newpass"]);
    $confirmpass = $db->escape_string($_POST["confirmpass"]);

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
