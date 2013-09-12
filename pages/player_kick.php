<?php

checkIfOnline();
checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

if (!array_key_exists("subject", $_GET)) {
    exit;
}
if (!array_key_exists("message", $_GET)) {
    exit;
}

$subjectId = $_GET["subject"];
$issuerId = $_SESSION["id"];
$message = $_GET["message"];

$result = tregmine_kick_player($tregmineApiKey, $subjectId, $issuerId);
echo json_encode($result);
