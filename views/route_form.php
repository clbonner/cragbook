<form class="content" action="<?= SITEURL ?>/admin/route.php" method="post">
    <label><b>Route name</b></label>
    <input type="text" name="name" value="<?= $data["route"]["name"] ?>" required>
    <label><b>Route description</b></label>
    <textarea name="description" rows=5><?= $data["route"]["description"] ?></textarea><br>
    <label><b>Grade</b></label>
    <input type="text" name="grade" value="<?= $data["route"]["grade"] ?>">
    <label><b>Stars</b></label>
    <input type="text" name="stars" value="<?= $data["route"]["stars"] ?>">
    <label><b>Length in metres</b></label>
    <input type="text" name="length" value="<?= $data["route"]["length"] ?>" required>
    <label><b>Crag Sector (if applicable)</b></label>
    <input type="text" name="sector" value="<?= $data["route"]["sector"] ?>">
    <label><b>First ascent:</b></label>
    <input type="text" name="fascent" value="<?= $data["route"]["firstascent"] ?>">
    <div>
        <button class="btn-save" type="submit"><?= $data["button"] ?></button>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Cancel</button>
    </div>
</form>