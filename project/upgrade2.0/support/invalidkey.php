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

require_once("includes/decode.php");
if(isValid(0)) {
	header("location:index.php");
	exit();
}
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
        <font size="2" face="Verdana, Arial, Helvetica, sans-serif">Invalid License.Please purchase a valid license with <a href="http://www.iscripts.com" style="text-decoration:none ">iscripts.com</a>.</font> <br>
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
