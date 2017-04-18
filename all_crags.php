<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * all_crags.php 
 * Controller for listing climbing areas
 */

require_once(__DIR__ ."/include/config.php");
view("all_crags.php", ["areas" => $areas]);

?>