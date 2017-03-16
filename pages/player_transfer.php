<?php
checkIfOnline();
checkRank("junior_admin", "senior_admin");

$context = array();

$styles = array();
$scripts = array();
render('player_transfer.phtml', 'Player Conversion System', $context, $styles, $scripts);
