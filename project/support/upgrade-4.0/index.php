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


if($flag == true) {
    $sql = "SELECT `vLookUpValue` FROM sptbl_lookup WHERE `vLookUpName` = 'Version'";
    $res = mysql_query($sql);
    if(mysql_num_rows($res) > 0) {
        header("location: ../index.php");
        exit;
    } else {
        $sq1 = "INSERT INTO `sptbl_lookup`  VALUES (125, 'Version', '4.1')";
        $sq2 = "INSERT INTO sptbl_lang VALUES ('de','German')";
        $sq3 = "INSERT INTO sptbl_lang VALUES ('fr','French')";
        $sq4 = "INSERT INTO sptbl_lang VALUES ('es','Spanish')";
        
        mysql_query($sq1);
        mysql_query($sq2);
        mysql_query($sq3);
        mysql_query($sq4);
        
        $msg = "Successfully upgraded to SupportDesk 4.1";
    }
}




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
				//$header = HEADER_PRELIMINARY_CHECK;
				//$sub_header = "";
			echo($msg);
			?>  

		
          </td>
        </tr>
		
		

      </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="topbar">
		<tr>
		  <td width="97%" align="right" class="toplinks">Powered by <a href="http://www.iscripts.com" style="text-decoration:none;color:#FFFF00">iScripts.com</a></td>
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