<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2020 Christopher L Bonner
 *
 * admin/export.php
 * Controller for exporting all routes from a specified crag.
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// export all routes
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $routes = [];

    // get routes for crag
    $sql = "SELECT * FROM routes WHERE cragid=" .$_GET["exportcrag"]
      ." ORDER BY orderid ASC;";

    if (!$result = $db->query($sql))
        error("Error in admin/export.php. SQL: " .$sql ." ERROR: " .$db->error);
    elseif ($result->num_rows !== NULL)
        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }

    $fp = fopen('export.csv', 'w');

    // insert header line
    fputcsv($fp, array("OrderID", "Name", "Grade", "Length" , "Stars", "First Ascent",
      "Crag Sector", "Description", "Discipline"));

    // insert routes
    foreach($routes as $route) {
      fputcsv($fp, array($route["orderid"], $route["name"], $route["grade"],
        $route["length"], $route["stars"], $route["firstascent"], $route["sector"],
        $route["description"], $route["discipline"]));
    }

    fclose($fp);

    // dump export file
    header("Location:" .SITEURL ."/admin/export.csv");
}

$db->close();
?>
