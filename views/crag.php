<script>
$(document).ready( function () {
    getCrag(<?= $_GET["cragid"] ?>);
});
</script>
<div id="backlink">
    <a href="<?= SITEURL ?>/area.php?areaid=<?= $data["area"]["areaid"] ?>"><i class="fa fa-angle-left">&nbsp<?= $data["area"]["name"] ?></i></a>
</div>
<div class="content panel">
    <?php if (isset($_SESSION["userid"])): ?>
        <div class="right">
            <button class="btn-edit fa fa-edit" onclick="window.location.assign('<?= SITEURL ?>/admin/crag.php?action=edit&cragid=<?= $_GET["cragid"] ?>')"></button>
            <button class="btn-edit fa fa-trash" onclick="window.location.assign('<?= SITEURL ?>/admin/crag.php?action=delete&cragid=<?= $_GET["cragid"] ?>')"></button>
        </div>
    <?php endif ?>
    <div id="name" class="title"></div>
    <div id="viewpicker">
        <button id="infoview" class="fa fa-info btn-picker" onclick="viewCragInfo()"></button>
        <button id="mapview" class="fa fa-map-marker btn-picker" onclick="viewCragMap('crag')"></button>
        <!--<button id="photoview" class="fa fa-photo btn-picker" onclick="viewCragPhotos()"></button>-->
        <button id="printview" class="fa fa-print btn-picker" onclick="printRoutes('crag')"></button>
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
    <div id="gradefilter"></div>
    <div id="routes"></div>
    <div id="modal" class="modal"></div>
</div>
