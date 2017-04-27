<script>
$(document).ready( function () {
    getAllAreas();
});
</script>
<div class="content panel">
    <?php if(isset($_SESSION["userid"])): ?>
        <div class="right">
            <button class="btn-edit fa fa-plus" onclick="window.location.assign('<?= SITEURL ?>/admin/area.php?action=add')"></button>
        </div>
    <?php endif ?>
    <div class="heading">Climbing Areas</div>
    <div id="viewpicker">
        <button id="listview" class="fa fa-list btn-picker" onclick="viewAreaList()"></button>
        <button id="mapview" class="fa fa-map-marker btn-picker" onclick="viewAreaMap()"></button>
    </div>
    <div id="view"></div>
</div>
