<div class="content center middle">
    <div class="heading"><?php echo $data["message"] ?></div>
    <?php if(isset($data["button"])): ?>
        <button class="btn-border" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="<?= $data["button"] ?>"></button>
    <?php endif ?>
</div>