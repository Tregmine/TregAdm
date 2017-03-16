<?php

checkIfOnline();

$context = array();

$styles = array();
$scripts = array();
render('donate_finish.phtml', 'Donation finished!', $context, $styles, $scripts);

