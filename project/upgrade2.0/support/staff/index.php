<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer1<programmer1@armia.com>                          |
// |          programmer1<programmer2@armia.com>                          |
// +----------------------------------------------------------------------+
  	require_once("../includes/decode.php");
	if(!isValid(1)) {
	echo("<script>window.location.href='../invalidkey.php'</script>");
	exit();
	}
    require_once("includes/applicationheader.php");
    include("includes/functions/miscfunctions.php");
    include("languages/".$_SP_language."/index.php");
    $conn = getConnection();
    $_SESSION["sess_cssurl"] = getCurrentThemeUrl();
   
//Modification November 3, 2005
if($_POST["postback"] == "Login"){
	$error = false;
	$errormessage = "" ;
	if(isNotNull($_POST["txtUserID"])){
			$loginname = trim($_POST["txtUserID"]);
	}else{//user name null
			$error = true;
			$errormessage .= MESSAGE_USER_ID_REQUIRED . "<br>";
	}
	if(isNotNull($_POST["txtPassword"])){
			$password = $_POST["txtPassword"];
	}else{//user name null
			$error = true;
			$errormessage .= MESSAGE_PASSWORD_REQUIRED . "<br>";
	}
	if($error){
			$errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
	}else{//no error so validate
			$sql  = "SELECT s.nStaffId , s.vStaffname , s.vMail , s.vLogin , s.vPassword,s.nCSSId,s.nRefreshRate
									  FROM sptbl_staffs s   ";
			$sql .= " WHERE vLogin = '".addslashes($loginname)."' and  vPassword ='".addslashes(md5($password))."' and vType='S' and vDelStatus ='0' ";
		   $result = executeSelect($sql,$conn);
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				$staffid   = $row["nStaffId"];
				$staffname = $row["vLogin"];
				$staffemail = $row["vMail"];
				$cssurl = $row["vCSSURL"];
				$refresh = $row["nRefreshRate"];

				$_SESSION['sess_isstaff'] = 1;
				$_SESSION["sess_staffname"] = $staffname;
				$_SESSION["sess_staffid"] = $staffid;
				$_SESSION["sess_staffemail"] = $staffemail;
				$_SESSION["sess_stafffullname"] = $stafffullname;
				$_SESSION["sess_staffdept"] = $depts;
				$_SESSION["sess_cssurl"] = getCurrentThemeUrl();

				$_SESSION["sess_refresh"] = $refresh;

	/*			$sql1  = "UPDATE sptbl_staffs  ";
				$sql1 .= " SET vOnline = '1' WHERE `nStaffId` = '".$staffid."' ";
				$result1 = executeSelect($sql1,$conn);
*/
				$sql  = "Select F.vFieldName,F.vFieldDesc from sptbl_stafffields SF inner join sptbl_fields F
								 ON SF.nFieldId = F.nFieldId WHERE nStaffId='$staffid' ";
				$rs = executeSelect($sql,$conn);

				if (mysql_num_rows($rs) > 0) {
						$cnt = 0;
						while($row = mysql_fetch_array($rs)) {
								$fld_arr[$cnt][0] = $row["vFieldName"];
								$fld_arr[$cnt][1] = $row["vFieldDesc"];
								$cnt++;
						}
				}
				$_SESSION["sess_fieldlist"] = $fld_arr;
				mysql_free_result($rs);
				
				/* Set departments session variabnle ****************/
				$lst_dept = "'',";
                               $sql = "Select nDeptId from sptbl_staffdept WHERE nStaffId='".$staffid."'";
                                $result = executeSelect($sql,$conn);
                                $depts="";
                                $xxx=0;
                                while ($row =mysql_fetch_array($result)){
                                       ($xxx=="1")?$depts.=",":$depts.="";
                                       $depts.=$row["nDeptId"];
                                       $xxx=1;
									   $lst_dept .= $row["nDeptId"] . ",";
                                }
                                ($depts=="")?$depts="0":$depts=$depts;
								$lst_dept = substr($lst_dept,0,-1);
								$_SESSION['departmentids'] = $lst_dept;
								mysql_free_result($result);
				/* End set departments session variabnle ****************/
				
				$sql  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
				$rs = executeSelect($sql,$conn);

				if (mysql_num_rows($rs) > 0) {
						$cnt = 0;
						while($row = mysql_fetch_array($rs)) {
								$fld_prio[$cnt][0] = $row["nPriorityValue"];
								$fld_prio[$cnt][1] = $row["vTicketColor"];
								$fld_prio[$cnt][2] = $row["vPriorityDesc"];
								$cnt++;
						}
				}
				$_SESSION["sess_priority"] = $fld_prio;
				mysql_free_result($rs);

//				$_SESSION["sess_totaltickets"] == 0;

				$_SESSION["sess_totaltickets"] = 0;
				$_SESSION["sess_login_flag"] = 1;   //this session variable is set for private message alert

				header("Location: staffmain.php");
				exit;
		}else{
				$error = true;
				$errormessage = MESSAGE_INVALID_LOGIN;
		}
	}
}
	
	
                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','MaxPostsPerPage','OldestMessageFirst','SMTPSettings','SMTPServer','SMTPPort')";
                $rs = executeSelect($sql,$conn);

				if(!isset($_SESSION['sess_cssurl'])){
        			$_SESSION['sess_cssurl']="styles/AquaBlue/style.css";
				}
                if (mysql_num_rows($rs) > 0) {
                        while($row = mysql_fetch_array($rs)){
                                switch($row["vLookUpName"]) {
                                        case "LangChoice":
                                                        $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                                                        break;
                                        case "DefaultLang":
                                                        $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                                                        break;
                                        case "HelpdeskTitle":
                                                        $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                                                        break;
                                        case "Logourl":
                                                        $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                                                        break;
										case "logactivity":    //this session variable decides to log activities or not
														$_SESSION["sess_logactivity"] = $row["vLookUpValue"];
														break;	
										case "MaxPostsPerPage":
														$_SESSION["sess_maxpostperpage"] = $row["vLookUpValue"];
														break;
										case "OldestMessageFirst":
														 $_SESSION["sess_messageorder"] = $row["vLookUpValue"];
														break;										
										case "SMTPSettings":
														 $_SESSION["sess_smtpsettings"] = $row["vLookUpValue"];
														break;
										case "SMTPServer":
														 $_SESSION["sess_smtpserver"] = $row["vLookUpValue"];
														break;
										case "SMTPPort":
														 $_SESSION["sess_smtpport"] = $row["vLookUpValue"];
														break;
                                }
                        }
                }
                mysql_free_result($rs);

                /*//$_SESSION["sess_language"] = "en";
                if ($_SESSION["sess_defaultlang"] != "en") {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        header("location:index.php");
                        exit;
                }*/
				
				if($_SESSION["sess_stafflangchange"] =="1"){
				   ;
				}else{
				
				    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        echo("<script>window.location.href='index.php'</script>");
						exit();
						
						//header("location:index.php");
                        //exit;
                   } 
			 }
				

//warning message before 10 days 
if($glob_date_check == "Y")
{
	echo("<script>alert('" . MESSAGE_LICENCE_EXPIRE . $glob_date_days . MESSAGE_LICENSE_DAYS . "');</script>");
}
//end warning		

?>

<?php include("../includes/docheader.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo HEADING_LOGIN ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
function checkLoginForm(){
        var frm = window.document.frmLogin;
        var errors="";
        if(frm.txtUserID.value == ""){
                errors += "<?php echo MESSAGE_USER_ID_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtPassword.value == ""){
                errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>" + "\n";
        }
        if(errors !=""){
                errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
                alert(errors);
                return false;
        }else{
                frm.postback.value = "Login";
                frm.submit();
        }
}

function passPress()
{ 
if(window.event.keyCode=="13"){
//		document.frmLogin.btnSubmit.focus();
		checkLoginForm();
	}
}

-->
</script>
</head>

<body>
<form name="frmLogin" method="post" >

<?php include "includes/indextop.php" ?>
<?php include "includes/indexcenter.php"; ?>
<?php include "includes/indexbottom.php" ?>
</form>
</body>
<script language="JavaScript">
<!--
if (document.frmLogin) {
document.frmLogin.txtUserID.focus();
}
// -->
</script>
</html>