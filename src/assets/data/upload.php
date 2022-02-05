<?php
    //--
    //-- DOCUMENT UPLOAD FROM ANGULAR 12
    //--

    header("Content-Type: text/html; charset=utf-8");
    ini_set('display_errors','On');
    header('Content-Type: application/json');
    ini_set('memory_limit', '-1');

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');

    //-- Get Oracle and ASSETS class.
    require_once('../../lib/class.OracleDB.php');
    require_once('class.assets.php');
    $fsa=0;
    $X=new OracleDB();
    $A=new ASSETS();
    $db=$X->connectACN();

    $fileName = basename($_FILES["file"]["name"]);
    $file_blob = file_get_contents($_FILES['file']['tmp_name']);

    //--
    //-- Need Facility ID
    //--
    $sql="SELECT FACILITY_ID FROM FPS_BSA WHERE ASSET_ID = " . $_POST['assetid'];
    $f=$X->sql($sql);
    $facility_id=$f[0]['FACILITY_ID'];

    //--
    //-- Get Primary Key
    //--
    $sql = "select MEANINGLESS_KEY_SEQ.NEXTVAL as C from DUAL";
    $result=$X->sql($sql);
    $newKey = $result[0]['C'];

    //--
    //-- Copied from Legacy Upload Code
    //--

    $docInfoStmt = $db->prepare("insert into fps_fsa_documents (FSA_DOC_ID, FSA_ID, DOC_TYPE_ID, DOC_TITLE, DOC_FILE_NAME, FACILITY_ID, UPLOADED_BY, STD_DOC_ID, UPDLOAD_DT_TM, ASSET_ID) " .
    "VALUES (?,?,?,?,?,?,?,?,sysdate,?)");
    $docInfoStmt->bindParam(1,  $newKey);
    $docInfoStmt->bindParam(2,  $fsa);
    $docInfoStmt->bindParam(3,  $_POST['doctype']);
    $docInfoStmt->bindParam(4,  $_POST['title']);
    $docInfoStmt->bindParam(5,  $fileName );
    $docInfoStmt->bindParam(6,  $facility_id);
    $docInfoStmt->bindParam(7,  $_COOKIE['uid']);
    $docInfoStmt->bindParam(8,  $_POST['stddoc']);
    $docInfoStmt->bindParam(9,  $_POST['assetid']);
    $docInfoStmt->execute(OCI_DEFAULT);

    $sql = "INSERT INTO FPS_FSA_DOC_BLOB(fsa_doc_id, file_blob) VALUES($newKey, empty_blob()) RETURNING file_blob INTO :file_blob";
    $docStmt = $db->prepare($sql);
    $blob = $db->getNewDescriptor(OCI_D_LOB);
    $docStmt->bindValue(":file_blob", $blob, -1, OCI_B_BLOB);
    $docStmt->execute(OCI_DEFAULT);
    if(!$blob->save($file_blob)){
        $docStmt->rollback();
    } else {
        $docStmt->commit();
    }
    $blob->free();

    //--
    //-- Make parameter to reload Dashboard Data
    //--
    $data=array();
    $data['uid']=$_COOKIE['uid'];
    $data['id']=$facility_id;
    $data2=array();
    $data2['id']=$facility_id;
    $data['data']=$data2;

    //--
    //-- Get New Version of Dashboard Data (including new document)
    //--

    
    $output=$A->getFacilityDashboard($data);
$o=json_encode($output);
$o=stripcslashes($o);
$o=str_replace('null','""',$o);
echo $o;

?>