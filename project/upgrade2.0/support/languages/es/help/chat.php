<?php
include("../../../includes/session.php");
include("../../../config/settings.php");
include("../../../includes/functions/dbfunctions.php");
include("../../../includes/functions/miscfunctions.php");
include("../../../includes/functions/impfunctions.php");
/*ini_set('magic_quotes_runtime', 0);*/
        if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}         if
(!isset($_SERVER['REQUEST_URI'])) {
    if (isset($_SERVER['SCRIPT_NAME']))                 $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];             else                 $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}
if
(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("../../../languages/" . $_SP_language . "/main.php");
$conn = getConnection();
?>
<html><head>
        <title>charla</title>
        <meta name="generator"  content="HelpMaker.net" >
        <meta name="keywords"  content="Topic 1," >
<?php
include("../../../includes/constants.php");
include("../../../includes/headsettings.php");
?>

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
