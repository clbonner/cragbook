<script>
$(document).ready( function () {
    getCrags(<?= $_GET["areaid"] ?>);
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
        <button class="btn" onclick="viewAreaRoutes(routes)">All</button>
        <button class="btn" onclick="trad('area')">Trad</button>
        <button class="btn" onclick="sport('area')">Sport</button>
        <button class="btn" onclick="bouldering('area')">Bouldering</button>
        </div>
        <div id="routes"></div>
        <div id="routeinfo" class="modal"></div>
    </div>
</div>