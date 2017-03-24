<form class="content" action="<?= SITEURL ?>/admin/route.php" method="post">
    <label>Route name</label>
    <input type="text" name="name" value="<?= $data["route"]["name"] ?>" required>
    <label>Route description</label>
    <textarea name="description" rows=5><?= $data["route"]["description"] ?></textarea><br>
    <label>Discipline</label>
    <select name="discipline">
        <option disabled <?php if($data["route"]["discipline"] == '0') echo("selected") ?>>Select...</option>
        <option value="1" <?php if($data["route"]["discipline"] == '1') echo("selected") ?>>Trad</option>
        <option value="2" <?php if($data["route"]["discipline"] == '2') echo("selected") ?>>Sport</option>
        <option value="3" <?php if($data["route"]["discipline"] == '3') echo("selected") ?>>Bouldering</option>
    </select>
    <label>Grade</label>
    <input type="text" name="grade" value="<?= $data["route"]["grade"] ?>">
    <label>Seriousness</label>
    <select name="seriousness">
        <option disabled <?php if($data["route"]["seriousness"] == '0') echo("selected") ?>>Select...</option>
        <option value="1" <?php if($data["route"]["seriousness"] == '1') echo("selected") ?>>Safe</option>
        <option value="2" <?php if($data["route"]["seriousness"] == '2') echo("selected") ?>>Care required</option>
        <option value="3" <?php if($data["route"]["seriousness"] == '3') echo("selected") ?>>Scary</option>
    </select>
    <label>Stars</label>
    <input type="text" name="stars" value="<?= $data["route"]["stars"] ?>">
    <label>Length in metres</label>
    <input type="text" name="length" value="<?= $data["route"]["length"] ?>" required>
    <label>Crag Sector (if applicable)</label>
    <input type="text" name="sector" value="<?= $data["route"]["sector"] ?>">
    <label>First ascent:</label>
    <input type="text" name="fascent" value="<?= $data["route"]["firstascent"] ?>">
    <div class="margin-bottom-5">
        <input type="checkbox" name="private" class="inline" <?php if ($data["route"]["private"] == true) echo "checked" ?>>
        <label class="inline">Hide description</label>
    </div>
    <div>
        <button class="btn-save" type="submit"><?= $data["button"] ?></button>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Cancel</button>
    </div>
</form>