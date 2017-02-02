<div class="content center middle">
    <p><h3><?php echo $data["message"] ?></h3></p>
    <?php if(isset($data["button"])): ?>
        <button class="btn-cancel" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="<?= $data["button"] ?>"></button>
    <?php endif ?>
</div>