<?php

require_once '../include/functions.php';

checkIfOnline();

$id = $_SESSION["id"];
if (hasRank("junior_admin", "senior_admin") &&
    array_key_exists("id", $_GET)) {

    $id = $_GET["id"];
}

$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($id));

$player = $stmt->fetch();

$stmt->closeCursor();

// transactions
$sqlTransactions  = "SELECT * FROM player_transaction " 
                  . "INNER JOIN player ON player_id = recipient_id "
                  . "WHERE sender_id = ? ";
$sqlTransactions .= "UNION SELECT * FROM player_transaction "
                  . "INNER JOIN player ON player_id = sender_id "
                  . "WHERE recipient_id = ? ";
$sqlTransactions .= "ORDER BY transaction_timestamp DESC ";
if (!array_key_exists("all_transactions", $_GET)) {
    $sqlTransactions .= "LIMIT 20";
}

$stmt = $conn->prepare($sqlTransactions);
$stmt->execute(array($id, $id));

$transactions = $stmt->fetchAll();

$stmt->closeCursor();

// fishyblocks
// +---------------------------+--------------------------+------+-----+---------+----------------+
// | Field                     | Type                     | Null | Key | Default | Extra          |
// +---------------------------+--------------------------+------+-----+---------+----------------+
// | fishyblock_id             | int(10) unsigned         | NO   | PRI | NULL    | auto_increment |
// | player_id                 | int(10) unsigned         | YES  | MUL | NULL    |                |
// | fishyblock_created        | int(10) unsigned         | YES  |     | NULL    |                |
// | fishyblock_status         | enum('active','deleted') | YES  |     | active  |                |
// | fishyblock_material       | int(10) unsigned         | YES  |     | NULL    |                |
// | fishyblock_data           | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_enchantments   | text                     | YES  |     | NULL    |                |
// | fishyblock_cost           | int(10) unsigned         | YES  |     | NULL    |                |
// | fishyblock_inventory      | int(10) unsigned         | YES  |     | NULL    |                |
// | fishyblock_world          | varchar(50)              | YES  |     | NULL    |                |
// | fishyblock_blockx         | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_blocky         | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_blockz         | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_signx          | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_signy          | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_signz          | int(11)                  | YES  |     | NULL    |                |
// | fishyblock_storedenchants | enum('0','1')            | YES  |     | 0       |                |
// +---------------------------+--------------------------+------+-----+---------+----------------+
// 17 rows in set (0.00 sec)

$sqlFishyblocks  = "SELECT * FROM fishyblock " 
                 . "LEFT JOIN item ON fishyblock_material = item_id AND fishyblock_data = item_data "
                 . "WHERE player_id = ? AND fishyblock_status = 'active'";

$stmt = $conn->prepare($sqlFishyblocks);
$stmt->execute(array($id));

$fishyblocks = $stmt->fetchAll();

$stmt->closeCursor();

$sqlAccounts  = "SELECT * FROM bank_account ";
$sqlAccounts .= "INNER JOIN bank USING (bank_id) ";
$sqlAccounts .= "INNER JOIN zone_lot USING (lot_id) ";
$sqlAccounts .= "INNER JOIN zone USING (zone_id) ";
$sqlAccounts .= "WHERE player_id = ?";

$stmt = $conn->prepare($sqlAccounts);
$stmt->execute(array($id));

$accounts = $stmt->fetchAll();

$stmt->closeCursor();

$context = array();
$context["player"] = $player;
$context["transactions"] = $transactions;
$context["fishyblocks"] = $fishyblocks;
$context["accounts"] = $accounts;

$styles = array();
$scripts = array("/js/economy.js");
render('economy.phtml', 'Economy', $context, $styles, $scripts);

