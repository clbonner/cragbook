<form class="content" action="<?= SITEURL ?>/admin/prefs.php" method="post">
    <div class="heading">User Preferences</div><br>
    <label><b>Username</b></label>
    <input type="text" name="username" value="<?= $data["user"]["username"] ?>" required>
    <label><b>Display Name</b></label>
    <input type="text" name="displayname" value="<?= $data["user"]["displayname"] ?>" required>    
    <h6>Change Password</h6>
    <label><b>Old password</b></label>
    <input type="password" autocomplete="off" name="oldpass">    
    <label><b>New password</b></label>
    <input type="password" autocomplete="off" name="newpass">
    <label><b>Confirm new password</b></label>
    <input type="password" autocomplete="off" name="confirmpass">
    <div>
        <button class="btn-save" type="submit">Save</button>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= SITEURL ."/index.php" ?>')">Cancel</button>
    </div>
</form>