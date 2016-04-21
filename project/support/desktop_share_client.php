<?php
   $chatid=$_GET["chatid"];
   if($chatid=="")
   {
      echo "Chat ID Cannot be empty";
      exit;
   }
   include("./config/settings.php");
   include("./includes/functions/dbfunctions.php");
   $conn = getConnection();
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
   header("Cache-Control: no-store, no-cache, must-revalidate");
   header("Cache-control: post-check=0, pre-check=0, false");
   header("Pragma: no-cache");
   header("Content-Type: application/x-java-jnlp-file");
   echo ('<?xml version="1.0" encoding="UTF-8"?>');
   $sql = "Select vClientIp from sptbl_desktop_share where 
           nChatId = '". addslashes($chatid) ."' AND vStatus='active'";
   $result = executeSelect($sql,$conn);
   if ( mysql_num_rows($result) > 0 ) {
      $row = mysql_fetch_array($result);
	  $serverIP = trim($row["vClientIp"]);
   }
   $sql = "select vLookUpValue from sptbl_lookup where vLookUpName = 'HelpDeskURL'";
   $res = executeSelect($sql,$conn);
   if ( mysql_num_rows($res) > 0 ) {
     $row = mysql_fetch_array($res) ;
	 $site = $row["vLookUpValue"] ;
	 $site = preg_replace('/index.php/','',$site);
   }
?>


<jnlp spec="1.0+" codebase="<?php echo $site."RDP/" ;?>">
	<information>
		<title>Java Remote Desktop</title>
		<vendor>Armia Systems</vendor>
		<homepage href="<?php echo $site."chat/" ;?>"/>
		<description>Java Remote Desktop Connection</description>
	</information>
	<security>
		<all-permissions/>
	</security>
	<resources>
		<j2se version="1.6+" />
		<jar href="RDP.jar" />
	</resources>
	
	  <applet-desc 
         name="client"
         main-class="server"
         width="800"
         height="600">
		 <param name=id value="<?php echo $chatid ?>">
     </applet-desc>
</jnlp>