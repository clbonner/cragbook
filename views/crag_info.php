<div class="w3-container w3-small">
    <div class="w3-margin-top">
        <a style="text-decoration: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $data["area"]["areaid"] ?>"><i class="fa fa-angle-left"></i> <?= $data["area"]["name"] ?> </a>
    </div>
    <div class="w3-container w3-small w3-card-2 w3-border w3-margin-top">
        <?php if (isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/crag.php?action=edit&cragid=<?= $_GET["cragid"] ?>">Edit <?= $data["crag"]["name"] ?></a>
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/crag.php?action=delete&cragid=<?= $_GET["cragid"] ?>">Delete <?= $data["crag"]["name"] ?></a>
            </div>
        <?php endif ?>
        <h1><?= $data["crag"]["name"] ?></h1>
        <h6><?= htmlspecialchars_decode($data["crag"]["description"]) ?></h6>
        <p><b>Access: </b><?= $data["crag"]["access"] ?></p>
        <p><b>Policy on fixed gear: </b><?= $data["crag"]["policy"] ?></p>
        <p><b>Approach: </b><?= htmlspecialchars_decode($data["crag"]["approach"]) ?></p>
    </div>
    <div class="w3-container w3-card-2 w3-margin-top">
        <?php if (isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/route.php?action=add&cragid=<?= $_GET["cragid"] ?>">Add Route</a>
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/route_sort.php?cragid=<?= $_GET["cragid"] ?>">Sort</a>
            </div>
        <?php endif ?>
        <h4>Routes</h4>
        <div class="w3-tiny w3-margin-bottom">
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>">All</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>&filter=british">British</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>&filter=french">French</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>&filter=yds">YDS</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>&filter=uiaa">UIAA</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>&filter=font">Font</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crag_info.php?cragid=<?= $_GET["cragid"] ?>&filter=vgrade">V grade</a>
        </div>
        <?php if ($data["routes"] != 0): ?>
            <table class="w3-table-all w3-tiny w3-margin-bottom">
                <tr class="w3-blue">
                    <th><a href="<?= SITEURL ?>/crag_info.php?cragid=<?= $data["crag"]["cragid"] ?>&sort=name">Name</a></th>
                    <th><a href="<?= SITEURL ?>/crag_info.php?cragid=<?= $data["crag"]["cragid"] ?>&sort=grade">Grade</a></th>
                    <th><a href="<?= SITEURL ?>/crag_info.php?cragid=<?= $data["crag"]["cragid"] ?>&sort=stars">Stars</a></th>
                    <th>Length</th>
                    <th>Sector</th>
                    <th style="width:50%">Description</th>
                </tr>
                <?php if (isset($_SESSION["userid"])): ?>
                    <?php foreach ($data["routes"] as $route): ?>
                        <tr class="w3-round w3-hover-red">
                            <td><a href="<?= SITEURL ?>/admin/route.php?action=delete&routeid=<?= $route["routeid"] ?>"><i class="fa fa-times w3-small"></i></a>    <a href="<?= SITEURL ?>/admin/route.php?action=edit&routeid=<?= $route["routeid"] ?>"><?= $route["name"] ?></a></td>
                            <td><?= $route["grade"] ?></td>
                            <td><?= $route["stars"] ?></td>
                            <td><?= $route["length"] ?>m</td>
                            <td><?= $route["sector"] ?></td>
                            <td><?= htmlspecialchars_decode($route["description"]) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <?php foreach ($data["routes"] as $route): ?>
                        <tr>
                            <td><?= $route["name"] ?></td>
                            <td><?= $route["grade"] ?></td>
                            <td><?= $route["stars"] ?></td>
                            <td><?= $route["length"] ?>m</td>
                            <td><?= $route["sector"] ?></td>
                            <td><?= htmlspecialchars_decode($route["description"]) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </table>
        <?php endif ?>
    </div>
</div>