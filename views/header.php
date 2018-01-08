<!DOCTYPE html>
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
    <div class="margin-5"><img src="http://greatwesternrock.co.uk/wp-content/uploads/2016/12/gwr-logo-verysmall.png"></img></div>
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
                    <a href="<?= SITEURL ?>/grades.php?grade=F2" class="btn-menu txt-left">F2 - F2+</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F3" class="btn-menu txt-left">F3 - F3+</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F4" class="btn-menu txt-left">F4 - F4+</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F5" class="btn-menu txt-left">F5 - F5+</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F6" class="btn-menu txt-left">F6a - F6c+</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F7" class="btn-menu txt-left">F7a - F7c+</a>
                    <a href="<?= SITEURL ?>/grades.php?grade=F8" class="btn-menu txt-left">F8a - F8c+</a>
                </div>
            </div>
            <a class="btn-menu left" href="<?= SITEURL ?>/search.php"><i class="fa fa-search menu-icon"></i></a>
            <a class="btn-menu left" href="<?= SITEURL ?>/help.php"><i class="fa fa-question menu-icon"></i></a>
        </div>
        <div class="right">
            <a class="btn-menu left" href="http://greatwesternrock.co.uk">Back to Main Site&nbsp<i class="fa fa-sign-out menu-icon"></i></a>
            <?php if(isset($_SESSION["userid"])): ?>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/prefs.php"><i class="fa fa-cog menu-icon"></i></a>
                <a class="btn-menu left" href="<?= SITEURL ?>/admin/logout.php"><i class="fa fa-sign-out menu-icon"></i></a>
            <?php endif ?>
        </div>
    </div>