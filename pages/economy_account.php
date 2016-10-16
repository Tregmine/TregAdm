<?php

require_once '../include/functions.php';

checkIfOnline();

$sql  = "SELECT * FROM bank_account " 
      . "WHERE account_id = ? ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$account = $stmt->fetch();

$stmt->closeCursor();

if ($_SESSION["id"] != $account["player_id"] && !hasRank("junior_admin", "senior_admin")) {
    exit;
}

$sql  = "SELECT bank_transaction.*, player_name, player_rank FROM bank_transaction " 
      . "INNER JOIN player USING (player_id) "
      . "WHERE account_id = ? ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$transactions = $stmt->fetchAll();

$stmt->closeCursor();

foreach ($transactions as &$transaction) {
    $transaction["transaction_timestamp"] = niceTime($transaction["transaction_timestamp"]);
}

print json_encode($transactions);
