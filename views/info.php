<div class="content center middle">
    <p><h5><?php echo $data["message"] ?></h5></p>
    <?php if(isset($data["button"])): ?>
        <input class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="<?= $data["button"] ?>">
    <?php endif ?>
</div>