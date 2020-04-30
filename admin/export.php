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
//if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["action"] == "export") {
    //$sql = "SELECT * FROM routes WHERE cragid = " .$_GET["cragid"] .";";
    $sql = "SELECT * FROM routes WHERE cragid = 18"
    if (!$result = $db->query($sql))
        error("Error in admin/route.php: " .$db->error);
    elseif ($result->num_rows !== NULL)
        $routes = $result->fetch_assoc();

    $fp = fopen("export.csv", "w");
    foreach($route as $routes) {
      fputcsv($fp, $route);
    }

    require("export.csv");
//}

$db->close();
