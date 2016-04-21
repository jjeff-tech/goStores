<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//echo '<pre>'; print_r($kbData); echo '</pre>';

if($kbData["vMetaTage_desc"]){
    $metaTagDesc = $kbData["vMetaTage_desc"];
}else{
    $metaTagDesc = trim_the_string($kbData["vKBTitle"],75).' - '.trim_the_string(strip_tags($kbData["tKBDesc"]),220);
}
$metaTagKeyword = $kbData["vMetaTage_keyword"] == ""?$kbData["vCatDesc"]:$kbData["vMetaTage_keyword"];

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
$_SESSION["sess_cssurl"] = $_SESSION["sess_cssurl"] == ""?"styles/AquaBlue/style.css":$_SESSION["sess_cssurl"];
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta content="<?php echo $metaTagDesc;?>" name="description">
<meta content="<?php echo $metaTagKeyword;?>" name="keywords">
<link href="<?php echo SITE_URL.$_SESSION["sess_cssurl"]; ?>" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>

