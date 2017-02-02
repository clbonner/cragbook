<script>
    $(document).ready( function () {
        setAreaMap('<?= $data["area"]["location"] ?>');
    });
</script>
<form class="content" action="<?= SITEURL ?>/admin/area.php" method="post">
    <label>Area name</label>
    <input type="text" name="name" value="<?= $data["area"]["name"] ?>" required>
    <label>Area description</label>
    <textarea name="description" rows=5><?= $data["area"]["description"] ?></textarea><br>
    <label>Area location (centre location on map)</label>
    <div id="map" class="panel"></div>
    <input id="latlng" type="text" name="location" value="<?= $data["area"]["location"] ?>" readonly>
    <button class="btn-save" type="submit"><?= $data["button"] ?></button>
    <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Cancel</button>
</form>