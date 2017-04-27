<?php

include_once(__DIR__ ."/config.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION["userid"]))
        echo json_encode(true);
    else
        echo json_encode(false);
}

?>