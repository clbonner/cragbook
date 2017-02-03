<script>
$(document).ready( function () {
    getAreas();
    
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url.includes("include/area_json.php"))
            viewAreaList();
    });
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
        <button id="listview" class="fa fa-list btn-border" onclick="viewAreaList()"></button>
        <button id="mapview" class="fa fa-map-marker btn-border" onclick="viewAreaMap()"></button>
    </div>
    <div id="view"></div>
</div>
