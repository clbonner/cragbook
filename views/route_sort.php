<script>
    $(function() {
        getRoutes(<?= $data["crag"]["cragid"] ?>);
    });
</script>
<div class="w3-container w3-small w3-margin-top">
    <h4>Sort Routes for <?= $data["crag"]["name"] ?></h4>
    <div id="routes"><i class="fa fa-circle-o-notch fa-spin fa-5x w3-display-middle"></i></div>
    <div class="w3-margin-top w3-margin-bottom">
        <input class="w3-btn w3-round w3-green" type="button" onclick="updateRoutes('<?= $data["returnurl"] ?>')" value="Save">
        <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="Cancel">
    </div>
</div>