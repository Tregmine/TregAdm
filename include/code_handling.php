<?php

function code_exists($code, $registry){
	if(isset($registry["reason"][$code])){
		return true;
	}else{
		return false;
	}
}
function get_code_header($code, $registry){
  return $registry["reason"][$code];
}
function get_code_message($code, $registry){
  return $registry["description"][$code];
}
function get_code_type($code, $registry){
  return $registry["type"][$code];
}
