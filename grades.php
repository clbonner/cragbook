<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * grades.php 
 * Controller for showing lists of grades.
 */
 
require_once(__DIR__ ."/include/config.php");

$grade = sec_check($_GET["grade"]);

view("grades.php", ["grade" => $grade ]);

?>