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
            <a class="btn-edit" href="<?= SITEURL ?>/admin/area.php?action=add">Add Area</a>
        </div>
    <?php endif ?>
    <div class="heading">Climbing Areas</div>
    <div id="viewpicker">
        <i id="listview" class="fa fa-list btn" onclick="viewAreaList()"></i>
        <i id="mapview" class="fa fa-map-marker btn" onclick="viewAreaMap()"></i>
    </div>
    <div id="view"></div>
</div>
