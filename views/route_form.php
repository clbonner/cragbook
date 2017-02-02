<form class="content" action="<?= SITEURL ?>/admin/route.php" method="post">
    <label>Route name</label>
    <input type="text" name="name" value="<?= $data["route"]["name"] ?>" required>
    <label>Route description</label>
    <textarea name="description" rows=5><?= $data["route"]["description"] ?></textarea><br>
    <label>Grade</label>
    <input type="text" name="grade" value="<?= $data["route"]["grade"] ?>">
    <label>Stars</label>
    <input type="text" name="stars" value="<?= $data["route"]["stars"] ?>">
    <label>Length in metres</label>
    <input type="text" name="length" value="<?= $data["route"]["length"] ?>" required>
    <label>Crag Sector (if applicable)</label>
    <input type="text" name="sector" value="<?= $data["route"]["sector"] ?>">
    <label>First ascent:</label>
    <input type="text" name="fascent" value="<?= $data["route"]["firstascent"] ?>">
    <div>
        <button class="btn-save" type="submit"><?= $data["button"] ?></button>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Cancel</button>
    </div>
</form>