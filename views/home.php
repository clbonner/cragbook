<div class="content">
    <?php if($_SESSION["userid"] != NULL): ?>
        <div class="right">
            <a class="btn-edit" href="<?= SITEURL ?>/admin/home.php">Edit</a>
        </div>
    <?php endif ?>
    <?php echo htmlspecialchars_decode($data["home_text"]) ?>
</div>