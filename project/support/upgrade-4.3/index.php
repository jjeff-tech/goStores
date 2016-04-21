<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : index.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: BINU CHANDRAN.E<binu.chandran@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Modified: ARUN SADASIVAN (01/07/2012)								  |
// |----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+
error_reporting(0);
ob_start(); 
include_once('../config/settings.php'); 
include_once('cls_serverconfig.php');


/*
 * get the path dynamically
 * NOTE:- need to add www via htaccess if required
 */
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) .$s . "://";
define('ROOT_URL', $protocol );
 
//Generating Root URL
 
$current_path = $_SERVER['SCRIPT_FILENAME'];
$current_path=rtrim($current_path,"/");
$current_path=$current_path."/";
$current_path=str_replace("\\","/",$current_path);
$fckeditor_path=str_replace('/upgrade-4.3/index.php', '', $current_path);

$DocumentRoot=$_SERVER["DOCUMENT_ROOT"];
$DocumentRoot=rtrim($DocumentRoot,"/");
$DocumentRoot=$DocumentRoot."/";
$DocumentRoot=str_replace("\\","/",$DocumentRoot);



$path=str_replace($DocumentRoot,"",$current_path);
$path=str_replace('/upgrade-4.3/index.php', '', $path);
$root_url = ROOT_URL . $_SERVER['SERVER_NAME'] ."/".$path;

////////

//add trailing slashes
if($root_url[strlen($root_url) - 1] <> '/'){
    $root_url .= '/';
}

define('BASE_URL', $root_url);


$perm_flag = true;
$perm_msg = '';
$error_message = '';
$error = false;
$installed = false;
$user_data = array();
$post_flag = false;

$configfile     = "../config/config.php";
$settingsfile   = "../config/settings.php";
$useradd		="../api/useradd.php";
$serverclass	="../api/server_class.php";
$schemafile     = "sql/schema.sql";
$fckeditor = "../FCKeditor/editor/filemanager/connectors/php/config.php";

$directories = array(
		"../custom/", 
		"../styles/", 
		"../attachments/", 
		"../backup/", 
		"../downloads/", 
		"../csvfiles/", 
		"../api/useradd.php", 
		"../api/server_class.php", 
		"../config/settings.php", 
		"../config/config.php",
		"../admin/purgedtickets/", 
		"../admin/purgedtickets/attachments/", 
		"../staff/images/", 
		"../fckeditorimages/", 
		"../FCKeditor/editor/filemanager/connectors/php/config.php");



$serverSettings = ServerConfig::checkServerConfiguration();
$serverCurrentSettings = ServerConfig::getServerSettings();

$host_name = parse_url($_SERVER['HTTP_HOST']);

if (isset($_POST['upgraderCheck'])) {

    $post_flag = true;
    
    //For Getting Folder Permission Status and Proper Error messages
    
    foreach ($directories as $dir) {
    	$permission = ServerConfig::fileWritable($dir, str_replace("../","", $dir));
    	if (!$permission['status'] && $error == false) {
    		$error = true;
    		$serverPermission="true";
    	  
    	}
    
    }
    
    if ($error == true) {
        if (isset($_POST["btnContinue"]) && !isset($_POST["auto_set"])) {
            $txtFTPusername = $_POST['FTPusername'];
            $txtFTPpassword = $_POST['FTPpassword'];

            $user_data['FTPusername'] = $_POST["FTPusername"];
            $user_data['FTPpassword'] = $_POST["FTPpassword"];

            if (trim($txtFTPusername) == '') {
                $perm_msg .= '* Please enter FTP username <br/>';
            }
            if (trim($txtFTPpassword) == '') {
                $perm_msg .= '* Please enter FTP password <br/>';
            } else {
                $conn_id = @ftp_connect($host_name["path"]);
                $login_result = @ftp_login($conn_id, $txtFTPusername, $txtFTPpassword);
                if ($login_result) {
                    $mode = 777;
                    $np = '0' . $mode;

                    $user_install = str_replace('upgrade-4.3', '', getcwd());

                    //get the path staring from public_html
                    if (strstr($user_install, 'public_html')) {
                        $path_parts = explode('/public_html', $user_install);
                        $user_install = '/public_html' . $path_parts[1];
                    } elseif (strstr($user_install, 'httpdocs')) {
                        $path_parts = explode('/httpdocs', $user_install);
                        $user_install = '/httpdocs' . $path_parts[1];
                    }

                    foreach ($directories as $directory) {
                        $edited_path = str_replace('..', '', $directory);
                        $directory = $user_install . $edited_path;
                        if ($directory[strlen($directory) - 1] == '/') {
                            $directory = substr($directory, 0, strlen($directory) - 1);
                        }
                        if (!@ftp_chmod($conn_id, eval("return({$np});"), $directory)) {
                            $perm_flag = false;
                        }
                    }

                    if (!$perm_flag) {
                        $perm_msg .= '* Sorry, an error occurred. Please try again or set the permissions manually <br/>';
                    } else {
                        $perm_msg = '<b>* File permissions successfuly set </b><br/>';
                    }
                } else {
                    $perm_msg .= '* Sorry, could not connect to the server. Please check the credentials <br/>';
                }
            }
        }
    } 

    // Get connection
    $connection = @mysql_connect($glb_dbhost, $glb_dbuser, $glb_dbpass);
    if ($connection === false) {
        $error = true;
        $message .= " * Connection Not Successful! Please verify your database details!<br>";
    } else {
        $dbselected = @mysql_select_db($glb_dbname, $connection);
        if (!$dbselected) {
            $error = true;
            $message .= " * Database could not be selected! Please verify your database details!<br>";
        }
    }
    
    
    
    
    $version = '4.3';
    if (!$error) {
    	//update SupportDesk config file
    	$fp = fopen($configfile, "w+");
    
    
    	$configcontent = "<?php\n";
    	$configcontent .= "define('INSTALLED', true); \n\n";
    	$configcontent .= "define('VERSION', $version); \n\n";
    	$configcontent .= "\n?>";
    	fwrite($fp, $configcontent);
    	fclose($fp);
    
    	//update SupportDesk settings file
    	$handle = fopen($settingsfile, "rb");
    	$contents = fread($handle, filesize($settingsfile));
    	fclose($handle);
    	$contents = "<?php \r\n$" . "glb_dbhost=\"" . $glb_dbhost . "\";\r\n";
    	$contents .= "$" . "glb_dbuser=\"" . $glb_dbuser . "\";\r\n";
    	$contents .= "$" . "glb_dbpass=\"" . $glb_dbpass . "\";\r\n";
    	$contents .= "$" . "glb_dbname=\"" . $glb_dbname . "\";\r\n";
    	$fp = fopen($settingsfile, 'w');
    	fwrite($fp, $contents);
    	fclose($fp);
    
    	//Reading User Add file
    
    
    	$handle = fopen($useradd, "rb");
    	$contents_useradd = fread($handle, filesize($useradd));
    	fclose($handle);
    
    	//Writing to User Add File
    	$fp = fopen($useradd, 'w');
    	fwrite($fp, $contents.$contents_useradd);
    	fclose($fp);
    
    
    
    	//Writing to Server Class API
    
    	$contents_serverclass = "\r\n" . "function xml_server() {\r\n";
    	$contents_serverclass .= "$" . "this->users_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->function_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->values_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->username_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->password_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->email_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->company_tag = \"\";\r\n";
    	$contents_serverclass .= "$" . "this->errno = \"0\";\r\n";
    	$contents_serverclass .= "$" . "this->conapi = mysql_connect(\"" . $glb_dbhost . "\",\"" . $glb_dbuser . "\",\"" . $glb_dbpass . "\") or die(mysql_error());\r\n";
    	$contents_serverclass .= "mysql_select_db(\"" . $glb_dbname . "\",$" . "this->conapi) or die(mysql_error());\r\n}\r\n}\r\n?>";
    
    	$fp = fopen($serverclass, "a+");
    	fwrite($fp, $contents_serverclass);
    	fclose($fp);
    
    	// FCK editor
    
    
    	$handle = fopen($fckeditor, "rb");
    	$contents = fread($handle, filesize($fckeditor));
    	fclose($handle);
    	$contents = str_replace('FCK_IMAGE_UPLOAD_DIRECTORY', $fckeditor_path.'fckeditorimages/', $contents);
    	$fp = fopen($fckeditor, 'w');
    	fwrite($fp, $contents);
    	fclose($fp);
    
    
    
    	//------------------------UPDATE THE DB---------------------------------//
    	$sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
    	$sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");
    	
    	
    	
    	for ($i = 0; $i < sizeof($sqlquery); $i++) {
    		mysql_query($sqlquery[$i], $connection);
    	}
    	  
    
      /************************* Code for all Ticket Entry to Statics TABLE **************/

 $total_ticket_sql = "select * from sptbl_tickets";
 $total_ticket_total = mysql_query($total_ticket_sql);
 while($row = mysql_fetch_array($total_ticket_total))
 {
     $ticket_ids = $row['nTicketId'];
     
     $postDate  = $row['dPostDate'];
     $checkSql = "select * from sptbl_ticket_statistics where ticket_id = '".$ticket_ids."'";
     
     $checkTicket = mysql_query($checkSql);
     
     if(mysql_num_rows($checkTicket)==0)
     {
         
          $total_unattempted_sql = "select sptb.*,sptbr.* from sptbl_tickets sptb INNER JOIN sptbl_replies sptbr ON sptbr.nTicketId = sptb.nTicketId where sptb.nTicketId = '".$ticket_ids."'  GROUP BY sptbr.nTicketId";
         
          $total_unattempted_time = mysql_query($total_unattempted_sql);
          $total_revolved_sql = "select * from (select sptb.*,sptbr.dDate,sptbr.nTicketId as ntick from sptbl_tickets sptb INNER JOIN sptbl_replies sptbr ON sptbr.nTicketId = sptb.nTicketId where sptb.nTicketId = '".$ticket_ids."' AND sptb.vStatus = 'closed' ORDER BY sptbr.dDate ASC) as temp GROUP BY temp.ntick";
        
          $total_revolved_time = mysql_query($total_revolved_sql);
          if(mysql_num_rows($total_unattempted_time)!=0)
          {
            $row_ticket = mysql_fetch_array($total_unattempted_time);
            $total_unattempted_time_min = strtotime($row_ticket['dDate']) - strtotime($row_ticket['dPostDate']);
            $interval  = abs($total_unattempted_time_min);
            $minutes1   = round($interval / 60);
            $total_unattempted_time_minutes = $minutes1;
          }else{
              $total_unattempted_time_minutes = 0;
          }
          
          if(mysql_num_rows($total_revolved_time)!=0)
          {
        $row_ticket1 = mysql_fetch_array($total_revolved_time);
        $total_revolved_time_min = strtotime($row_ticket1['dDate']) - strtotime($row_ticket1['dPostDate']);
        $interval  = abs($total_revolved_time_min);
        $minutes1   = round($interval / 60);
        $total_revolved_time_minutes = $minutes1;    
          }else{
              $total_revolved_time_minutes = 0;
          }
          
       
        $inserSql = "insert into sptbl_ticket_statistics(ticket_id,reply_time,closing_time,posted_date) values($ticket_ids,$total_unattempted_time_minutes,$total_revolved_time_minutes,'".mysql_real_escape_string($postDate)."')" ; 

        mysql_query($inserSql);
     }
     
 }
 
 
 

 
 
 /************************* Code for all Ticket Entry to Statics TABLE **************/
   
    
    
    
        //installation tracker
        $rootserver = BASE_URL;
        $productVersion = 'SupportDesk 4.3';
        $string = "";
        $pro = urlencode($productVersion);
        $dom = urlencode($rootserver);
        $ipv = urlencode($_SERVER['REMOTE_ADDR']);
        $mai = urlencode($user_data['txtAdminEmail']);
        $string = "pro=$pro&dom=$dom&ipv=$ipv&mai=$mai";
        $contents = "no";
        $file = @fopen("http://www.iscripts.com/installtracker.php?$string", 'r');
        if ($file) {
            $contents = @fread($file, 8192);
        }

        $installed = true;


    }
}

//check current directory permissions
foreach ($directories as $dir) {
    $permission = ServerConfig::fileWritable($dir, str_replace("../","", $dir));
    if (!$permission['status']) {
        $error = true;
		$serverPermission="true";
        $error_message .= $permission['message'];
    }
}

if ($installed) {
    header("location: upgrade_success.php");
    exit;
}

/*
$lines = file($oldSettingsFile);
function get_value_of($name){
    global $lines;
    for($i=1; $i < count($lines);$i++){
    @list($key, $val) = @explode('=', @trim($lines[$i]) );
    if(trim($key) == trim($name))
        return str_replace(";",'',str_replace("'",'',$val));
    }
}
*/

$upgraderTitle = 'iScripts SupportDesk Upgrader';
$productName = 'SupportDesk';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $upgraderTitle; ?></title>
    </head>
    <script type="text/javascript" src="../scripts/jquery.min.js"></script>
    <script type="text/javascript" src="../scripts/install.js"></script>
    <link href="css/install.css" rel="stylesheet" type="text/css" />
    <body>
        <div class="header_row">
            <div class="header_container  sitewidth">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td width="23%" align="left" ><img src="<?php echo BASE_URL; ?>images/logo.png" alt="Logo"></td>
                        <td width="77%" align="right">
                            <h4><?php echo $upgraderTitle; ?></h4>
                            <div align="center" id="items_top_area">
                                &nbsp;&nbsp;
                                <a title="OnlineInstallationManual" href="#" onClick="window.open('<?php echo BASE_URL; ?>docs/supportdesk.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation manual</strong></a> |
                                <a title="Readme" href="#" onClick="window.open('<?php echo BASE_URL; ?>Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a> |
                                <a title="If you have any difficulty, submit a ticket to the support department" href="#" onClick="window.open('http://www.iscripts.com/support/postticketbeforeregister.php','','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd,resizable=yes');">
                                <strong>Get Support</strong></a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="css/spacer.gif" width="1" height="5"></td>
            </tr>
        </table>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>

                <td width="76%" valign="top" height ="400">
                    <!-- Here's where I want my views to be displayed -->
                    <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td>
                                <!--------Installer starts------------------------->

                                <!--items display area start -->
                                <?php
                                if ($serverSettings == "FAILURE") {
                                    ?>
                                    <table width="100%" border=0 align="center">
                                        <?php
                                        foreach ($serverCurrentSettings as $settings) {
                                            $span_class = $settings['flag'] ? "install_value_ok" : "install_value_fail";
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $settings['feature']; ?> <span class="<?php echo $span_class; ?>"><?php echo $settings['setting']; ?></span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="install_value_fail">Fatal errors detected.  Please correct the above red items and reload.</span>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php
                                } else if (!$installed) {
                                    ?>
                                    <table width="80%" border="0" align="center">
                                        <tr>
                                            <td align="center" ><b><font size="1">
                                                    <div align="justify" >
                                                        <br>
                                                        <font color="#F4700E" size="+1">Thank you for choosing <?php echo $productName;?>&nbsp;</font> <br><br>
                                                        <font color="#000000" size="2">To complete this upgradation process, please enter the details below.</font>
                                                    </div>

                                                    </font></b>
                                            </td>
                                        </tr>
                                        <?php if ($post_flag) { ?>
                                            <tr>
                                                <td align=center class="message" >
                                                    <div align="left" class="text_information">
                                                        <br>
                                                        <font color="#FF0000"><?php echo $perm_msg; ?></font><br>
                                                        <font color="#FF0000"><?php echo $error_message . '<br/>' . $message; ?></font><br>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class=maintext align="left" >
                                                Note: All Fields Are Mandatory.
                                                <br>
                                                <form name="frmInstall" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                    <br>

                                                    <FIELDSET>
                                                        <LEGEND class='block_class'>File Permissions</LEGEND>
                                                        <table width=85% border="0" cellpadding="2" cellspacing="2" class=maintext>
                                                            <tr>
                                                                <td colspan="2" align="left">
                                                                    <b>
                                                                        <?php if ($serverPermission==true) { ?>
                                                                            <?php echo $productName;?> requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br/><br/>
                                                                            For security reasons, it is best to create a separate FTP user account with access to the <?php echo $productName;?> upgradation only and not the entire web server. Your host can assist you with this.
                                                                            If you have difficulties completing upgradation without these credentials, please click "I would provide permissions manually" to do it yourself.<br/><br/>
                                                                         <?php } ?>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                            <?php if ($serverPermission==true)  { ?>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP username</td>
                                                                    <td width="61%" align=left>
                                                                        <input name="FTPusername"  id="FTPusername" type="text" size="50" value="<?php echo htmlentities($user_data['FTPusername']); ?>">								
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP password</td>
                                                                    <td width="61%" align=left>
                                                                        <input name="FTPpassword"  id="FTPpassword" type="password" size="50" value="<?php echo htmlentities($user_data['FTPpassword']); ?>">
                                                                    </td>
                                                                </tr>
                                                                <?php if ($serverPermission==true) { ?>
                                                                    <tr>
                                                                        <td colspan="2" align="left">
                                                                            <input type="checkbox" name="auto_set" id="auto_set" onclick="divToggle(this)" /> &nbsp; I would provide permissions manually
                                                                        </td>
                                                                    </tr>                                                
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="2" align="left">
                                                                        <b>File permissions are OK.</b>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                        <?php if ($serverPermission==true) { ?>
                                                            <div id="err_div" style="display:none">
                                                                <fieldset>
                                                                    <legend>Directories/Files List</legend>
                                                                    <?php echo $error_message; ?>
                                                                </fieldset>
                                                            </div>
                                                        <?php } ?>
                                                    </FIELDSET>
                                                    <br>
                                                    <br>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="center">
                                                                <input type="submit" name="btnContinue" value="Continue" class="buttn_admin">
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- DO NOT REMOVE -->
                                                    <input type="hidden" name="upgraderCheck" id="upgraderCheck" value="1" />
                                                    <!-- ------------- -->
                                                </form>	
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="installr_footer"></div>
    </body>
</html>