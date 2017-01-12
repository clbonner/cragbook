<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/login.php
 * Controller for logging users in.
 */

require_once("../include/config.php");

// show login form
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    view("login.php", []);
}

// check users credentials
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // validate submission
    if (empty($_POST["username"])) {
        error("You must provide your username.");
    }
    else if (empty($_POST["password"])) {
        error("You must provide your password.");
    }
    
    // security checks
    $username = sec_check($_POST["username"]);
    $password = sec_check($_POST["password"]);
    
    $db = db_connect();
    
    // get user details
    $sql = "SELECT * FROM users WHERE username = \"" .$username ."\";";
    $result = $db->query($sql);
    
    // check the user exists
    if ($result->num_rows == 1) {
        $user = [];
        $user = $result->fetch_assoc();
    }
    else 
        error("Username not found.");
    
    // check the password supplied matches the hash in the database
    if (password_verify($password, $user["password"])) {
        $_SESSION["userid"] = $user["userid"];
        $_SESSION["username"] = $user["username"];
    }
    else
        error("Invalid username and/or password.");
        
    // show homepage
    require(SITEROOT ."index.php");
    
    $db->close();
}

?>