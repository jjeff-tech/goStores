<html><head>
   <title>Configure</title>
   <meta name="generator"  content="HelpMaker.net" >
   <meta name="keywords"  content="Topic 1," ><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><?php error_reporting(E_ALL ^ E_NOTICE);    include("../../../includes/session.php");      include("../../../../config/settings.php");   if( !isset($INSTALLED))         	header("location:../../../../install/index.php") ;     include("../../../includes/functions/dbfunctions.php");           include("../../../includes/functions/impfunctions.php");             /*  ini_set('magic_quotes_runtime',0); */  if (get_magic_quotes_gpc()) {               	$_POST = array_map('stripslashes_deep', $_POST);               	$_GET = array_map('stripslashes_deep', $_GET);               	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);             }        if(!isset($_SERVER['REQUEST_URI'])) {      	if(isset($_SERVER['SCRIPT_NAME']))       		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];      	else       		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];        			if($_SERVER['QUERY_STRING']){      $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING']; }     }                         if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){                  $_SP_language = "en";          }else{                  $_SP_language = $_SESSION["sess_language"];          }                   include("../../../languages/".$_SP_language."/main.php");          include("../../../includes/main_smtp.php");                  $conn = getConnection();    function FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613){     preg_match("/^(http:\/\/)?([^\/]+)/i",$RD7A9632D7A0B3B4AC99AAFB2107A2613, $R2BC3A0F3554F7C295CD3CC4A57492121);     $RADA370F97D905F76B3C9D4E1FFBB7FFF = $R2BC3A0F3554F7C295CD3CC4A57492121[2];     $R74A7D124AAF5D989D8BDF81867C832AC = 0;     $RA7B9A383688A89B5498FC84118153069 = strlen($RADA370F97D905F76B3C9D4E1FFBB7FFF);     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      if($RF5687F6BBE9EC10202A32FA6C037D42B == ".")       $R74A7D124AAF5D989D8BDF81867C832AC = $R74A7D124AAF5D989D8BDF81867C832AC + 1;     }     $R14AFFF8F3EA02262F39E2785944AAF6F = explode('.',$RADA370F97D905F76B3C9D4E1FFBB7FFF);     $R7CC58E1ED1F92A448A027FD22153E078 = strtolower(substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, -7));         $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = "";     $R368D5A631F1B03C79555B616DDAC1F43 = array('.com.uk','kids.us','kids.uk','.com.au','.com.br','.com.pl','.com.ng','.com.ar','.com.ve',             '.com.ng','.com.mx','.com.cn');     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = in_array($R7CC58E1ED1F92A448A027FD22153E078, $R368D5A631F1B03C79555B616DDAC1F43);         if(!$RF413F06AEBBCEF5E1C8B1019DEE6FE6B) {      if(count($R14AFFF8F3EA02262F39E2785944AAF6F) == 1){       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $RADA370F97D905F76B3C9D4E1FFBB7FFF;      }else if((count($R14AFFF8F3EA02262F39E2785944AAF6F) > 1) && (strlen(substr($R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-2],0,38)) > 2)){       preg_match("/[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }else{       preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }     }else      $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-3];         $R10870E60972CEA72E14A11D115E17EA5 = explode('.',$RF877B1AAD1B2CBCDEC872ADF18E765B7);     $RD48CAD37DBDD2B2F8253B59555EFBE03   = strtoupper(trim($R10870E60972CEA72E14A11D115E17EA5[0]));          return $RD48CAD37DBDD2B2F8253B59555EFBE03;    }    function F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613){     if(F12DE84D0D1210BE74C53778CF385AA4D($RD7A9632D7A0B3B4AC99AAFB2107A2613))      return true;     $RD7A9632D7A0B3B4AC99AAFB2107A2613  = FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RB5719367F67DC84F064575F4E19A2606 =  getLicense();         $RFDFD105B00999E2642068D5711B49D5D  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, 0, 3);     $RA6CC906CDD1BAB99B7EB044E98D68FAE  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, -3,3);         $R8439A88C56A38281A17AE2CE034DB5B7  =  substr($RB5719367F67DC84F064575F4E19A2606, 0, 3);     $R254A597F43FF6E1BE7E3C0395E9409D4 =  substr($RB5719367F67DC84F064575F4E19A2606, 3, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE  =  substr($RB5719367F67DC84F064575F4E19A2606, -3,3);          $R254A597F43FF6E1BE7E3C0395E9409D4 = FCE67EB692054EBB3F415F8AF07562D82($R254A597F43FF6E1BE7E3C0395E9409D4, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE = FCE67EB692054EBB3F415F8AF07562D82($RDE2A352768EABA0E164B92F7ACA37DEE, 3);         $R705EE0B4D45EEB1BC55516EB53DF7BCE  = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6,            'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10,'K' => 11,'L' => 12,            'M' => 13,'N' => 14,'O' => 15,'P' => 16,'Q' => 17,'R' => 18,            'S' => 19,'T' => 20,'U' => 21,'V' => 22,'W' => 23,'X' => 24,            'Y' => 25,'Z' => 26,'1' => 1, '2' => 2, '3' => 3, '4' => 4,            '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '0' => 0);     $RA7B9A383688A89B5498FC84118153069 = strlen($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RA5694D3559F011A29A639C0B10305B51 = 0;     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      $RA5694D3559F011A29A639C0B10305B51 = $RA5694D3559F011A29A639C0B10305B51 + $R705EE0B4D45EEB1BC55516EB53DF7BCE[$RF5687F6BBE9EC10202A32FA6C037D42B];     }     if($RA5694D3559F011A29A639C0B10305B51 != ($R8439A88C56A38281A17AE2CE034DB5B7 - 25))      return false;     else if(strcmp($RFDFD105B00999E2642068D5711B49D5D,$R254A597F43FF6E1BE7E3C0395E9409D4) != 0)      return false;     else if(strcmp($RA6CC906CDD1BAB99B7EB044E98D68FAE,$RDE2A352768EABA0E164B92F7ACA37DEE) != 0)      return false;     else      return true;    }    function FCE67EB692054EBB3F415F8AF07562D82($R8409EAA6EC0CE2EA307354B2E150F8C2, $R68EAF33C4E51B47C7219F805B449C109) {     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = strrev($R8409EAA6EC0CE2EA307354B2E150F8C2);     return $RF413F06AEBBCEF5E1C8B1019DEE6FE6B;    }    function F12DE84D0D1210BE74C53778CF385AA4D($R5E4A58653A4742A450A6F573BD6C4F18){     if (preg_match("/^[0-9].+$/", $R5E4A58653A4742A450A6F573BD6C4F18)){      return true;     }else      return false;    }    $R8FF184E9A1491F3EC1F61AEB9A33C033 = "invalidlicense.php";    $RD7A9632D7A0B3B4AC99AAFB2107A2613 = strtoupper(trim($_SERVER['HTTP_HOST']));    if($RD7A9632D7A0B3B4AC99AAFB2107A2613 == 'LOCALHOST' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == '127.0.0.1'){ ;     }else if(!F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613)) {     header("Location:$R8FF184E9A1491F3EC1F61AEB9A33C033");     exit;    }     include("../../../../includes/constants.php"); include("../../../includes/headsettings.php"); ?> </head>
<body bgcolor="FFFCEA" >
<table width="100%"  border="0"  cellspacing="0"  cellpadding="2"  class="header_row" >
  <tr>
    <td align="left" >
      <div align="left" ><span id="result_box" lang="fr">configurer</span><font color="#010101" ></font></div>

    </td>
    <td align="right" >
      <font face="Arial"  size="2" >
        <a href="introduction.htm"><span id="result_box" lang="fr">pr&eacute;c&eacute;dente</span></a>&nbsp;&nbsp;<a href="languages.htm"><span id="result_box" lang="fr">Suivant</span></a>      </font>    </td>
  </tr></table>
<hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><span id="result_box" lang="fr">Les options de configuration g&eacute;n&eacute;rale comprennent:</span><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Url du site: L'url du site que vous installez le logiciel helpdesk</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><span id="result_box" lang="fr">URL HelpDesk: l'URL o&ugrave; vous installez le logiciel helpdesk. Cela peut &ecirc;tre n'importe o&ugrave; sous la racine. Une installation typique url sera http://www.yoursite.com/support ou http://www.yoursite.com/helpdesk</span></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Max. Messages par page: Cette option est de contr&ocirc;ler le nombre de correspondances qui s'est pass&eacute; pour un billet. La valeur par d&eacute;faut est fix&eacute; &agrave; 30, vous pouvez augmenter / diminuer cette valeur pour avoir l'effet sur &#8203;&#8203;l'&eacute;cran d'affichage pour un billet d'admin / staff / user</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Le temps entre les messages: Cette option est d'emp&ecirc;cher l'inondation des billets dans des billets. Cette valeur repr&eacute;sente le temps entre deux messages suivants par le m&ecirc;me utilisateur. Ainsi, si vous augmentez cet intervalle, vous pouvez contr&ocirc;ler efficacement le spam affichages</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><span id="result_box" lang="fr">Langue par d&eacute;faut: Dans une configuration multi lingue o&ugrave; votre helpdesk prend en charge plusieurs langues, cette option d&eacute;cide quelle langue &agrave; &ecirc;tre affich&eacute;s par d&eacute;faut. G&eacute;n&eacute;ralement, ce sera l'anglais</span></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><span id="result_box" lang="fr">Activer Choix de la langue utilisateur: Cela permet de s&eacute;lectionner les langues s'il ya plus d'un langues prises en charge</span></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Voir billets dans la commande: Cette option est de contr&ocirc;ler l'ordre de la correspondance s'est produite sur un billet. S&eacute;lectionnez message le plus ancien d'abord pour que la correspondance sera affich&eacute;e dans l'ordre croissant de date [premi&egrave;re correspondance s'est produite sera affich&eacute; en premier]. S&eacute;lection "message le plus r&eacute;cent d'abord&raquo; affichera les correspondances dans l'ordre d&eacute;croissant de date [derni&egrave;re correspondance s'est produite sera affich&eacute; en premier] sur l'&eacute;cran d'affichage pour un billet d'admin / staff / user</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Autoriser le verrouillage automatique de billets: Si cette option est activ&eacute;e, chaque fois qu'un r&eacute;ponses du personnel pour un billet r&eacute;cemment ouvert (ie., pour la premi&egrave;re fois), le billet est automatiquement verrouill&eacute;e avec le personnel qui l'a ouvert comme son propri&eacute;taire. (Une fois le billet est verrouill&eacute;, seul le propri&eacute;taire du billet peuvent faire d'autres op&eacute;rations sur elle</span>.)</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Faut-admin V&eacute;rifiez Mod&egrave;les: Cette option peut &ecirc;tre utilis&eacute;e pour restreindre les mod&egrave;les post&eacute; par le personnel. Les mod&egrave;les affich&eacute;s et enregistr&eacute;s par le personnel peut &ecirc;tre approuv&eacute;e par l'administrateur et que les inscriptions retenues seront disponibles pour une utilisation par le personnel</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Faut-admin V&eacute;rifiez la Base de connaissances: L'activation de cette option, les entr&eacute;es de base de connaissances par le personnel post&eacute; doit &ecirc;tre approuv&eacute; par l'administrateur pour les utilisateurs / employ&eacute;s pour afficher les entr&eacute;es</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Si l'utilisateur / du personnel Activit&eacute;s &ecirc;tre enregistr&eacute;: L'activation de cette option, toutes les activit&eacute;s de l'utilisateur / du personnel seront enregistr&eacute;es, qui pourrait &ecirc;tre consid&eacute;r&eacute; par la section journal d'activit&eacute; respectifs</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Tournez Tuyauterie Courriel: activation de cette option [avec 'redirecteurs de courriers &eacute;lectroniques "bon pour le service par courriel], les utilisateurs peuvent poster des billets en envoyant des courriels &agrave; l'adresse email d&eacute;partement. La d&eacute;sactivation de cette option &eacute;vitera aux utilisateurs de poster des billets en envoyant des courriels, m&ecirc;me si le &laquo;redirecteurs de courriers &eacute;lectroniques sont mis en</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font color="#010101" ></font></div><div align="left" ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Si l'utilisateur &ecirc;tre enregistr&eacute; pour afficher des billets? : L'activation de cette option, les utilisateurs doivent &ecirc;tre enregistr&eacute;s avant de poster un billet</span>.</span></font><font color="#010101" ></font></div>
<div align="left" ><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Faut-utilisateur soit authentifi&eacute;? : Activez cette option, un email sera envoy&eacute; &agrave; l'utilisateur inscrit avec le lien d'authentification</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Si les r&egrave;gles message soit appliqu&eacute;e? : L'activation de cette option, le billet doit &ecirc;tre attribu&eacute; &agrave; un personnel particulier que le titre de billets correspondant aux r&egrave;gles</span>.</span></font><font color="#010101" ></font></div>
<div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Activer Live Chat? : Vous pouvez activer / d&eacute;sactiver le module de chat en direct dans le supportdesk</span>. </span></font><font color="#010101" ></font></div>
<div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Activer messagerie SMTP? : L'activation de cette option, tous les mails seront envoyer par le serveur SMTP</span></span></font></div>
<div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Serveur SMTP: nom du serveur SMTP</span></span></font></div>
<div align="left" ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Port: num&eacute;ro du port SMTP</span></span></font></div>
<div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

</body></html>
