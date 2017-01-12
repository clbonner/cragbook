<?php if($_SESSION["userid"] != NULL): ?>
    <div class="w3-margin w3-small w3-right">
        <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/home.php">Edit</a>
    </div>
<?php endif ?>
<div class="w3-container w3-margin-top">
    <?php echo htmlspecialchars_decode($data["home_text"]) ?>
</div>