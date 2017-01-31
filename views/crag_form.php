<script>
$(document).ready( function () {
    setCragMap('<?= $data["crag"]["location"] ?>');
});
</script>
<form class="content" action="<?= SITEURL ?>/admin/crag.php" method="post">
    <label ><b>Crag name</b></label>
    <input type="text" name="name" value="<?= $data["crag"]["name"] ?>" required>
    <label ><b>Crag description</b></label>
    <textarea name="description" rows=5><?= $data["crag"]["description"] ?></textarea><br>
    <label ><b>Crag approach</b></label>
    <textarea name="approach" rows=5><?= $data["crag"]["approach"] ?></textarea><br>
    <label ><b>Fixed gear policy</b></label>
    <input type="text" name="policy" value="<?= $data["crag"]["policy"] ?>">
    <label ><b>Access issues</b></label>
    <input type="text" name="access" value="<?= $data["crag"]["access"] ?>">
    <label ><b>Location (right click to set crag location)</b></label>
    <div id="map" class="panel"></div>
    <input id="latlng" type="text" name="location" value="<?= $data["crag"]["location"] ?>" readonly>
    <button class="btn-save" type="submit"><?= $data["button"] ?></button>
    <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Cancel</button>
</form>