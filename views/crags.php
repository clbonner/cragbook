<script>
$(document).ready( function () {
    getCrags(<?= $_GET["areaid"] ?>);
    
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url.includes("include/crag_json.php"))
            viewCragList();
    });
});
</script>
<div>
    <div class="content panel">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="right">
                <button class="btn-edit fa fa-edit" onclick="window.location.assign('<?= SITEURL ?>/admin/area.php?action=edit&areaid=<?= $_GET["areaid"] ?>')"></button>
                <button class="btn-edit fa fa-trash" onclick="window.location.assign('<?= SITEURL ?>/admin/area.php?action=delete&areaid=<?= $_GET["areaid"] ?>')"></button>
            </div>
        <?php endif ?>
        <div class="title"><?= $data["area"]["name"] ?></div>
        <div class="heading"><?= htmlspecialchars_decode($data["area"]["description"]) ?></div>
    </div>
    <div class="content panel">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="right">
                <button class="btn-edit fa fa-plus" onclick="window.location.assign('<?= SITEURL ?>/admin/crag.php?action=add&areaid=<?= $_GET["areaid"] ?>')"></button>
            </div>
        <?php endif ?>
        <div class="heading">Crags</div>
        <div id="viewpicker">
            <button id="listview" class="fa fa-list btn-border" onclick="viewCragList()"></button>
            <button id="mapview" class="fa fa-map-marker btn-border" onclick="viewCragMap('<?= $data["area"]["location"] ?>')"></button>
        </div>
        <div id="view"></div>
    </div>
    <div class="content panel">
        <div class="heading">Routes</div>
        <div id="gradefilter">
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'all')">All</button>
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'british')">British</button>
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'french')">French</button>
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'yds')">YDS</button>
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'uiaa')">UIAA</button>
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'font')">Font</button>
            <button class="btn" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'vgrade')">V grade</button>
        </div>
        <div id="routes"><p onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'all')">Show all routes for <?= $data["area"]["name"] ?>...</p></div>
        <div id="routeinfo" class="modal"></div>
    </div>
</div>