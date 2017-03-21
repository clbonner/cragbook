<script>
$(document).ready( function () {
    getCragInfo(<?= $_GET["cragid"] ?>);
});
</script>

<div id="backlink">
    <a href="<?= SITEURL ?>/crags.php?areaid=<?= $data["area"]["areaid"] ?>"><i class="fa fa-angle-left"></i> <?= $data["area"]["name"] ?> </a>
</div>
<div class="content panel">
    <?php if (isset($_SESSION["userid"])): ?>
        <div class="right">
            <button class="btn-edit fa fa-edit" onclick="window.location.assign('<?= SITEURL ?>/admin/crag.php?action=edit&cragid=<?= $_GET["cragid"] ?>')"></button>
            <button class="btn-edit fa fa-trash" onclick="window.location.assign('<?= SITEURL ?>/admin/crag.php?action=delete&cragid=<?= $_GET["cragid"] ?>')"></button>
        </div>
    <?php endif ?>
    <div class="title"><?= $data["crag"]["name"] ?></div>
    <div id="viewpicker">
        <button id="listview" class="fa fa-info btn-border" onclick="viewCragInfo()"></button>
        <button id="mapview" class="fa fa-map-marker btn-border" onclick="viewCragMap('crag')"></button>
    </div>
    <div id="view"></div>
</div>
<div class="content panel">
    <?php if (isset($_SESSION["userid"])): ?>
        <div class="right">
            <button class="btn-edit fa fa-plus" onclick="window.location.assign('<?= SITEURL ?>/admin/route.php?action=add&cragid=<?= $_GET["cragid"] ?>')"></button>
            <button class="btn-edit fa fa-sort" onclick="window.location.assign('<?= SITEURL ?>/admin/route_sort.php?cragid=<?= $_GET["cragid"] ?>')"></button>
        </div>
    <?php endif ?>
    <div class="heading">Routes</div>
    <div id="gradefilter">
        <button class="btn" onclick="viewCragRoutes(routes)">All</button>
        <button class="btn" onclick="trad('crag')">Trad</button>
        <button class="btn" onclick="sport('crag')">Sport</button>
        <button class="btn" onclick="bouldering('crag')">Bouldering</button>
    </div>
    <div id="routes"></div>
    <div id="routeinfo" class="modal"></div>
</div>
