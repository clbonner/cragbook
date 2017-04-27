<div class="content">
    <?php if($_SESSION["userid"] != NULL): ?>
        <div class="right">
            <button class="btn-edit fa fa-edit" onclick="window.location.assign('<?= SITEURL ?>/admin/home.php')"></button>
        </div>
    <?php endif ?>
    <?php echo htmlspecialchars_decode($data["home_text"]) ?>
</div>