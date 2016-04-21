<?php 
if(defined('HTML5_ENABLED') && HTML5_ENABLED==1){?>
<!DOCTYPE html>
<?php }else{ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if(FAVICON){?>
<link rel="icon" type="image/png" href="<?php  echo ConfigUrl::root(); ?>project/styles/images/<?php echo FAVICON; ?>">
<?php }?>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="description" content="<?php echo META_DES; ?>" />
<meta name="keywords" content="<?php echo META_KEYWORDS; ?>" />
<meta name="author" content="" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>

<title><?php echo ((PageContext::$metaTitle!='')?PageContext::$metaTitle:META_TITLE); ?></title>

<!-- Style Sheets -->
<link rel="stylesheet" href="<?php echo ConfigUrl::root()?>public/styles/jquery-ui-1.8.23.custom.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="<?php echo ConfigUrl::root()?>public/styles/fw.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="<?php echo ConfigUrl::root();?>project/styles/app.css" type="text/css" media="screen" title="default" />


<?php
if(CONTROLLER=='cms' || CONTROLLER=='admin' )
{ ?>

	<link href="<?php echo ConfigUrl::root();?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<?php 	
}else { ?>
<link href="<?php echo ConfigUrl::root();?>public/bootstrap/css/bootstrap.css" rel="stylesheet">
<?php } ?>

<link href="<?php echo ConfigUrl::root();?>project/styles/new_style.css" rel="stylesheet">


<!-- Add StyleSheets -->
<?php 
if(PageContext::$styleObj){
foreach(PageContext::$styleObj->urls as $url){
?>
<?php if (preg_match('/http:/', $url)) {?>
<link rel="stylesheet" href="<?php echo $url;?>" type="text/css" />
<?php }else {?>
<link rel="stylesheet" href="<?php echo ConfigUrl::root();?>project/styles/<?php echo $url;?>" type="text/css" />
<?php  }
}}
?>


<!-- Add Theme BasedStyleSheets -->
<?php 
if(PageContext::$themeStyleObj){
foreach(PageContext::$themeStyleObj->urls as $url){
?>
<link rel="stylesheet" href="<?php PageContext::printThemePath();?>css/<?php echo $url;?>" type="text/css" />
<?php  
}}
?>
<!-- IE Specific CSS  -->

 <!--[if IE 8]>
<style type="text/css">
.placeholder { color: #aaa!important; }
</style>
<![endif]-->

<!--[if IE 9]>
<style type="text/css">
.placeholder { color: #aaa!important; }
</style>
<![endif]-->


<!-- Add JS Vars -->
<script type="text/javascript">
<?php 
	if(PageContext::$jsVarsObj){
		foreach(PageContext::$jsVarsObj->jsvar as $jsvar){ 
			echo 'var '.$jsvar->variable.' = "'.$jsvar->value.'";';
		}
	} 
?>
</script>
<!-- JS Files -->
<?php if(PageContext::$includeLatestJquery){?>
<script src="<?php echo ConfigUrl::root()?>public/js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="<?php echo ConfigUrl::root()?>public/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<?php }?>
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php //echo ConfigUrl::root()?>public/js/jquery-1.8.0.min.js" type="text/javascript"></script>
-->
<script src="<?php echo ConfigUrl::root()?>public/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script src="<?php echo ConfigUrl::root();?>public/js/jquery.blockUI.js" type="text/javascript"></script>
<script src="<?php echo ConfigUrl::root()?>public/js/fw.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>project/js/jquery.metadata.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>project/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo ConfigUrl::root();?>project/js/app.js" type="text/javascript"></script>

<script src="<?php echo ConfigUrl::root();?>public/bootstrap/js/bootstrap.js"></script>

<?php if(PageContext::$enableBootStrap){?>
<script src="<?php echo ConfigUrl::root();?>public/bootstrap/js/cms/bootstrap.min.js"></script>
<?php }?>
   <?php if(PageContext::$enableFusionchart){?>
<script src="<?php echo ConfigUrl::root();?>public/fusioncharts/JSClass/FusionCharts.js" type="text/javascript"></script>
<?php }?>
<?php if(PageContext::$enableFCkEditor){?>
<script src="<?php echo ConfigUrl::root();?>public/fckeditor/fckeditor.js" type="text/javascript"></script>
<?php }?>
<!-- Add Scripts -->
<?php 
if(PageContext::$scriptObj){
foreach(PageContext::$scriptObj->urls as $url){
?>

<?php if ( (preg_match('/http:/', $url)) ||(preg_match('/https:/', $url))) {?>
<script src="<?php echo $url;?>" type="text/javascript"></script>
<?php }else {?>
<script src="<?php echo ConfigUrl::root();?>project/js/<?php echo $url;?>" type="text/javascript"></script>
<?php }
}}
?>
<!-- Head Code Snippet -->
<?php if(PageContext::$headerCodeSnippet)echo PageContext::$headerCodeSnippet;?>
</head>
<body class='<?php if(PageContext::$body_class)  echo PageContext::$body_class; ?>' id="<?php if(PageContext::$body_id)  echo PageContext::$body_id; ?>">
<?php 

if(DYNAMIC_THEME_ENABLED==true &&PageContext::$isCMS==false ){
	echo PageContext::renderCurrentTheme();	
}else{
	echo $this->_layout;
}

?>
<!-- Footer Code Snippet -->
<?php if(PageContext::$footerCodeSnippet)echo PageContext::$footerCodeSnippet;?>

<script>
$('.dropdown-toggle').dropdown();
</script>

</body>
</html>