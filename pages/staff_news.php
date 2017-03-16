<?php
checkIfOnline();
if(!isStaff($_SESSION['rank'], $permissionList)){
	header('Location: /');
    exit;
}
if(!empty($_GET['delete'])){
	if($_SESSION['rank'] != "senior_admin"){
		exit;
	}
	$sql = "DELETE FROM staffnews WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($_GET['delete']));
	$stmt->closeCursor();
	header('Location: /index.php/staff/news');
}
$sql = "SELECT * FROM staffnews";

$stmt = $conn->prepare($sql);
$stmt->execute();
$sendnews = array();
$news = $stmt->fetchAll();
foreach($news as $row){
	$msg = array();
	$msg['id'] = $row["id"];
	$msg['msg'] = $row["text"];
	$msg['timestamp'] = $row["timestamp"];
	$msg['player'] = $row["username"];
	$msg['playercolor'] = userCSSColor($row['username']);
	$sendnews[$row['id']] = $msg;
}
$stmt->closeCursor();
$context = array();
$context["news"] = $sendnews;
$context["settings"] = array();
$title = "Staff News";
$styles = array("/css/inventory.css");
$scripts = array();

render('staff_news.phtml', $title, $context, $styles, $scripts);
