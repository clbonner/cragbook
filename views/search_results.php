<div class="heading margin-15">
    Search Results
</div>
<div class="content panel">
    <div class="heading">Areas</div>
    <div class="list">
        <?php if($data["areas"] == 0): ?>
            <div class="center">
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
</div>
<div class="content panel">
    <div class="heading">Crags</div>
    <div class="list">
        <?php if($data["crags"] == 0): ?>
            <div class="center">
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
</div>
<div class="content panel">
    <div class="heading">Routes</div>
    <div id="routes" class="margin-top-5">
        <?php if($data["routes"] == 0): ?>
            <p>
                No routes found
            </p>
        <?php else: ?>
            <table>
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
</div>
