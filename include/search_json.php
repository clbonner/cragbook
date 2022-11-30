<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/search_json.php 
 * Return JSON data for the search function.
 */
 
require_once(__DIR__ ."/config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $search = rawurldecode($_POST["search"]);
    $search = json_decode($search, true);

    foreach ($search as $item) {
        $item = $db->escape_string($item);
    }
    
    // get list of crags for the area
    if ($search["area"] !== "") {
        if (isset($_SESSION["userid"]))
            $areas = "SELECT * FROM areas WHERE (LCASE(name) LIKE LCASE(\"%" .$search["area"] ."%\"));";
        else
            $areas = "SELECT * FROM areas WHERE (LCASE(name) LIKE LCASE(\"%" .$search["area"] ."%\")) AND public=1;";
        
        $result = $db->query($areas);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $arealist .= $row["areaid"] .",";
            }
            $arealist[strlen($arealist)-1] = " ";
            
            if (isset($_SESSION["userid"]))
                $areas = "SELECT * FROM crags WHERE areaid IN (" .$arealist .");";
            else
                $areas = "SELECT * FROM crags WHERE areaid IN (" .$arealist .") AND public=1;";
            
            $result = $db->query($areas);
            
            if ($result->num_rows > 0) {
                
                while ($row = $result->fetch_assoc()) {
                    $craglist .= $row["cragid"] .",";
                }
            }
        }
    }
    
    // get list of matching crags
    if ($search["crag"] !== "") {
        if (isset($craglist)) {
            $craglist[strlen($craglist)-1] = " ";
            
            if (isset($_SESSION["userid"]))
                $crags = "SELECT * FROM crags WHERE (LCASE(name) LIKE LCASE(\"%" .$search["crag"] ."%\") " 
                    ."AND cragid IN (" .$craglist ."));";
            else
                $crags = "SELECT * FROM crags WHERE (LCASE(name) LIKE LCASE(\"%" .$search["crag"] ."%\") " 
                    ."AND cragid IN (" .$craglist .")) AND public=1;";
        }
        else {
            if (isset($_SESSION["userid"]))
                $crags = "SELECT * FROM crags WHERE (LCASE(name) LIKE LCASE(\"%" .$search["crag"] ."%\"));";
            else
                $crags = "SELECT * FROM crags WHERE (LCASE(name) LIKE LCASE(\"%" .$search["crag"] ."%\")) AND public=1;";
        }
        
        $result = $db->query($crags);
        unset($craglist);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $craglist .= $row["cragid"] .",";
            }
        }
    }
    
    // build SQL query
    $sql = "SELECT * FROM routes WHERE ";
    
    if (isset($craglist)) {
        $craglist[(strlen($craglist)-1)] = " ";
        $sql .= "cragid IN (" .$craglist .") ";
        
        if ($search["route"] !== "") {
            $sql .= "AND LCASE(name) LIKE LCASE(\"%" .$search["route"] ."%\") ";
        }
        
        if ($search["grade"] !== "") {
            $sql .= "AND grade LIKE \"%" .$search["grade"] ."%\"";
        }
        
        $result = $db->query($sql);
    }
    
    elseif ($search["route"] !== "") {
        $sql .= "LCASE(name) LIKE LCASE(\"%" .$search["route"] ."%\") ";
        
        if ($search["grade"] !== "") {
            $sql .= "AND grade LIKE \"%" .$search["grade"] ."%\"";
        }
        
        $result = $db->query($sql);
    }
    
    elseif ($search["grade"] !== "") {
        $sql .= "grade LIKE \"%" .$search["grade"] ."%\"";
        
        $result = $db->query($sql);
    }
    
    elseif ($search["area"] !== "" || $search["crag"] !== "") {
            $routes = [];
    }
    
    // send search results
    if ($routes !== 0 && $result->num_rows > 0) {
        $routes = [];
        while ($row = $result->fetch_assoc()) {
            array_push($routes, $row);
        }

    }
    else {
        $routes = [];
    }

    echo json_encode($routes);
}

$db->close();
    
?>