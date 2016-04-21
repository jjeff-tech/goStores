<?php
session_start();
if($_SESSION["checkLevel"] == "") {
$_SESSION["checkLevel"] = 0;
}
include_once("./languages/en/index.php");
require_once("../includes/decode.php");
if(!isValid(1)) {
echo("<script>window.location.href='../invalidkey.php'</script>");
exit();
} 
//Section - A - include the settings  file here and assign the database connection 
//and open a live connecton here to the database
include_once("../config/settings.php");
$var_host = $glb_dbhost;
$var_user = $glb_dbuser;
$var_password = $glb_dbpass;
$var_database = $glb_dbname;
$flag = false;
$num = 0;
if ($conn = mysql_connect($var_host,$var_user,$var_password)) {
	if (mysql_select_db($var_database,$conn)) {
		$flag = true;
	}
	else {
		echo("Cannot select the db from the given connection.  Pleasecheck your configuration settings.");
		exit;
	}
}	
else {
	echo("Cannot select the db from the given connection.  Pleasecheck your configuration settings.");
	exit;
}

//End Section - A
//Section - B - set the magic quotes runtime to Off
//if php get_magic_quotes_gpc is on then strip the slashes from GET,POST,COOKIE superglobals.
function stripslashes_deep($value){
	$value = is_array($value) ?
	array_map('stripslashes_deep', $value) :
	stripslashes($value);
	return $value;
}
///*set_magic_quotes_runtime(0);*/
// Check if magic_quotes_runtime is active
if(get_magic_quotes_runtime())
{
    // Deactivate
    /*set_magic_quotes_runtime(false);*/
}
if (get_magic_quotes_gpc()) {
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
//End Section - B

//Section - C - Directory specific functions 
//htmlpath(), getDirList(), etc. functions 
function htmlpath($relative_path) {
   $realpath=realpath($relative_path);
   $htmlpath=str_replace($_SERVER['DOCUMENT_ROOT'],'',$realpath);
   return $htmlpath;
}
function permission($per){
			  $retarray=array();
			  if($per=="1"){
			       $retarray[0]=1;
			       $retarray[1]=0;
				   $retarray[2]=0;
			  }else if($per=="2"){
			       $retarray[0]=0;
			       $retarray[1]=1;
				   $retarray[2]=0;
			  }else if($per=="3"){
			       $retarray[0]=1;
			       $retarray[1]=1;
				   $retarray[2]=0;
			  }else if($per=="4"){
			       $retarray[0]=0;
			       $retarray[1]=0;
				   $retarray[2]=1;
			  }else if($per=="5"){
			       $retarray[0]=1;
			       $retarray[1]=0;
				   $retarray[2]=1;
			  }else if($per=="6"){
			       $retarray[0]=0;
			       $retarray[1]=1;
				   $retarray[2]=1;
			  }else if($per=="7"){
			       $retarray[0]=1;
			       $retarray[1]=1;
				   $retarray[2]=1;
			  }else{
			       $retarray[0]=0;
			       $retarray[1]=0;
				   $retarray[2]=0;
			  }
			   return  $retarray; 
}
function getDirList($base,$fl)
{
   
 	if ($fl == 0) { 
 	  if(@is_dir($base)){
               $subbase = $base . '/';
			   $per=substr(sprintf('%o', fileperms($subbase)), -3);
			  
			   $uper=substr($per,0,1);
			   $gper=substr($per,1,1);
			   $wper=substr($per,2,1);
			   $wr_per = TEXT_WRITE_PERMISSION_AVAILABLE;
			   $permis=permission($wper);
			   if($permis[1]=="0" || $permis[0]=="0" || $permis[2]=="0")
				       $wr_per=TEXT_ENABLE_WRITE_PERMISSION;	
               return $wr_per;
	   }
	}
	elseif ($fl == 1) {
               $subbase = $base;
			   $per=substr(sprintf('%o', fileperms($subbase)), -3);
			  
			   $uper=substr($per,0,1);
			   $gper=substr($per,1,1);
			   $wper=substr($per,2,1);
			   $wr_per = TEXT_WRITE_PERMISSION_AVAILABLE;
			   $permis=permission($wper);
			   if($permis[1]=="0" || $permis[2]=="0")
				       $wr_per=TEXT_ENABLE_WRITE_FILE;	
               return $wr_per;
	}	   
}
//End Section - C


//Section - D - Functions to fix the company, department, staff, user, lookup email ids 
function fixCompany() {
	global $conn;
	$arr_company = array();
	$sql = "Select nCompId,vCompName,vCompMail from sptbl_companies where nCompId IN('" . implode("','",$_POST["cmbCompanyList"]) . "')";
	$rs = mysql_query($sql) or die("Cannot access information from company master");
	while($row = mysql_fetch_array($rs)) {
		$arr_company[$row["nCompId"]][0] = $row["vCompName"];
		$arr_company[$row["nCompId"]][1] = $row["vCompMail"];
	}
	if($fp = @fopen("logfile.txt","a+")) {
	}
	else {
		echo("Cannot log details to logfile.txt.  Please give appropriate permissions to logfile.txt.");
		exit;
	}
	foreach($_POST["cmbCompanyList"] as $key=>$value) {
			$var_newmail = uniqid("c") . "@yoursite.com";
			while(!isUniqueEmail($var_newmail,$value,"c")) {
				$var_newmail = uniqid("c") . "@yoursite.com";
			}
			$sql = "Update sptbl_companies set vCompMail='" . addslashes($var_newmail) . "' Where 
					nCompId='" . addslashes($value) .  "'";
			mysql_query($sql,$conn) or die("Cannot update table sptbl_companies.  Please contact administrator for details.");	
			fwrite($fp,"sptbl_companies \r\n" . str_repeat("*",20) . "\r\nCompany id:" . $value . " \r\nName : " . $arr_company[$value][0] . " \r\nPrevious Value : " . $arr_company[$value][1] . " \r\nModified To : " . $var_newmail . "\r\n" . str_repeat("=",(strlen($var_newmail)+20)) . "\r\n");
	}
	fclose($fp);
}

function fixDepartment() {
	global $conn;
	$arr_depts = array();
	$sql = "Select nDeptId,vDeptDesc,vDeptMail from sptbl_depts where nDeptId IN('" . implode("','",$_POST["cmbDepartmentList"]) . "')";
	$rs = mysql_query($sql) or die("Cannot access information from department master");
	while($row = mysql_fetch_array($rs)) {
		$arr_depts[$row["nDeptId"]][0] = $row["vDeptDesc"];
		$arr_depts[$row["nDeptId"]][1] = $row["vDeptMail"];
	}
	if($fp = @fopen("logfile.txt","a+")) {
	}
	else {
		echo("Cannot log details to logfile.txt.  Please give appropriate permissions to logfile.txt.");
		exit;
	}
	foreach($_POST["cmbDepartmentList"] as $key=>$value) {
			$var_newmail = uniqid("d") . "@yoursite.com";
			while(!isUniqueEmail($var_newmail,$value,"d")) {
				$var_newmail = uniqid("d") . "@yoursite.com";
			}
			$sql = "Update sptbl_depts set vDeptMail='" . addslashes($var_newmail) . "' Where 
					nDeptId='" . addslashes($value) .  "'";
			mysql_query($sql,$conn) or die("Cannot update table sptbl_depts.  Please contact administrator for details.");	
			//fwrite($fp,"sptbl_depts ==> " . $value . " ==> " . $arr_depts[$value][0] . " ==> " . $arr_depts[$value][1] . " ==> " . $var_newmail . "\r\n");
			fwrite($fp,"sptbl_depts \r\n" . str_repeat("*",15) . "\r\nDepartment id:" . $value . " \r\nName : " . $arr_depts[$value][0] . " \r\nPrevious Value : " . $arr_depts[$value][1] . " \r\nModified To : " . $var_newmail . "\r\n" . str_repeat("=",(strlen($var_newmail)+2)) . "\r\n");
	}
	fclose($fp);
}

function fixStaff() {
	global $conn;
	$arr_staff = array();
	$sql = "Select nStaffId,vLogin,vMail from sptbl_staffs where nStaffId IN('" . implode("','",$_POST["cmbStaffList"]) . "')";
	$rs = mysql_query($sql) or die("Cannot access information from staff master");
	while($row = mysql_fetch_array($rs)) {
		$arr_staff[$row["nStaffId"]][0] = $row["vLogin"];
		$arr_staff[$row["nStaffId"]][1] = $row["vMail"];
	}
	if($fp = @fopen("logfile.txt","a+")) {
	}
	else {
		echo("Cannot log details to logfile.txt.  Please give appropriate permissions to logfile.txt.");
		exit;
	}
	foreach($_POST["cmbStaffList"] as $key=>$value) {
			$var_newmail = uniqid("s") . "@yoursite.com";
			while(!isUniqueEmail($var_newmail,$value,"s")) {
				$var_newmail = uniqid("s") . "@yoursite.com";
			}
			$sql = "Update sptbl_staffs set vMail='" . addslashes($var_newmail) . "' Where 
					nStaffId='" . addslashes($value) .  "'";
			mysql_query($sql,$conn) or die("Cannot update table sptbl_staffs.  Please contact administrator for details.");	
			//fwrite($fp,"sptbl_staffs ==> " . $value . " ==> " . $arr_staff[$value][0] . " ==> " . $arr_staff[$value][1] . " ==> " . $var_newmail . "\r\n");
			fwrite($fp,"sptbl_staffs \r\n" . str_repeat("*",15) . "\r\nStaff id:" . $value . " \r\nName : " . $arr_staff[$value][0] . " \r\nPrevious Value : " . $arr_staff[$value][1] . " \r\nModified To : " . $var_newmail . "\r\n" . str_repeat("=",(strlen($var_newmail)+2)) . "\r\n");
	}
	fclose($fp);
}

function fixUser() {
	global $conn;
	$arr_user = array();
	$sql = "Select nUserId,nCompId,vLogin,vEmail from sptbl_users where nUserId IN('" . implode("','",$_POST["cmbUserList"]) . "')";
	$rs = mysql_query($sql) or die("Cannot access information from user master");
	while($row = mysql_fetch_array($rs)) {
		$arr_user[$row["nUserId"]][0] = $row["vLogin"];
		$arr_user[$row["nUserId"]][1] = $row["vEmail"];
		$arr_user[$row["nUserId"]][2] = $row["nCompId"];
	}
	if($fp = @fopen("logfile.txt","a+")) {
	}
	else {
		echo("Cannot log details to logfile.txt.  Please give appropriate permissions to logfile.txt.");
		exit;
	}
	foreach($_POST["cmbUserList"] as $key=>$value) {
			$var_newmail = uniqid("u") . "@yoursite.com";
			while(!isUniqueEmail($var_newmail,$value,"u",$arr_user[$value][2])) {
				$var_newmail = uniqid("u") . "@yoursite.com";
			}
			$sql = "Update sptbl_users set vEmail='" . addslashes($var_newmail) . "' Where 
					nUserId='" . addslashes($value) .  "'";
			mysql_query($sql,$conn) or die("Cannot update table sptbl_users.  Please contact administrator for details.");	
			//fwrite($fp,"sptbl_users ==> " . $value . " ==> " . $arr_user[$value][0] . " ==> " . $arr_user[$value][1] . " ==> " . $var_newmail . "\r\n");
			fwrite($fp,"sptbl_users \r\n" . str_repeat("*",15) . "\r\nUser id:" . $value . " \r\nName : " . $arr_user[$value][0] . " \r\nPrevious Value : " . $arr_user[$value][1] . " \r\nModified To : " . $var_newmail . "\r\n" . str_repeat("=",(strlen($var_newmail)+2)) . "\r\n");
	}
	fclose($fp);
}

function fixLookup() {
	global $conn;
	$arr_lookup = array();
	$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup where vLookUpName IN('" . implode("','",$_POST["cmbLookupList"]) . "')";
	$rs = mysql_query($sql) or die("Cannot access information from company master");
	while($row = mysql_fetch_array($rs)) {
		$arr_lookup[$row["vLookUpName"]] = $row["vLookUpValue"];
	}
	if($fp = @fopen("logfile.txt","a+")) {
	}
	else {
		echo("Cannot log details to logfile.txt.  Please give appropriate permissions to logfile.txt.");
		exit;
	}
	foreach($_POST["cmbLookupList"] as $key=>$value) {
			$var_newmail = uniqid("l") . "@yoursite.com";
			while(!isUniqueEmail($var_newmail,0,"l")) {
				$var_newmail = uniqid("l") . "@yoursite.com";
			}
			$sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($var_newmail) . "' Where 
					vLookUpName='" . addslashes($value) .  "'";
			mysql_query($sql,$conn) or die("Cannot update table sptbl_lookup.  Please contact administrator for details.");	
			fwrite($fp,"sptbl_lookup \r\n" . str_repeat("*",20) . "\r\nLookup parameter:" . $value . " \r\nPrevious Value : " . $arr_lookup[$value] . " \r\nModified To : " . $var_newmail . "\r\n" . str_repeat("=",(strlen($var_newmail)+20)) . "\r\n");
	}
	fclose($fp);
}
//End Section - D

//Section - E - Function used by check company, check department, check staff, check user 
// and by fix company, fix staff, fix user, fix department. 
function isUniqueEmail($email,$var_id=0,$var_type="c",$var_user_compid=0) {
	global $conn;
	switch($var_type) {
		case "c":
			$var_str_comp = " AND c.nCompId != '$var_id' ";
			break;
		case "d":
			$var_str_dept = " AND dt.nDeptId != '$var_id' ";
			break;
		case "s":
			$var_str_staff = " AND s.nStaffId != '$var_id' ";
			break;
		case "u":
			$var_str_user = " AND u.nUserId != '$var_id' ";
			$var_str_user .= ($var_user_compid > 0)?" AND u.nCompId = '{$var_user_compid}'":"";
			break;
	}
	$sql = "Select * from dummy d 
		Left join sptbl_users u on (d.num=0 AND u.vEmail='" . addslashes($email) . "'{$var_str_user}) 
		Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . addslashes($email) . "'{$var_str_staff})
		Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . addslashes($email) . "'{$var_str_dept})
		Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . addslashes($email) . "'{$var_str_comp})
		where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
	if(mysql_num_rows(mysql_query($sql,$conn)) > 0) {
		return false;
	}
	else {
		$sql = "Select nLookUpId from sptbl_lookup where vLookUpValue='" . addslashes($email) . "' 
		AND vLookUpName IN('MailAdmin','MailTechnical','MailEscalation','MailFromMail','MailReplyMail')";
		if(mysql_num_rows(mysql_query($sql,$conn)) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
}
//End Section - E

//Section - F - Functions for checking company, staff, department, user, lookup for email duplication  
//This function returns TRUE if company email is unique in the system
//FALSE if company email is non-unique
//If it returns FALSE it will have a LIST box that contains the company id, email  
function checkCompanyDetails(&$returnList,&$command,$num) {
	global $conn;
	$flag = true;
	$returnList = "<SELECT name='cmbCompanyList[]' id='cmbCompanyList' MULTIPLE Size=5 style=\"width:300px;\" class=\"button\"> ";
	$sql = "Select nCompId,vCompName,vCompMail,vDelStatus from sptbl_companies";
	$rs_company = mysql_query($sql,$conn) or die("Cannot access sptbl_companies"); 
	if(mysql_num_rows($rs_company) > 0) {
		while($row = mysql_fetch_array($rs_company)) {
			if(!isUniqueEmail($row["vCompMail"],$row["nCompId"],"c")) {
				if($row["vDelStatus"] == "0") {
					$flag = false;
					$returnList .= "<OPTION VALUE=\"" . $row["nCompId"] . "\">" . htmlentities($row["vCompName"] . " - [" . $row["vCompMail"] . "]") . "</OPTION>";
				}
				else {
					$var_newmail = uniqid("c") . "@yoursite.com";
					while(!isUniqueEmail($var_newmail,$row["nCompId"],"c")) {
						$var_newmail = uniqid("c") . "@yoursite.com";
					}
					$sql = "Update sptbl_companies set vCompMail='" . addslashes($var_newmail) . "' Where 
							nCompId='" . $row["nCompId"] .  "'";
					mysql_query($sql,$conn) or die("Cannot update table sptbl_companies.  Please contact administrator for details.");		
				}	
			}
		}
	}
	else {
		$returnList = "";
		$command = "";
		return false;	
	}
	if($flag == false) {
		$returnList .= "</SELECT>";
		$command = "<input type=\"button\" name=\"btCompany\" id=\"btCompany\" class=\"button\" onClick=\"javascript:clickFixCompany();\" value=\"Fix company\"" . (($num == 11)?"":"disabled") . " >";
		return false;
	}
	else {
		$returnList = "Passed company table check!";
		$command = "";
		return true;	
	}
}


//This function returns TRUE if company email is unique in the system
//FALSE if company email is non-unique
//If it returns FALSE it will have a LIST box that contains the company id, email  
function checkDepartmentDetails(&$returnList,&$command,$num) {
	global $conn;
	$flag = true;
	$returnList = "<SELECT name='cmbDepartmentList[]' id='cmbDepartmentList' MULTIPLE Size=5 style=\"width:300px;\" class=\"button\"> ";
	$sql = "Select nDeptId,vDeptCode,vDeptMail from sptbl_depts";
	$rs_company = mysql_query($sql,$conn) or die("Cannot access sptbl_depts"); 
	if(mysql_num_rows($rs_company) > 0) {
		while($row = mysql_fetch_array($rs_company)) {
			if(!isUniqueEmail($row["vDeptMail"],$row["nDeptId"],"d")) {
					$flag = false;
					$returnList .= "<OPTION VALUE=\"" . $row["nDeptId"] . "\">" . htmlentities($row["vDeptCode"] . " - [" . $row["vDeptMail"] . "]") . "</OPTION>";
			}
		}
	}
	else {
		$returnList = "";
		$command = "";
		return false;	
	}
	if($flag == false) {
		$returnList .= "</SELECT>";
		$command = "<input type=\"button\" name=\"btDepartment\" id=\"btDepartment\" class=\"button\" onClick=\"javascript:clickFixDepartment();\" value=\"Fix Department\" style=\"width:100px;\"" . (($num == 11)?"":"disabled") . ">";
		return false;
	}
	else {
		$returnList = "Passed department table check!";
		$command = "";
		return true;	
	}
}

//This function returns TRUE if company email is unique in the system
//FALSE if company email is non-unique
//If it returns FALSE it will have a LIST box that contains the company id, email  
function checkStaffDetails(&$returnList,&$command,$num) {
	global $conn;
	$flag = true;
	$returnList = "<SELECT name='cmbStaffList[]' id='cmbStaffList' MULTIPLE Size=5 style=\"width:300px;\" class=\"button\"> ";
	$sql = "Select nStaffId,vLogin,vMail,vDelStatus from sptbl_staffs";
	$rs_company = mysql_query($sql,$conn) or die("Cannot access sptbl_staffs"); 
	if(mysql_num_rows($rs_company) > 0) {
		while($row = mysql_fetch_array($rs_company)) {
			if(!isUniqueEmail($row["vMail"],$row["nStaffId"],"s")) {
				if($row["vDelStatus"] == "0") {
					$flag = false;
					$returnList .= "<OPTION VALUE=\"" . $row["nStaffId"] . "\">" . htmlentities($row["vLogin"] . " - [" . $row["vMail"] . "]") . "</OPTION>";
				}
				else {
					$var_newmail = uniqid("s") . "@yoursite.com";
					while(!isUniqueEmail($var_newmail,$row["nStaffId"],"s")) {
						$var_newmail = uniqid("s") . "@yoursite.com";
					}
					$sql = "Update sptbl_staffs set vMail='" . addslashes($var_newmail) . "' Where 
							nStaffId='" . $row["nStaffId"] .  "'";
					mysql_query($sql,$conn) or die("Cannot update table sptbl_staffs.  Please contact administrator for details.");		
				}	
			}
		}
	}
	else {
		$returnList = "";
		$command = "";
		return false;	
	}
	if($flag == false) {
		$returnList .= "</SELECT>";
		$command = "<input type=\"button\" name=\"btStaff\" id=\"btStaff\" class=\"button\" onClick=\"javascript:clickFixStaff();\" value=\"Fix Staff\" style=\"width:100px;\"" . (($num == 11)?"":"disabled") . ">";
		return false;
	}
	else {
		$returnList = "Passed staff table check!";
		$command = "";
		return true;	
	}
}

//This function returns TRUE if company email is unique in the system
//FALSE if company email is non-unique
//If it returns FALSE it will have a LIST box that contains the company id, email  
function checkUserDetails(&$returnList,&$command,$num) {
	global $conn;
	$flag = true;
	$returnList = "<SELECT name='cmbUserList[]' id='cmbUserList' MULTIPLE Size=5 style=\"width:300px;\" class=\"button\"> ";
	$sql = "Select nUserId,nCompId,vLogin,vEmail,vDelStatus from sptbl_users";
	$rs_company = mysql_query($sql,$conn) or die("Cannot access sptbl_users"); 
	if(mysql_num_rows($rs_company) > 0) {
		while($row = mysql_fetch_array($rs_company)) {
			if(!isUniqueEmail($row["vEmail"],$row["nUserId"],"u",$row["nCompId"])) {
				if($row["vDelStatus"] == "0") {
					$flag = false;
					$returnList .= "<OPTION VALUE=\"" . $row["nUserId"] . "\">" . htmlentities($row["vLogin"] . " - [" . $row["vEmail"] . "]") . "</OPTION>";
				}
				else {
					$var_newmail = uniqid("u") . "@yoursite.com";
					while(!isUniqueEmail($var_newmail,$row["nUserId"],"u",$row["nCompId"])) {
						$var_newmail = uniqid("u") . "@yoursite.com";
					}
					$sql = "Update sptbl_users set vEmail='" . addslashes($var_newmail) . "' Where 
							nUserId='" . $row["nUserId"] .  "'";
					mysql_query($sql,$conn) or die("Cannot update table sptbl_users.  Please contact administrator for details.");		
				}	
			}
		}
	}
	else {
		$returnList = "Passed user table check!";
		$command = "";
		return true;	
	}
	if($flag == false) {
		$returnList .= "</SELECT>";
		$command = "<input type=\"button\" name=\"btUser\" id=\"btUser\" class=\"button\" onClick=\"javascript:clickFixUser();\" value=\"Fix User\"" . (($num == 11)?"":"disabled") . ">";
		return false;
	}
	else {
		$returnList = "Passed user table check!";
		$command = "";
		return true;	
	}
}

//This function returns TRUE if company email is unique in the system
//FALSE if company email is non-unique
//If it returns FALSE it will have a LIST box that contains the company id, email  
function checkLookupDetails(&$returnList,&$command,$num) {
	global $conn;
	$flag = true;
	$arr_duplicate = array();
	$arr_lookup=array();
	$returnList = "<SELECT name='cmbLookupList' MULTIPLE Size=5 style=\"width:300px;\" class=\"button\"> ";
	$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup where vLookUpName IN('MailAdmin','MailTechnical',
	'MailEscalation','MailFromMail','MailReplyMail')";
	$rs_company = mysql_query($sql,$conn) or die("Cannot access sptbl_lookup"); 
	if(mysql_num_rows($rs_company) > 0) {
		while($row = mysql_fetch_array($rs_company)) {
			$arr_lookup[$row["vLookUpName"]] = $row["vLookUpValue"];
		}
		foreach($arr_lookup as $key=>$value) {
			$sub_flag = true;
			foreach($arr_lookup as $key_sub=>$value_sub) {
				if($key != $key_sub && strcasecmp($value,$value_sub) == 0) {
					if($key != "MailFromMail" && $key != "MailReplyMail") {
						$flag = false;
						$sub_flag = false;
						$arr_duplicate[$key] = $value; 
					}
					elseif(($key == "MailFromMail" && $key_sub != "MailReplyMail") || ($key == "MailReplyMail" && $key_sub != "MailFromMail")) {
						$flag = false;
						$sub_flag = false;
						$arr_duplicate[$key] = $value; 
					}
				}
			} // end foreach - II
			if($sub_flag == true) {
				$sql = "Select * from dummy d 
					Left join sptbl_users u on (d.num=0 AND u.vEmail='" . addslashes($value) . "') 
					Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . addslashes($value) . "')
					Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . addslashes($value) . "')
					Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . addslashes($value) . "')
					where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
				if(mysql_num_rows(mysql_query($sql,$conn)) > 0) {
					$arr_duplicate[$key] = $value; 
				}
			}
		} // end foreach - I
		if(count($arr_duplicate) > 0) {
			$returnList = "<SELECT name='cmbLookupList[]' id='cmbLookupList' MULTIPLE Size=5 style=\"width:300px;\" class=\"button\"> ";
			foreach($arr_duplicate as $key=>$value) {
				$returnList .= "<OPTION value=\"" . $key . "\">" . htmlentities($key . " - [" . $value . "]") . "</OPTION>";
			}
			$returnList .= "</SELECT>";
			$command = "<input type=\"button\" name=\"btLookup\" id=\"btLookup\" class=\"button\" onClick=\"javascript:clickFixLookup();\" value=\"Fix Lookup\"" . (($num == 11)?"":"disabled") . ">";
			return false;
		}
		else {
			$returnList = "Passed email settings check!";
			$command = "";
			return true;
		}
	}
	else {
		$returnList = "";
		$command = "";
		return false;	
	}
}

function alterTableStructure(&$returnList,&$command,$num) {
	global $conn,$var_host,$var_user,$var_password,$var_database;
   
	$sql = "DROP TABLE IF EXISTS `sptbl_chat`";
	mysql_query($sql,$conn) or die("Cannot remove chat table.");
    $sql = "DROP TABLE IF EXISTS `sptbl_operatorchat`";
	mysql_query($sql,$conn) or die("Cannot remove operatorchat table.");
    $sql = "DROP TABLE IF EXISTS `sptbl_cannedmessages`";
	mysql_query($sql,$conn) or die("Cannot remove cannedmessages table.");
	$sql = "DROP TABLE IF EXISTS `sptbl_chattransfer`";
	mysql_query($sql,$conn) or die("Cannot remove chattransfer table.");
	$sql = "DROP TABLE IF EXISTS `sptbl_visitors`";
	mysql_query($sql,$conn) or die("Cannot remove visitors table.");
    $sql = "DROP TABLE IF EXISTS `sptbl_desktop_share`";
	mysql_query($sql,$conn) or die("Cannot remove desktop_share table.");
	
	$sql = "CREATE TABLE sptbl_chat(nChatId bigint(20) NOT NULL auto_increment, dTimeStart datetime default '0000-00-00 00:00:00',nUserId bigint(20) default '0', vUserName varchar(100),nStaffId bigint(20) NOT NULL default '0',tMatter text default '',dTimeEnd datetime NOT NULL default '0000-00-00 00:00:00',vStatus varchar(10), vNewMsg char(1)  NOT NULL default '1',  nDeptId bigint(20), PRIMARY KEY (nChatId))  TYPE=MyISAM";
	mysql_query($sql,$conn) or die("Cannot create table sptbl_chat.");
	$sql = "CREATE TABLE sptbl_operatorchat(nChatId bigint(20) NOT NULL auto_increment, dTimeStart datetime default '0000-00-00 00:00:00',nFirstStaffId bigint(20) default '0',nSecondStaffId bigint(20) NOT NULL default '0',tMatter text default '',dTimeEnd datetime NOT NULL default '0000-00-00 00:00:00',vStatus varchar(10), vNewMsg char(1)  NOT NULL default '1',  vChatSts char(1), PRIMARY KEY (nChatId)) TYPE=MyISAM";
	mysql_query($sql,$conn) or die("Cannot create table sptbl_operatorchat.");
	$sql = "CREATE TABLE sptbl_cannedmessages(nMsgId int(11) NOT NULL auto_increment, dDate date default '0000-00-00', vTitle varchar(100), vDescription varchar(250), nStaffId bigint(20), vStatus char(1) default '0', PRIMARY KEY (nMsgId)) TYPE=MyISAM";
	mysql_query($sql,$conn) or die("Cannot create table sptbl_cannedmessages.");
	$sql = "CREATE TABLE sptbl_chattransfer(nTransferId bigint(20) NOT NULL auto_increment, cChatId bigint(20) NOT NULL default '0', nFirstStaff bigint(20) NOT NULL default '0', nSecondStaff bigint(20) NOT NULL default '0', vStatus varchar(10), PRIMARY KEY (nTransferId)) TYPE=MyISAM";
    mysql_query($sql,$conn) or die("Cannot create table sptbl_chattransfer.");
	$sql = "CREATE TABLE sptbl_visitors (nVisitingId BIGINT( 20 ) NOT NULL auto_increment, nCompId BIGINT( 20 ) , vIpAddr  VARCHAR( 25 ) , vPage  VARCHAR( 128 ) , vStatus  VARCHAR( 10 ), dVisitTime  datetime, dLastUpdTime  datetime ,  PRIMARY KEY (nVisitingId)) TYPE = MYISAM";
	mysql_query($sql,$conn) or die("Cannot create table sptbl_visitors.");
	$sql = "CREATE TABLE sptbl_desktop_share (nShareId  BIGINT( 20 ) NOT NULL auto_increment, nChatId BIGINT( 20 ), vClientIp varchar(25), vStatus  varchar(15),  PRIMARY KEY (nShareId)) TYPE = MYISAM";
	mysql_query($sql,$conn) or die("Cannot create table sptbl_desktop_share.");
	if(checkDBUpdate("sptbl_staffs","vStaffImg") == false) {
		$sql = "ALTER TABLE `sptbl_staffs` ADD `vStaffImg` VARCHAR( 128 )";
		mysql_query($sql,$conn) or die("Cannot alter staffs table.");
	}
	if(checkDBUpdate("sptbl_companies","vChatWelcomeMessage") == false) {
		$sql = "ALTER TABLE `sptbl_companies` ADD `vChatWelcomeMessage` VARCHAR( 128 ) default 'Welcome'";
		mysql_query($sql,$conn) or die("Cannot alter companies table.");
	}
	if(checkDBUpdate("sptbl_companies","vChatIcon") == false) {
		$sql = "ALTER TABLE `sptbl_companies` ADD `vChatIcon`  CHAR( 1 ) default '1'";
		mysql_query($sql,$conn) or die("Cannot alter companies table.");
	}
	if(checkDBUpdate("sptbl_companies","vChatOperatorRating") == false) {
		$sql = "ALTER TABLE `sptbl_companies` ADD `vChatOperatorRating` CHAR( 1 ) default '0'";
		mysql_query($sql,$conn) or die("Cannot alter companies table.");
	}
	if(checkDBUpdate("sptbl_staffratings","vType") == false) {
		$sql = "ALTER TABLE `sptbl_staffratings` ADD `vType` CHAR( 1 ) default 'T'";
		mysql_query($sql,$conn) or die("Cannot alter staffratings table.");
	}
	$sql = "Insert Into sptbl_lookup(vLookUpName,vLookUpValue) values('LiveChat','1')";
	mysql_query($sql,$conn) or die("Cannot update lookup values.");
	
	$returnList="Database synchronization complete!";
	$command="";
	return true;
}

function checkDBUpdate($tableName,$fieldName) {
	$flag = false;
	$result = mysql_query("SHOW COLUMNS FROM $tableName");
	while($row = mysql_fetch_object($result)){
		   if(strcasecmp(trim($row->Field),trim($fieldName)) == 0) {
		   	$flag = true;
			break;
		   }
	}
	return $flag;
}

//End Section - F

//Section - G
// Handles Postback by clicking the  'Fix xxxxx' button
// xxxx stands for Company, User, Staff, Department 
switch($_POST["checkDetails"]) {
	case "fc":
			fixCompany();
			break;
	case "fd":
			fixDepartment();
			$_POST["checkDetails"] = "d";
			break;		
	case "fs":
			fixStaff();
			$_POST["checkDetails"] = "s";
			break;
	case "fu":
			fixUser();
			$_POST["checkDetails"] = "u";
			break;
	case "fl":
			fixLookup();
			$_POST["checkDetails"] = "l";
			break;						
}
$message ="";
if ($_POST["btnGo"] == "Submit") {
	$txtAdminPass  = trim($_POST['txtAdminPass']);
	$txtLicenseKey  = trim($_POST['txtLicenseKey']);
	if ($txtLicenseKey != "" && $txtAdminPass != "") {
		$sqlSelect	= "SELECT * FROM sptbl_staffs WHERE vPassword='".md5($txtAdminPass)."' AND vLogin ='admin'";
		$res =	mysql_query($sqlSelect);
		if(mysql_num_rows($res) > 0){	
			if (strlen($txtLicenseKey) == '30') {
				$sql = "INSERT INTO sptbl_lookup VALUES ('','vLicenceKey','" . addslashes($txtLicenseKey) . "')";
				mysql_query($sql);
				header("Location:index.php");
				exit;
			}else
				$message = "Invalid key. Please enter a valid key";
		}else
			$message = "Invalid admin password. Please enter a valid admin password";
	}else
		$message = "Please enter new key";
}
//End - Section G
$num=-1;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo(TITLE_UPGRADATION);?></title>
<style type="text/css">
<!--
.orangelistingmaintext { /*Approved*/
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 9px;
        color:#FF9900;
		font-weight:bold;
}
.linknewtext { /*Approved*/
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10px;
        color: #000000;
		text-decoration:none;
}
-->
</style>
<link href="../styles/coolgreen.css" rel="stylesheet" type="text/css">
<script>
<!--
	function clickCheckDetails(i) {
		document.frmSettings.checkDetails.value=i;
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}
	function clickFixCompany() {
		var cnt = document.frmSettings.cmbCompanyList.length;
		for(i=0;i < cnt;i++) {
			document.frmSettings.cmbCompanyList[i].selected = true;
		}
		document.frmSettings.checkDetails.value="fc";
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}
	function clickFixDepartment() {
		var cnt = document.frmSettings.cmbDepartmentList.length;
		for(i=0;i < cnt;i++) {
			document.frmSettings.cmbDepartmentList[i].selected = true;
		}
		document.frmSettings.checkDetails.value="fd";
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}
	function clickFixStaff() {
		var cnt = document.frmSettings.cmbStaffList.length;
		for(i=0;i < cnt;i++) {
			document.frmSettings.cmbStaffList[i].selected = true;
		}
		document.frmSettings.checkDetails.value="fs";
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}
	function clickFixUser() {
		var cnt = document.frmSettings.cmbUserList.length;
		for(i=0;i < cnt;i++) {
			document.frmSettings.cmbUserList[i].selected = true;
		}
		document.frmSettings.checkDetails.value="fu";
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}
	function clickFixLookup() {
		var cnt = document.frmSettings.cmbLookupList.length;
		for(i=0;i < cnt;i++) {
			document.frmSettings.cmbLookupList[i].selected = true;
		}
		document.frmSettings.checkDetails.value="fl";
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}
	function clickNext() {
		document.frmSettings.action="alterdb.php";
		document.frmSettings.method="post";
		document.frmSettings.submit();
	}

 -->	
</script>
<script language="javascript1.1" type="text/javascript">
function emptyCheck()
{
	if(document.frmSettings.txtAdminPass.value == ""){
		alert('Please enter administrator password');
		document.frmSettings.txtAdminPass.focus();
		return false;	
	}else if(document.frmSettings.txtLicenseKey.value == ""){
		alert('Please enter valid license key');
		document.frmSettings.txtLicenseKey.focus();
		return false;	
	}else{
		document.frmSettings.postback.value = "Submit";
		document.frmSettings.action = "index.php";
		return true;
	}
}
</script>
</head>
<body bgcolor="#EDEBEB" topmargin="0" leftmargin="10" rightmargin="10">
<form name="frmSettings" action="index.php" method="post">
<input type="hidden" name="checkDetails" value="">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="1" rowspan="2" ><img src="../images/spacerr.gif" width="1" height="1"></td>
    <td align="right"><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="topbar">
        <tr>
          <td align="left"><span class="helpdeskname">&nbsp;</span></td>
        </tr>	
      </table>
  	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
			<tr>
			  <td width="21%" bgcolor="#FFFFFF"><img src="../images/logoo.gif" width="145" height="48"></td>
			  <td width="79" valign="bottom" class="corner" align="right"><img src="../images/spacer.gif" width="79" height="62"></td>  
			  <td width="78%" valign="bottom" class="column1" align="left">&nbsp;</td>
			</tr>
	  <tr><td colspan="3" class="column1"><img src="../images/spacer.gif" height="1" width="0"></td></tr>
	  </table>
    </td>
    <td width="1" rowspan="2" ><img src="../images/spacerr.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td align="center"><table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr bgcolor="#F3F3F3">
          <td width="24%" align="center" valign="top" bgcolor="#FFFFFF">   
		  <img src="./images/updation_main.jpg" border="0">
		  <?php
				$header = HEADER_PRELIMINARY_CHECK;
				$sub_header = "";
			//echo($header);
			?>  
		  <table width="90%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1" ></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" valign="top" ><img src="images/spacerr.gif" width="1" height="1" class="vline"></td>
                        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="93%" background="images/barbg1.gif" class="subhead">&nbsp;</td>
                            </tr>
                          </table>
		  
		  
		  
		  
		  
		  
            <table width="90%" border="0" cellpadding="5" cellspacing="0" class="ashbodydark" align="center">
            <tr>
              <td align="center" class="ashbodydark"> <?php echo($sub_header); ?> </td>
            </tr>
          </table>
            <table width="90%" border="0" cellpadding="0" cellspacing="0" class="ashbody" align="center">
              <tr>
                <td align="center" class="redtext"><?php echo($err_message); ?> </td>
              </tr>
            </table>
            <table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <td align="center" class="redtext">Note: You will lose all customisations, language will be set as english, and Cool green will be set as the default style for staff/users.</td>
              </tr>
            </table>

            <br>
		
		<table width="90%" cellpadding="2" cellspacing="2" border="0" align="center">
			<tr class="linktext">
					<td colspan="4" class="toplinks"><img src="./images/updation_file.jpg" border="0"><?php //echo TEXT_PERMISSION_CHECK ?></td>
					 </tr>
			   <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			   </tr>
			   <tr>
			   	<td colspan="4" >
			   	  <table width="100%"  border="0">
				  <?php
					  $htmlpath = "";
					  if(is_dir("../attachments")) {
						  $htmlpath = htmlpath("../attachments");
						  $wr=getDirList("../attachments",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/attachments missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_dir("../backup")) {
						  $htmlpath = htmlpath("../backup");
						  $wr=getDirList("../backup",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/backup missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_dir("../custom")) {
						  $htmlpath = htmlpath("../custom");
						  $wr=getDirList("../custom",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/custom missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_dir("../downloads")) {
						  $htmlpath = htmlpath("../downloads");
						  $wr=getDirList("../downloads",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/downloads missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_dir("../csvfiles")) {
						  $htmlpath = htmlpath("../csvfiles");
						  $wr=getDirList("../csvfiles",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/csvfiles missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_dir("../styles")) {
						  $htmlpath = htmlpath("../styles");
						  $wr=getDirList("../styles",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/styles missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_file("../api/useradd.php")) {
						  $htmlpath = htmlpath("../api/useradd.php");
						  $wr=getDirList("../api/useradd.php",1);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/api/useradd.php missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_file("../api/server_class.php")) {
						  $htmlpath = htmlpath("../api/server_class.php");
						  $wr=getDirList("../api/server_class.php",1);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/api/server_class.php missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
				  <?php
					  $htmlpath = "";
					  if(is_file("../config/settings.php")) {
						  $htmlpath = htmlpath("../config/settings.php");
						  $wr=getDirList("../config/settings.php",1);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
						   $command=TEXT_NO_ACTION;
						   $style = "listingmaintext";
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
						 }
						 else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/config/settings.php missing";
						  } 
					?>
					 <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
					<?php
						$htmlpath = "";
						if(is_file("./logfile.txt")) {
							  $htmlpath = htmlpath("./logfile.txt");		
							  $wr=getDirList("./logfile.txt",1);
							  if($wr==TEXT_WRITE_PERMISSION_AVAILABLE){
							   $command=TEXT_NO_ACTION;
								$style = "listingmaintext";
							   $num++;
							  }else{
								$style = "redlistingmaintext";
							  $command=TEXT_ENABLE_WRITE_PERMISSION;
							  }
						  }
						  else {
						  	$command="";
							$style = "redlistingmaintext";	
						  	$htmlpath = "yourinstalldirectory/updations/logfile.txt missing";
						  }
						?>

                    <tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
					<?php
					  if(is_dir("../admin/purgedtickets")) {
					  		$htmlpath = htmlpath("../admin/purgedtickets");			
						  $wr=getDirList("../admin/purgedtickets",0);
						  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
							$style = "listingmaintext";
						   $command=TEXT_NO_ACTION;
						   $num++;
						  }else{
						  $style = "redlistingmaintext";
						  $command=TEXT_ENABLE_WRITE_PERMISSION;
						  }
					  }
					  else {
						$command="";
						$style = "redlistingmaintext";	
						$htmlpath = "yourinstalldirectory/admin/purgedtickets missing";
					  }
					  
					?>
					<tr class="<?php echo($style); ?>">
                      <td><?php echo  $htmlpath;?></td>
                      <td><b>
                        <?php   echo $command;?>
                      </b></td>
                    </tr>
					<tr align="left">
					   <td colspan="2"  height="1"></td>
					</tr>
					<?php
						if(is_dir("../admin/purgedtickets/attachments")) {
							  $htmlpath = htmlpath("../admin/purgedtickets/attachments");
							  $wr=getDirList("../admin/purgedtickets/attachments",0);
							  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
								$style = "listingmaintext";
							   $command=TEXT_NO_ACTION;
							   $num++;
							  }else{
							  $style = "redlistingmaintext";
							  $command=TEXT_ENABLE_WRITE_PERMISSION;
							  }
						  }
						  else {
							$command="";
							$style = "redlistingmaintext";	
							$htmlpath = "yourinstalldirectory/admin/purgedtickets/attachments missing";
						  }
						?>
						<tr class="<?php echo($style); ?>">
						  <td><?php echo  $htmlpath;?></td>
						  <td><b>
							<?php   echo $command;?>
						  </b></td>
						</tr>
						<tr align="left">
						   <td colspan="2"  height="1"></td>
						</tr>
					
                  </table>
			   	  <b>			   	</b></td>
				 </tr>
		   

				<tr align="left">
				   <td colspan="4"  height="30"><img src="./../images/spacerr.gif" width="1" height="30"></td>
			   </tr>

				<tr class="linktext">
					<td colspan="4" class="toplinks"><img src="./images/updation_db.jpg" border="0"><?php //echo TEXT_DB_CONSISTENCY ?></td>
					 </tr>
			   <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			   </tr>
			   <?php
				   $returnList = "";
				   $command = "";
				   $help_message="";
				   if($_SESSION["checkLevel"] <= 0) {
					   if(checkCompanyDetails($returnList,$command,$num)) {
					   		$_SESSION["checkLevel"] = 1;
							$style="listingmaintext";
					   }
					   else {
					   		$style = "redlistingmaintext";
					   		$help_message="<tr class='$style'><td colspan=\"4\" width=\"100%\">
							<br><font color='#FF9966'>The list of duplicate email addresses in company master is given below.  You have two options here:<br>&nbsp;<br>
							(i)&nbsp;&nbsp;Edit company details --> Change the email addresses given below to any other unique email address in the system.<br>&nbsp;<br>
							(ii)&nbsp;&nbsp;Click the 'Fix company' button to the right of the list box so that we will modify the given email addresses to a unique address which you can modify later by using the Admin control panel.<br>&nbsp;<br>
							</font>
							</td> </tr><tr align=\"left\">
							<td colspan=\"4\" height=\"1\"><img src=\"./../images/spacerr.gif\" width=\"1\" height=\"1\"></td></tr>";
					   }
					}
					else {
					   $returnList = "Passed company details check!";
					   $command = "";
					   $style="listingmaintext";
					}   
					echo($help_message);
				?>
			   <tr class="<?php echo($style); ?>">
				 <td width="49%" height="30" >&nbsp;<?php echo   TEXT_CHECK_COMPANY?></td>
				 <td width="47%" height="30" colspan="2"><?php echo  "<font color=green>".$returnList."</font>"; ?></td>
				 <td width="4%" height="30" align="left" ><b><?php  echo $command;?></b></td>
		   </tr>
		   <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			</tr>
			<?php
				   $display_text=TEXT_CHECK_DEPARTMENT;
				   $help_message="";
				   $returnList = "";
				   $command = "";
				   if($_POST["checkDetails"] == "d") {
					   if(checkDepartmentDetails($returnList,$command,$num)) {
					   		$_SESSION["checkLevel"] = 2;
							$style="listingmaintext";
					   }
					   else {
					   		$style = "redlistingmaintext";
					   		$help_message="<tr class='$style'><td colspan=\"4\" width=\"100%\">
							<br><font color='#FF9966'>The list of duplicate email addresses in department master is given below.  You have two options here:<br>&nbsp;<br>
							(i)&nbsp;&nbsp;Edit department details --> Change the email addresses given below to any other unique email address in the system.<br>&nbsp;<br>
							(ii)&nbsp;&nbsp;Click the 'Fix department' button to the right of the list box so that we will modify the given email addresses to a unique address which you can modify later by using the Admin control panel.<br>&nbsp;<br>
							</font>
							</td> </tr><tr align=\"left\">
							<td colspan=\"4\" height=\"1\"><img src=\"./../images/spacerr.gif\" width=\"1\" height=\"1\"></td></tr>";
					   }
				   }
				   elseif($_SESSION["checkLevel"] >= 2) {
					   $style="listingmaintext";
					   $returnList = "Passed department details check!";
					   $command = "";
				   }
				   else {
				   	 $display_text="<input type=\"button\" name=\"btcheckDetails\" id=\"btcheckDetails\" class=\"button\" onClick=\"javascript:clickCheckDetails('d');\" value=\"Continue with department table check.\" style=\"width:240px;\"" . (($_SESSION["checkLevel"] == 1 && $num == 11)?" > &nbsp;&nbsp;<img src=\"./images/blink.gif\" height='22' width='24'>":"disabled >");
				   }
				   echo($help_message);
				?>
			<tr class="<?php echo($style); ?>">
				
				 <td width="49%" height="30" >&nbsp;<?php echo   $display_text?></td>
				 <td height="30" colspan="2" ><?php echo  "<font color=green>".$returnList."</font>";?></td>
				 <td width="4%" height="30" align="left" ><b><?php   echo "&nbsp;" . $command;?></b></td>
				 
			   </tr>	
			   <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			</tr>
			<?php
				$display_text=TEXT_CHECK_STAFF;
				$help_message="";
			   $returnList = "";
			   $command = "";
			   if($_POST["checkDetails"] == "s") {
				   if(checkStaffDetails($returnList,$command,$num)) {
				   		$_SESSION["checkLevel"] = 3;
						$style="listingmaintext";
				   }
				   else {
						$style = "redlistingmaintext";
						$help_message="<tr class='$style'><td colspan=\"4\" width=\"100%\">
							<br><font color='#FF9966'>The list of duplicate email addresses in staff master is given below.  You have two options here:<br>&nbsp;<br>
							(i)&nbsp;&nbsp;Edit staff details --> Change the email addresses given below to any other unique email address in the system.<br>&nbsp;<br>
							(ii)&nbsp;&nbsp;Click the 'Fix staff' button to the right of the list box so that we will modify the given email addresses to a unique address which you can modify later by using the Admin control panel.<br>&nbsp;<br>
							</font>
							</td> </tr><tr align=\"left\">
							<td colspan=\"4\" class=\"dotedhoriznline\" height=\"1\"><img src=\"./../images/spacerr.gif\" width=\"1\" height=\"1\"></td></tr>";
				   }
			   }
			   elseif($_SESSION["checkLevel"] >= 3) {
				   $style="listingmaintext";
				   $returnList = "Passed department details check!";
				   $command = "";
			   }
			   else {
				 $display_text="<input type=\"button\" name=\"btcheckDetails\" id=\"btcheckDetails\" class=\"button\" onClick=\"javascript:clickCheckDetails('s');\" value=\"Continue with staff details check.\" style=\"width:240px;\"" . (($_SESSION["checkLevel"] == 2  && $num == 11)?" > &nbsp;&nbsp;<img src=\"./images/blink.gif\" height='22' width='24'>":"disabled >");
			   }   
			   echo($help_message);
			?>
			<tr class="<?php echo($style); ?>">
				
				 <td width="49%" height="30" >&nbsp;<?php echo   $display_text?></td>
				 <td height="30" colspan="2" ><?php echo  "<font color=green>".$returnList."</font>";?></td>
				 <td width="4%" height="30" align="left" ><b><?php   echo $command;?></b></td>
				 
			   </tr>	
			   
		   <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			</tr>
			<?php
				$display_text=TEXT_CHECK_USER;
				$help_message="";
			   $returnList = "";
			   $command = "";
			   if($_POST["checkDetails"] == "u") {
				   if(checkUserDetails($returnList,$command,$num)) {
				   		$_SESSION["checkLevel"] = 4;
						$style="listingmaintext";
				   }
				   else {
						$style = "redlistingmaintext";
						$help_message="<tr class='$style'><td colspan=\"4\" width=\"100%\">
							<br><font color='#FF9966'>The list of duplicate email addresses in user master is given below.  You have two options here:<br>&nbsp;<br>
							(i)&nbsp;&nbsp;Edit user details --> Change the email addresses given below to any other unique email address in the system.<br>&nbsp;<br>
							(ii)&nbsp;&nbsp;Click the 'Fix user' button to the right of the list box so that we will modify the given email addresses to a unique address which you can modify later by using the Admin control panel.<br>&nbsp;<br>
							</font>
							</td> </tr><tr align=\"left\">
							<td colspan=\"4\" class=\"dotedhoriznline\" height=\"1\"><img src=\"./../images/spacerr.gif\" width=\"1\" height=\"1\"></td></tr>";
				   }
			   }
			   elseif($_SESSION["checkLevel"] >= 4) {
				   $style="listingmaintext";
				   $returnList = "Passed user details check!";
				   $command = "";
			   }
			   else {
				 $display_text="<input type=\"button\" name=\"btcheckDetails\" id=\"btcheckDetails\" class=\"button\" onClick=\"javascript:clickCheckDetails('u');\" value=\"Continue with user details check.\" style=\"width:240px;\"" . (($_SESSION["checkLevel"] == 3 && $num == 11)?" > &nbsp;&nbsp;<img src=\"./images/blink.gif\" height='22' width='24'>":"disabled >");
			   } 
			   echo($help_message);    
			?>
			<tr class="<?php echo($style); ?>">
				
				 <td width="49%" height="30" >&nbsp;<?php echo   $display_text?></td>
				 <td height="30" colspan="2" ><?php echo  "<font color=green>".$returnList."</font>";?></td>
				 <td width="4%" height="30" align="left" ><b><?php   echo $command;?></b></td>
				 
			   </tr>	
			   
			   <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			</tr>
			<?php
	/*			$display_text=TEXT_CHECK_LOOKUP;
				$help_message="";
			   $returnList = "";
			   $command = "";
			   if($_POST["checkDetails"] == "l") {	
				   if(checkLookupDetails($returnList,$command,$num)) {
						$_SESSION["checkLevel"] = 5;
						$style="listingmaintext";
				   }
				   else {
						$style = "redlistingmaintext";
						$help_message="<tr class='$style'><td colspan=\"4\" width=\"100%\">
							<br><font color='#FF9966'>The list of duplicate email addresses in Email Settings is given below.  You have two options here:<br>&nbsp;<br>
							(i)&nbsp;&nbsp;Edit Email Settings --> Change the email addresses given below to any other unique email address in the system.<br>&nbsp;<br>
							(ii)&nbsp;&nbsp;Click the 'Fix Lookup' button to the right of the list box so that we will modify the given email addresses to a unique address which you can modify later by using the Admin control panel.<br>&nbsp;<br>
							</font>
							</td> </tr><tr align=\"left\">
							<td colspan=\"4\" height=\"1\"><img src=\"./../images/spacerr.gif\" width=\"1\" height=\"1\"></td></tr>";
				   }
			   }
			   elseif($_SESSION["checkLevel"] >= 5) {
				   $style="listingmaintext";
				   $returnList = "Passed lookup details check!";
				   $command = "";
			   }
			   else {
				 $display_text="<input type=\"button\" name=\"btcheckDetails\" id=\"btcheckDetails\" class=\"button\" onClick=\"javascript:clickCheckDetails('l');\" value=\"Continue with lookup details check.\" style=\"width:240px;\"" . (($_SESSION["checkLevel"] == 4 && $num == 4)?" > &nbsp;&nbsp;<img src=\"./images/blink.gif\" height='22' width='24'>":"disabled >");
			   }     
			   echo($help_message); 
			?>
			<tr class="<?php echo($style); ?>">
				
				 <td width="49%" height="30" >&nbsp;<?php echo   $display_text?></td>
				 <td height="30" colspan="2" ><?php echo  $returnList;?></td>
				 <td width="4%" height="30" align="LEFT" ><b><?php   echo $command;?></b></td>
				 
			   </tr>	
			  <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			</tr> 
			<?php
		*/		$display_text=TEXT_CHECK_ALTERDB;
				$help_message="";
			   $returnList = "";
			   $command = "";
			   if($_POST["checkDetails"] == "a") {	
				   if(alterTableStructure($returnList,$command,$num)) {
						$_SESSION["checkLevel"] = 6;
						$style="listingmaintext";
				   }
				   else {
						$style = "redlistingmaintext";
						$help_message="<tr class='$style'><td colspan=\"4\" width=\"100%\">
							<br><font color='#FF9966'>Cannot alter table structure.  Please grant all permissions to the database $var_database  for the user $var_user.
							</font>
							</td> </tr><tr align=\"left\">
							<td colspan=\"4\" height=\"1\"><img src=\"./../images/spacerr.gif\" width=\"1\" height=\"1\"></td></tr>";
				   }
			   }
			   elseif($_SESSION["checkLevel"] >= 6) {
				   $style="listingmaintext";
				   $returnList = "Database synchronized!";
				   $command = "";
			   }
			   else {
				 $display_text="<input type=\"button\" name=\"btcheckDetails\" id=\"btcheckDetails\" class=\"button\" onClick=\"javascript:clickCheckDetails('a');\" value=\"Synchronize table structure.\" style=\"width:240px;\"" . (($_SESSION["checkLevel"] == 4 && $num == 11)?" > &nbsp;&nbsp;<img src=\"./images/blink.gif\" height='22' width='24'>":"disabled >");
			   }     
			   echo($help_message); 
			?>
			<tr class="<?php echo($style); ?>">
				
				 <td width="49%" height="30" >&nbsp;<?php echo   $display_text?></td>
				 <td height="30" colspan="2" ><?php echo  "<font color=green>".$returnList."</font>";?></td>
				 <td width="4%" height="30" align="LEFT" ><b><?php   echo $command;?></b></td>
				 
			   </tr>	
			  <tr align="left">
				   <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
			</tr>
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr><td align="center" colspan="4" class="maintext"><font color="#FF0000"><?php echo $message;?></font></td></tr>
			<tr id="adminpass" bgcolor="#FFFFFF">
				<td width="36%" align="right" class="maintext">Admin password&nbsp;&nbsp;</td>
				<td width="32%" align="left">
					<input name="txtAdminPass"  id="txtAdminPass" type="text" class="textbox" size="25" maxlength="40" value="<?php echo htmlentities($txtAdminPass);?>">
				</td>
				<td width="22%" align="left" colspan="2">&nbsp;</td>
			</tr>
			<tr id="licensekey" bgcolor="#FFFFFF">
				<td width="36%" align="right" class="maintext">Enter new license key &nbsp;&nbsp;</td>
				<td width="32%" align="left">
					<input name="txtLicenseKey"  id="txtLicenseKey" type="text" class="textbox" size="45" maxlength="40" value="<?php echo htmlentities($txtLicenseKey);?>">
				</td>
				<td width="22%" align="left" colspan="2">&nbsp;</td>
			</tr>
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" value="" name="postback">
					<input type="Submit" name="btnGo" value="Submit" class="button" onClick="return emptyCheck();">
				</td>
			</tr>
		</table>
	</td>
                        <td width="1" valign="top" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                      <tr>
                        <td><img src="images/spacerr.gif" width="1" height="1" ></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
		
          </td>
        </tr>
		
		
		
		   <?php
				if ($_SESSION["checkLevel"] == 6) {
			?>
			
			<tr>
				<td colspan="4" align="center" class="linktext">
					<table width="90%" align="center" class="linktext">
						<tr>
							<td align="center">
								<fieldset>
								<legend align="center">Turn Email Piping ON</legend>
								<table width="90%" align="center">
									<tr>
										<td width="100%" valign="top" align="center">
											
											  <table class="linknewtext">
												<tr>
													<td>
														Steps to set forwarder for your mails						</td>
												</tr>
												<tr>
													<td height="134">
														To use mail forwarder for your ticket system please follow the step given below<br>
														&nbsp;<br>
												  (i) Add &nbsp;&nbsp;<font color="#CC0000" style="font-weight:bold;">path_to_php -q yourinstalldirectory/parser/parser.php</font>&nbsp;&nbsp; as the forwarder address for your support mail address.<br>&nbsp;<br>Eg: <font color="#CC0000" style="font-weight:bold;">|/usr/bin/php -q /home/user/www/support/parser/parser.php</font><br>&nbsp;</td>
												</tr>
											  </table> 
											
										</td>
									</tr>
								</table>
								</fieldset>
								<a href="../admin/index.php" class="listing"><b>Go to Admin Panel</b></a>&nbsp;
							
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php 
				}
			?>
		
      </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="topbar">
		<tr>
		  <td width="97%" align="right" class="toplinks">Powered by <a rel="nofollow" href="http://www.iscripts.com" style="text-decoration:none;color:#FFFF00">iScripts.com</a></td>
		  <td width="3%" align="right">&nbsp;</td>
		</tr>
	  </table>
 	 </td>
  </tr>
</table>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td ><img src="../images/spacerr.gif" width="1" height="1"></td>
  </tr>
</table>
</form>	
</body>
</html>