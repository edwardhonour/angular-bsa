<?php

require_once('../../../lib/class.OracleDB.php');

class ASSETS {

    public $X;

    function __construct() {
         $this->X=new OracleDB();
    }

    function getDashboard($data) {
		
       $output=array();

           //--
           
	   $sql="SELECT REGION_ID, ROLE, SEGMENT_ID FROM TBL_USER WHERE USER_ID = " . $data['uid'];	
	   $u=$this->X->sql($sql);
       	   $user=$u[0];
           $role=$user['ROLE'];
           $segment_id=$user['SEGMENT_ID'];
	   $rid=$user['REGION_ID'];
	   $sql="SELECT COUNT(*) AS C FROM RWH_DIM_FACILITY WHERE REGION_ID = " . $rid . " AND ACTIVE_FLAG = 'Y' AND FPS_RESPONSIBLE = 'Y' AND BUILDING = 'Y'";
	   $u=$this->X->sql($sql);		
	   $output['facility_count']=$u[0]['C'];

	   $sql="SELECT COUNT(*) AS C FROM FPS_BSA WHERE REGION_ID = " . $rid . " AND STATUS NOT IN ('OutofService','Excessed','Pending Replacement')";
	   $u=$this->X->sql($sql);		
	   $output['asset_count']=$u[0]['C'];
	   
	   $sql="SELECT COUNT(*) AS C FROM RWH_DIM_FACILITY WHERE C_FSA_STATUS IN ('ASSESSMENT','REVIEW1','REVIEW2','REVISE','LESPM','PRESENTED') AND REGION_ID = " . $rid;
	   $u=$this->X->sql($sql);		
	   $output['in_progress_count']=$u[0]['C'];
	   
	   $sql="SELECT SUM(C_OVERDUE_TEST_COUNT) AS C FROM RWH_DIM_FACILITY WHERE REGION_ID = " . $rid;
	   $u=$this->X->sql($sql);		
	   $output['overdue_count']=$u[0]['C'];

	   $g=$this->getMyFacilities($data);
	   $active=array();
	   $waiting=array();
	   foreach($g as $h) {
		   if ($h['A_STATUS']=="WAITING") {
			   array_push($waiting,$h);   
		   } else {
			   array_push($active,$h);
		   }
	   }
	   $output['waiting']=$waiting;
	   $output['active']=$active;
       return $output;
    }
	
	function getFacilityDashboard($data) {
	
		$output=array();
		//-- Put Facility Record in output.
		
		$sql="SELECT ";
		$sql .= "FACILITY_ID, BUILDING_NBR, FSL,	REGION_ID, DISTRICT_ID, CITY_NAME, STATE_ABBR, ZIPCODE, FACILITY_NAME, ADDRESS, ADDRESS2, ";
		$sql .= "LAST_FSA_DATE, NEXT_FSA_DATE, AREA_COMMANDER_ID, DISTRICT_COMMANDER_ID, ASSIGNED_INSPECTOR, P_BEAT, POPULATION, RENTABLE_SQFT, ";
		$sql .= "LAST_FSA_ID, DELEGATED, DELEGATION_TYPE, DELEGATION_AGENCY, CAMPUS, CAMPUS_PRIMARY, CAMPUS_NAME, CAMPUS_ID, ";
		$sql .= "FSCDO_NAME,	FSCDO_TITLE, FSCDO_EMAIL, FSCDO_OFFICE_PHONE, FSCDO_CELL_PHONE, FSCDO_OTHER_TYPE, ";
		$sql .= "FSCDO_OTHER_PHONE, FSCDO_LAST_UPDATED, SPECIAL_INSTRUCTIONS, NO_SEC_FEE, FSCDO_AGENCY,  ";
		$sql .= "C_FSA_ID, C_FSA_STATUS,	C_VALIDATION_ERROR, C_BLOP_COUNT, C_NLOP_COUNT,	C_LAST_TAR_DATE, C_BSA_COUNT, C_OVERDUE_TEST_COUNT, C_FSA_YEAR,  ";
		$sql .= "C_FINAL_COUNT, C_ACTIVE_COUNT, C_WAITING_COUNT, DHS_FLAG, AGENCY_BUILDING_NBR, C_TAR_COUNT, C_ELOP_COUNT, C_BASELINE,  ";
		$sql .= "C_MITIGATION, C_MISSING_CM,	C_FSL_COUNT, C_FPS_OVERDUE_TEST_COUNT, C_FPS_BSA_COUNT, C_GSA_OVERDUE_TEST_COUNT, C_GSA_BSA_COUNT, C_TENANT_OVERDUE_TEST_COUNT,  ";
		$sql .= "C_TENANT_BSA_COUNT, C_UNK_OVERDUE_TEST_COUNT, C_UNK_BSA_COUNT, C_OWNED_BSA_COUNT, C_LEASED_BSA_COUNT, C_OWNED_OVERDUE_COUNT, C_LEASED_OVERDUE_COUNT, FOREIGNLEASE, FOREIGNCOUNTRY,  ";
		$sql .= "0 AS C_LESSOR_OVERDUE_TEST_COUNT, C_LESSOR_BSA_COUNT  ";
                $sql .= " FROM RWH_DIM_FACILITY WHERE FACILITY_ID = " . $data['id'];
		$f=$this->X->sql($sql);
		foreach($f[0] as $name => $value) {
                   $v=$value;
                   $v=str_replace('\n','',$v);
                   $v=str_replace('\r','',$v);
                   $v=str_replace("\n",'',$v);
                   $v=str_replace("\r",'',$v);
		   $output[$name]=$v;	
		}
		
		//-- Get Assets
		
		$sql="SELECT * FROM FPS_BSA WHERE  NVL(COMPONENT_ID,0) = 0 AND FACILITY_ID = " . $data['id'] . " AND IS_SYSTEM IN ('Y','N') AND STATUS NOT IN ('Excessed')";

		$f=$this->X->sql($sql);
		$r=array();
		foreach($f as $g) {
			$g['BARCODE']='N';
			$g['LAST_INSP_RESULT']=str_replace('\r\n','',$g['LAST_INSP_RESULT']);
			$g['LAST_INSP_RESULT']=str_replace("\r\n",'',$g['LAST_INSP_RESULT']);
			$g['LAST_INSP_RESULT']=str_replace("\r",'',$g['LAST_INSP_RESULT']);
			$g['LAST_INSP_RESULT']=str_replace("\n",'',$g['LAST_INSP_RESULT']);
			$g['LAST_INSP_RESULT']="";
			$g['NOTE']="";
			$sql="SELECT * FROM FPS_BSA WHERE COMPONENT_ID = " . $g['ASSET_ID'];
			$m=$this->X->sql($sql);
                        $t=array();
                        foreach($m as $n) {
                            $n['BARCODE']='N';
                            array_push($t,$n);
                        }
			$g['components']=$t;
			$g['component_count']=sizeof($m);
					
			$sql="SELECT * FROM FPS_FSA_DOCUMENTS WHERE ASSET_ID = " . $g['ASSET_ID'];
			$m=$this->X->sql($sql);
			$g['docs']=$m;
			$g['doc_count']=sizeof($m);
			
			$sql="SELECT * FROM FPS_BSA_TEST WHERE ASSET_ID = " . $g['ASSET_ID'] . " ORDER BY TEST_DATE";
			$m=$this->X->sql($sql);
                        $t=array();
                        foreach($m as $n) {
                            $n['BARCODE']='N';
                            array_push($t,$n);
                        }
			$g['history']=$t;
			$g['history_count']=sizeof($m);
			
			array_push($r,$g);	
			
		}
		$output['pacs']=$r;
		
		$formData=array();
		$formData['ASSET_ID']="";
		$formData['FACILITY_ID']="";
		$formData['BUILDING_NBR']="";
		$formData['ASSET_TYPE']="";
		$formData['POST_ID']="";
		$formData['AGENCY_CODE']="";
		$formData['LOCATION_DSC']="";
		$formData['MFG']="";
		$formData['MODEL']="";
		$formData['SERIAL']="";
		$formData['INVENTORY_NUM']="";
		$formData['XR_TAG']="";
		$formData['COST']="";
		$formData['CONTRACT_NUM']="";
		$formData['WARRANTY_END_DT']="";
		$formData['LIFE_END_DT']="";
		$formData['SERVICE_VENDOR_ID']="";
		$formData['LAST_SERVICE_DT']="";
		$formData['LAST_SERVICE_DSC']="";
		$formData['INSTALL_DT']="";
		$formData['EXCESS_DT']="";     
		$formData['EXCESS_REASON']="";
		$formData['LAST_INSP_DT']="";
		$formData['LAST_INSP_RESULT']="";
		$formData['NOTE']="";
		$formData['STATUS']="";
		$formData['NEXT_SERVICE_DT']="";
		$formData['NEXT_SERVICE_DSC']="";
		$formData['OWNERSHIP']="";
		$formData['AGENCY_POC']="";
		$formData['DESCRIPTION']="";
		$formData['SERVICE_VENDOR_NAME']="";
		$formData['ADD_USER_ID']="";
		$formData['ADD_DT']="";
		$formData['DEL_USER_ID']="";
		$formData['DEL_DT']="";
		$formData['DISPOSAL_SERIAL']="";
		$formData['SUNFLOWER_NUM']="";
		$formData['POC_NAME']="";
		$formData['POC_NUMBER']="";
		$formData['POC_EMAIL']="";
		$formData['REGION_ID']="";
		$formData['ORIGINAL_ORDER']="";
		$formData['CURRENT_TASK_ORDER']="";
		$formData['SALES_ORDER']="";
		$formData['LEASE_CONTRACT']="";        
		$formData['ADDRESS']="";
		$formData['CITY']="";
		$formData['ENTRANCE_TABLE']="";
		$formData['EXIT_TABLE']="";
		$formData['ORDER_DT']="";
		$formData['POP_START_DT']="";
		$formData['POP_EXPIRE_DT']="";
		$formData['EST_DELIV_DT']="";
		$formData['TASK_ORDER_STATUS']="";
		$formData['LOCATION_VER']="";
		$formData['LOAD_STATUS']="";
		$formData['ORIGINAL_MASTER']="";
		$formData['BARCODE']="";
		$formData['OFFICIAL_NAME']="";
		$formData['STOCK_NUMBER']="";
		$formData['FLAGS']="";
		$formData['UNIQUE_NAME']="";
		$formData['STEWARD']="";
		$formData['COMPONENT']="";
		$formData['OWNER']="";
		$formData['CUSTODIAN']="";
		$formData['ACTIVITY_STATUS']="";
		$formData['ASSET_CONDITIION']="";
		$formData['CONDITION_DESCRIPTION']="";
		$formData['SITE']="";
		$formData['CONTAINER']="";
		$formData['RESOLUTION ']="";
		$formData['AGREEMENT']="";
		$formData['DOCUMENT_TYPE']="";
		$formData['DOC_NUM']="";
		$formData['STORAGE_TYPE']="";
		$formData['ORGANIZATION']="";
		$formData['INITIAL_EVENT']="";
		$formData['MODEL_NAME']="";
		$formData['PHYSICAL_INVENTORY_DATE']="";
		$formData['PHYSICAL_INVENTORY_DT']="";
		$formData['COMPONENT_FLAG']="";
		$formData['COMPONENT_ID']="";
		$formData['ASSET_SUB_TYPE']="";
		$formData['ASSET_CLASS']="";
		$formData['ASSET_NAME']="";
		$formData['ACTIVE_FLAG']="";
		$formData['ATTR_ANALOG']="";
		$formData['ATTR_DIGITAL']="";
		$formData['ATTR_IP']="";
		$formData['ATTR_FIXED']="";
		$formData['ATTR_PTZ']="";
		$formData['ATTR_BW']="";
		$formData['ATTR_INFRARED']="";
		$formData['ATTR_HSPD12']="";
		$formData['ATTR_APL_LIST']="";
		$formData['ATTR_CRASH_RATED']="";
		$formData['CREATE_TIME']="";
		$formData['UPLOADED_BY']="";
		$formData['MAINT_CONTRACT']="";
		$formData['MAINT_VENDOR']="";
		$formData['AGE']="";
		$formData['COMPONENT_COUNT']="";
		$formData['COMPONENT_WORKING']="";
		$formData['COMPONENT_NEED_REPAIR']="";
		$formData['AGENCY_RESPONSIBILITY']="";
		$formData['LESSOR_NAME']="";
		$formData['IS_SYSTEM']="";
		$formData['COUNT_PTZ']="";
		$formData['COUNT_FIXED']="";
		$formData['COUNT_IP']="";
		$formData['COUNT_MONITORS']="";
		$formData['COUNT_DVR']="";
		$formData['COUNT_CONTROLLERS']="";
		$formData['HAS_UPS']="";
		$formData['RECORDING_TYPE']="";
		$formData['CONTROLLER_TYPE']="";
		$formData['PEN_RATING']="";
		$formData['ITEM_COUNT']="";
		$formData['VERIFIED']="";
		$formData['VERIFIED_BY']="";
		$formData['VERIFIED_DT']="";
		$formData['MFG_ID']="";
		$formData['MODEL_ID']="";
		$formData['R2_ASSET_TYPE_ORIG']="";
		$formData['CREATE_DATE']="";
		$formData['COUNT_IDS_CONTROL_PANEL']="";
		$formData['COUNT_IDS_POWER_SUPPLY']="";
		$formData['COUNT_IDS_UPS']="";
		$formData['COUNT_IDS_USER_INTERFACE']="";
		$formData['COUNT_IDS_DETECTOR']="";
		$formData['COUNT_IDS_WARNING_DEVICE']="";
		$formData['COUNT_IDS_REMOTE_CONSOLE']="";
		$formData['COUNT_IDS_DURESS_BUTTON']="";
		$formData['COUNT_IDS_WINDOW_SENSOR']="";
		$formData['COUNT_IDS_GLASS_BREAK_SENSOR']="";
		$formData['COUNT_IDS_DOOR_SENSOR']="";
		$formData['LAST_TEST_DATE']="";
		$formData['LAST_TEST_USER_ID']="";
		$formData['TEST_DUE_DATE']="";
		$formData['TEST_DAYS']="";
		$formData['STATE']="";
		$formData['ZIP']="";
		$formData['REP_XRAY_UNIT']="";
		$output['formData']=$formData;
		
		$formData=array();
		$formData['ID']="";
		$formData['ASSET_ID']="";
		$formData['TEST_DATE']="";
		$formData['TITLE']="";
		$formData['RESULTS']="";		
		$formData['REASON']="";
		$output['formData2']=$formData;		
		
		$formData=array();
		$formData['ASSET_ID']="";
		$formData['ASSET_NAME']="";
		$formData['ASSET_TYPE']="";
		$formData['ASSET_CLASS']="";
		$formData['QTY']="1";
		$formData['MFG']="";
		$formData['MODEL']="";
		$formData['SERIAL']="";
		$output['formData3']=$formData;
     
        return $output;
		
    }		
	function getMyFacilities($data) {

		$uid=$data['uid'];
		$sql="SELECT REGION_ID, ROLE, SEGMENT_ID FROM TBL_USER WHERE USER_ID = " . $data['uid'];	
		$u=$this->X->sql($sql);
        $user=$u[0];
        $role=$user['ROLE'];
        $segment_id=$user['SEGMENT_ID'];
		$rid=$user['REGION_ID'];
		
        $sql="SELECT COUNT(*) AS C FROM FPS_USER_PRIVS WHERE USER_ID = " . $uid . " AND PRIV_ID = 508";
	$u=$this->X->sql($sql);
        $p508=$u[0]['C'];

        if ($role=="LESPM"||$p508!='0') {
            $sql = "SELECT BUILDING_NBR, FACILITY_ID, ";
	        $sql .= " C_FSA_STATUS AS A_STATUS, ";
			$sql .= " FACILITY_NAME, ZIPCODE, ADDRESS, ";
	        $sql .= " CITY_NAME, STATE_ABBR, FSL, C_VALIDATION_ERROR, C_BLOP_COUNT, C_NLOP_COUNT, C_OVERDUE_TEST_COUNT, ";
         	$sql .= " C_TAR_COUNT, C_MISSING_CM, C_LAST_TAR_DATE, ";
	        $sql .= " LAST_FSA_DATE, NEXT_FSA_DATE, SUBSTR(NEXT_FSA_DATE,7,4) AS FSA_YEAR, C_FSA_STATUS, ";
            $sql .= " LAST_FSL_DATE, C_FSA_ID AS FSA_ID, 'INSP' AS ROLE, ASSIGNED_INSPECTOR, AREA_COMMANDER_ID, ";
			$sql .= " C_FSL_COUNT FROM TBL_DIM_FACILITY WHERE BUILDING = 'Y' AND SEGMENT_ID = '" . $segment_id . "' ";
            $sql .= " AND FPS_RESPONSIBLE = 'Y' AND ACTIVE_FLAG = 'Y' AND ";
	        $sql .= " REGION_ID = " . $rid . " AND C_FSA_STATUS IN ('WAITING','APPROVED','PRESENTED','FINAL','ASSESSMENT','REVISE','LESPM','REVIEW1','REVIEW2') ";
            $sql .= " ORDER BY 1";
         } else {
        	$sql = "SELECT BUILDING_NBR, FACILITY_ID, ";
			$sql .= " C_FSA_STATUS AS A_STATUS, ";
	        $sql .= " FACILITY_NAME, ZIPCODE, ADDRESS, ";
	        $sql .= " CITY_NAME, STATE_ABBR, FSL, C_VALIDATION_ERROR, C_BLOP_COUNT, C_NLOP_COUNT, C_OVERDUE_TEST_COUNT, C_TAR_COUNT, C_MISSING_CM, ";
			$sql .= " C_LAST_TAR_DATE, LAST_FSA_DATE, NEXT_FSA_DATE, SUBSTR(NEXT_FSA_DATE,7,4) AS FSA_YEAR, C_FSA_STATUS, LAST_FSL_DATE, ";
	        $sql .= " C_FSA_ID AS FSA_ID, 'INSP' AS ROLE, ASSIGNED_INSPECTOR, AREA_COMMANDER_ID, C_FSL_COUNT FROM RWH_DIM_FACILITY WHERE ACTIVE_FLAG = 'Y' AND ";
            $sql .= " C_FSA_STATUS IN ('WAITING','ASSESSMENT','REVISE') AND ";
         	$sql .= " ASSIGNED_INSPECTOR = " . $uid . " OR (" . $uid . " IN (SELECT ASSIGNED_TO FROM FPS_FACILITY_ASSIGNMENT WHERE BUILDING_NBR = RWH_DIM_FACILITY.BUILDING_NBR UNION ";
            $sql .= " SELECT ASSIGNED_TO FROM FPS_TEST_ASSIGNMENT WHERE BUILDING_NBR = RWH_DIM_FACILITY.BUILDING_NBR)) ";
            $sql .= " UNION ";
	        $sql .= " SELECT BUILDING_NBR, FACILITY_ID, ";
	        $sql .= " C_FSA_STATUS AS A_STATUS, FACILITY_NAME, ZIPCODE, ADDRESS, ";
	        $sql .= " CITY_NAME, STATE_ABBR, FSL, C_VALIDATION_ERROR, C_BLOP_COUNT, C_NLOP_COUNT, C_OVERDUE_TEST_COUNT, C_TAR_COUNT, C_MISSING_CM, ";
			$sql .= " C_LAST_TAR_DATE, LAST_FSA_DATE, NEXT_FSA_DATE, SUBSTR(NEXT_FSA_DATE,7,4) AS FSA_YEAR, C_FSA_STATUS, LAST_FSL_DATE, ";
	        $sql .= " C_FSA_ID AS FSA_ID, 'AC' AS ROLE, ASSIGNED_INSPECTOR, AREA_COMMANDER_ID, C_FSL_COUNT FROM RWH_DIM_FACILITY WHERE ACTIVE_FLAG = 'Y' AND ";
	        $sql .= " AREA_COMMANDER_ID = " . $uid . " AND C_FSA_STATUS IN ('WAITING','ASSESSMENT','REVISE') ";
            $sql .= " UNION ";
	        $sql .= " SELECT BUILDING_NBR, FACILITY_ID, ";
			$sql .= " C_FSA_STATUS AS A_STATUS, FACILITY_NAME, ZIPCODE, ADDRESS, ";
		    $sql .= " CITY_NAME, STATE_ABBR, FSL, C_VALIDATION_ERROR, C_BLOP_COUNT, C_NLOP_COUNT, C_OVERDUE_TEST_COUNT, C_TAR_COUNT, C_MISSING_CM, ";
			$sql .= " C_LAST_TAR_DATE, LAST_FSA_DATE, NEXT_FSA_DATE, SUBSTR(NEXT_FSA_DATE,7,4) AS FSA_YEAR, C_FSA_STATUS, LAST_FSL_DATE, ";
	        $sql .= " C_FSA_ID AS FSA_ID, 'DC' AS ROLE, ASSIGNED_INSPECTOR, AREA_COMMANDER_ID, C_FSL_COUNT FROM RWH_DIM_FACILITY WHERE ACTIVE_FLAG = 'Y' AND ";
         	$sql .= " DISTRICT_COMMANDER_ID = " . $uid . " AND C_FSA_STATUS IN ('WAITING','ASSESSMENT','REVISE') ";
        	$sql .= " ORDER BY 1";
	} 
		$list=$this->X->sql($sql);
		return $list;
		

	}



	function getFacilitySearch($data) {


	   $sql="SELECT REGION_ID, ROLE, SEGMENT_ID FROM TBL_USER WHERE USER_ID = " . $data['uid'];	
	   $u=$this->X->sql($sql);
       		$user=$u[0];
       		$role=$user['ROLE'];
       		$segment_id=$user['SEGMENT_ID'];
	   	$rid=$user['REGION_ID'];

		$output=array();
		$output['list']=array();
                if (!isset($data['data']['formData'])) {
		$formData=array();
			$formData['FACILITY_NAME']="";
			$formData['ADDRESS']="";
			$formData['CITY']="";
			$formData['STATE']="";
			$formData['ZIPCODE']="";
			$formData['REGION_ID']="";
			$formData['AGENCY_CODE']="";
			$formData['TENANT_GROUP']="";		
		$output['formData']=$formData;
                } else {
                     $output['formData']=$data['data']['formData'];
                }
                $sql="SELECT FACILITY_ID, BUILDING_NBR, FACILITY_NAME, CITY_NAME, STATE_ABBR, ZIPCODE, FSL, ";
                $sql.="C_BSA_COUNT, C_OVERDUE_TEST_COUNT, C_FSA_STATUS FROM RWH_DIM_FACILITY WHERE ";
                $sql.="REGION_ID = " . $rid . " AND FPS_RESPONSIBLE = 'Y' AND BUILDING = 'Y' AND ACTIVE_FLAG = 'Y'";
                $sql.=" ORDER BY BUILDING_NBR";
//echo $sql;
                $j=$this->X->sql($sql);
                $output['list']=$j; 
		return $output;
	} 

	function getAssetSearch($data) {

	   $sql="SELECT REGION_ID, ROLE, SEGMENT_ID FROM TBL_USER WHERE USER_ID = " . $data['uid'];	
	   $u=$this->X->sql($sql);
       		$user=$u[0];
       		$role=$user['ROLE'];
       		$segment_id=$user['SEGMENT_ID'];
	   	$rid=$user['REGION_ID'];

		$output=array();
		$output['list']=array();

		$formData=array();

                if (!isset($data['data']['formData'])) {
		$formData=array();
			$formData['MFG']="";
			$formData['MODEL']="";
			$formData['SERIAL']="";
			$formData['REGION']="";
			$formData['CLASS']="";
			$formData['NAME']="";	
			$output['formData']=$formData;
                } else {
                     $output['formData']=$data['data']['formData'];
                }

                $sql="SELECT ASSET_ID, ASSET_CLASS, ASSET_TYPE, ASSET_NAME, MFG, MODEL, LOCATION_DSC, FACILITY_ID, BUILDING_NBR, TEST_DUE_DATE, STATUS, ";
                $sql.=" 'N' AS BARCODE, COUNT_IDS_CONTROL_PANEL, COUNT_IDS_POWER_SUPPLY, COUNT_IDS_UPS, COUNT_IDS_USER_INTERFACE, COUNT_IDS_DETECTOR, ";
                $sql.=" COUNT_IDS_WARNING_DEVICE, COUNT_IDS_REMOTE_CONSOLE, COUNT_IDS_DURESS_BUTTON, COUNT_IDS_WINDOW_SENSOR, ";
                $sql.=" COUNT_IDS_GLASS_BREAK_SENSOR, COUNT_IDS_DOOR_SENSOR, ROUND(TRUNC(SYSDATE)-TEST_DUE_DATE) AS DAYS, ";
                $sql.=" COUNT_PTZ, COUNT_FIXED, COUNT_IP, COUNT_MONITORS, COUNT_DVR, COUNT_CONTROLLERS FROM ";
                $sql.=" FPS_BSA WHERE IS_SYSTEM IN ('Y','N') AND NVL(COMPONENT_ID,0) = 0 AND FACILITY_ID IN (SELECT FACILITY_ID FROM RWH_DIM_FACILITY WHERE REGION_ID = " . $rid . ") AND STATUS NOT IN ('Excessed') ";
                $sql.=" ORDER BY BUILDING_NBR ";
//echo $sql;
//die();
                $l=$this->X->sql($sql);
                $list=array();
                foreach($l as $m) {
                     $sql="SELECT FACILITY_NAME, ADDRESS, CITY_NAME, STATE_ABBR, ZIPCODE, "; 
                     $sql.=" C_BSA_COUNT, C_OVERDUE_TEST_COUNT, C_FSA_YEAR, C_FPS_OVERDUE_TEST_COUNT, ";
                     $sql.=" C_FPS_BSA_COUNT, C_GSA_OVERDUE_TEST_COUNT, C_GSA_BSA_COUNT, C_TENANT_OVERDUE_TEST_COUNT, ";
                     $sql.=" C_TENANT_BSA_COUNT, C_UNK_OVERDUE_TEST_COUNT, C_UNK_BSA_COUNT, C_OWNED_BSA_COUNT, ";          
                     $sql.=" C_LEASED_BSA_COUNT, C_OWNED_OVERDUE_COUNT, C_LEASED_OVERDUE_COUNT,C_LESSOR_OVERDUE_TEST_COUNT, C_LESSOR_BSA_COUNT ";
                     $sql.=" FROM TBL_DIM_FACILITY WHERE FACILITY_ID = " . $m['FACILITY_ID'];
                     $y=$this->X->sql($sql);
                     if (isset($y[0])) {
                            foreach($y[0] as $name => $value) {
                                $m[$name]=$value;
                            }
                     }
                     array_push($list,$m);
                }
                $output['list']=$list;
		return $output;
	} 	

	function getOverdueTests($data) {
		$output=array();
		$output['list']=array();
		$formData=array();
		$formData['MFG']="";
		$formData['MODEL']="";
		$formData['SERIAL']="";
		$formData['REGION']="";
		$formData['CLASS']="";
		$formData['NAME']="";			
		$output['formData']=$formData;
		return $output;
	} 
	
	function getActiveAssessments($data) {
		$output=array();
		$output['list']=array();
		$formData=array();
		$formData['MFG']="";
		$formData['MODEL']="";
		$formData['SERIAL']="";
		$formData['REGION_ID']="";	
		$output['formData']=$formData;
		return $output;
	} 	

	function postBSATest($data) {

	   $id=$data['data']['ID'];
	   $asset_id=$data['data']['ASSET_ID'];
           $title=$data['data']['TITLE'];
           $results=$data['data']['RESULTS'];
	   $date=$data['data']['TEST_DATE'];
           $reason=$data['data']['REASON'];
           $uid=$data['uid'];
 
           $post=array();
           $post['table_name']="FPS_BSA_TEST";
           $post['sequence_name']="MEANINGLESS_KEY_SEQ";
           $post['primary_key']="ID";
           $post['action']="insert";
           $post['ASSET_ID']=$asset_id;
           $post['TITLE']=$title;
           if ($id!="") $post['ID']=$id;
           $mon=substr($date,5,2);
           $year=substr($date,2,2);
           $day=substr($date,8,2);
           if ($mon=='01') { $m="JAN"; }
           if ($mon=='02') { $m="FEB"; }
           if ($mon=='03') { $m="MAR"; }
           if ($mon=='04') { $m="APR"; }
           if ($mon=='05') { $m="MAY"; }
           if ($mon=='06') { $m="JUN"; }
           if ($mon=='07') { $m="JUL"; }
           if ($mon=='08') { $m="AUG"; }
           if ($mon=='09') { $m="SEP"; }
           if ($mon=='10') { $m="OCT"; }
           if ($mon=='11') { $m="NOV"; }
           if ($mon=='12') { $m="DEC"; }
           $d=$day . '-' . $m . '-' . $year;
           $post['TEST_DATE']=$d;
           $post['REASON']=$reason;
           $post['USER_ID']=$uid;
           $sql=" select first_name||' '||last_name AS C from tbl_user where user_id = " . $uid;
           $g=$this->X->sql($sql);
           $post['FULL_NAME']=$g[0]['C'];
           $this->X->post($post);
           $post=array();
	   $sql="select BUILDING_NBR, FACILITY_ID FROM FPS_BSA WHERE ASSET_ID = " . $asset_id; 
           $b=$this->X->sql($sql);
           $building_nbr=$b[0]['BUILDING_NBR'];
           $facility_id=$b[0]['FACILITY_ID'];

           $sql="UPDATE FPS_BSA SET LAST_DATE_DATE = '" . $date . "' ";
           $sql.=" WHERE ASSET_ID = " . $asset_id;
           $this->X->execute($sql);

           $sql="UPDATE FPS_BSA SET TEST_DUE_DATE = LAST_TEST_DATE + 365 ";
           $sql.=" WHERE ASSET_ID = " . $asset_id;
           $this->X->execute($sql);
           
           $sql="BEGIN FPS_SURVEY_VALIDATION.UPDATE_ONE_STATUS ('" . $building_nbr . "'); END;";
	   $this->X->execute($sql);

           $data=array();
           $data['id']=$facility_id;
           $data['uid']=$uid;    
           $data['data']=array();    
           $data['data']['id']=$facility_id;
           $data['data']['uid']=$uid;    
	   
	   return $this->getFacilityDashboard($data);
	} 	

	function postEditAsset($data) {

           $uid=$data['uid'];
 
           $post=array();
           $post['table_name']="FPS_BSA";
           $post['sequence_name']="MEANINGLESS_KEY_SEQ";
           $post['primary_key']="ASSET_ID";
           $post['action']="insert";
           $post['ASSET_ID']=$data['data']['ASSET_ID'];
           foreach($data['data'] as $name => $value) {
               if($name!="components"&&$name!="docs"&&$name!="history"&&$name!="doc_count"&&$name!="history_count") $post[$name]=$value;
           }

           $this->X->post($post);

	   $sql="select BUILDING_NBR, FACILITY_ID FROM FPS_BSA WHERE ASSET_ID = " . $data['data']['ASSET_ID'];
           $b=$this->X->sql($sql);
           $building_nbr=$b[0]['BUILDING_NBR'];
           $facility_id=$b[0]['FACILITY_ID'];
           
           $data=array();
           $data['id']=$facility_id;
           $data['uid']=$uid;    
           $data['data']=array();    
           $data['data']['id']=$facility_id;
           $data['data']['uid']=$uid;    
	   
	   return $this->getFacilityDashboard($data);
	} 	
        
        function deleteAsset($data) {
     
           $uid=$data['uid'];
	   $sql="select BUILDING_NBR, FACILITY_ID FROM FPS_BSA WHERE ASSET_ID = " . $data['data']['ASSET_ID'];
           $b=$this->X->sql($sql);
           $building_nbr=$b[0]['BUILDING_NBR'];
           $facility_id=$b[0]['FACILITY_ID'];
           
           $sql="DELETE FROM FPS_BSA WHERE ASSET_ID = " . $data['data']['ASSET_ID'];
           $this->X->execute($sql);

           $data=array();
           $data['id']=$facility_id;
           $data['uid']=$uid;    
           $data['data']=array();    
           $data['data']['id']=$facility_id;
           $data['data']['uid']=$uid;    
	   
	   return $this->getFacilityDashboard($data);

        }
        function postAddComponents($data) {
 

         	   $sql="select BUILDING_NBR, FACILITY_ID FROM FPS_BSA WHERE ASSET_ID = " . $data['data']['ASSET_ID'];
                 $b=$this->X->sql($sql);
                 $building_nbr=$b[0]['BUILDING_NBR'];
                 $facility_id=$b[0]['FACILITY_ID'];

		$qty=$data['data']['QTY'];
                $asset_id=$data['data']['ASSET_ID'];
                $asset_class=$data['data']['ASSET_CLASS'];
                $asset_type=$data['data']['ASSET_TYPE'];
                $asset_name=$data['data']['ASSET_NAME'];
                $mfg=$data['data']['MFG'];
                $model=$data['data']['MODEL'];
                $serial=$data['data']['SERIAL'];
                $uid=$data['uid'];
                
           	if ($qty=="1"||$qty=="") {
	           $post=array();
        	   $post['table_name']="FPS_BSA";
           	   $post['sequence_name']="MEANINGLESS_KEY_SEQ";
           	   $post['primary_key']="ASSET_ID";
           	   $post['action']="insert";
		   $post['COMPONENT_ID']=$asset_id;
                   $post['FACILITY_ID']=$facility_id;
                   $post['BUILDING_NBR']=$building_nbr;
                   $post['ASSET_TYPE']=$asset_type;
                   $post['ASSET_CLASS']=$asset_class;
                   $post['ASSET_NAME']=$asset_name;
                   $post['MFG']=$mfg;
                   $post['MODEL']=$model;
                   $post['SERIAL']=$serial;
                   $post['ADD_USER_ID']=$uid;
                   $post['VERIFIED']='Y';
                   $post['VERIFIED_BY']=$uid;
                   $post['STATUS']="In-Service";
                   $post['IS_SYSTEM']="C";
                   $this->X->post($post);
                 } else {
                   $c=intval($qty);
                   if ($serial!="") {
                       $ss=explode(",",$serial);
                   }
                   for($i=0;$i<$c;$i++) {
		           $post=array();
        		   $post['table_name']="FPS_BSA";
           		   $post['sequence_name']="MEANINGLESS_KEY_SEQ";
           		   $post['primary_key']="ASSET_ID";
           		   $post['action']="insert";
			   $post['COMPONENT_ID']=$asset_id;
               		    $post['FACILITY_ID']=$facility_id;
                   		$post['BUILDING_NBR']=$building_nbr;
                   	$post['ASSET_TYPE']=$asset_type;
                   	$post['ASSET_CLASS']=$asset_class;
                        $j=$i+1;
			$post['ASSET_NAME']=$asset_name . " #" . $j;
                   	$post['MFG']=$mfg;
                   	$post['MODEL']=$model;
                        if (isset($ss[$i])) $post['SERIAL']=$ss[$i];
                   	$post['ADD_USER_ID']=$uid;
                   	$post['VERIFIED']='Y';
                   	$post['VERIFIED_BY']=$uid;
                   	$post['STATUS']="In-Service";
                   	$post['IS_SYSTEM']="C";
                   	$this->X->post($post);
                   }
                 }
           
           $data=array();
           $data['id']=$facility_id;
           $data['uid']=$uid;    
           $data['data']=array();    
           $data['data']['id']=$facility_id;
           $data['data']['uid']=$uid;    
	   
	   return $this->getFacilityDashboard($data);

        }

} //-- End of Class