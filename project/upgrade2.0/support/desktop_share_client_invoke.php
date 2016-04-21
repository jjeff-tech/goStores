<?php
  include_once("./includes/session.php");
  include_once("./languages/".$_SESSION["sess_language"]."/client_chat.php");
  $chatid=$_GET["cid"];
?>
 <html>
 <heade>
  <script src="http://java.com/js/deployJava.js"></script>
  <script language="javascript" type="text/javascript">
     function jreversionCheck(cid) {
	    //alert("versioncheck " + deployJava.versionCheck('1.6.0_10+'));
        if (deployJava.versionCheck('1.6.0_10+') == false) {                   
           userInput = confirm("You need the latest Java(TM) Runtime Environment. Would you like to update now?");        
           if (userInput == true) {  
              // Set deployJava.returnPage to make sure user comes back to 
              // your web site after installing the JRE
              deployJava.returnPage = location.href;
              // install latest JRE or redirect user to another page to get JRE from.
              deployJava.installLatestJRE(); 
           }
         }
		 startServer(cid);
     }
	 function startServer( cid ) {
	   window.location.href = "desktop_share_server.php?cid="+cid;	
     }
  </script>
  <link href="<?php echo($_SESSION["sess_cssurl"]);?>" rel="stylesheet" type="text/css">
 </head>
 <body onLoad="jreversionCheck(<?php echo $chatid; ?>);">
 </body>
</html>  