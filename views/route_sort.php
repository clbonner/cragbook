<script>
    $(document).ready( function() {
        getRouteOrder(<?= $data["crag"]["cragid"] ?>);
    });
</script>
<div class="content">
    <h4>Sort Routes for <?= $data["crag"]["name"] ?></h4>
    <div id="routes" class="panel"><i class="fa fa-circle-o-notch fa-spin fa-5x center"></i></div>
    <div id="buttons"></div>
</div>