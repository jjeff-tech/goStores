<html>
<head>
<?php
error_reporting(E_ALL ^ E_NOTICE); 
include("../../../../includes/session.php");
include("../../../../config/settings.php");
include("../../../../includes/functions/dbfunctions.php");
include("../../../../includes/functions/miscfunctions.php");
include("../../../../includes/functions/impfunctions.php");

/*ini_set('magic_quotes_runtime',0);*/

if (get_magic_quotes_gpc()) {              
	$_POST = array_map('stripslashes_deep', $_POST);              
	$_GET = array_map('stripslashes_deep', $_GET);              
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);            
}       
if(!isset($_SERVER['REQUEST_URI'])) {     
	if(isset($_SERVER['SCRIPT_NAME']))      
		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];     
	else      
		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];       
			if($_SERVER['QUERY_STRING']){      $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING']; }
    }
           
            if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){                  $_SP_language = "en";          }else{                  $_SP_language = $_SESSION["sess_language"];          }          
        include("../../../../languages/".$_SP_language."/main.php");                   
        $conn = getConnection();    function FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613){     preg_match("/^(http:\/\/)?([^\/]+)/i",$RD7A9632D7A0B3B4AC99AAFB2107A2613, $R2BC3A0F3554F7C295CD3CC4A57492121);     $RADA370F97D905F76B3C9D4E1FFBB7FFF = $R2BC3A0F3554F7C295CD3CC4A57492121[2];     $R74A7D124AAF5D989D8BDF81867C832AC = 0;     $RA7B9A383688A89B5498FC84118153069 = strlen($RADA370F97D905F76B3C9D4E1FFBB7FFF);     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      if($RF5687F6BBE9EC10202A32FA6C037D42B == ".")       $R74A7D124AAF5D989D8BDF81867C832AC = $R74A7D124AAF5D989D8BDF81867C832AC + 1;     }     $R14AFFF8F3EA02262F39E2785944AAF6F = explode('.',$RADA370F97D905F76B3C9D4E1FFBB7FFF);     $R7CC58E1ED1F92A448A027FD22153E078 = strtolower(substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, -7));         $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = "";     $R368D5A631F1B03C79555B616DDAC1F43 = array('.com.uk','kids.us','kids.uk','.com.au','.com.br','.com.pl','.com.ng','.com.ar','.com.ve',             '.com.ng','.com.mx','.com.cn');     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = in_array($R7CC58E1ED1F92A448A027FD22153E078, $R368D5A631F1B03C79555B616DDAC1F43);         if(!$RF413F06AEBBCEF5E1C8B1019DEE6FE6B) {      if(count($R14AFFF8F3EA02262F39E2785944AAF6F) == 1){       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $RADA370F97D905F76B3C9D4E1FFBB7FFF;      }else if((count($R14AFFF8F3EA02262F39E2785944AAF6F) > 1) && (strlen(substr($R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-2],0,38)) > 2)){       preg_match("/[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }else{       preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }     }else      $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-3];         $R10870E60972CEA72E14A11D115E17EA5 = explode('.',$RF877B1AAD1B2CBCDEC872ADF18E765B7);     $RD48CAD37DBDD2B2F8253B59555EFBE03   = strtoupper(trim($R10870E60972CEA72E14A11D115E17EA5[0]));          return $RD48CAD37DBDD2B2F8253B59555EFBE03;    }    function F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613){     if(F12DE84D0D1210BE74C53778CF385AA4D($RD7A9632D7A0B3B4AC99AAFB2107A2613))      return true;     $RD7A9632D7A0B3B4AC99AAFB2107A2613  = FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RB5719367F67DC84F064575F4E19A2606 =  getLicense();         $RFDFD105B00999E2642068D5711B49D5D  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, 0, 3);     $RA6CC906CDD1BAB99B7EB044E98D68FAE  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, -3,3);         $R8439A88C56A38281A17AE2CE034DB5B7  =  substr($RB5719367F67DC84F064575F4E19A2606, 0, 3);     $R254A597F43FF6E1BE7E3C0395E9409D4 =  substr($RB5719367F67DC84F064575F4E19A2606, 3, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE  =  substr($RB5719367F67DC84F064575F4E19A2606, -3,3);          $R254A597F43FF6E1BE7E3C0395E9409D4 = FCE67EB692054EBB3F415F8AF07562D82($R254A597F43FF6E1BE7E3C0395E9409D4, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE = FCE67EB692054EBB3F415F8AF07562D82($RDE2A352768EABA0E164B92F7ACA37DEE, 3);         $R705EE0B4D45EEB1BC55516EB53DF7BCE  = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6,            'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10,'K' => 11,'L' => 12,            'M' => 13,'N' => 14,'O' => 15,'P' => 16,'Q' => 17,'R' => 18,            'S' => 19,'T' => 20,'U' => 21,'V' => 22,'W' => 23,'X' => 24,            'Y' => 25,'Z' => 26,'1' => 1, '2' => 2, '3' => 3, '4' => 4,            '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '0' => 0);     $RA7B9A383688A89B5498FC84118153069 = strlen($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RA5694D3559F011A29A639C0B10305B51 = 0;     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      $RA5694D3559F011A29A639C0B10305B51 = $RA5694D3559F011A29A639C0B10305B51 + $R705EE0B4D45EEB1BC55516EB53DF7BCE[$RF5687F6BBE9EC10202A32FA6C037D42B];     }     if($RA5694D3559F011A29A639C0B10305B51 != ($R8439A88C56A38281A17AE2CE034DB5B7 - 25))      return false;     else if(strcmp($RFDFD105B00999E2642068D5711B49D5D,$R254A597F43FF6E1BE7E3C0395E9409D4) != 0)      return false;     else if(strcmp($RA6CC906CDD1BAB99B7EB044E98D68FAE,$RDE2A352768EABA0E164B92F7ACA37DEE) != 0)      return false;     else      return true;    }    function FCE67EB692054EBB3F415F8AF07562D82($R8409EAA6EC0CE2EA307354B2E150F8C2, $R68EAF33C4E51B47C7219F805B449C109) {     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = strrev($R8409EAA6EC0CE2EA307354B2E150F8C2);     return $RF413F06AEBBCEF5E1C8B1019DEE6FE6B;    }    function F12DE84D0D1210BE74C53778CF385AA4D($R5E4A58653A4742A450A6F573BD6C4F18){     if (preg_match("/^[0-9].+$/", $R5E4A58653A4742A450A6F573BD6C4F18)){      return true;     }else      return false;    }    $R8FF184E9A1491F3EC1F61AEB9A33C033 = "invalidlicense.php";    $RD7A9632D7A0B3B4AC99AAFB2107A2613 = strtoupper(trim($_SERVER['HTTP_HOST']));    if($RD7A9632D7A0B3B4AC99AAFB2107A2613 == 'LOCALHOST' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == '127.0.0.1'){ ;     }else if(!F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613)) {     header("Location:$R8FF184E9A1491F3EC1F61AEB9A33C033");     exit;    }   

        
include("../../../../includes/constants.php");
include("../../../../includes/headsettings.php"); ?>
</head>
<body class="header_row">
  <br>
  <br>
  <!-- Start of TOC -->
  <table border="0" cellspacing="2" cellpadding="0"><tr><td width="20" align=right valign=top><img src="icon1.gif" border="0"></span></td><td align=left><font face="Arial" size="2">Welcome to SupportDesk</font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="introduction.php" target="stuff" style="color:#000000;">Introduction</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="features.php" target="stuff" style="color:#000000;">Features</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="20" align=right valign=top><img src="icon1.gif" border="0"></span></td><td align=left><font face="Arial" size="2">Getting Started</font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="tickets.php" target="stuff" style="color:#000000;">Tickets</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="replies.php" target="stuff" style="color:#000000;">Replies</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="reminders.php" target="stuff" style="color:#000000;">Reminders</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="privatemessage.php" target="stuff" style="color:#000000;">Private Messages</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="users.php" target="stuff" style="color:#000000;">Users</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="personalnates.php" target="stuff" style="color:#000000;">Personal Notes</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="templates.php" target="stuff" style="color:#000000;">Templates</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="statistics.php" target="stuff" style="color:#000000;">Statistics</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="downloads.php" target="stuff" style="color:#000000;">Downloads</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="labels.php" target="stuff" style="color:#000000;">Labels</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="knowledgebase.php" target="stuff" style="color:#000000;">Knowledge base</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="preferences.php" target="stuff" style="color:#000000;">Preferences</a></font></td></tr></table>
<table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="chat.php" target="stuff" style="color:#000000;">Chat</a></font></td></tr></table>

  <!-- End of TOC -->
  <br>
  <hr>
  </font>
</body>
</html>

