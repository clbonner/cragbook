<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title><?= $sitetitle ?></title>
    
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="<? SITEURL ?>/include/js/functions.js"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    
    <link rel="stylesheet" href="<?= SITEURL ?>/css/w3.css">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <div class="w3-container w3-blue">
      <h1><?= $sitetitle ?></h1>
    </div>
    <div id="menu" class="w3-btn-bar w3-grey w3-border-grey">
        <a href="<?= SITEURL ?>/index.php"><i class="fa fa-home w3-large w3-btn w3-round w3-grey w3-hover-red w3-left w3-padding"></i></a>
        <a class="w3-btn w3-round w3-grey w3-hover-red w3-left" href="<?= SITEURL ?>/areas.php">Areas</a>
        <a class="w3-btn w3-round w3-grey w3-hover-red w3-left" href="<?= SITEURL ?>/crags.php">Crags</a>
        <a class="w3-btn w3-round w3-grey w3-hover-red w3-left" href="<?= SITEURL ?>/search.php">Search</a>
        <?php if(!isset($_SESSION["userid"])): ?>
            <a class="w3-btn w3-round w3-grey w3-border-grey w3-hover-red w3-right" href="<?= SITEURL ?>/admin/login.php">Login</a>
        <?php else: ?>
            <a class="w3-btn w3-round w3-grey w3-hover-red w3-right" href="<?= SITEURL ?>/admin/logout.php">Logout</a>
            <a class="w3-btn w3-round w3-grey w3-hover-red w3-right" href="<?= SITEURL ?>/admin/prefs.php">Preferences</a>
        <?php endif ?>
    </div>