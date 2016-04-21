<?php
if($_SESSION["sess_support_cssurl"] == ""){
    $_SESSION["sess_support_cssurl"] = "styles/AquaBlue/style.css";
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Meta Content" />
<meta name="keywords" content="Meta Keywords" />
<link href="<?php echo SITE_URL.$_SESSION["sess_cssurl"]; ?>" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>