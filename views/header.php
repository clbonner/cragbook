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
                    <a href="<?= SITEURL ?>/grades.php?grade=2" class="btn-menu txt-left">2 / F2 / f2 / V2</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=3" class="btn-menu txt-left">3 / F3 / f3 / V3</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=4" class="btn-menu txt-left">4 / F4 / f4 / V4</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=5" class="btn-menu txt-left">5 / F5 / f5 / V5</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=6" class="btn-menu txt-left">6 / F6 / f6 / V6</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=7" class="btn-menu txt-left">7 / F7 / f7 / V7</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=8" class="btn-menu txt-left">8 / F8 / f8 / V8</a>
                </div>
            </div>
            <a class="btn-menu left" href="<?= SITEURL ?>/search.php"><i class="fa fa-search menu-icon"></i></a>
            <a class="btn-menu left" href="<?= SITEURL ?>/help.php"><i class="fa fa-question menu-icon"></i></a>
        </div>
        <div class="right">
            <?php if(isset($_SESSION["userid"])): ?>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/prefs.php"><i class="fa fa-cog menu-icon"></i></a>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/logout.php"><i class="fa fa-sign-out menu-icon"></i></a>
            <?php else: ?>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/login.php"><i class="fa fa-sign-in menu-icon"></i></a>
            <?php endif ?>
            
        </div>
    </div>