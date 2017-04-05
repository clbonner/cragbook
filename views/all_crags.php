<script>
$(document).ready( function () {
    getAllCrags();
});
</script>
<div class="content panel">
    <div class="heading">All Crags</div>
    <div id="viewpicker">
        <button id="listview" class="fa fa-list btn-picker" onclick="viewCragList()"></button>
        <button id="mapview" class="fa fa-map-marker btn-picker" onclick="viewCragMap('all')"></button>
    </div>
    <div id="view"></div>
</div>