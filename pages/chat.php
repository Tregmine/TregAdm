<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin", "coder", "guardian", "builder");

$context = array();

$styles = array();
$scripts = array("/js/chat.js");
render('chat.phtml', 'Chat', $context, $styles, $scripts);
