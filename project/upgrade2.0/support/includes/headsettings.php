<?php

?>
<style type="text/css">
<!--
body {
      /*  font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        background-color:#EDEBEB;
		margin-top:0px;
		margin-left:10px;
		margin-right:10px;
		margin-bottom:0px;*/
}
-->
</style>
<?php
//$_SESSION["sess_cssurl"] = "";
$_SESSION["sess_cssurl"] = $_SESSION["sess_cssurl"] == ""?"styles/AquaBlue/style.css":$_SESSION["sess_cssurl"];
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo SITE_URL.$_SESSION["sess_cssurl"]; ?>" rel="stylesheet" type="text/css">
