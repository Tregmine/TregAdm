<?php
require_once '_init.php';
require_once '_check.php';
require_once '_perm.php';

if (!array_key_exists("senioradmin", $_SESSION)) {
    header('Location: index.php');
    exit;
}

if (!array_key_exists("id", $_GET)) {
    exit;
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

$stmt = $conn->prepare("SELECT * FROM player_property WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$rawSettings = $stmt->fetchAll();
foreach ($rawSettings as $setting) {
    $settings[$setting["property_key"]] = $setting["property_value"];
}

$sql  = "SELECT player_id, player_name, property_value FROM player_property ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE property_key = 'guardian' ";
$sql .= "ORDER BY property_value ";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$guardians = array();
$maxRank = 0;
foreach ($result as $guardian) {
    $guardians[$guardian["property_value"]] = $guardian;
    $maxRank = max($maxRank, $guardian["property_value"]);
}
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

        <h2 class="info"><span <?php echo userCSSColor($player["player_name"]); ?>><?php echo $player["player_name"]; ?></span> (<?php echo $player["player_id"]; ?>)</h2>

        <div class="col75">

            <table class="info">
                <tr>
                    <th colspan="9" class="infoHeader">Properties</th>
                </tr>

                <?php foreach ($settings as $key => $value):
                    if (array_key_exists($key, $permissionList) && $key != "color") continue;
                    ?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <form method="post" action="player_perm_save.php?id=<?php echo $player["player_id"]; ?>">

            <table class="info">
                <tr>
                    <th colspan="9" class="infoHeader">Permissions</th>
                </tr>

                <?php foreach ($permissionList as $key => $name): ?>
                    <tr>
                        <td><input type="checkbox" name="perm[<?php echo $key; ?>]" id="perm_<?php echo $key; ?>" value="1" <?php if (array_key_exists($key, $settings) && $settings[$key] == "true") echo ' checked="checked"'; ?> /></td>
                        <td><label for="perm_<?php echo $key; ?>"><?php echo $name; ?></label></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3 class="infoHeader">Other</h3>

            <div class="field">
                <label for="guardian">Guardian Rank</label>
                <div class="element">
                    <select name="guardian" id="guardian">
                        <option value="">Not a guardian</option>
                        <?php for ($i = 1; $i <= $maxRank+1; $i++): ?>
                            <?php if (array_key_exists($i, $guardians)):
                                $guardian = $guardians[$i]; ?>
                                <?php if ($guardian["player_id"] == $player["player_id"]): ?>
                                    <option selected="selected" value="<?php echo $i; ?>"><?php echo $i; ?> - <?php echo $guardian["player_name"]; ?></option>
                                <?php else: ?>
                                    <option disabled="disabled" value="<?php echo $i; ?>"><?php echo $i; ?> - <?php echo $guardian["player_name"]; ?></option>
                                <?php endif; ?>
                            <?php else: ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="end">&nbsp;</div>
            </div>

            <div class="field">
                <label for="color">Color</label>
                <div class="element">
                    <select name="color" id="color">
                        <?php foreach ($colors as $name => $color): ?>
                            <option value="<?php echo $name; ?>" <?php if (array_key_exists("color", $settings) && $settings["color"] == $name) echo 'selected="selected"'; ?>><?php echo $name; ?> (<?php echo $color; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="end">&nbsp;</div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="element">
                    <input type="text" name="password" id="password" />
                    <p>
                        The password will only be changed if you enter something in this box.
                    </p>
                </div>
                <div class="end">&nbsp;</div>
            </div>

            <div class="button">
                <button type="submit">Save changes</button>
            </div>

            </form>

        </div>

        <div class="col25">

            <h3 class="actionsHeader">Actions</h3>

            <ul class="actions">
                <li><a href="player_report.php?id=<?php echo $player["player_id"]; ?>">Reports</a></li>
                <li><a href="player_stats.php?id=<?php echo $player["player_id"]; ?>">Stats</a></li>
            </ul>

        </div>

        <div class="col_clear">&nbsp;</div>
    </div>
</body>
</html>
