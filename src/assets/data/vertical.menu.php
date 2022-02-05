<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Content-type: application/json');

$uid=$_COOKIE['uid'];

//$X=new OracleDB();
//$db=$X->connectACN();

$data = file_get_contents("php://input");
$data = json_decode($data, TRUE);

$output='[
{
		"id": "home",
		"title": "Navigation",
		"subtitle": "",
		"type": "group",
		"icon": "heroicons_outline:home",	
		"children": [{
				"id": "db",
				"title": "Home",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/sadmin"
		},
		{
				"id": "db",
				"title": "Facility Search",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/facility-search"
		},
		{
				"id": "db",
				"title": "Asset Search",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/asset-search"
		},
		{
				"id": "db",
				"title": "Overdue Tests",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/overdue-tests"
		},
		{
				"id": "db",
				"title": "Active Assessments",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/active-assessments"
		},
		{
				"id": "db",
				"title": "Classic MIST",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/mist-home"
		},
		{
				"id": "db",
				"title": "Logout",
				"type": "basic",
				"icon": "heroicons_outline:clipboard-check",
				"link": "/mist-logout"
		}]
}]';
   $arr=json_decode($output,true);
   $output=array();
   $output['default']=$arr;
   $output['compact']=array();
   $output['futuristic']=array();
   $output['horizontal']=array();   
  echo json_encode($output);	

?>