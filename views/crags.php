<script>
    getCrags(<?= $_GET["areaid"] ?>);
    $(document).ajaxSuccess(function() {
        listViewCrags();
    });
</script>
<div class="w3-container w3-small">
    <div class="w3-container w3-small w3-card-2 w3-border w3-margin-top">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/area.php?action=edit&areaid=<?= $_GET["areaid"] ?>">Edit <?= $data["area"]["name"] ?></a>
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/area.php?action=delete&areaid=<?= $_GET["areaid"] ?>">Delete <?= $data["area"]["name"] ?></a>
            </div>
        <?php endif ?>
        <h1><?= $data["area"]["name"] ?></h1>
        <h6><?= htmlspecialchars_decode($data["area"]["description"]) ?></h6>
    </div>
    <div class="w3-container w3-card-2 w3-small w3-margin-top">
        <?php if(isset($_SESSION["userid"])): ?>
            <div class="w3-right w3-margin-top">
                <a class="w3-btn w3-red w3-round" href="<?= SITEURL ?>/admin/crag.php?action=add&areaid=<?= $_GET["areaid"] ?>">Add Crag</a>
            </div>
        <?php endif ?>
        <h4>Crags</h4>
        <div class="w3-btn-bar">
            <i id="listview" class="fa fa-list w3-btn w3-round w3-white w3-hover-red" onclick="listViewCrags()"></i>
            <i id="mapview" class="fa fa-map-o w3-btn w3-round w3-white w3-hover-red" onclick="showMapCrags('<?= $data["area"]["location"] ?>')"></i>
        </div>
        <div id="view" class="w3-small w3-margin-bottom"></div>
    </div>
    <div class="w3-container w3-card-2 w3-margin-top">
        <h4>Routes</h4>
        <div class="w3-white w3-tiny w3-margin-bottom">
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>">All</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>&filter=british">British</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>&filter=french">French</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>&filter=yds">YDS</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>&filter=uiaa">UIAA</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>&filter=font">Font</a>
            <a class="w3-btn w3-white w3-hover-red w3-round" style="box-shadow: none" href="<?= SITEURL ?>/crags.php?areaid=<?= $_GET["areaid"] ?>&filter=vgrade">V grade</a>
        </div>
        <?php if ($data["routes"] != 0): ?>
            <table class="w3-table-all w3-tiny w3-margin-bottom">
                <tr class="w3-blue">
                    <th><a href="<?= SITEURL ?>/crags.php?areaid=<?= $data["area"]["areaid"] ?>&sort=name">Name</a></th>
                    <th>Grade</th>
                    <th><a href="<?= SITEURL ?>/crags.php?areaid=<?= $data["area"]["areaid"] ?>&sort=stars">Stars</a></th>
                    <th style="width:50%">Crag</th>
                </tr>
                <?php foreach ($data["routes"] as $route): ?>
                    <tr>
                        <td><?= $route["name"] ?></td>
                        <td><?= $route["grade"] ?></td>
                        <td><?= $route["stars"] ?></td>
                        <td>
                            <?php foreach($data["crags"] as $crag): ?>
                                <?php if($crag["cragid"] ==  $route["cragid"]): ?>
                                    <a href="<?= SITEURL ?>/crag_info.php?cragid=<?= $crag["cragid"] ?>"><?= $crag["name"] ?></a>
                                <?php endif ?>
                            <?php endforeach ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
    </div>
</div>