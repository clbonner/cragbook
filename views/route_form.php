<form class="w3-container w3-small w3-margin-top" action="<?= SITEURL ?>/admin/route.php" method="post">
    <label class="w3-label w3-text-black"><b>Route name</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="name" value="<?= $data["route"]["name"] ?>" required>
    <label class="w3-label w3-text-black"><b>Route description</b></label>
    <textarea class="w3-input w3-white w3-border" name="description" rows=5><?= $data["route"]["description"] ?></textarea><br>
    <label class="w3-label w3-text-black"><b>Grade</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="grade" value="<?= $data["route"]["grade"] ?>">
    <label class="w3-label w3-text-black"><b>Stars</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="stars" value="<?= $data["route"]["stars"] ?>">
    <label class="w3-label w3-text-black"><b>Length in metres</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="length" value="<?= $data["route"]["length"] ?>" required>
    <label class="w3-label w3-text-black"><b>Crag Sector (if applicable)</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="sector" value="<?= $data["route"]["sector"] ?>">
    <div>
        <input class="w3-btn w3-round w3-green" type="submit" value="<?= $data["button"] ?>">
        <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="Cancel">
    </div>
</form>