<html><head>
   <title>chat</title>
   <meta name="generator"  content="HelpMaker.net" >
   <meta name="keywords"  content="" ><?php error_reporting(E_ALL ^ E_NOTICE);  include("../../../../includes/session.php"); include("../../../../config/settings.php"); include("../../../../includes/functions/dbfunctions.php"); include("../../../../includes/functions/miscfunctions.php"); include("../../../../includes/functions/impfunctions.php");  /*  ini_set('magic_quotes_runtime',0); */  if (get_magic_quotes_gpc()) {               	$_POST = array_map('stripslashes_deep', $_POST);               	$_GET = array_map('stripslashes_deep', $_GET);               	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);             }        if(!isset($_SERVER['REQUEST_URI'])) {      	if(isset($_SERVER['SCRIPT_NAME']))       		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];      	else       		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];        			if($_SERVER['QUERY_STRING']){      $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING']; }     }                         if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){                  $_SP_language = "en";          }else{                  $_SP_language = $_SESSION["sess_language"];          }                   include("../../../../languages/".$_SP_language."/main.php");                            $conn = getConnection();    function FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613){     preg_match("/^(http:\/\/)?([^\/]+)/i",$RD7A9632D7A0B3B4AC99AAFB2107A2613, $R2BC3A0F3554F7C295CD3CC4A57492121);     $RADA370F97D905F76B3C9D4E1FFBB7FFF = $R2BC3A0F3554F7C295CD3CC4A57492121[2];     $R74A7D124AAF5D989D8BDF81867C832AC = 0;     $RA7B9A383688A89B5498FC84118153069 = strlen($RADA370F97D905F76B3C9D4E1FFBB7FFF);     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      if($RF5687F6BBE9EC10202A32FA6C037D42B == ".")       $R74A7D124AAF5D989D8BDF81867C832AC = $R74A7D124AAF5D989D8BDF81867C832AC + 1;     }     $R14AFFF8F3EA02262F39E2785944AAF6F = explode('.',$RADA370F97D905F76B3C9D4E1FFBB7FFF);     $R7CC58E1ED1F92A448A027FD22153E078 = strtolower(substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, -7));         $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = "";     $R368D5A631F1B03C79555B616DDAC1F43 = array('.com.uk','kids.us','kids.uk','.com.au','.com.br','.com.pl','.com.ng','.com.ar','.com.ve',             '.com.ng','.com.mx','.com.cn');     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = in_array($R7CC58E1ED1F92A448A027FD22153E078, $R368D5A631F1B03C79555B616DDAC1F43);         if(!$RF413F06AEBBCEF5E1C8B1019DEE6FE6B) {      if(count($R14AFFF8F3EA02262F39E2785944AAF6F) == 1){       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $RADA370F97D905F76B3C9D4E1FFBB7FFF;      }else if((count($R14AFFF8F3EA02262F39E2785944AAF6F) > 1) && (strlen(substr($R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-2],0,38)) > 2)){       preg_match("/[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }else{       preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }     }else      $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-3];         $R10870E60972CEA72E14A11D115E17EA5 = explode('.',$RF877B1AAD1B2CBCDEC872ADF18E765B7);     $RD48CAD37DBDD2B2F8253B59555EFBE03   = strtoupper(trim($R10870E60972CEA72E14A11D115E17EA5[0]));          return $RD48CAD37DBDD2B2F8253B59555EFBE03;    }    function F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613){     if(F12DE84D0D1210BE74C53778CF385AA4D($RD7A9632D7A0B3B4AC99AAFB2107A2613))      return true;     $RD7A9632D7A0B3B4AC99AAFB2107A2613  = FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RB5719367F67DC84F064575F4E19A2606 =  getLicense();         $RFDFD105B00999E2642068D5711B49D5D  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, 0, 3);     $RA6CC906CDD1BAB99B7EB044E98D68FAE  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, -3,3);         $R8439A88C56A38281A17AE2CE034DB5B7  =  substr($RB5719367F67DC84F064575F4E19A2606, 0, 3);     $R254A597F43FF6E1BE7E3C0395E9409D4 =  substr($RB5719367F67DC84F064575F4E19A2606, 3, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE  =  substr($RB5719367F67DC84F064575F4E19A2606, -3,3);          $R254A597F43FF6E1BE7E3C0395E9409D4 = FCE67EB692054EBB3F415F8AF07562D82($R254A597F43FF6E1BE7E3C0395E9409D4, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE = FCE67EB692054EBB3F415F8AF07562D82($RDE2A352768EABA0E164B92F7ACA37DEE, 3);         $R705EE0B4D45EEB1BC55516EB53DF7BCE  = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6,            'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10,'K' => 11,'L' => 12,            'M' => 13,'N' => 14,'O' => 15,'P' => 16,'Q' => 17,'R' => 18,            'S' => 19,'T' => 20,'U' => 21,'V' => 22,'W' => 23,'X' => 24,            'Y' => 25,'Z' => 26,'1' => 1, '2' => 2, '3' => 3, '4' => 4,            '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '0' => 0);     $RA7B9A383688A89B5498FC84118153069 = strlen($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RA5694D3559F011A29A639C0B10305B51 = 0;     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      $RA5694D3559F011A29A639C0B10305B51 = $RA5694D3559F011A29A639C0B10305B51 + $R705EE0B4D45EEB1BC55516EB53DF7BCE[$RF5687F6BBE9EC10202A32FA6C037D42B];     }     if($RA5694D3559F011A29A639C0B10305B51 != ($R8439A88C56A38281A17AE2CE034DB5B7 - 25))      return false;     else if(strcmp($RFDFD105B00999E2642068D5711B49D5D,$R254A597F43FF6E1BE7E3C0395E9409D4) != 0)      return false;     else if(strcmp($RA6CC906CDD1BAB99B7EB044E98D68FAE,$RDE2A352768EABA0E164B92F7ACA37DEE) != 0)      return false;     else      return true;    }    function FCE67EB692054EBB3F415F8AF07562D82($R8409EAA6EC0CE2EA307354B2E150F8C2, $R68EAF33C4E51B47C7219F805B449C109) {     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = strrev($R8409EAA6EC0CE2EA307354B2E150F8C2);     return $RF413F06AEBBCEF5E1C8B1019DEE6FE6B;    }    function F12DE84D0D1210BE74C53778CF385AA4D($R5E4A58653A4742A450A6F573BD6C4F18){     if (preg_match("/^[0-9].+$/", $R5E4A58653A4742A450A6F573BD6C4F18)){      return true;     }else      return false;    }    $R8FF184E9A1491F3EC1F61AEB9A33C033 = "invalidlicense.php";    $RD7A9632D7A0B3B4AC99AAFB2107A2613 = strtoupper(trim($_SERVER['HTTP_HOST']));    if($RD7A9632D7A0B3B4AC99AAFB2107A2613 == 'LOCALHOST' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == '127.0.0.1'){ ;     }else if(!F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613)) {     header("Location:$R8FF184E9A1491F3EC1F61AEB9A33C033");     exit;    }              include("../../../../includes/constants.php"); include("../../../../includes/headsettings.php"); ?> </head>
<body bgcolor="FFFCEA" >
<table width="100%"  border="0"  cellspacing="0"  cellpadding="2" class="header_row" >
  <tr>
    <td align="left" >
      <div align="left" ><font face="Verdana"  size="4" >de chat</font><font color="#010101" ></font></div>

    </td>
    <td align="right" >
      <font face="Arial"  size="2" >
        <a href="preferences.htm">pr�c�dente</a>&nbsp;&nbsp;
      </font>
    </td>
  </tr></table>
<hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " >La 
  section Chat est o&ugrave; le personnel peut discuter avec les utilisateurs 
  / clients et autres &eacute;tats-majors.
  <p>Vous serez en mesure de liste, ajouter et modifier des messages pr&eacute;enregistr&eacute;s 
    du personnel &agrave; utiliser dans le Live Chat. La recherche de messages 
    en conserve est &eacute;galement possible.Le</p>
  <font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&lsquo;Tchat 
  journaux&rsquo; ll'encre va afficher toutes les historiques de conversation 
  de ce personnel. Vous pouvez rechercher les journaux en entrant une date de 
  deux gammes de temps. Vous pouvez &eacute;galement imprimer les journaux de 
  chat.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" >
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;</span></font>
  <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Vous 
    pouvez lancer la fen&ecirc;tre de chat en cliquant sur ??le lien chat Lancement 
    de la zone de personnel. Puis la fen&ecirc;tre de chat s'ouvrira o&ugrave; 
    vous pouvez faire vous causez des op&eacute;rations.</span></font><font color="#010101" ></font></div>
  <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Au 
    bas de la fen&ecirc;tre de chat, il ya un onglet section.The onglets premi&egrave;res 
    listes de tous les personnels en ligne et disponible pour les utilisateurs 
    de chat. Si le personnel veut chatter avec n'importe lequel des personnels 
    &eacute;num&eacute;r&eacute;s / utilisateurs, cliquez sur le&lsquo;de chat&rsquo; 
    bouton qui se trouve dans le dernier champ de la ligne sp&eacute;cifique. 
    La demande sera envoy&eacute;e &agrave; l'utilisateur s&eacute;lectionn&eacute; 
    ou le personnel.Le deuxi&egrave;me onglet liste tous les chats en attente</span></font></div>
  <font face="Verdana"  color="#010101" ><span style="font-size:10pt" >.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Le 
  troisi&egrave;me onglet liste tous les chats qui sont transf&eacute;r&eacute;s 
  par le personnel connect&eacute;.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;L'onglet 
  Historique chat listes de l'histoire de chat ensemble du personnel. L'onglet 
  Informations utilisateur liste les utilisateurs qui sont actuellement en visite 
  la page Web de l'entreprise. Voici des informations telles que l'adresse IP 
  du visiteur&rsquo;L'onglet Historique chat listes de l'histoire de chat ensemble 
  du personnel. L'onglet Informations utilisateur liste les utilisateurs qui sont 
  actuellement en visite la page Web de l'entreprise. Voici des informations telles 
  que l'adresse IP du visiteur...&lsquo;Inviter chat&rsquo; bouton correspondant 
  &agrave; cette ligne particuli&egrave;re. Puis automatiquement, le visiteur 
  de chat obtiendrez une alerte pour le Chat.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Apr&egrave;s 
  l'envoi d'une demande de chat, si l'utilisateur / du personnel &agrave; l'autre 
  bout accepte la demande, une fen&ecirc;tre pop-up s'affiche avec le nom du personnel 
  dans le volet gauche de la fen&ecirc;tre de chat. S&eacute;lectionnez la fen&ecirc;tre 
  pop-up et de commencer &agrave; discuter avec le personnel / utilisateur en 
  entrant votre texte chat dans la zone de texte fourni. Les styles de police, 
  comme couleur de police, police et taille de la police peuvent &eacute;galement 
  &ecirc;tre appliqu&eacute;s sur le texte de chat. Vous pouvez appuyer sur&lsquo;Entrez&rsquo; 
  touche ou cliquez sur le bouton envoyer apr&egrave;s avoir saisi le texte du 
  chat afin de l'envoyer.</span></font>
  <p>Si un utilisateur ou envoyer des requ&ecirc;tes du personnel &agrave; l'autre 
    du personnel pour l'utilisateur, une fen&ecirc;tre pop-up avec un</p>
  <font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&lsquo;accepter&rsquo; 
  Bouton viendra dans le volet gauche de la fen&ecirc;tre de chat. Ainsi, si nous 
  voulons accepter la demande, cliquez sur le&lsquo;accepter&rsquo; bouton de 
  la fen&ecirc;tre contextuelle particuli&egrave;re.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Option 
  de transfert d'un chat &agrave; un autre personnel est possible. Si une &eacute;quipe 
  veut transf&eacute;rer l'un des chats, il est la manipulation &agrave; l'autre 
  du personnel, cliquez sur le&lsquo;transfert de chat&rsquo; ic&ocirc;ne de la 
  fen&ecirc;tre de chat. La fen&ecirc;tre un popup viendra avec une liste des 
  &eacute;tats-majors et une&lsquo;transfert chat&rsquo; bouton. Cliquez sur le 
  bouton correspondant pour le personnel &agrave; laquelle le chat doit &ecirc;tre 
  transf&eacute;r&eacute;. Puis automatiquement, le chat sera transf&eacute;r&eacute; 
  &agrave; ce que le personnel en particulier.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<b>La 
  principale caract&eacute;ristique de chat en direct est le partage de bureau 
  &agrave; distance.</b> Si le personnel veut des utilisateurs&rsquo;,ordinateur 
  de bureau &agrave; partager avec lui, demander &agrave; l'utilisateur de partager 
  son bureau. Si le bureau des utilisateurs est pr&ecirc;te &agrave; partager, 
  le personnel peut cliquer sur le&lsquo;desktop Share&rsquo; ic&ocirc;ne pour 
  afficher l'utilisateur&rsquo;s de bureau. </span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font color="#010101" ></font></div>

</body></html>
