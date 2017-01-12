<div class="w3-container w3-small w3-card-2 w3-white w3-margin">
    <?php if(isset($_SESSION["userid"])): ?>
        <div class="w3-right w3-margin-top">
            <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/area.php?action=add">Add Area</a>
        </div>
    <?php endif ?>
    <h4>Climbing Areas</h4>
    <div class="w3-small w3-margin-bottom">
        <?php if ($data["areas"] != 0): ?>
            <?php foreach($data["areas"] as $i => $area): ?>
                <a class="w3-btn w3-round w3-white w3-hover-red" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $i ?>"><?= $area ?></a>
            <?php endforeach ?>
        <?php else: ?>
            No areas found
        <?php endif ?>
    </div>
</div>