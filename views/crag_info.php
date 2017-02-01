<script>
$(document).ready( function () {
    getCragInfo(<?= $_GET["cragid"] ?>);
    getCragRoutes(<?= $_GET["cragid"] ?>, 'all');
    
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url.includes("include/crag_json.php"))
            viewCragInfo();
    });
});
</script>

<div id="backlink">
    <a href="<?= SITEURL ?>/crags.php?areaid=<?= $data["area"]["areaid"] ?>"><i class="fa fa-angle-left"></i> <?= $data["area"]["name"] ?> </a>
</div>
<div class="content panel">
    <?php if (isset($_SESSION["userid"])): ?>
        <div class="right">
            <a class="btn-edit" href="<?= SITEURL ?>/admin/crag.php?action=edit&cragid=<?= $_GET["cragid"] ?>">Edit <?= $data["crag"]["name"] ?></a>
            <a class="btn-edit" href="<?= SITEURL ?>/admin/crag.php?action=delete&cragid=<?= $_GET["cragid"] ?>">Delete <?= $data["crag"]["name"] ?></a>
        </div>
    <?php endif ?>
    <div class="title"><?= $data["crag"]["name"] ?></div>
    <div id="viewpicker">
        <i id="listview" class="fa fa-info btn-border" onclick="viewCragInfo()"></i>
        <i id="mapview" class="fa fa-map-marker btn-border" onclick="viewCragMap('crag')"></i>
    </div>
    <div id="view"></div>
</div>
<div class="content panel">
    <?php if (isset($_SESSION["userid"])): ?>
        <div class="right">
            <a class="btn-edit" href="<?= SITEURL ?>/admin/route.php?action=add&cragid=<?= $_GET["cragid"] ?>">Add Route</a>
            <a class="btn-edit" href="<?= SITEURL ?>/admin/route_sort.php?cragid=<?= $_GET["cragid"] ?>">Sort</a>
        </div>
    <?php endif ?>
    <div class="heading">Routes</div>
    <div id="gradefilter">
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'all')">All</button>
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'british')">British</button>
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'french')">French</button>
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'yds')">YDS</button>
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'uiaa')">UIAA</button>
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'font')">Font</button>
        <button class="btn" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'vgrade')">V grade</button>
    </div>
    <div id="routes"></div>
    <div id="routeinfo" class="modal"></div>
</div>
