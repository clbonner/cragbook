<form class="w3-container w3-small" action="<?= SITEURL ?>/admin/prefs.php" method="post">
    <h4>User Preferences</h4>
    <label class="w3-label w3-text-black"><b>Username</b></label>
    <input class="w3-input w3-border w3-margin-bottom" style="width: 50%" type="text" name="username" value="<?= $data["user"]["username"] ?>" required>
    <label class="w3-label w3-text-black"><b>Display Name</b></label>
    <input class="w3-input w3-border w3-margin-bottom" style="width: 50%" type="text" name="displayname" value="<?= $data["user"]["displayname"] ?>" required>    
    <h6>Change Password</h6>
    <label class="w3-label w3-text-black"><b>Old password</b></label>
    <input class="w3-input w3-border w3-margin-bottom" style="width: 50%" type="password" autocomplete="off" name="oldpass">    
    <label class="w3-label w3-text-black"><b>New password</b></label>
    <input class="w3-input w3-border w3-margin-bottom" style="width: 50%" type="password" autocomplete="off" name="newpass">
    <label class="w3-label w3-text-black"><b>Confirm new password</b></label>
    <input class="w3-input w3-border w3-margin-bottom" style="width: 50%" type="password" autocomplete="off" name="confirmpass">
    <input class="w3-btn w3-round w3-green" type="submit" value="Save">
    <input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign('<?= SITEURL ."/index.php" ?>')" value="Cancel">
</form>