<style type="text/css">
<!--
body {
        /*font-family: Arial, Helvetica, sans-serif;
        font-size: 9px;
        background-color:#EDEBEB;
		margin-top:0px;
		margin-left:10px;
		margin-right:10px;
		margin-bottom:0px;*/
}
-->
</style>
<?php

if($_SESSION["sess_cssurl"] == ""){
$_SESSION["sess_cssurl"] = "styles/AquaBlue/style.css";
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo SITE_URL.($_SESSION["sess_cssurl"]); ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SITE_URL ?>scripts/jquery.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>