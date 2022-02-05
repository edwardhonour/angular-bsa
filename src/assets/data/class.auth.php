<?php

require_once('../../../lib/class.OracleDB.php');

class AUTH {

    public $X;

    function __construct() {
         $this->X=new OracleDB();
    }

    function getLogin($data) {
		$sql="SELECT USER_ID, USER_NAME, ROLE FROM TBL_USER WHERE ID = " . $data['uid'];
		$u=$this->X->sql($sql);
		
        $output=array();
	    $output['uid']=$u[0]['USER_ID'];
	    $output['un']=$u[0]['USER_NAME'];
	    $output['role']=$u[0]['ROLE'];
	    $output['error_code']=0;
        return $output;
    }

}







