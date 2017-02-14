<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/functions.php 
 * System functions and variables for accessing data and displaying user information.
 */

// macros

define("SITEURL", $GLOBALS["siteurl"]);

// grade lists for ordering and filtering routes by grade

// start and end SQL statements for ordering by grade
$OrderByGrade = "ORDER BY (CASE ";
$ElseAsc = "ELSE 1000 END) ASC;";

// british adjective grade order
$BritishAdj .= "WHEN grade = 'E' THEN 1 ";
$BritishAdj .= "WHEN grade = 'M' THEN 2 ";
$BritishAdj .= "WHEN grade LIKE \"MD%\" THEN 3 ";
$BritishAdj .= "WHEN grade LIKE \"D%\" THEN 4 ";
$BritishAdj .= "WHEN grade LIKE \"HD%\" THEN 5 ";
$BritishAdj .= "WHEN grade LIKE \"MVD%\" THEN 6 ";
$BritishAdj .= "WHEN grade LIKE \"VD%\" THEN 7 ";
$BritishAdj .= "WHEN grade LIKE \"HVD%\" THEN 8 ";
$BritishAdj .= "WHEN grade LIKE \"MS%\" THEN 9 ";
$BritishAdj .= "WHEN grade LIKE \"S%\" THEN 10 ";
$BritishAdj .= "WHEN grade LIKE \"HS%\" THEN 11 ";
$BritishAdj .= "WHEN grade LIKE \"MVS%\" THEN 12 ";
$BritishAdj .= "WHEN grade LIKE \"VS%\" THEN 13 ";
$BritishAdj .= "WHEN grade LIKE \"HVS%\" THEN 14 ";
$BritishAdj .= "WHEN grade LIKE \"E%\" THEN 15 ";

// british adjective grade SQL filter
$BritishAdjFilter = " AND (grade LIKE \"M%\" OR grade LIKE \"D%\" OR grade LIKE \"E%\" ";
$BritishAdjFilter .= "OR grade LIKE \"VD%\" OR grade LIKE \"VS%\" OR grade LIKE \"H%\" OR grade LIKE \"E%\") ";

// french numerical grade SQL filter
$frenchGradeFilter = "AND grade LIKE \"F%\" ";

// font grade filter
$fontGradeFilter = "AND grade LIKE \"f%\" ";

// YDS grade filter
$ydsGradeFilter = "AND grade LIKE \"5.%\" ";

// UIAA numerical grade order
$uiaaGrade .= "WHEN grade LIKE \"I\" THEN 400 ";
$uiaaGrade .= "WHEN grade LIKE \"II\" THEN 401 ";
$uiaaGrade .= "WHEN grade LIKE \"III\" THEN 402 ";
$uiaaGrade .= "WHEN grade LIKE \"III+\" THEN 403 ";
$uiaaGrade .= "WHEN grade LIKE \"IV-\" THEN 404 ";
$uiaaGrade .= "WHEN grade LIKE \"IV\" THEN 405 ";
$uiaaGrade .= "WHEN grade LIKE \"IV+\" THEN 406 ";
$uiaaGrade .= "WHEN grade LIKE \"V-\" THEN 407 ";
$uiaaGrade .= "WHEN grade LIKE \"V\" THEN 408 ";
$uiaaGrade .= "WHEN grade LIKE \"V+\" THEN 409 ";
$uiaaGrade .= "WHEN grade LIKE \"VI-\" THEN 410 ";
$uiaaGrade .= "WHEN grade LIKE \"VI\" THEN 411 ";
$uiaaGrade .= "WHEN grade LIKE \"VI+\" THEN 412 ";
$uiaaGrade .= "WHEN grade LIKE \"VII-\" THEN 413 ";
$uiaaGrade .= "WHEN grade LIKE \"VII\" THEN 414 ";
$uiaaGrade .= "WHEN grade LIKE \"VII+\" THEN 415 ";
$uiaaGrade .= "WHEN grade LIKE \"VIII-\" THEN 416 ";
$uiaaGrade .= "WHEN grade LIKE \"VIII\" THEN 417 ";
$uiaaGrade .= "WHEN grade LIKE \"VIII+\" THEN 418 ";
$uiaaGrade .= "WHEN grade LIKE \"IX-\" THEN 419 ";
$uiaaGrade .= "WHEN grade LIKE \"IX\" THEN 420 ";
$uiaaGrade .= "WHEN grade LIKE \"IX+\" THEN 421 ";
$uiaaGrade .= "WHEN grade LIKE \"X-\" THEN 422 ";
$uiaaGrade .= "WHEN grade LIKE \"X\" THEN 423 ";
$uiaaGrade .= "WHEN grade LIKE \"X+\" THEN 424 ";
$uiaaGrade .= "WHEN grade LIKE \"XI-\" THEN 425 ";
$uiaaGrade .= "WHEN grade LIKE \"XI\" THEN 426 ";
$uiaaGrade .= "WHEN grade LIKE \"XI+\" THEN 427 ";

// UIAA grade filter
$uiaaGradeFilter = "AND (grade REGEXP \"V[+-]\" OR grade LIKE \"I%\" OR grade LIKE \"X%\" OR grade LIKE \"V\") ";

// V-grade grade filter
$vGradeFilter = "AND grade REGEXP \"^V[0-9]+\" ";


// outputs a page to the browser with header and footer
function view($filename, $data = []) {
    require(__DIR__ ."/config.php");
    
    if (file_exists(__DIR__ ."/../views/{$filename}")) {
        require(__DIR__ ."/../views/header.php");
        require(__DIR__ ."/../views/{$filename}");
        require(__DIR__ ."/../views/footer.php");
        return;
    }
    
	else
		error("Cannot find file: " . $filename);
}

// shows an error message to the user
function error($string) {
	view("error.php", ["error" => $string]);
	exit;
}

// returns an error message to a AJAX request
function ajax_err($string) {
    exit("<div id=\"ajaxerr\">" .$string ."</div>");
}

// shows an info screen to the user
function info($string) {
	view("info.php", ["message" => $string]);
	return;
}

// connect the user to the database
function db_connect() {
    require(__DIR__ ."/config.php");
    
    $db = new mysqli($host, $dbuser, $dbpass, $dbname, $dbport);
    
    if ($db->connect_error)
        error("Connection failed: " . $db->connect_error);
    else
        return $db;
}

// Performs security checks on data that will be outputted as html
function sec_check($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// sets specific session data for adding and editing areas/crags/routes
function set_data($action, $id) {
    $_SESSION["action"] = $action;
    $_SESSION["id"] = $id;
}

// clears any set session data
function clear_data() {
    unset($_SESSION["action"]);
    unset($_SESSION["id"]);
}

// check user is logged in before performing any database updates
function login_check() {
    if (!isset($_SESSION["userid"]))
        exit;
}

?>
