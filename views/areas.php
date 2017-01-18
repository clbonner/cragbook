<script>
    getAreas();
    $(document).ajaxSuccess(function() {
        listViewAreas();
    });
</script>
<div class="w3-container w3-small w3-card-2 w3-white w3-margin">
    <?php if(isset($_SESSION["userid"])): ?>
        <div class="w3-right w3-margin-top">
            <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/area.php?action=add">Add Area</a>
        </div>
    <?php endif ?>
    <h4>Climbing Areas</h4>
    <div id="tabs" class="w3-btn-bar">
        <i id="listview" class="fa fa-list w3-btn w3-round w3-white w3-hover-red" onclick="listViewAreas()"></i>
        <i id="mapview" class="fa fa-map-o w3-btn w3-round w3-white w3-hover-red" onclick="showMapAreas()"></i>
    </div>
    <div id="view" class="w3-small w3-margin-bottom"></div>
</div>