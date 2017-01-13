<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/config.php 
 * Configuration file for CragBook including site and database info.
 */

// site title
$sitetitle = "Cragbook";

// the folder where your Cragbook installation resides (slash at the end)
$siteroot = "/home/ubuntu/workspace/cragbook/";

// the root web URL for your Cragbook installation (no tailing slash)
$siteurl = "https://web-dev-workspace-clbonner.c9users.io/cragbook";

// hostname
$host = getenv('IP');

// port number (default 3306)
$dbport = 3306;

// database name
$dbname = "c9";

// database user name
$dbuser = getenv('C9_USER');

// database user password
$dbpass = "";

session_start();
require_once($siteroot ."include/functions.php");

?>
