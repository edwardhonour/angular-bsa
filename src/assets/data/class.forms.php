<?php

require_once('../../../lib/class.OracleDB.php');

class FORMS {

	protected $X;
	
    function __construct() {
	$this->X=new OracleDB();   
    }

function start_output($data) {
		$output=array();
		$output['user']=$this->getUser($data);
		return $output;
}

function sendtxt($to,$msg) {

    $to=str_replace("+","",$to);
    $to=str_replace("(","",$to);
    $to=str_replace(")","",$to);
    $to=str_replace(" ","",$to);	
	$to=str_replace("-","",$to);
	
	if (strlen($to)==11) {
	    // phone number must start with 1
	}
	
	if (strlen($to)==10) {
	    $to='1' . $to;	
	}
	
	$url = 'https://api.twilio.com/2010-04-01/Accounts/ACe54a4d9deb0216f3fe4640b3e33054a9/Messages';
	$postRequest = array(
		'Body' => $msg,
		'To' => $to,
		'From' => '14699084644'
	);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERPWD, "ACe54a4d9deb0216f3fe4640b3e33054a9:4c15f8291a8caf37654f586e02c0c65a");  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
	$response = curl_exec($ch);
	if(curl_errno($ch)){
		throw new Exception(curl_error($ch));
	}
}



    function force_logout($error) {
			 $user=array();
			 $user['force_logout']=$error;
			 $user['id']="";
			 $user['role']="";
			 $user['org_id']="";
			 $user['company_id']="";
			 $user['last_login']=0;
			 $user['last_timestamp']=0;
		     return $user;	
	}
	
	    function make_error($code,$dsc) {
	    $output=array();
		$output['error_code']=$code;
		$output['error_description']=$dsc;
	    if ($code==0) {
			$output['result']="success";
		} else {
			$output['result']="failed";			
		}
		return $output;
	}
	
}