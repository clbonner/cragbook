<form class="content center middle" action="<?= SITEURL ?>/admin/<?= $data["controller"] ?>" method="post">
    <div class="heading"><?= $data["message"] ?></div>
    <div class="margin-5">
        <button class="btn-edit" type="submit">Delete</button>
        <button class="btn-save" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Noooo!</button>
    </div>
</form>