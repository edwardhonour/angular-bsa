<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Content-type: application/json');

require_once('../../lib/class.OracleDB.php');

$uid=$_COOKIE['uid'];

$X=new OracleDB();
$db=$X->connectACN();

$data = file_get_contents("php://input");
$data = json_decode($data, TRUE);
	
$sql="select * from TBL_USER where USER_ID = " . $data['uid'];
$user=$X->sql($sql);
if (sizeof($user)==0) {
$output='{
    "id"    : "cfaad35d-07a3-4447-a6c3-d8c3d54fd5df",
    "name"  : "User Not Found",
    "email" : "nouser@nuaxess.org",
    "avatar": "assets/images/avatars/brian-hughes.jpg",
    "status": "online"
}';
 $arr=json_decode($output,true);  
 echo json_encode($arr);
} else {
   $output=array();
   $output['id']= "cfaad35d-07a3-4447-a6c3-d8c3d54fd5df";
   $output['name']=$user[0]['FULL_NAME'];
   $output['email']=$user[0]['EMAIL'];
   $output['avatar']="assets/images/avatars/brian-hughes.jpg";
   $output['status']="online";
   echo json_encode($output);
}

?>