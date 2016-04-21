<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>  		                          |
// |          									                          |
// +----------------------------------------------------------------------+
        error_reporting(E_ALL ^ E_NOTICE);
		include("./includes/session.php");
        include("../config/settings.php");
        include("./includes/functions/dbfunctions.php");
		include("./includes/functions/impfunctions.php");
        //set_magic_quotes_runtime(0);
        // Check if magic_quotes_runtime is active
      /*  if(get_magic_quotes_runtime())
        {
            // Deactivate
            set_magic_quotes_runtime(false);
        }*/
        if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

        }
        if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
                $_SP_language = "en";
        }else{
                $_SP_language = $_SESSION["sess_language"];
        }
        include("./languages/".$_SP_language."/main.php");
        include("./includes/functions/miscfunctions.php");

		if($_POST["post_back"] == "CL"){
				$_SESSION["sess_language"] = $_POST["cmbLan"];
				$_SESSION["sess_stafflangchange"] ="1";
				header("location:staffmain.php");
				exit;
		}

        include("./languages/".$_SP_language."/staffmain.php");
        $conn = getConnection();

                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl')";
                $rs = executeSelect($sql,$conn);
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
                        echo("<script>window.location.href='staffmain.php'</script>");
						exit();

						//header("location:index.php");
                        //exit;
                   }
			 }
			   $uid=addslashes($_GET['uid']);

			   $sqluser = "Select * from sptbl_users where nUserId='$uid' ";
               $rsuser = executeSelect($sqluser,$conn);

?>


<html>
<head>

<title><?php echo HEADING_STAFF_MAIN ?></title>
<?php include("./includes/headsettings.php"); ?>
</head>

<body bgcolor="#EDEBEB">
 <table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr class="whitebasic">

          <td width="100%" valign="top"  class="whitebasic">


                  <?php
				       if( mysql_num_rows($rsuser)>0){
				       $rowuser = mysql_fetch_array($rsuser);
				   ?>
				  <table width="100%" align=center>
						  <tr>
						   <td class=maintext align=right>Login: </td>
						   <td width="1">&nbsp;</td>
						   <td class=maintext ><?php echo $rowuser['vLogin'];?> </td>
						  </tr>
						   <tr>
						   <td class=maintext align=right>Name: </td>
						   <td width="1">&nbsp;</td>
						   <td class=maintext ><?php echo $rowuser['vUserName'];?> </td>
						  </tr>
						  <tr>
						   <td class=maintext align=right>Email: </td>
						   <td width="1">&nbsp;</td>
						   <td class=maintext ><?php echo $rowuser['vEmail'];?> </td>
						  </tr>
				  </table>
				   <?php

				        }
				  ?>
          </td>
        </tr>
      </table>
</body>

</html>