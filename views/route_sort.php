<script>
    $(document).ready( function() {
        getRouteOrder(<?= $data["crag"]["cragid"] ?>);
    });
</script>
<div class="content panel">
    <div class="heading">Sort Routes for <?= $data["crag"]["name"] ?></div><br>
    <div id="routes"></div>
    <div id="buttons"></div>
</div>