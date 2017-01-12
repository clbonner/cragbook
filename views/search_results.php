<div class="w3-container w3-white">
    <h4>Search Results</h4>
        <div class="w3-container w3-small w3-card-2 w3-margin-bottom">
            <h4>Areas</h4>
            <?php if($data["areas"] == 0): ?>
                <div class="w3-margin">
                    No areas found
                </div>
            <?php else: ?>
                <div class="w3-btn-bar w3-small w3-margin-bottom">
                    <?php foreach($data["areas"] as $i => $area): ?>
                                <a class="w3-btn w3-round w3-white w3-hover-red" href="<?= SITEURL ?>/crags.php?areaid=<?= $i ?>"><?= $area ?></a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
        <div class="w3-container w3-small w3-card-2 w3-margin-bottom">
            <h4>Crags</h4>
            <?php if($data["crags"] == 0): ?>
                <div class="w3-margin">
                    No crags found
                </div>
            <?php else: ?>
                <div class="w3-btn-bar w3-small w3-margin-bottom">
                    <?php foreach($data["crags"] as $i => $crag): ?>
                                <a class="w3-btn w3-round w3-white w3-hover-red" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $i ?>"><?= $crag ?></a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
        <div class="w3-container w3-small w3-card-2 w3-margin-bottom">
            <h4>Routes</h4>
            <?php if($data["routes"] == 0): ?>
                <div class="w3-margin">
                    No routes found
                </div>
            <?php else: ?>
                <table class="w3-table-all w3-tiny w3-margin-bottom">
                    <tr class="w3-blue">
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Crag</th>
                    </tr>
                    <?php foreach ($data["routes"] as $route): ?>
                        <tr>
                            <td><?= $route["name"] ?></td>
                            <td><?= $route["grade"] ?></td>
                            <td><?php   foreach($data["craglist"] as $crag) 
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