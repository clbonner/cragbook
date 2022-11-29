<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title><?= $sitetitle ?></title>
    
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= $googlemaps_apikey ?>"></script>
    <script src="<?= SITEURL ?>/js/classes.js"></script>
    <script src="<?= SITEURL ?>/js/db.js"></script>
    <script src="<?= SITEURL ?>/js/interface.js"></script>
    <script src="<?= SITEURL ?>/js/maps.js"></script>

    <link rel="icon" href="cragbook.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/cragbook.css">
    <link rel="stylesheet" href="<?= SITEURL ?>/css/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <header></header>
    <div id="menu">
        <div class="left">
            <a class="btn-menu left" href="<?= SITEURL ?>/index.php"><i class="fa fa-home menu-icon"></i></a>
            <a class="btn-menu left" href="<?= SITEURL ?>/all_areas.php">Areas</a>
            <a class="btn-menu left" href="<?= SITEURL ?>/all_crags.php">Crags</a>
            <div class="dropdown left">
                <a class="btn-menu">Grades</a>
                <div class="dropdown-content">
                    <div class="center">Show all grades at...</div>
                    <a href="<?= SITEURL ?>/grades.php?grade=F3" class="btn-menu txt-left">F3</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F4" class="btn-menu txt-left">F4</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F5" class="btn-menu txt-left">F5</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F6" class="btn-menu txt-left">F6</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F7" class="btn-menu txt-left">F7</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F8" class="btn-menu txt-left">F8</a>
                </div>
            </div>
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