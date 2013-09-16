<?php

checkIfOnline();

checkRank("senior_admin", "junior_admin");

$sql  = "SELECT mentorlog.*, student.player_name student_name, "
      . "mentor.player_name mentor_name FROM mentorlog ";
$sql .= "INNER JOIN player student ON student.player_id = student_id ";
$sql .= "INNER JOIN player mentor ON mentor.player_id = mentor_id ";
$sql .= "ORDER BY mentorlog_startedtime DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array();
$context["sessions"] = $sessions;

$styles = array();
$scripts = array();
render('mentorlog.phtml', 'Mentoring log', $context, $styles, $scripts);
