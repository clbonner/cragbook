<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * admin/logout.php
 * Controller to logout current user.
 */
 
require_once("../include/config.php");

unset($_SESSION["userid"]);
require(SITEROOT ."index.php");

?>