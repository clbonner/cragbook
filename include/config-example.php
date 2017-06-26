<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/config.php 
 * Configuration file for CragBook including site and database info.
 */

//================= Start of configuration section ===================//

// site title
$sitetitle = "Cragbook";

// the root web URL for your Cragbook installation (no tailing slash)
$siteurl = "http://example.com/cragbook";

// database hostname
$host = "";

// datbase port number (default 3306)
$dbport = 3306;

// database name
$dbname = "";

// database user name
$dbuser = "";

// database user password
$dbpass = "";

// key for google maps api
$googlemaps_apikey = "";

//================= End of configuration section ===================//

session_start();
require_once(__DIR__ ."/functions.php");

?>
