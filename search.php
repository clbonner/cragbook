<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * search.php 
 * Controller for searching areas/crags/routes.
 */
 
require_once("include/config.php");

// search database
if ($_GET["search"] != NULL)
{
    $db = db_connect();
    
    // security check
    $search = sec_check($_GET["search"]);
    
    // search areas/crags/routes
    $sql = "SELECT * FROM areas WHERE LCASE(name) LIKE LCASE(\"%" .$search ."%\");";
    $result = $db->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
            $areas[$row["areaid"]] = $row["name"];
    }
    else
        $areas = 0;
    
    $sql = "SELECT * FROM crags WHERE LCASE(name) LIKE LCASE(\"%" .$search ."%\");";
    $result = $db->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
            $crags[$row["cragid"]] = $row["name"];
    }
    else {
        $crags = 0;
    }
    
    $sql = "SELECT * FROM routes WHERE (LCASE(name) LIKE LCASE(\"%" .$search ."%\")) OR (grade LIKE \"%" .$search ."%\") ORDER BY name ASC;";
    $result = $db->query($sql);
    
    if ($result->num_rows > 0) {
        $routes = [];
        while($row = $result->fetch_assoc()) 
            array_push($routes, $row);
    }
    else {
        $routes = 0;
    }
    
    // get crag list for routes found
    $sql = "SELECT cragid, name FROM crags";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        $craglist = [];
        while($row = $result->fetch_assoc())
            array_push($craglist, $row);
    }
    else {
        $craglist = 0;
    }
    
    view("search_results.php", ["areas" => $areas, "crags" => $crags, "routes" => $routes, "craglist" => $craglist]);
    
    $db->close();
}

// show search form
else
{
    view("search.php", []);
}

?>