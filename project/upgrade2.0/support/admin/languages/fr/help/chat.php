<html><head>
   <title>Chat</title>
   <meta name="generator"  content="HelpMaker.net" >
   <meta name="keywords"  content="Topic 1," ><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><?php error_reporting(E_ALL ^ E_NOTICE);    include("../../../includes/session.php");      include("../../../../config/settings.php");   if( !isset($INSTALLED))         	header("location:../../../../install/index.php") ;     include("../../../includes/functions/dbfunctions.php");           include("../../../includes/functions/impfunctions.php");             /*ini_set('magic_quotes_runtime',0);*/  if (get_magic_quotes_gpc()) {               	$_POST = array_map('stripslashes_deep', $_POST);               	$_GET = array_map('stripslashes_deep', $_GET);               	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);             }        if(!isset($_SERVER['REQUEST_URI'])) {      	if(isset($_SERVER['SCRIPT_NAME']))       		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];      	else       		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];        			if($_SERVER['QUERY_STRING']){      $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING']; }     }                         if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){                  $_SP_language = "en";          }else{                  $_SP_language = $_SESSION["sess_language"];          }                   include("../../../languages/".$_SP_language."/main.php");          include("../../../includes/main_smtp.php");                  $conn = getConnection();    function FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613){     preg_match("/^(http:\/\/)?([^\/]+)/i",$RD7A9632D7A0B3B4AC99AAFB2107A2613, $R2BC3A0F3554F7C295CD3CC4A57492121);     $RADA370F97D905F76B3C9D4E1FFBB7FFF = $R2BC3A0F3554F7C295CD3CC4A57492121[2];     $R74A7D124AAF5D989D8BDF81867C832AC = 0;     $RA7B9A383688A89B5498FC84118153069 = strlen($RADA370F97D905F76B3C9D4E1FFBB7FFF);     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      if($RF5687F6BBE9EC10202A32FA6C037D42B == ".")       $R74A7D124AAF5D989D8BDF81867C832AC = $R74A7D124AAF5D989D8BDF81867C832AC + 1;     }     $R14AFFF8F3EA02262F39E2785944AAF6F = explode('.',$RADA370F97D905F76B3C9D4E1FFBB7FFF);     $R7CC58E1ED1F92A448A027FD22153E078 = strtolower(substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, -7));         $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = "";     $R368D5A631F1B03C79555B616DDAC1F43 = array('.com.uk','kids.us','kids.uk','.com.au','.com.br','.com.pl','.com.ng','.com.ar','.com.ve',             '.com.ng','.com.mx','.com.cn');     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = in_array($R7CC58E1ED1F92A448A027FD22153E078, $R368D5A631F1B03C79555B616DDAC1F43);         if(!$RF413F06AEBBCEF5E1C8B1019DEE6FE6B) {      if(count($R14AFFF8F3EA02262F39E2785944AAF6F) == 1){       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $RADA370F97D905F76B3C9D4E1FFBB7FFF;      }else if((count($R14AFFF8F3EA02262F39E2785944AAF6F) > 1) && (strlen(substr($R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-2],0,38)) > 2)){       preg_match("/[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }else{       preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);       $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];      }     }else      $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-3];         $R10870E60972CEA72E14A11D115E17EA5 = explode('.',$RF877B1AAD1B2CBCDEC872ADF18E765B7);     $RD48CAD37DBDD2B2F8253B59555EFBE03   = strtoupper(trim($R10870E60972CEA72E14A11D115E17EA5[0]));          return $RD48CAD37DBDD2B2F8253B59555EFBE03;    }    function F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613){     if(F12DE84D0D1210BE74C53778CF385AA4D($RD7A9632D7A0B3B4AC99AAFB2107A2613))      return true;     $RD7A9632D7A0B3B4AC99AAFB2107A2613  = FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RB5719367F67DC84F064575F4E19A2606 =  getLicense();         $RFDFD105B00999E2642068D5711B49D5D  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, 0, 3);     $RA6CC906CDD1BAB99B7EB044E98D68FAE  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, -3,3);         $R8439A88C56A38281A17AE2CE034DB5B7  =  substr($RB5719367F67DC84F064575F4E19A2606, 0, 3);     $R254A597F43FF6E1BE7E3C0395E9409D4 =  substr($RB5719367F67DC84F064575F4E19A2606, 3, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE  =  substr($RB5719367F67DC84F064575F4E19A2606, -3,3);          $R254A597F43FF6E1BE7E3C0395E9409D4 = FCE67EB692054EBB3F415F8AF07562D82($R254A597F43FF6E1BE7E3C0395E9409D4, 3);     $RDE2A352768EABA0E164B92F7ACA37DEE = FCE67EB692054EBB3F415F8AF07562D82($RDE2A352768EABA0E164B92F7ACA37DEE, 3);         $R705EE0B4D45EEB1BC55516EB53DF7BCE  = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6,            'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10,'K' => 11,'L' => 12,            'M' => 13,'N' => 14,'O' => 15,'P' => 16,'Q' => 17,'R' => 18,            'S' => 19,'T' => 20,'U' => 21,'V' => 22,'W' => 23,'X' => 24,            'Y' => 25,'Z' => 26,'1' => 1, '2' => 2, '3' => 3, '4' => 4,            '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '0' => 0);     $RA7B9A383688A89B5498FC84118153069 = strlen($RD7A9632D7A0B3B4AC99AAFB2107A2613);     $RA5694D3559F011A29A639C0B10305B51 = 0;     for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {      $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);      $RA5694D3559F011A29A639C0B10305B51 = $RA5694D3559F011A29A639C0B10305B51 + $R705EE0B4D45EEB1BC55516EB53DF7BCE[$RF5687F6BBE9EC10202A32FA6C037D42B];     }     if($RA5694D3559F011A29A639C0B10305B51 != ($R8439A88C56A38281A17AE2CE034DB5B7 - 25))      return false;     else if(strcmp($RFDFD105B00999E2642068D5711B49D5D,$R254A597F43FF6E1BE7E3C0395E9409D4) != 0)      return false;     else if(strcmp($RA6CC906CDD1BAB99B7EB044E98D68FAE,$RDE2A352768EABA0E164B92F7ACA37DEE) != 0)      return false;     else      return true;    }    function FCE67EB692054EBB3F415F8AF07562D82($R8409EAA6EC0CE2EA307354B2E150F8C2, $R68EAF33C4E51B47C7219F805B449C109) {     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = strrev($R8409EAA6EC0CE2EA307354B2E150F8C2);     return $RF413F06AEBBCEF5E1C8B1019DEE6FE6B;    }    function F12DE84D0D1210BE74C53778CF385AA4D($R5E4A58653A4742A450A6F573BD6C4F18){     if (preg_match("/^[0-9].+$/", $R5E4A58653A4742A450A6F573BD6C4F18)){      return true;     }else      return false;    }    $R8FF184E9A1491F3EC1F61AEB9A33C033 = "invalidlicense.php";    $RD7A9632D7A0B3B4AC99AAFB2107A2613 = strtoupper(trim($_SERVER['HTTP_HOST']));    if($RD7A9632D7A0B3B4AC99AAFB2107A2613 == 'LOCALHOST' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == '127.0.0.1'){ ;     }else if(!F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613)) {     header("Location:$R8FF184E9A1491F3EC1F61AEB9A33C033");     exit;    }     include("../../../../includes/constants.php"); include("../../../includes/headsettings.php"); ?> </head>
<body bgcolor="FFFCEA" >
<table width="100%"  border="0"  cellspacing="0"  cellpadding="2"  class="header_row" >
  <tr>
    <td align="left" >
      <div align="left" ><font face="Verdana"  size="4" ><span style="font-size:14pt" >Chat</span></font><font color="#010101" ></font></div>

    </td>
    <td align="right" >
      <font face="Arial"  size="2" >
        <a href="maintenance.htm"><span id="result_box" lang="fr">pr&eacute;c&eacute;dente</span></a>      </font>    </td>
  </tr></table>
<hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Section Chat est l'endroit o&ugrave; l'administrateur peut discuter avec les utilisateurs / clients et d'autres staffs.Administrator pouvez activer / d&eacute;sactiver la fonction de chat en direct dans les param&egrave;tres de configuration g&eacute;n&eacute;rale</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">L'administrateur peut configurer les param&egrave;tres de chat dans la section "Param&egrave;tres Chat" des param&egrave;tres area.Chat admin peut &ecirc;tre faite Soci&eacute;t&eacute; sage. S&eacute;lectionnez la soci&eacute;t&eacute; dans laquelle les param&egrave;tres de chat &agrave; applied.Then configurer comme vous selon vos besoins</span> .</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Il permet d'afficher une liste d'ic&ocirc;nes chat &agrave; partir de laquelle vous pouvez s&eacute;lectionner une ic&ocirc;ne de chat pour cette soci&eacute;t&eacute;</span>. </span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Vous pouvez activer / d&eacute;sactiver la fonction note l'op&eacute;rateur dans le chat en direct en entrant Oui / Non</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Section Chat Code Snippet affiche un code de chat HTML pour cette soci&eacute;t&eacute;. Vous pouvez utiliser ce code o&ugrave; vous voulez l'application de chat utilisateur d'&ecirc;tre mis en &oelig;uvre. Si vous copiez et collez ce code dans votre page Web de la soci&eacute;t&eacute;, cela va afficher l'ic&ocirc;ne de chat que vous avez s&eacute;lectionn&eacute; avant</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Les utilisateurs peuvent lancer l'application de chat en cliquant sur ce icon.You chat sera en mesure de liste, ajouter et modifier des messages pr&eacute;enregistr&eacute;s de l'Administrateur &agrave; utiliser dans le Live Chat. La recherche de messages conserve est &eacute;galement possible</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Le lien &laquo;chat logs 'affichera tous les logs de chat. Vous pouvez rechercher les journaux en entrant une date de deux temps gamme. Vous pouvez &eacute;galement imprimer les journaux de chat</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Vous pouvez lancer la fen&ecirc;tre de chat en cliquant sur &#8203;&#8203;le lien chat Lancement de la zone d'admin. Puis la fen&ecirc;tre de chat popup o&ugrave; vous pouvez faire vos op&eacute;rations de chat</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Au bas de la fen&ecirc;tre de chat, il ya un onglet section.The onglets premi&egrave;res listes de tous les personnels en ligne et disponible pour les utilisateurs de chat. Si l'administrateur souhaite discuter avec l'un des b&acirc;tons cot&eacute;es / utilisateurs, cliquez sur le bouton 'Chat' qui est dans le dernier champ de la ligne sp&eacute;cifique. La demande sera envoy&eacute;e &agrave; l'utilisateur s&eacute;lectionn&eacute; ou staff.The deuxi&egrave;me onglet r&eacute;pertorie toutes les demandes de chat attente qui sont envoy&eacute;s par l'onglet Administrator.The troisi&egrave;me liste de tous les chats qui sont transf&eacute;r&eacute;s par l'onglet Historique chat Administrator.The listes le chat enti&egrave;re histoire de l'administrateur. l'onglet Informations utilisateur liste les utilisateurs qui sont actuellement en visite la page Web de l'entreprise. Voici des informations telles que l'adresse IP de la machine du visiteur, la page visit&eacute;e par l'utilisateur etc Si l'administrateur veut lancer &agrave; l'utilisateur d'avoir une conversation avec lui, cliquez sur le bouton correspondant &agrave; cette ligne particuli&egrave;re de la &laquo;Inviter Chat". Puis automatiquement, le visiteur de chat obtiendrez une alerte pour le Chat</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Apr&egrave;s l'envoi d'une demande de chat, si l'utilisateur / du personnel &agrave; l'autre bout accepte la demande, une fen&ecirc;tre popup sera affich&eacute;e avec le nom du personnel dans le volet gauche de la fen&ecirc;tre de chat. S&eacute;lectionnez la fen&ecirc;tre popup et commencer &agrave; discuter avec le personnel / utilisateur en entrant votre texte chat dans la zone de texte fourni dans la fen&ecirc;tre de chat. Les styles de police, comme couleur de police, police et taille de la police peuvent &eacute;galement &ecirc;tre appliqu&eacute;s sur le texte de chat. Vous pouvez appuyer sur 'Entr&eacute;e' ou cliquez sur le bouton envoyer, apr&egrave;s la saisie du texte de chat pour envoyer it.If un utilisateur ou envoyer des requ&ecirc;tes du personnel &agrave; l'autre du personnel pour l'utilisateur, une fen&ecirc;tre popup avec un bouton "Accepter" viendra dans la volet gauche de la fen&ecirc;tre de chat. Ainsi, si nous voulons accepter la demande, cliquez sur le bouton "Accepter" de cette window.Option notamment popup pour transf&eacute;rer un chat &agrave; l'autre du personnel est possible. Si l'administrateur souhaite transf&eacute;rer l'un des chats, il est la manipulation &agrave; l'autre du personnel, cliquez sur l'ic&ocirc;ne &laquo;Transfert de chat" de la fen&ecirc;tre de chat. Ensuite, une fen&ecirc;tre popup va venir avec une liste d'&eacute;tats-majors et un bouton &laquo;Transf&eacute;rer Chat". Cliquez sur le bouton correspondant pour le personnel &agrave; laquelle le chat doit &ecirc;tre transf&eacute;r&eacute;. Puis automatiquement, le chat sera transf&eacute;r&eacute; &agrave; ce que le personnel en particulier</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">La principale caract&eacute;ristique de chat en direct est le partage de bureau &agrave; distance. Si le personnel veut des utilisateurs, ordinateur de bureau &agrave; partager avec lui, demander &agrave; l'utilisateur de partager son desktop.If les utilisateurs de bureau est pr&ecirc;t &agrave; partager, l'administrateur peut cliquer sur l'ic&ocirc;ne repr&eacute;sentant Desktop Share 'pour voir le bureau de l'utilisateur</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></font></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font color="#010101" ></font></div>

</body></html>
