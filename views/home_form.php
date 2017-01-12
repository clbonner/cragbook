<form class="w3-container w3-small w3-margin-top" action="<?= SITEURL ?>/admin/home.php" method="post">
    <label class="w3-label w3-text-black"><b>Home page text</b></label>
    <textarea class="w3-input w3-border" name="text" rows=10><?= $data["site"]["home_text"] ?></textarea>
    <div class="w3-margin-top">
        <input class="w3-btn w3-round w3-green" type="submit" value="Save">
        <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= SITEURL ."/index.php" ?>')" value="Cancel">
    </div>
</form>