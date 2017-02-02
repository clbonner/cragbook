<script>
    $(document).ready( function() {
        getRouteOrder(<?= $data["crag"]["cragid"] ?>);
    });
</script>
<div class="content">
    <div class="heading">Sort Routes for <?= $data["crag"]["name"] ?></div><br>
    <div id="routes" class="panel"><i class="fa fa-circle-o-notch fa-spin fa-5x center"></i></div>
    <div id="buttons"></div>
</div>