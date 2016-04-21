<?php
$cmsData 	= PageContext::$response->cmsData;
$cmsArray = array();
foreach($cmsData as $value){
		array_push($cmsArray, $value->cms_name);
}
//echopre($cmsData);
//echopre($cmsArray);
$company_email 		= Utils::getSettingsData('company_email');
$company_phone 		= Utils::getSettingsData('company_phone');
$company_address 	= Utils::getSettingsData('company_address');
?>
<footer>
<div class="footer_row1">
<div class="container">
<div class="row">
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
	<img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/footer_logo.png">
<p>
Create your own e-commerce platform enabling businesses and individuals to create and host online stores quickly. 	 In this model, customers will purchase a hosting plan and set up an online store within minutes.
</p>
</div>
<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 footer-menu">
	<ul>
		<li><a href="<?php echo ConfigUrl::base(); ?>">Home</a></li>
		<?php if(trim($_SERVER["ORIG_PATH_INFO"]) <> "" && trim($_SERVER["ORIG_PATH_INFO"]) <> "/index"){ ?>
		<li><a href="<?php echo ConfigUrl::base(); ?>index#ourfeatures">Features</a></li>
		<?php }else{ ?>
		<li><a href="<?php echo ConfigUrl::base(); ?>index#ourfeatures" id="">Features</a></li>
		<?php } ?>
		<!-- <?php if(trim($_SERVER["ORIG_PATH_INFO"]) <> "" && trim($_SERVER["ORIG_PATH_INFO"]) <> "/index"){ ?>
		<li><a href="<?php echo ConfigUrl::base(); ?>index#partners">Partners</a></li>
		<?php }else{ ?>
		<li><a href="<?php echo ConfigUrl::base(); ?>index#partners" id="scrollLink3">Partners</a></li>
		<?php } ?> -->

      <li><a href="<?php echo ConfigUrl::base(); ?>screenshots">Screenshots</a></li>



		
	</ul>
	</div>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 footer-menu">
	<ul>
	<!-- 	<li><a href="<?php echo ConfigUrl::base(); ?>dropshipping.pdf" target="_blank">Dropshipping</a></li>
		<li><a href="<?php echo ConfigUrl::base(); ?>card_processing.pdf" target="_blank">Card Processing</a></li> -->
		<?php
		if(is_array($cmsArray) && count($cmsArray) > 0){
				if(in_array("customization",$cmsArray)){
		?>
		<li><a href="<?php echo ConfigUrl::base(); ?>customization">Customization</a></li>
		<?php
				}
		}		
		if(is_array($cmsArray) && count($cmsArray) > 0){
				if(in_array("policy",$cmsArray)){
		?>
		<li><a href="<?php echo ConfigUrl::base(); ?>policy">Policy</a></li>
		<?php
				}
		}
		?>
		<li><a <?php if(PageContext::$response->selectedLink == 'storedemo'){?> <?php } ?> href="<?php echo BASE_URL; ?>storedemo">Demo</a></li>

 <li><a <?php if(PageContext::$response->selectedLink == 'templates'){?> <?php } ?> href="<?php echo BASE_URL; ?>templates">Templates</a></li>

 <li><a href="<?php echo ConfigUrl::base(); ?>disclaimernotice">Disclaimer notice</a></li>
	</ul>
	</div>
	<div class="col-lg-1 col-md-1  col-sm-1 col-xs-4 footer-menu">
	<ul>
		<?php
		if(is_array($cmsArray) && count($cmsArray) > 0){
				if(in_array("howitworks",$cmsArray)){
		?>
		<li><a href="<?php echo ConfigUrl::base(); ?>howitworks">How It Works</a></li>
		<?php
				}
		}
		if(is_array($cmsArray) && count($cmsArray) > 0){
				if(in_array("faq",$cmsArray)){
		?>
		<li><a href="<?php echo ConfigUrl::base(); ?>faq">FAQ</a></li>
		<?php
				}
		}
		?>
		<!-- <li><a href="<?php echo ConfigUrl::base(); ?>#">Blog</a></li> -->
		<li><a href="<?php echo ConfigUrl::base(); ?>contactus">Contact</a></li>
		<li><a href="<?php echo ConfigUrl::base(); ?>plan">Plans</a></li>
		 <li><a href="<?php echo ConfigUrl::base(); ?>help">Help</a></li>
<!--      <li><a href="<?php echo ConfigUrl::base(); ?>disclaimernotice">Disclaimer notice</a></li>-->
	</ul>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 footer-menu">
	<div class="full-width f-pad">
			 <?php
			 if(trim($company_address) <> ""){ echo trim(stripslashes($company_address)); }
			 if(trim($company_phone) <> ""){ ?>
     	 <div class="full-width f-pad"><img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/phone-icon.png"><?php echo trim($company_phone); ?></div>
		 	 <?php }if(trim($company_email) <> ""){ ?>
       <div class="full-width"><img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/e-mail.png"><a href="mailto:<?php echo trim($company_email); ?>" class="mail-link"><?php echo trim($company_email); ?></a></div>
		 	 <?php } ?>
	</div>
	</div>

<div class="clear"></div>
</div>
</div>
</div>

<div class="footer_row2">
<div class="container">
<div class="row">
Powered by iScripts GoStores. A premium product from iScripts.com
</div>
</div>
</div>
</div>
<div class="clear"></div>
</footer>
