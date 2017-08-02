<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title><?= $sitetitle ?></title>
    
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= $googlemaps_apikey ?>"></script>
    <script src="<?= SITEURL ?>/include/js/classes.js"></script>
    <script src="<?= SITEURL ?>/include/js/functions.js"></script>

    <link rel="icon" href="cragbook.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/cragbook.css">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <header>
        <div id="sitetitle">
            <?= $sitetitle ?>
        </div>
    </header>
    <div id="menu">
        <div class="left">
            <a class="btn-menu left" href="<?= SITEURL ?>/index.php"><i class="fa fa-home menu-icon"></i></a>
            <a class="btn-menu left" href="<?= SITEURL ?>/all_areas.php">Areas</a>
            <a class="btn-menu left" href="<?= SITEURL ?>/all_crags.php">Crags</a>
            <a class="btn-menu left" href="<?= SITEURL ?>/search.php"><i class="fa fa-search menu-icon"></i></a>
            <a class="btn-menu left" href="<?= SITEURL ?>/help.php"><i class="fa fa-question menu-icon"></i></a>
        </div>
        <div class="right">
            <?php if(isset($_SESSION["userid"])): ?>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/prefs.php"><i class="fa fa-cog menu-icon"></i></a>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/logout.php"><i class="fa fa-sign-out menu-icon"></i></a>
            <?php endif ?>
        </div>
    </div>