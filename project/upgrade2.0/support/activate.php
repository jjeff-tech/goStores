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

	if($_GET["id"]!="")
		$id = $_GET["id"];
	else
		$id=0;
?>

<body onLoad="document.frmActivate.submit()">
<form name="frmActivate" action="activated.php" method="post">
<input type="hidden" name="id" value="<?php echo $id;?>"> 
</form>
</body>