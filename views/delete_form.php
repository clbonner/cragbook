<form class="w3-container w3-display-middle w3-center" action="<?= SITEURL ?>/admin/<?= $data["controller"] ?>" method="post">
    <p><h5><?= $data["message"] ?></h5></p>
    <div class="w3-margin-top">
        <input class="w3-btn w3-round w3-red" type="submit" value="Delete">
        <input class="w3-btn w3-round w3-green" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="Noooo!">
    </div>
</form>