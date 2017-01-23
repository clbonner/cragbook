<script>
    $(document).ready( function () {
        setAreaMap('<?= $data["area"]["location"] ?>');
    });
</script>
<form class="w3-container w3-small" action="<?= SITEURL ?>/admin/area.php" method="post">
    <div class="w3-margin-top">
        <label class="w3-label w3-text-black"><b>Area name</b></label>
        <input class="w3-input w3-border" style="width: 50%" type="text" name="name" value="<?= $data["area"]["name"] ?>" required>
    </div>
    <div class="w3-margin-top">
        <label class="w3-label w3-text-black"><b>Area description</b></label>
        <textarea class="w3-input w3-border" name="description" rows=5><?= $data["area"]["description"] ?></textarea>
    </div>
    <div class="w3-margin-top">
        <label class="w3-label w3-text-black"><b>Area location (centre location on map)</b></label>
        <div id="map" class="w3-card-2" style="height: 300px; width: 100%"></div>
        <input id="latlng" class="w3-input w3-border w3-margin-top" style="width: 50%" type="text" name="location" value="<?= $data["area"]["location"] ?>" readonly>
    </div>
    <div class="w3-margin-top">
        <input class="w3-btn w3-round w3-green" type="submit" value="<?= $data["button"] ?>">
        <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="Cancel">    
    </div>
</form>