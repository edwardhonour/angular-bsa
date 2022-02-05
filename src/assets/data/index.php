<?php
//---------------------------------------------------------------------
// Main API Router for this angular directory.
// Author:  Edward Honour
// Date: 07/18/2021
//---------------------------------------------------------------------

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Content-type: application/json');

require_once('../../../lib/class.OracleDB.php');

$uid=$_COOKIE['uid'];

$X=new OracleDB();
$db=$X->connectACN();

// Require and initialize the class libraries necessary for this module. Code 
// specific for your application goes in here.

//=======================================================================================
// APPLICATION SPECIFIC CODE BELOW - CONNECT STRING CODE ABOVE
//=======================================================================================

require_once('class.forms.php');
require_once('class.auth.php');
require_once('class.assets.php');

$U=new AUTH();
$A=new ASSETS();
$F=new FORMS();

// Get the Data from the POST.  Note:  This is not how PHP normally sends POST data. The 
// data from the angular post will be in a variable called data.

$data = file_get_contents("php://input");
$data = json_decode($data, TRUE);

$output=array();

if (!isset($data['q'])) $data['q']="vertical-menu";
$aa=explode("/",$data['q']);

if (isset($aa[1])) {
     $data['q']=$aa[1];
     if (isset($aa[2])) {
         $data['id']=$aa[2]; 
	 }
     if (isset($aa[3])) {
         $data['id2']=$aa[3]; 
	 }		 
	 if (isset($aa[4])) {
         $data['id3']=$aa[4]; 
	 }		 
}
	
//--
//-- ROUTER based on q.
//--

if ($data['q']=='login') {
    $output=$U->getLogin($data);
} else {		

	switch ($data['q']) {
        case 'sadmin':
                $output=$A->getDashboard($data);
                break;	
        case 'facility-dashboard':
                $output=$A->getFacilityDashboard($data);
                break;			
        case 'facility-search':
                $output=$A->getFacilitySearch($data);
                break;		                	
        case 'asset-search':
                $output=$A->getAssetSearch($data);
                break;	
        case 'overdue-tests':
                $output=$A->getOverdueTests($data);
                break;		
        case 'active-assessments':
                $output=$A->getActiveAssessments($data);
                break;					
        case 'post-bsa-test':
                $output=$A->postBSATest($data);
                break;
        case 'post-edit-asset':
                $output=$A->postEditAsset($data);
                break;	
        case 'post-delete-asset':
                $output=$A->deleteAsset($data);
                break;	
        case 'post-add-compnents':
        case 'post-add-components':
                $output=$A->postAddComponents($data);
                break;
        default:
                $output=$A->getDashboard($data);
                break;
	}
}

$o=json_encode($output);
$o=stripcslashes($o);
$o=str_replace('null','""',$o);
echo $o;
?>