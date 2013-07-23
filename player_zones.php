<?php
require_once '_init.php';
require_once '_check.php';
require_once '_perm.php';

if (!array_key_exists("admin", $_SESSION)) {
    header('Location: index.php');
    exit;
}

if (!array_key_exists("id", $_GET)) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));
$player = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$stmt = $conn->prepare("SELECT player.player_name, zone.zone_name, zone.zone_id, zone.zone_world, zone_user.user_perm FROM zone_user INNER JOIN player ON zone_user.user_id = player.player_id INNER JOIN zone ON zone_user.zone_id = zone.zone_id WHERE player.player_id = ? ORDER BY zone.zone_name");
$stmt->execute(array($_GET["id"]));
$zones = $stmt->fetchAll(PDO::FETCH_ASSOC);;
$stmt->closeCursor();

$stmt = $conn->prepare("SELECT zone_lot.lot_name, zone_lot.zone_id, zone_lotuser.lot_id, zone.zone_name, zone.zone_world FROM zone_lot INNER JOIN zone_lotuser ON zone_lot.lot_id = zone_lotuser.lot_id INNER JOIN zone ON zone_lot.zone_id = zone.zone_id WHERE zone_lotuser.user_id = ? ORDER BY zone_lot.lot_name");
$stmt->execute(array($_GET["id"]));
$lots = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Tregmine Admin Tool</title>
    <style type="text/css">
    @import 'style.css';
    </style>
</head>
<body>
    <div id="layout_wrapper">
        <h1 id="banner"><span>Tregmine Admin Tool</span></h1>

        <?php require 'menu.php'; ?>

        <h2 class="info">Zone and Lot Info: <div style="display:inline"<?php echo userCSSColor($player["player_id"]) . ">" . $player["player_name"] ?></div> (<?php echo $player["player_id"] ?>)</h2>

        <div class="col75">

            <table class="info">
                <tr>
                    <th colspan=3 class="infoHeader">Zones</th>
                </tr>
                <tr>
                    <th width=100%>Zone</th>
                    <th>Rank</th>
                    <th>World</th>
                </tr>
                <?php
                foreach($zones as $zone) {
                    echo "<tr><td><a href=\"zone_info.php?id=" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</a></td><td>" . $zone['user_perm'] . "</td><td>" . $zone['zone_world'] . "</td></tr>\n";
                }
                ?>
            </table>

            <table class="info">
                <tr>
                    <th colspan=3 class="infoHeader">Lots</th>
                </tr>
                <tr>
                    <th width=100%>Lot Name</th>
                    <th>Zone</th>
                    <th>World</th>
                </tr>
                <?php
                foreach($lots as $lot) {
                    echo "<tr><td>" . $lot['lot_name'] . "</td><td><a href=\"zone_info.php?id=" . $lot['zone_id'] . "\">" . $lot['zone_name'] . "</a></td><td>" . $lot['zone_world'] . "</td></tr>\n";
                }
                ?>
            </table>

        </div>

        <div class="col25">

            <h3 class="actionsHeader">Actions</h3>
            <ul class="actions">
                <li><a href="player_report.php?id=<?php echo $player["player_id"]; ?>">Reports</a></li>
                <li><a href="player_stats.php?id=<?php echo $player["player_id"]; ?>">Stats</a></li>
                <?php if (array_key_exists("senioradmin", $_SESSION)): ?>
                <li><a href="player_perm.php?id=<?php echo $player["player_id"]; ?>">Permissions</a></li>
                <?php endif; ?>
            </ul>
            <br />

        </div>

        <div class="col_clear">&nbsp;</div>
    </div>
</body>
</html>
