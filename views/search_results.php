<div class="margin-15">
    <h4>Search Results</h4>
</div>
<div class="content panel">
    <h4>Areas</h4>
    <?php if($data["areas"] == 0): ?>
        <div>
            No areas found
        </div>
    <?php else: ?>
        <div>
            <?php foreach($data["areas"] as $i => $area): ?>
                        <a class="btn" href="<?= SITEURL ?>/crags.php?areaid=<?= $i ?>"><?= $area ?></a>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
<div class="content panel">
    <h4>Crags</h4>
    <?php if($data["crags"] == 0): ?>
        <div>
            No crags found
        </div>
    <?php else: ?>
        <div>
            <?php foreach($data["crags"] as $i => $crag): ?>
                        <a class="btn" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $i ?>"><?= $crag ?></a>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
<div class="content panel">
    <h4>Routes</h4>
    <?php if($data["routes"] == 0): ?>
        <div>
            No routes found
        </div>
    <?php else: ?>
        <table class="panel">
            <tr>
                <th>Name</th>
                <th>Grade</th>
                <th>Crag</th>
            </tr>
            <?php foreach ($data["routes"] as $route): ?>
                <tr>
                    <td><?= $route["name"] ?></td>
                    <td><?= $route["grade"] ?></td>
                    <td><?php foreach($data["craglist"] as $crag) 
                        { 
                            if($crag["cragid"] ==  $route["cragid"])
                                echo "<a href=\"" . SITEURL ."/crag_info.php?cragid=" .$crag["cragid"] ."\">" .$crag["name"] ."</a>";
                        } ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</div>
