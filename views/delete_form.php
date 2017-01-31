<form class="content center middle" action="<?= SITEURL ?>/admin/<?= $data["controller"] ?>" method="post">
    <p><h5><?= $data["message"] ?></h5></p>
    <div>
        <button class="btn-edit" type="submit">Delete</button>
        <button class="btn-save" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Noooo!</button>
    </div>
</form>