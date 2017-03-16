<?php
if(!$settings["maintenance"]){
	header("Location: /");
}
$context = array();

$styles = array();
$scripts = array();
render('maintenance.phtml', 'Maintenance', $context, $styles, $scripts);