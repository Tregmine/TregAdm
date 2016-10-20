<?php
checkIfOnline();
checkRank("senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}
$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET['id']));
$player = $stmt->fetch();
if(!array_key_exists("password", $_POST)){
  //Prompt for authentication page
  $title = "Re-Authenticate";
  $context = array("subject" => $player);
  $styles = array();
  $scripts = array();
  render('player_reset_keyword_authenticate.phtml', $title, $context, $styles, $scripts);
}else{
  //Player has sent authentication values. Verify, then lift player keyword.
  $sql = "SELECT * FROM player WHERE player_name = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array($_SESSION['name']));
  $user = $stmt->fetch();
  $inputpassword = $_POST['password'];
  if (crypt($inputpassword, $player["player_password"]) != $user["player_password"]) {
    //Terminate session and redirect to invalid password page.
      setcookie("tregadm_login_nonce", "", 0);
      session_destroy();
      header('Location: /index.php?error=fail');
      exit;
  }
  //Player authenticated! Move on.
  if($_POST['subject'] != $player['player_name']){
    //Usernames did not match. Redirect to error page.
    header('Location: /code/913');
    exit;
  }

  if($_POST['remove']){
    $sql = "DELETE FROM player_property WHERE player_id = ? AND property_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($player['player_id'], "keyword"));
    $html = "<html><body><form id='form' action='/player/perm?id=".$player['player_id']."' method='post'><input type='hidden' name='keywordRemoved' value='true'></form><script>document.getElementById('form').submit();</script></body></html>";
    print($html);
  }else{
    //Did not check box. Redirect to error page.
    header('Location: /code/914');
    exit;
  }
}

 ?>
