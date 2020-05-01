<form class="content" action="<?= SITEURL ?>/admin/prefs.php" method="post">
    <div class="heading">User Preferences</div><br>
    <label>Username</label>
    <input type="text" name="username" value="<?= $data["user"]["username"] ?>" required>
    <label>Display Name</label>
    <input type="text" name="displayname" value="<?= $data["user"]["displayname"] ?>" required>
    <br>
    <div class="heading">Change Password</div>
    <label>Old password</label>
    <input type="password" autocomplete="off" name="oldpass">
    <label>New password</label>
    <input type="password" autocomplete="off" name="newpass">
    <label>Confirm new password</label>
    <input type="password" autocomplete="off" name="confirmpass">
    <div>
        <button class="btn-save" type="submit">Save</button>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= SITEURL ."/index.php" ?>')">Cancel</button>
    </div>
</form>
<form class="content" action="<?= SITEURL ?>/admin/export.php" method="get">
    <div class="heading">Export</div><br>
    <div>All crag routes must be exported before importing.</div>
    <div>Please select a crag.</div>
    <select name="cragid">
      <?php foreach($data["crags"] as $crag)
          echo "<option value=\"" .$crag["cragid"] ."\">" .$crag["name"] ."</option>";
      ?>
    </select>
    <button class="btn-save" type="submit">Export</button>
</form>
<form class="content" action="<?= SITEURL ?>/admin/import.php" method="post">
    <div class="heading">Import</div><br>
    <div>When importing all exisiting routes will be replaced with the imported routes.</div>
    <div>Please select a crag.</div>
    <select name="cragid">
      <?php foreach($data["crags"] as $crag)
          echo "<option value=\"" .$crag["id"] ."\">" .$crag["name"] ."</option>";
      ?>
    </select>
    <button class="btn-cancel" type="submit">Import</button>
</form>
