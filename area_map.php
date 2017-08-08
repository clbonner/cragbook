<!DOCTYPE html>
<?php require_once(__DIR__ ."/include/config.php") ?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title><?= $sitetitle ?></title>
    
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= $googlemaps_apikey ?>"></script>
    <script src="<?= SITEURL ?>/include/js/classes.js"></script>
    <script src="<?= SITEURL ?>/include/js/db.js"></script>
    <script src="<?= SITEURL ?>/include/js/interface.js"></script>
    <script src="<?= SITEURL ?>/include/js/maps.js"></script>

    <link rel="icon" href="cragbook.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/cragbook.css">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/font-awesome/css/font-awesome.min.css">
</head>
<body>
<script>
$(document).ready( function () {
    getAllAreasMap();
});
</script>
<div id="view"></div>
</body>
</html>