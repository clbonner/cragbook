<script>
$(document).ready( function () {
    getCrags('all');
});
</script>
<div class="content panel">
    <div class="heading">All Crags</div>
    <div id="viewpicker">
        <button id="listview" class="fa fa-list btn-border" onclick="viewCragList()"></button>
        <button id="mapview" class="fa fa-map-marker btn-border" onclick="viewCragMap('all')"></button>
    </div>
    <div id="view"></div>
</div>