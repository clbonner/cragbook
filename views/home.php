<div class="content">
    <?php if($_SESSION["userid"] != NULL): ?>
        <div class="right">
            <button class="btn-edit" onclick="window.location.assign('<?= SITEURL ?>/admin/home.php')">Edit</button>
        </div>
    <?php endif ?>
    <?php echo htmlspecialchars_decode($data["home_text"]) ?>
</div>