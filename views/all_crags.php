<script>
$(document).ready( function () {
    getCrags('all');
    $(document).ajaxSuccess(function() {
        viewCragList();
    });
});
</script>
<div class="content panel">
    <div class="heading">All Crags</div>
    <div id="viewpicker">
        <i id="listview" class="fa fa-list btn-border" onclick="viewCragList()"></i>
        <i id="mapview" class="fa fa-map-marker btn-border" onclick="viewCragMap('all')"></i>
    </div>
    <div id="view"></div>
</div>