<form class="w3-container w3-small w3-margin-top" action="<?= SITEURL ?>/admin/crag.php" method="post">
    <label class="w3-label w3-text-black"><b>Crag name</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="name" value="<?= $data["crag"]["name"] ?>" required>
    <label class="w3-label w3-text-black"><b>Crag description</b></label>
    <textarea class="w3-input w3-white w3-border" name="description" rows=5><?= $data["crag"]["description"] ?></textarea><br>
    <label class="w3-label w3-text-black"><b>Crag approach</b></label>
    <textarea class="w3-input w3-white w3-border" name="approach" rows=5><?= $data["crag"]["approach"] ?></textarea><br>
    <label class="w3-label w3-text-black"><b>Fixed gear policy</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="policy" value="<?= $data["crag"]["policy"] ?>">
    <label class="w3-label w3-text-black"><b>Access issues</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="access" value="<?= $data["crag"]["access"] ?>">
    <label class="w3-label w3-text-black"><b>Location (lat,long)</b></label>
    <input class="w3-input w3-white w3-border w3-margin-bottom" style="width: 50%" type="text" name="location" value="<?= $data["crag"]["location"] ?>">
    <div>
        <input class="w3-btn w3-round w3-green" type="submit" value="<?= $data["button"] ?>">
        <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="Cancel">
    </div>
    </form>