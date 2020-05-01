<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2020 Christopher L Bonner
 *
 * admin/export.php
 * Controller for exporting all routes from a crag.
 */

require_once(__DIR__ ."/../include/config.php");
login_check();

$db = db_connect();

// export all routes
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // get routes for crag
    $sql = "SELECT * FROM routes WHERE cragid=" .$_GET["exportcrag"]
      ." ORDER BY orderid ASC;";

    $routes = [];

    if (!$result = $db->query($sql))
        error("Error in admin/route.php: " .$db->error);
    elseif ($result->num_rows !== NULL)
        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }

    $fp = fopen('export.csv', 'w');

    fputcsv($fp, array("OrderID", "Name", "Grade", "Length" , "Stars", "First Ascent",
      "Crag Sector", "Seriousness", "Description", "Discipline", "Hidden"));

    foreach($routes as $route) {
      fputcsv($fp, array($route["orderid"], $route["name"], $route["grade"],
        $route["length"], $route["stars"], $route["fascent"], $route["sector"],
        $route["seriosness"], $route["description"], $route["discipline"],
        $route["private"]));
    }

    fclose($fp);

    // dump export file
    header("Location:" .SITEURL ."/admin/export.csv");
}

$db->close();
?>
