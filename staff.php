<?php
require_once '_init.php';
require_once '_check.php';
require_once '_perm.php';

if (!array_key_exists("senioradmin", $_SESSION)) {
    header('Location: index.php');
    exit;
}

$staffTmp = array();
$staff = array("Senior" => array(), "Admin" => array(), "Guardian" => array(), "Builder" => array());

$sql  = "SELECT player.player_name, player_property.* FROM player_property ";
$sql .= "INNER JOIN player ON player_property.player_id = player.player_id ";
$sql .= "WHERE (property_key IN (?, ?, ?) AND property_value = 'true') OR "
      . "property_key = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array("senioradmin","admin","builder","guardian"));
$staffTmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

foreach ($staffTmp as $key => $val) {
    if ($staffTmp[$key]['property_key'] == "senioradmin") {
        $staff['Senior'][] = $staffTmp[$key]['player_name'];
        if ($temp = array_keys($staff['Admin'],$staffTmp[$key]['player_name'])) {
            unset($staff['Admin'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Guardian'],$staffTmp[$key]['player_name'])) {
            unset($staff['Guardian'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Builder'],$staffTmp[$key]['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($staffTmp[$key]['property_key'] == "admin" and !(array_keys($staff['Senior'],$staffTmp[$key]['player_name']))) {
        $staff['Admin'][] = $staffTmp[$key]['player_name'];
        if ($temp = array_keys($staff['Guardian'],$staffTmp[$key]['player_name'])) {
            unset($staff['Guardian'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Builder'],$staffTmp[$key]['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($staffTmp[$key]['property_key'] == "guardian" and !(array_keys($staff['Senior'],$staffTmp[$key]['player_name'])) and !(array_keys($staff['Admin'],$staffTmp[$key]['player_name']))) {
        $staff['Guardian'][] = $staffTmp[$key]['player_name'];
        if ($temp = array_keys($staff['Builder'],$staffTmp[$key]['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($staffTmp[$key]['property_key'] == "builder" and !(array_keys($staff['Senior'],$staffTmp[$key]['player_name'])) and !(array_keys($staff['Admin'],$staffTmp[$key]['player_name'])) and !(array_keys($staff['Guardian'],$staffTmp[$key]['player_name']))) {
        $staff['Builder'][] = $staffTmp[$key]['player_name'];
    }
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

        <h2 class="info">Tregmine Staff</h2>
        <?php foreach(array("Guardian","Admin","Builder","Senior") as $rank): ?>
        <div class="col25">
            <table class="info">
                <tr>
                    <th colspan="9" class="infoHeader"><?php echo $rank; ?></th>
                </tr>
                <?php foreach ($staff[$rank] as $key): ?>
                <tr>
                    <td>
                        <a <?php echo userCSSColor($key); ?>" href="search.php?q=<?php echo $key; ?>"> <?php echo $key; ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endforeach; ?>

        <div class="col_clear">&nbsp;</div>
    </div>
</body>
</html>
