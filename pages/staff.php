<?php

$staffTmp = array();
$staff = array("Senior" => array(), "Admin" => array(), "Guardian" => array(), "Builder" => array());

$sql  = "SELECT player_id, player_name, player_rank FROM player ";
$sql .= "WHERE player_rank IN (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->execute(array("senior_admin","junior_admin","builder","guardian"));
$staffTmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

foreach ($staffTmp as $key => $val) {
    if ($val['player_rank'] == "senior_admin") {
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

    if ($val['player_rank'] == "junior_admin"
        and !(array_keys($staff['Senior'],$val['player_name']))) {

        $staff['Admin'][] = $val['player_name'];
        if ($temp = array_keys($staff['Guardian'],$val['player_name'])) {
            unset($staff['Guardian'][$temp[0]]);
        }
        if ($temp = array_keys($staff['Builder'],$val['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($val['player_rank'] == "guardian"
        and !(array_keys($staff['Senior'],$val['player_name']))
        and !(array_keys($staff['Admin'],$val['player_name']))) {

        $staff['Guardian'][] = $val['player_name'];
        if ($temp = array_keys($staff['Builder'],$val['player_name'])) {
            unset($staff['Builder'][$temp[0]]);
        }
    }

    if ($val['player_rank'] == "builder"
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
