<?php
include("../../../includes/session.php");
include("../../../config/settings.php");
include("../../../includes/functions/dbfunctions.php");
include("../../../includes/functions/miscfunctions.php");
include("../../../includes/functions/impfunctions.php");

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
    if($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING'];
    }
}

if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ) {
    $_SP_language = "en";
}else {
    $_SP_language = $_SESSION["sess_language"];
}
include("../../../languages/".$_SP_language."/main.php");
$conn = getConnection();
?>
<html><head>
        <title>introducción</title>
        <meta name="generator"  content="HelpMaker.net" >
        <meta name="keywords"  content="Topic 1," ><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include("../../../includes/constants.php");
include("../../../includes/headsettings.php"); ?>
    </head>
    <body bgcolor="FFFCEA" >
        <table width="100%"  border="0"  cellspacing="0"  cellpadding="2"  class="header_row" >
            <tr>
                <td align="left" >
                    <div align="left" ><font face="Verdana"  color="#010101"  size="4" ><span style="font-size:14pt" >introducción</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        &nbsp;&nbsp;<a href="features.htm">próximo</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >El iScripts soporto es un sistema integrado para la gestión del cliente
                    preguntas, respuestas y comunicaciones resultantes de estas consultas y
                    problemas relacionados con la ayuda de una serie de herramientas e instalaciones agrupadas
                    todo el sistema de gestión de ticket como el sistema de base de conocimientos,
                    sistema de recordatorio, de correo y sistema de mensajería, correo electrónico y el sistema de tuberías
                    por mencionar algunos.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Los usuarios potenciales del sistema son los usuarios (clientes), personal (técnicos),
                y los administradores.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Los usuarios / clientes se comunican con el sistema para conseguir sus problemas y
            preguntas resuelto. Por lo general, buscar en la correspondiente sección de preguntas frecuentes para ver
            si un problema similar (y solución) está en la lista ya. Si el usuario
            no es capaz de encontrar alguna coincidencia, s / mensajes que un billete con la necesaria
            detalles. El personal (técnicos) responder a las entradas con un valor predefinido
            respuesta o una recién creada, de acuerdo con la situación. el principal
            funcionalidad del sistema es la siguiente comunicación de aquí para allá entre el
            usuario y el personal. En el caso de un equipo es incapaz de resolver un problema s / él puede
            escalar el problema al administrador.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Otra característica importante del sistema de apoyo es iScripts LiveChat. Además de los
        sistema de gestión de entradas, los clientes pueden chatear con los operadores para cualquier
        consultas. Instalación entre conversación con el operador también está disponible en este sistema. si
        necesario, los clientes pueden compartir sus computadora de escritorio con el operador
        quien está conversando través de la función Escritorio remoto compartido. este
        les ayudará a resolver sus problemas con facilidad. Los clientes pueden seleccionar un determinado
        departamento de la compañía y es capaz de conversar con el personal de los que
        departamento en particular. </span></font><font color="#010101" ></font></div><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

    </body></html>
