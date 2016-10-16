<?php
require_once '../include/init.php';

$news = array(
  1 => array(
    'nwsttl' => "Welcome to Tregmine!",
    'nwssbtl' => "mc.tregmine.com",
    'nwsbdy' => "Tregmine is a Minecraft Economy server running Spigot 1.10. If you want to build up towns and corporations, go out on your own, or become a builder, this server is the one for you!

We run a custom set of plugins developed by our team. They're all publicly available and anyone is welcome to contribute!",
    'nwstyp' => 'wlcm'
  ),
  2 => array(
    'nwsttl' => "Status Update",
    'nwssbtl' => "4th June, 2016",
    'nwsbdy' => "No news here!",
    'nwstyp' => 'nws'
  )
);

if(isset($_GET['method']) && !empty($_GET['method'])){
  //$method = str_replace("/", "_", $_GET['method']);
  $method = str_replace('/', '_', $_GET['method']);
  if(function_exists($method)){
    $method($conn, $news, $settings);
  }else{
    $error = array(
      'type' => "error",
      'title' => "Missing or invalid method call",
      'message' => "The method you called was either missing, invalid, or disabled."
    );
    echo json_encode($error);
    exit;
  }
}else{
  $error = array(
    'type' => "error",
    'title' => "Missing or invalid method call",
    'message' => "The method you called was either missing, invalid, or disabled."
  );
  echo json_encode($error);
  exit;
}

//Getters

function get_homepage($conn, $news, $settings){
  $homepage = array();
  $recentLogins = array();
  $newMembers = array();
  $sql  = "SELECT * FROM player_login ";
  $sql .= "INNER JOIN player USING (player_id) ";
  $sql .= "WHERE login_action = 'login' ";
  $sql .= "ORDER BY login_timestamp DESC LIMIT 10";

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  $recentLogins = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $loginsKeys = array_keys($recentLogins);
  foreach($loginsKeys as $key){
    unset($recentLogins[$key]['player_password']);
    unset($recentLogins[$key]['player_email']);
    unset($recentLogins[$key]['login_ip']);
    unset($recentLogins[$key]['login_hostname']);
    unset($recentLogins[$key]['player_flags']);
  }

  $sql  = "SELECT student_id, student.player_name student_name, "
        . "mentor_id, mentor.player_name mentor_name, "
        . "mentorlog_completedtime FROM mentorlog ";
  $sql .= "INNER JOIN player student ON student.player_id = student_id ";
  $sql .= "INNER JOIN player mentor ON mentor.player_id = mentor_id ";
  $sql .= "WHERE mentorlog_status = 'completed' ";
  $sql .= "ORDER BY mentorlog_completedtime DESC LIMIT 10";

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  $newMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $homepage['logins'] = $recentLogins;
  $homepage['settlers'] = $newMembers;
  $homepage['news'] = $news;
  $homepage['nologins'] = $settings["disablelogin"];
  echo json_encode($homepage);
  exit;
}

?>
