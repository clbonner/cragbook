<script>
$(document).ready( function () {
    setCragMap('<?= $data["crag"]["location"] ?>');
});
</script>
<form class="content" action="<?= SITEURL ?>/admin/crag.php" method="post">
    <label >Crag name</label>
    <input type="text" name="name" value="<?= $data["crag"]["name"] ?>" required>
    <label >Crag description</label>
    <textarea name="description" rows=5><?= $data["crag"]["description"] ?></textarea><br>
    <label >Crag approach</label>
    <textarea name="approach" rows=5><?= $data["crag"]["approach"] ?></textarea><br>
    <label >Fixed gear policy</label>
    <input type="text" name="policy" value="<?= $data["crag"]["policy"] ?>">
    <label >Access issues</label>
    <input type="text" name="access" value="<?= $data["crag"]["access"] ?>">
    <label >Location (right click to set crag location)</label>
    <div id="map" class="panel"></div>
    <input id="latlng" type="text" name="location" value="<?= $data["crag"]["location"] ?>" readonly>
    <div class="margin-bottom-5">
        <input type="checkbox" name="public" class="inline" <?php if ($data["crag"]["public"] == true) echo "checked" ?>>
        <label class="inline">Publicly viewable</label>
    </div>
    <div>
        <button class="btn-save" type="submit"><?= $data["button"] ?></button>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Cancel</button>
    </div>
</form>