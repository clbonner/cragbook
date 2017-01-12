<div class="w3-container w3-card-2 w3-margin">
    <h4>All Crags</h4>
    <div class="w3-btn-bar w3-small w3-margin-bottom">
        <?php if ($data["crags"] != 0): ?>
            <?php foreach($data["crags"] as $i => $name): ?>
                    <a class="w3-btn w3-hover-red w3-round" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $i ?>"><?= $name ?></a>
            <?php endforeach ?>
        <?php else: ?>
            No crags found
        <?php endif ?>
    </div>
</div>