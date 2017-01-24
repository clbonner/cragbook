<script>
    $(document).ready( function() {
        getRouteOrder(<?= $data["crag"]["cragid"] ?>);
    });
</script>
<div class="w3-container w3-small w3-margin-top">
    <h4>Sort Routes for <?= $data["crag"]["name"] ?></h4>
    <div id="routes"><i class="fa fa-circle-o-notch fa-spin fa-5x w3-display-middle"></i></div>
    <div id="buttons" class="w3-margin-top"></div>
</div>