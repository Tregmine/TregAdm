<?php

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
    if ($val['property_key'] == "senioradmin") {
        $staff['Senior'][] = $val['player_name'];
        if ($temp = array_keys($staff['Admin'],$val['player_name'])) {
            unset($staff['Admin'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Guardian'],$val['player_name'])) {
            unset($staff['Guardian'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Builder'],$val['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($val['property_key'] == "admin"
        and !(array_keys($staff['Senior'],$val['player_name']))) {

        $staff['Admin'][] = $val['player_name'];
        if ($temp = array_keys($staff['Guardian'],$val['player_name'])) {
            unset($staff['Guardian'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Builder'],$val['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($val['property_key'] == "guardian"
        and !(array_keys($staff['Senior'],$val['player_name']))
        and !(array_keys($staff['Admin'],$val['player_name']))) {

        $staff['Guardian'][] = $val['player_name'];
        if ($temp = array_keys($staff['Builder'],$val['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($val['property_key'] == "builder"
        and !(array_keys($staff['Senior'],$val['player_name']))
        and !(array_keys($staff['Admin'],$val['player_name']))
        and !(array_keys($staff['Guardian'],$val['player_name']))) {

        $staff['Builder'][] = $val['player_name'];
    }
}

$context = array();
$context["staff"] = $staff;

$styles = array();
$scripts = array();
render('staff.phtml', 'Tregmine Staff', $context, $styles, $scripts);
