<?php
   $chatid=$_GET["cid"];
   if($chatid=="") 
   {
     echo "Chat Id Cannot be empty";
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
   if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
   {
     $server_ip=$_SERVER['HTTP_CLIENT_IP'];
   } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
   {
     $server_ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
   }else {
     $server_ip=$_SERVER['REMOTE_ADDR'];
   }
   $status = "active";
   /*Newly modified*/
   $sql = "select * from sptbl_desktop_share where nChatId='".addslashes($chatid)."'";
   $res = executeSelect($sql,$conn);
   if ( mysql_num_rows($res) == 0 ) {
       $sql = "insert into sptbl_desktop_share( nChatId, vClientIp, vStatus )
           Values('". addslashes($chatid) ."', '" . addslashes($server_ip) . "', '" . addslashes($status) . "')";
 //  $sql = "update sptbl_desktop_share set vClientIp='".addslashes($server_ip)."', vStatus='active' where nUserId =  '".addslashes($chatid)."' and vStatus='pending'";
   executeQuery($sql,$conn);
   }
   $sql = "select vLookUpValue from sptbl_lookup where vLookUpName = 'HelpDeskURL'";
   $result = executeSelect($sql,$conn);
   if ( mysql_num_rows($result) > 0 ) {
     $row = mysql_fetch_array($result) ;
	 $site = $row["vLookUpValue"] ;
	 $site = preg_replace('/index.php/','',$site);
   }
?>

<jnlp spec="1.0+" codebase="<?php echo $site."RDP/"?>">
	<information>
		<title>Java Remote Desktop</title>
		<vendor>Armia Systems</vendor>
		<homepage href="<?php echo $site."chat/"?>" />
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
         main-class="client"
		 width="156"
         height="125">
		 
		 <param name=id value="<?php echo $chatid ?>">
     </applet-desc>
</jnlp>



