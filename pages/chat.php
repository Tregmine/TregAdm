<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

$context = array();

$styles = array();
$scripts = array("/js/chat.js");
render('chat.phtml', 'Chat', $context, $styles, $scripts);
