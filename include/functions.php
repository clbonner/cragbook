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

// french grade order
$frenchGrade .= "WHEN grade LIKE \"F1\" THEN 100 ";
$frenchGrade .= "WHEN grade LIKE \"F2\" THEN 101 ";
$frenchGrade .= "WHEN grade LIKE \"F3\" THEN 102 ";
$frenchGrade .= "WHEN grade LIKE \"F4\" THEN 103 ";
$frenchGrade .= "WHEN grade LIKE \"F4a\" THEN 104 ";
$frenchGrade .= "WHEN grade LIKE \"F4b\" THEN 105 ";
$frenchGrade .= "WHEN grade LIKE \"F4c\" THEN 106 ";
$frenchGrade .= "WHEN grade LIKE \"F4+\" THEN 107 ";
$frenchGrade .= "WHEN grade LIKE \"F5\" THEN 108 ";
$frenchGrade .= "WHEN grade LIKE \"F5a\" THEN 109 ";
$frenchGrade .= "WHEN grade LIKE \"F5b\" THEN 110 ";
$frenchGrade .= "WHEN grade LIKE \"F5c\" THEN 111 ";
$frenchGrade .= "WHEN grade LIKE \"F5+\" THEN 112 ";
$frenchGrade .= "WHEN grade LIKE \"F6a\" THEN 113 ";
$frenchGrade .= "WHEN grade LIKE \"F6a+\" THEN 114 ";
$frenchGrade .= "WHEN grade LIKE \"F6b\" THEN 115 ";
$frenchGrade .= "WHEN grade LIKE \"F6b+\" THEN 116 ";
$frenchGrade .= "WHEN grade LIKE \"F6c\" THEN 117 ";
$frenchGrade .= "WHEN grade LIKE \"F6c+\" THEN 118 ";
$frenchGrade .= "WHEN grade LIKE \"F7a\" THEN 119 ";
$frenchGrade .= "WHEN grade LIKE \"F7a+\" THEN 120 ";
$frenchGrade .= "WHEN grade LIKE \"F7b\" THEN 121 ";
$frenchGrade .= "WHEN grade LIKE \"F7b+\" THEN 122 ";
$frenchGrade .= "WHEN grade LIKE \"F7c\" THEN 123 ";
$frenchGrade .= "WHEN grade LIKE \"F7c+\" THEN 124 ";
$frenchGrade .= "WHEN grade LIKE \"F8a\" THEN 125 ";
$frenchGrade .= "WHEN grade LIKE \"F8a+\" THEN 126 ";
$frenchGrade .= "WHEN grade LIKE \"F8b\" THEN 127 ";
$frenchGrade .= "WHEN grade LIKE \"F8b+\" THEN 128 ";
$frenchGrade .= "WHEN grade LIKE \"F8c\" THEN 129 ";
$frenchGrade .= "WHEN grade LIKE \"F8c+\" THEN 130 ";
$frenchGrade .= "WHEN grade LIKE \"F9a\" THEN 131 ";
$frenchGrade .= "WHEN grade LIKE \"F9a+\" THEN 132 ";
$frenchGrade .= "WHEN grade LIKE \"F9b\" THEN 133 ";
$frenchGrade .= "WHEN grade LIKE \"F9b+\" THEN 134 ";
$frenchGrade .= "WHEN grade LIKE \"F9c\" THEN 135 ";

// french numerical grade SQL filter
$frenchGradeFilter = "AND grade LIKE \"F%\" ";

// font grade order
$fontGrade .= "WHEN grade LIKE \"f3\" THEN 200 ";
$fontGrade .= "WHEN grade LIKE \"f4\" THEN 201 ";
$fontGrade .= "WHEN grade LIKE \"f4+\" THEN 202 ";
$fontGrade .= "WHEN grade LIKE \"f5\" THEN 203 ";
$fontGrade .= "WHEN grade LIKE \"f5+\" THEN 204 ";
$fontGrade .= "WHEN grade LIKE \"f6a\" THEN 205 ";
$fontGrade .= "WHEN grade LIKE \"f6a+\" THEN 206 ";
$fontGrade .= "WHEN grade LIKE \"f6b\" THEN 207 ";
$fontGrade .= "WHEN grade LIKE \"f6b+\" THEN 208 ";
$fontGrade .= "WHEN grade LIKE \"f6c\" THEN 209 ";
$fontGrade .= "WHEN grade LIKE \"f6c+\" THEN 210 ";
$fontGrade .= "WHEN grade LIKE \"f7a\" THEN 211 ";
$fontGrade .= "WHEN grade LIKE \"f7a+\" THEN 212 ";
$fontGrade .= "WHEN grade LIKE \"f7b\" THEN 213 ";
$fontGrade .= "WHEN grade LIKE \"f7b+\" THEN 214 ";
$fontGrade .= "WHEN grade LIKE \"f7c\" THEN 215 ";
$fontGrade .= "WHEN grade LIKE \"f7c+\" THEN 216 ";
$fontGrade .= "WHEN grade LIKE \"f8a\" THEN 217 ";
$fontGrade .= "WHEN grade LIKE \"f8a+\" THEN 218 ";
$fontGrade .= "WHEN grade LIKE \"f8b\" THEN 219 ";
$fontGrade .= "WHEN grade LIKE \"f8b+\" THEN 220 ";
$fontGrade .= "WHEN grade LIKE \"f8c\" THEN 221 ";
$fontGrade .= "WHEN grade LIKE \"f8c+\" THEN 222 ";
$fontGrade .= "WHEN grade LIKE \"f9a\" THEN 223 ";

// font grade filter
$fontGradeFilter = "AND grade LIKE \"f%\" ";

// YDS numerical grade order
$ydsGrade .= "WHEN grade LIKE \"5.1\" THEN 300 ";
$ydsGrade .= "WHEN grade LIKE \"5.2\" THEN 301 ";
$ydsGrade .= "WHEN grade LIKE \"5.3\" THEN 302 ";
$ydsGrade .= "WHEN grade LIKE \"5.4\" THEN 303 ";
$ydsGrade .= "WHEN grade LIKE \"5.5\" THEN 304 ";
$ydsGrade .= "WHEN grade LIKE \"5.6\" THEN 305 ";
$ydsGrade .= "WHEN grade LIKE \"5.7\" THEN 306 ";
$ydsGrade .= "WHEN grade LIKE \"5.8\" THEN 307 ";
$ydsGrade .= "WHEN grade LIKE \"5.9\" THEN 308 ";
$ydsGrade .= "WHEN grade LIKE \"5.10\" THEN 309 ";
$ydsGrade .= "WHEN grade LIKE \"5.11\" THEN 310 ";
$ydsGrade .= "WHEN grade LIKE \"5.12\" THEN 311 ";
$ydsGrade .= "WHEN grade LIKE \"5.13\" THEN 312 ";
$ydsGrade .= "WHEN grade LIKE \"5.14\" THEN 313 ";
$ydsGrade .= "WHEN grade LIKE \"5.15\" THEN 314 ";


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

// V-grade grade order
$vGrade .= "WHEN grade LIKE \"VB\" THEN 501 ";
$vGrade .= "WHEN grade LIKE \"V0-\" THEN 502 ";
$vGrade .= "WHEN grade LIKE \"V0\" THEN 503 ";
$vGrade .= "WHEN grade LIKE \"V0+\" THEN 504 ";
$vGrade .= "WHEN grade LIKE \"V1\" THEN 505 ";
$vGrade .= "WHEN grade LIKE \"V2\" THEN 506 ";
$vGrade .= "WHEN grade LIKE \"V3\" THEN 507 ";
$vGrade .= "WHEN grade LIKE \"V4\" THEN 508 ";
$vGrade .= "WHEN grade LIKE \"V5\" THEN 509 ";
$vGrade .= "WHEN grade LIKE \"V6\" THEN 510 ";
$vGrade .= "WHEN grade LIKE \"V7\" THEN 511 ";
$vGrade .= "WHEN grade LIKE \"V8\" THEN 512 ";
$vGrade .= "WHEN grade LIKE \"V9\" THEN 513 ";
$vGrade .= "WHEN grade LIKE \"V10\" THEN 514 ";
$vGrade .= "WHEN grade LIKE \"V11\" THEN 515 ";
$vGrade .= "WHEN grade LIKE \"V12\" THEN 516 ";
$vGrade .= "WHEN grade LIKE \"V13\" THEN 517 ";
$vGrade .= "WHEN grade LIKE \"V14\" THEN 518 ";
$vGrade .= "WHEN grade LIKE \"V15\" THEN 519 ";

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
