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
        <option value="4" <?php if($data["route"]["discipline"] == '4') echo("selected") ?>>Hybrid</option>
        <option value="5" <?php if($data["route"]["discipline"] == '5') echo("selected") ?>>BANNED</option>
    </select>
    <label>Grade</label>
    <input type="text" name="grade" value="<?= $data["route"]["grade"] ?>">
    <label>Sport Bolting Happiness :)</label>
    <select name="seriousness">
        <option <?php if($data["route"]["seriousness"] == '0') echo("selected") ?>>n/a</option>
        <option value="1" <?php if($data["route"]["seriousness"] == '1') echo("selected") ?>>Safe</option>
        <option value="2" <?php if($data["route"]["seriousness"] == '2') echo("selected") ?>>Caution</option>
        <option value="3" <?php if($data["route"]["seriousness"] == '3') echo("selected") ?>>Scary</option>
        <option value="4" <?php if($data["route"]["seriousness"] == '4') echo("selected") ?>>Danger</option>
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