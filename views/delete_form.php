<form class="content center middle" action="<?= SITEURL ?>/admin/<?= $data["controller"] ?>" method="post">
    <p><?= $data["message"] ?></p>
    <div>
        <button class="btn-edit" type="submit">Delete</button>
        <button class="btn-save" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')">Noooo!</button>
    </div>
</form>