<?php       include("../../../includes/session.php");         include("../../../config/settings.php");         include("../../../includes/functions/dbfunctions.php");         include("../../../includes/functions/miscfunctions.php");         include("../../../includes/functions/impfunctions.php");          /*  ini_set('magic_quotes_runtime',0); */          if (get_magic_quotes_gpc()) {             $_POST = array_map('stripslashes_deep', $_POST);             $_GET = array_map('stripslashes_deep', $_GET);             $_COOKIE = array_map('stripslashes_deep', $_COOKIE);         }         if (!isset($_SERVER['REQUEST_URI'])) {             if (isset($_SERVER['SCRIPT_NAME']))                 $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];             else                 $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];             if ($_SERVER['QUERY_STRING']) {                 $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];             }         }          if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {             $_SP_language = "en";         } else {             $_SP_language = $_SESSION["sess_language"];         }         include("../../../languages/" . $_SP_language . "/main.php");         $conn = getConnection();          function FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613) {             preg_match("/^(http:\/\/)?([^\/]+)/i", $RD7A9632D7A0B3B4AC99AAFB2107A2613, $R2BC3A0F3554F7C295CD3CC4A57492121);             $RADA370F97D905F76B3C9D4E1FFBB7FFF = $R2BC3A0F3554F7C295CD3CC4A57492121[2];             $R74A7D124AAF5D989D8BDF81867C832AC = 0;             $RA7B9A383688A89B5498FC84118153069 = strlen($RADA370F97D905F76B3C9D4E1FFBB7FFF);             for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {                 $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);                 if ($RF5687F6BBE9EC10202A32FA6C037D42B == ".")                     $R74A7D124AAF5D989D8BDF81867C832AC = $R74A7D124AAF5D989D8BDF81867C832AC + 1;             } $R14AFFF8F3EA02262F39E2785944AAF6F = explode('.', $RADA370F97D905F76B3C9D4E1FFBB7FFF);             $R7CC58E1ED1F92A448A027FD22153E078 = strtolower(substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, -7));             $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = "";             $R368D5A631F1B03C79555B616DDAC1F43 = array('.com.uk', 'kids.us', 'kids.uk', '.com.au', '.com.br', '.com.pl', '.com.ng', '.com.ar', '.com.ve', '.com.ng', '.com.mx', '.com.cn');             $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = in_array($R7CC58E1ED1F92A448A027FD22153E078, $R368D5A631F1B03C79555B616DDAC1F43);             if (!$RF413F06AEBBCEF5E1C8B1019DEE6FE6B) {                 if (count($R14AFFF8F3EA02262F39E2785944AAF6F) == 1) {                     $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $RADA370F97D905F76B3C9D4E1FFBB7FFF;                 } else if ((count($R14AFFF8F3EA02262F39E2785944AAF6F) > 1) && (strlen(substr($R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F) - 2], 0, 38)) > 2)) {                     preg_match("/[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);                     $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];                 } else {                     preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);                     $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];                 }             }else                 $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F) - 3]; $R10870E60972CEA72E14A11D115E17EA5 = explode('.', $RF877B1AAD1B2CBCDEC872ADF18E765B7);             $RD48CAD37DBDD2B2F8253B59555EFBE03 = strtoupper(trim($R10870E60972CEA72E14A11D115E17EA5[0]));             return $RD48CAD37DBDD2B2F8253B59555EFBE03;         }          function F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613) {             if (F12DE84D0D1210BE74C53778CF385AA4D($RD7A9632D7A0B3B4AC99AAFB2107A2613))                 return true; $RD7A9632D7A0B3B4AC99AAFB2107A2613 = FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613);             $RB5719367F67DC84F064575F4E19A2606 = getLicense();             $RFDFD105B00999E2642068D5711B49D5D = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, 0, 3);             $RA6CC906CDD1BAB99B7EB044E98D68FAE = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, -3, 3);             $R8439A88C56A38281A17AE2CE034DB5B7 = substr($RB5719367F67DC84F064575F4E19A2606, 0, 3);             $R254A597F43FF6E1BE7E3C0395E9409D4 = substr($RB5719367F67DC84F064575F4E19A2606, 3, 3);             $RDE2A352768EABA0E164B92F7ACA37DEE = substr($RB5719367F67DC84F064575F4E19A2606, -3, 3);             $R254A597F43FF6E1BE7E3C0395E9409D4 = FCE67EB692054EBB3F415F8AF07562D82($R254A597F43FF6E1BE7E3C0395E9409D4, 3);             $RDE2A352768EABA0E164B92F7ACA37DEE = FCE67EB692054EBB3F415F8AF07562D82($RDE2A352768EABA0E164B92F7ACA37DEE, 3);             $R705EE0B4D45EEB1BC55516EB53DF7BCE = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13, 'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '0' => 0);             $RA7B9A383688A89B5498FC84118153069 = strlen($RD7A9632D7A0B3B4AC99AAFB2107A2613);             $RA5694D3559F011A29A639C0B10305B51 = 0;             for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {                 $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);                 $RA5694D3559F011A29A639C0B10305B51 = $RA5694D3559F011A29A639C0B10305B51 + $R705EE0B4D45EEB1BC55516EB53DF7BCE[$RF5687F6BBE9EC10202A32FA6C037D42B];             } if ($RA5694D3559F011A29A639C0B10305B51 != ($R8439A88C56A38281A17AE2CE034DB5B7 - 25))                 return false; else if (strcmp($RFDFD105B00999E2642068D5711B49D5D, $R254A597F43FF6E1BE7E3C0395E9409D4) != 0)                 return false; else if (strcmp($RA6CC906CDD1BAB99B7EB044E98D68FAE, $RDE2A352768EABA0E164B92F7ACA37DEE) != 0)                 return false; else                 return true;         }          function FCE67EB692054EBB3F415F8AF07562D82($R8409EAA6EC0CE2EA307354B2E150F8C2, $R68EAF33C4E51B47C7219F805B449C109) {             $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = strrev($R8409EAA6EC0CE2EA307354B2E150F8C2);             return $RF413F06AEBBCEF5E1C8B1019DEE6FE6B;         }          function F12DE84D0D1210BE74C53778CF385AA4D($R5E4A58653A4742A450A6F573BD6C4F18) {             if (preg_match("/^[0-9].+$/", $R5E4A58653A4742A450A6F573BD6C4F18)) {                 return true;             }else                 return false;         }  $R8FF184E9A1491F3EC1F61AEB9A33C033 = "invalidlicense.php";         $RD7A9632D7A0B3B4AC99AAFB2107A2613 = strtoupper(trim($_SERVER['HTTP_HOST']));         if ($RD7A9632D7A0B3B4AC99AAFB2107A2613 == 'LOCALHOST' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == '127.0.0.1') { ;         } else if (!F10984563791DEB243A5DC2A8AC17FB84($RD7A9632D7A0B3B4AC99AAFB2107A2613)) {             header("Location:$R8FF184E9A1491F3EC1F61AEB9A33C033");             exit;         }
?>
 <html><head>
    <title>charla</title>
   <meta name="generator"  content="HelpMaker.net" >
   <meta name="keywords"  content="Topic 1," >
<?php
include("../../../includes/constants.php");         include("../../../includes/headsettings.php");         ?>

</head>
<table width="100%"  border="0"  cellspacing="0"  cellpadding="2"  class="header_row" >
  <tr>
    <td align="left" >
      <div align="left" ><font face="Verdana"  size="4" ><span style="font-size:14pt" >charla</span></font><font color="#010101" ></font></div>

    </td>
    <td align="right" >
      <font face="Arial"  size="2" >
        <a href="settings.htm">anterior</a>&nbsp;&nbsp;
      </font>
    </td>
  </tr></table>
<hr><div align="left"> <br> </ div> <font color="#010101"> <div align="left"> <br> </ font> </ div> color de la fuente </ font>  <div align =" left "style =" margin-left: 13 mm; margin-right : 0 mm; text-indent: 0 mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> Sección de Chat ES Que Cliente de la ONU de usuario / pueden chatear Con Los Operadores párrafo resolver SUS questions.You You can abrir la ventana de chat de Haciendo clic en Inicio <b> Chat </ b> de la zona de Usuario. A Continuación, sí abrirá Una ventana en La Que Su Nombre de Usuario y ID de Correo electrónico debidamente cumplimentado sueros. DESDE alli Se Puede Entrar un sucuestión. Also Se Puede Seleccionar ningun departamento QUE DESEA Conversar with.If El personal es seleccionado El departamento Fuera de Línea, entonces sí le Pregunto si la pregunta TIENE Que servicios Publicado Como un boleto. Si El usuario Haga clic en ACEPTAR, entonces la pregunta Que Ingrese sueros Publicado Como un boleto. </ Span> </ font> <font color="#010101"> </ font> </ div> <div align = "left "style =" margin-left: 13 mm; margin-right: 0 mm; text-indent: 0 mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> Si alguno de los ESTADOS Mayores En El departamento seleccionado no está en línea, chat MIENTRAS Que Haciendo clic en el 'ahora "Boton, Usted sueros Capaz de charlar him.In Con la ventana de chat, habra Una Sección de visualización del Estado Donde Se Puede Ver El Estado actual de la Sesión de chat. Se mostrará COMO "Llamando ..." si no està conectado Hace un Todo el personal todavia. Despues de conectarse un CUALQUIERA de los ESTADOS mayores, Se Puede ver El Estado de "Conectado un Nombre de personal de personal. Si la charla ha TERMINADO, El Estado sueros "Chat Completado" y si El personal no está en línea, Que Sera 'No hay personal es Sin conexión ". </ Span> </ font> <font color="#010101"> </ font> </ div> <div align="left" style="margin-left:13mm; margin-right:0mm; text-indent:0mm; "> tipo de letra <=" Verdana "color =" # 010101 "> <span style="font-size:10pt"> Si està conectado Hace un Todo el personal, Usted Comenzar una charlar You can Con El personal MEDIANTE la Introducción de texto de chat en la zona de El de Texto proporcionado en la Parte inferior de la ventana de chat. Despues de introducir de texto El chat, el pulso de La Tecla Enter o clic en Enviar Botón Haga elEl. </ span> </ font> <font color="#010101"> </ font> </ div> <div align="left" style="margin-left:13mm; margin-right:0mm; text-indent:0mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> Habra Una serie de iconos de como "Comparte tu Escritorio ',' Transcripción de chat Correo ',' Transcripción de Impresión", "Apoyo una Tipos Los" en 'Salir' y la instancia de parte superior, de la ventana de chat. El 'Compartir <b> Su Escritorio </ b> "Botón es de El Compartir El Escritorio del Ordenador Con El Operador. Si es necesario, El Operador podra Solicitar al Usuario Compartir Su / su Escritorio Con El párrafo Que pueda resolver SUS Problemas con facilidad. Si El Operador le pedira Que Compartir Su Escritorio, You can HACER clic en Botón Este. Se le pedira Una Confirmación. Si El Usuario Haga clic en "Sí", ésto le You can Pedir Que ejecute las Naciones Unidas Archivo </ span> </ font > <font color="#010101"> </ font> </ div> <div align="left" style="margin-left:13mm; margin-right:0mm; text-indent:0mm; "> <font face = "Verdana" color = "# 010101"> <span style="font-size:10pt"> con JRE. Si se ejecuta el archivo con el JRE, el servidor se iniciará en el sistema. De este modo el operador puede ver el escritorio desde el otro lado </ span> </ font> <font color="#010101"> </ font> </ div> <div align = "left" style = "margin-left:. 13 mm; margin-right : 0 mm; text-indent: 0 mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> El Correo 'Chat botón de transcripción 'es la de enviar el registro del chat a cualquier correo electrónico
Identificación. Si hace clic en este botón, se le pedirá que introduzca la dirección de correo electrónico a
que los registros de chat se van a enviar. Luego haga clic en el botón enviar </ span> </ font> <font color="#010101"> </ font> </ div> <div align = "left" style = "margin-left:. 13 mm; margin- derecha: 0 mm; text-indent: 0 mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> La impresión ' Chat en el botón Transcript "es para imprimir los registros de chat. </ span> </ font> <font color="#010101"> </ font> </ div> <div align =" left "style =" margin-left : 13 mm; margin-right: 0 mm; text-indent: 0 mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> ; Puedes puntuar el apoyo actual utilizando este botón. Si hace clic en este
botón, se le pedirá que seleccione una velocidad de la gama de 1-10. También puede
para escribir sus comentarios acerca de este apoyo en la sección "Comentarios". Después de
entrar en los detalles, haga clic en el botón de envío </ span> </ font> <font color="#010101"> </ font> </ div> <div align = "left" style = "margin-left:. 13 mm ; margin-right: 0 mm; text-indent: 0 mm; "> <font face="Verdana" color="#010101"> <span style="font-size:10pt"> El 'Salir' se utiliza para finalizar la sesión de conversación. </ span> </ font> <font color="#010101"> </ font> </ div> <div align = "left" style = "margin-left : 13 mm; margin-right: 0 mm; text-indent: 0 mm; "> <br> </ div> <font color="#010101"> <div align =" left "style =" margin-left: 13 mm; margen derecha: 0 mm; text-indent: 0 mm; "> </ font> <font color="#010101"> </ font> </ div> <div align="left"> <br> </ div> < color de la fuente = "# 010101"> <div align="left"> </ font> <font color="#010101"> </ font> </ div>

</body></html>