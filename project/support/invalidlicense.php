<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			*/
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>             		              |
// |          										                      |
// +----------------------------------------------------------------------+
        include("./config/settings.php");
        include("./includes/functions/dbfunctions.php");
        include("./includes/functions/miscfunctions.php");
        include("./includes/functions/impfunctions.php");
	    $conn = getConnection();		
?>
<html>
<head>
	<title>
		Invalid License
	</title>
</head>
<body>
<table width="100%"  border="0">
  <tr>
    <td width="17%">&nbsp;</td>
    <td width="72%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"><br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        	<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				Invalid License. Please contact <?php echo getAdminMail();?>
			</font> <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
