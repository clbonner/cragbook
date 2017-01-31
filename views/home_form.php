<form class="content" action="<?= SITEURL ?>/admin/home.php" method="post">
    <label><b>Home page text</b></label>
    <textarea name="text" rows=10><?= $data["site"]["home_text"] ?></textarea><br>
    <button class="btn-save" type="submit">Save</button>
    <button class="btn-cancel" type="button" onclick="window.location.assign('<?= SITEURL ."/index.php" ?>')">Cancel</button>
</form>