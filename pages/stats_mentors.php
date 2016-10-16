<?php

$sql  = "SELECT mentor_id, player_name, player_rank, count(*) c from mentorlog ";
$sql .= "INNER JOIN player mentor ON mentor.player_id = mentor_id ";
$sql .= "WHERE mentorlog_status = 'completed' ";
$sql .= "GROUP BY mentor_id ";
$sql .= "ORDER BY c desc";
if($_SESSION["mobile"]) $sql .= " LIMIT 20";

$stmt = $conn->prepare($sql);
$stmt->execute();

$mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("mentors" => $mentors);

$styles = array();
$scripts = array();
render('stats_mentors.phtml', 'Most students mentored', $context, $styles, $scripts);

