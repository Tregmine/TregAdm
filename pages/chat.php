<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin", "coder");

$context = array();

$styles = array();
$scripts = array("/js/chat.js");
render('chat.phtml', 'Chat', $context, $styles, $scripts);
