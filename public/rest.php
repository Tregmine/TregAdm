<?php
require_once '../include/init.php';
//database is $conn
/*

$sql = "this is a stmt";

$stmt = $conn->prepare($sql);
$stmt->execute(array($inventory["inventory_id"]));

*/

if(isset($_GET['method']) && !empty($_GET['method'])){
  $method = str_replace("/", "_", $_GET['method']);
  if(function_exists($method){
    $method();
  }else{
    echo "Invalid API method '$method'. Terminating";
    exit;
  }
}

//Getters

function get_homepage(){
  echo "get_homepage";
}

?>
