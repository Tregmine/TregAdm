<?php

checkIfOnline();

$context = array();

$styles = array();
$scripts = array();
render('donate.phtml', 'Donate to Tregmine', $context, $styles, $scripts);
