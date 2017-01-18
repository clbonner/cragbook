<script>
    getCrags('all');
    $(document).ajaxSuccess(function() {
        listViewCrags();
    });
</script>
<div class="w3-container w3-card-2 w3-margin">
    <h4>All Crags</h4>
    <div id="tabs" class="w3-btn-bar w3-small">
        <i id="listview" class="fa fa-list w3-btn w3-round w3-white w3-hover-red" onclick="listViewCrags()"></i>
        <i id="mapview" class="fa fa-map-o w3-btn w3-round w3-white w3-hover-red" onclick="showMapCrags('all')"></i>
    </div>
    <div id="view" class="w3-small w3-margin-bottom"></div>
</div>