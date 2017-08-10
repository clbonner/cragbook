<script>
$(document).ready( function () {
    getArea(<?= $_GET["areaid"] ?>);
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
        <div id="name" class="title"></div>
        <div id="description" class="heading"></div>
    </div>
    <div class="content panel">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="right">
                <button class="btn-edit fa fa-plus" onclick="window.location.assign('<?= SITEURL ?>/admin/crag.php?action=add&areaid=<?= $_GET["areaid"] ?>')"></button>
            </div>
        <?php endif ?>
        <div class="heading">Crags</div>
        <div id="viewpicker">
            <button id="listview" class="fa fa-list btn-picker" onclick="viewCragList()"></button>
            <button id="mapview" class="fa fa-map-o btn-picker" onclick="viewCragMap('<?= $data["area"]["location"] ?>')"></button>
            <button id="printview" class="fa fa-print btn-picker" onclick="printRoutes('area')"></button>
        </div>
        <div id="view"></div>
    </div>
    <div class="content panel">
        <div class="heading">Routes</div>
        <div id="gradefilter"></div>
        <div id="routes"></div>
        <div id="modal" class="modal"></div>
    </div>
</div>