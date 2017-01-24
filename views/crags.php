<script>
$(document).ready( function () {
    getCrags(<?= $_GET["areaid"] ?>);
    
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url.includes("include/crag_json.php"))
            viewCragList();
    });
});
</script>
<div class="w3-container w3-small">
    <div class="w3-container w3-small w3-card-2 w3-border w3-margin-top">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/area.php?action=edit&areaid=<?= $_GET["areaid"] ?>">Edit <?= $data["area"]["name"] ?></a>
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/area.php?action=delete&areaid=<?= $_GET["areaid"] ?>">Delete <?= $data["area"]["name"] ?></a>
            </div>
        <?php endif ?>
        <h1><?= $data["area"]["name"] ?></h1>
        <h6><?= htmlspecialchars_decode($data["area"]["description"]) ?></h6>
    </div>
    <div class="w3-container w3-card-2 w3-small w3-margin-top">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/crag.php?action=add&areaid=<?= $_GET["areaid"] ?>">Add Crag</a>
            </div>
        <?php endif ?>
        <h4>Crags</h4>
        <div class="w3-btn-bar">
            <i id="listview" class="fa fa-list w3-btn w3-round w3-white w3-hover-red" onclick="viewCragList()"></i>
            <i id="mapview" class="fa fa-map-o w3-btn w3-round w3-white w3-hover-red" onclick="viewCragMap('<?= $data["area"]["location"] ?>')"></i>
        </div>
        <div id="view" class="w3-small w3-margin-bottom"></div>
    </div>
    <div class="w3-container w3-card-2 w3-margin-top">
        <h4>Routes</h4>
        <div class="w3-white w3-tiny w3-margin-bottom">
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'all')">All</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'british')">British</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'french')">French</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'yds')">YDS</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'uiaa')">UIAA</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'font')">Font</button>
            <button class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'vgrade')">V grade</button>
        </div>
        <div id="routes" class="w3-center w3-margin-bottom"><p onclick="getAreaRoutes(<?= $_GET["areaid"] ?>, 'all')">Click to show routes...</p></div>
        <div id="routeinfo" class="w3-modal"></div>
    </div>
</div>