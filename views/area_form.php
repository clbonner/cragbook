<form class="w3-container w3-small w3-margin-top" action="<?= SITEURL ?>/admin/area.php" method="post">
    <label class="w3-label w3-text-black"><b>Area name</b></label>
    <input class="w3-input w3-border w3-margin-bottom" style="width: 50%" type="text" name="name" value="<?= $data["area"]["name"] ?>" required>
    <label class="w3-label w3-text-black"><b>Area description</b></label>
    <textarea class="w3-input w3-border w3-margin-bottom" name="description" rows=5><?= $data["area"]["description"] ?></textarea>
    <div class="w3-margin-top">
        <input class="w3-btn w3-round w3-green" type="submit" value="<?= $data["button"] ?>">
        <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="Cancel">    
    </div>
</form>