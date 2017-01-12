<div class="w3-container w3-small w3-display-middle w3-center">
    <p><h5><?php echo $data["message"] ?></h5></p>
    <?php if(isset($data["button"])): ?>
        <input class="w3-btn w3-round w3-red w3-margin-top" type="button" onclick="window.location.assign('<?= $data["returnurl"] ?>')" value="<?= $data["button"] ?>">
    <?php endif ?>
</div>