<script>
$(document).ready( function () {
    getCrag(<?= $_GET["cragid"] ?>);
    getCragRoutes(<?= $_GET["cragid"] ?>, 'all');
    
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url.includes("include/crag_json.php"))
            viewCragInfo();
    });
});
</script>
<div class="w3-container w3-small">
    <div class="w3-margin-top">
        <a style="text-decoration: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $data["area"]["areaid"] ?>"><i class="fa fa-angle-left"></i> <?= $data["area"]["name"] ?> </a>
    </div>
    <div class="w3-container w3-small w3-card-2 w3-border w3-margin-top">
        <?php if (isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/crag.php?action=edit&cragid=<?= $_GET["cragid"] ?>">Edit <?= $data["crag"]["name"] ?></a>
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/crag.php?action=delete&cragid=<?= $_GET["cragid"] ?>">Delete <?= $data["crag"]["name"] ?></a>
            </div>
        <?php endif ?>
        <h1><?= $data["crag"]["name"] ?></h1>
        <div class="w3-btn-bar">
            <i id="listview" class="fa fa-info w3-btn w3-round w3-white w3-hover-red" onclick="viewCragInfo()"></i>
            <i id="mapview" class="fa fa-map-o w3-btn w3-round w3-white w3-hover-red" onclick="viewCragMap('crag')"></i>
        </div>
        <div id="view" class="w3-small w3-margin-bottom"></div>
    </div>
    <div class="w3-container w3-card-2 w3-margin-top">
        <?php if (isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/route.php?action=add&cragid=<?= $_GET["cragid"] ?>">Add Route</a>
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/route_sort.php?cragid=<?= $_GET["cragid"] ?>">Sort</a>
            </div>
        <?php endif ?>
        <h4>Routes</h4>
        <div class="w3-tiny w3-margin-bottom">
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'all')">All</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'british')">British</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'french')">French</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'yds')">YDS</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'uiaa')">UIAA</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'font')">Font</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getCragRoutes(<?= $_GET["cragid"] ?>, 'vgrade')">V grade</button>
        </div>
        <div id="routes" class="w3-center w3-margin-bottom"></div>
    </div>
</div>