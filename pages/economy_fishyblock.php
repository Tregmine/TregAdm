<?php

require_once '../include/functions.php';

checkIfOnline();

$sql  = "SELECT * FROM fishyblock " 
      . "WHERE fishyblock_id = ? ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$fishyblock = $stmt->fetch();

$stmt->closeCursor();

if ($_SESSION["id"] != $fishyblock["player_id"] && !hasRank("junior_admin", "senior_admin")) {
    exit;
}

// +-----------------------+----------------------------------+------+-----+---------+----------------+
// | Field                 | Type                             | Null | Key | Default | Extra          |
// +-----------------------+----------------------------------+------+-----+---------+----------------+
// | transaction_id        | int(10) unsigned                 | NO   | PRI | NULL    | auto_increment |
// | fishyblock_id         | int(10) unsigned                 | YES  | MUL | NULL    |                |
// | player_id             | int(10) unsigned                 | YES  | MUL | NULL    |                |
// | transaction_type      | enum('deposit','withdraw','buy') | YES  |     | NULL    |                |
// | transaction_timestamp | int(10) unsigned                 | YES  |     | NULL    |                |
// | transaction_amount    | int(10) unsigned                 | YES  |     | NULL    |                |
// | transaction_unitcost  | int(10) unsigned                 | YES  |     | NULL    |                |
// | transaction_totalcost | int(10) unsigned                 | YES  |     | NULL    |                |
// +-----------------------+----------------------------------+------+-----+---------+----------------+

$sql  = "SELECT fishyblock_transaction.*, player_name, player_rank FROM fishyblock_transaction " 
      . "INNER JOIN player USING (player_id) "
      . "WHERE fishyblock_id = ? ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$transactions = $stmt->fetchAll();

$stmt->closeCursor();

foreach ($transactions as &$transaction) {
    $transaction["transaction_timestamp"] = niceTime($transaction["transaction_timestamp"]);
}

print json_encode($transactions);
