<?php
$sessionPageObject 	= new LibSession();
$userDetails 				= User::fetchUserProfile();
//echopre($_SERVER);echo "ORIG_PATH_INFO = ".$_SERVER["ORIG_PATH_INFO"];

$company_email = Utils::getSettingsData('company_email');
$company_phone = Utils::getSettingsData('company_phone');
?>
<div class="header_wrapper">
	<?php /* <div class="header_first_row">
		<div class="container">
			<div class="header_phnandlogin">
				<?php
				$phone = COMPANY_PHONE;
									if(!empty($phone)) {
				?>
				<div class="header_phnno">

					<p><span>CALL US :</span> <?php echo $phone;?></p>

				</div>
				<?php
				}
				?>
				<div class="dropdown loginboxdropdown left">&nbsp;</div>
			</div>
			 <?php if($sessionPageObject->get('userID')!="") { ?>
			 <div style="float:left;">Welcome <span class="user_clr"><?php echo $sessionPageObject->get('firstName'); ?></span> &nbsp; | &nbsp;
				 <a href="<?php echo ConfigUrl::base(); ?>user/dashboard">My Account </a> &nbsp; | &nbsp; <a href="<?php echo ConfigUrl::base(); ?>index/logout">Logout</a>
			 </div>
			<div class="clear"></div>
			<?php } ?>
		</div>
	</div>	*/ ?>

	<div class="header_second_row">
		<div class="container">
		<div class="row">
		<div class="col-md-3">
			<div class="logo">
				<a href="<?php echo BASE_URL; ?>">
				<img src="<?php echo SITE_LOGO;?>" alt="<?php echo SITE_NAME; ?>" class="img-responsive">
				<span><?php echo SITE_NAME; ?></span></a>
			</div>
		</div>
		<div class="col-md-9">
			<div class="full-width top-band">
			<?php
			if(LibSession::get('userID')){
					if(trim($userDetails->vFirstName) <> ""){
							echo "Welcome, ".$userDetails->vFirstName." ".$userDetails->vLastName;
					}else{
							echo "Welcome,".$userDetails->vUsername;
					}
			}else{
					if(trim($company_phone) <> ""){
							echo trim($company_phone);
					}
					if(trim($company_email) <> ""){
							echo "&nbsp;|&nbsp;";
							echo "<a href='mailto:".trim($company_email)."'>".trim($company_email)."</a>";
					}
			}
			?>
			</div>
			<!-- Navigation -->
		    <nav class="navbar topnav" role="navigation"  style="overflow:hidden;clear:both;">
		    		<div class="row">
		            <!-- Brand and toggle get grouped for better mobile display -->
		            <div class="navbar-header">
		                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		                    <span class="sr-only">Toggle navigation</span>
		                    <span class="icon-bar"></span>
		                    <span class="icon-bar"></span>
		                    <span class="icon-bar"></span>
		                </button>
		            </div>
		            <!-- Collect the nav links, forms, and other content for toggling -->
		            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		                <ul class="nav navbar-nav navbar-right">
                                    
                                    <li><a <?php if(PageContext::$response->selectedLink == 'plan'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>plan">Plans</a></li>
                                    
							<!-- <li><a <?php if(PageContext::$response->selectedLink == 'plan'){?>class="top_nav_active"<?php } ?> href="<?php// echo BASE_URL; ?>plan">PLANS</a></li> -->
							<?php  if(trim($_SERVER["ORIG_PATH_INFO"]) <> "" && trim($_SERVER["ORIG_PATH_INFO"]) <> "/index"){ ?>
<!--                                    <li><a <?php if(PageContext::$response->selectedLink == 'whoweare'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>index#whoweare" >Turn Key E-Comm</a></li>-->
							<li><a <?php if(PageContext::$response->selectedLink == 'features'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>index#ourfeatures">Features</a></li>
							<?php }else{ ?>
<!--							<li><a <?php if(PageContext::$response->selectedLink == 'whoweare'){?>class="top_nav_active"<?php } ?> href="#whoweare" id="scrollLink1">Turn Key E-Comm</a></li>-->
							<li><a <?php if(PageContext::$response->selectedLink == 'features'){?>class="top_nav_active"<?php } ?> href="#ourfeatures" id="scrollLink2">Features</a></li>
							<?php } ?>

							 <li><a <?php if(PageContext::$response->selectedLink == 'screenshots'){?>class="top_nav_active"<?php } ?> href="<?php //echo BASE_URL; ?>screenshots">Screen Shots</a></li>
							<li><a <?php if(PageContext::$response->selectedLink == 'storedemo'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>storedemo">Demo</a></li>
							
                                                        <li><a <?php if(PageContext::$response->selectedLink == 'templates'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>templates">Templates</a></li>
							<li><a <?php if(PageContext::$response->selectedLink == 'contactus'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>contactus">Contact</a></li>
							<!--<li><a href="<?php echo BASE_URL; ?>support">SUPPORT </a></li>-->
							<?php if($sessionPageObject->get('userID') == "") { ?>
							<li ><a <?php if(PageContext::$response->selectedLink == 'signup'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>signup">Sign Up</a></li>
							<?php } ?>
							<?php if($sessionPageObject->get('userID')=="") { ?>
                                                        <li class="no_margin"><a <?php if(PageContext::$response->selectedLink == 'signin'){?>class="top_nav_active"<?php } ?> href="<?php echo BASE_URL; ?>signin">Login</a></li>
<!--							<li class="no_margin">
								<button id="loginbuttonhome" class="btn bluebtn dropdown-toggle" type="button" data-toggle="dropdown">Login</button>
								<?php if($sessionPageObject->get('userID')=="") { ?>
								  <ul class="dropdown-menu">
								    <form id="loginForm" onsubmit="return loginuseraction();">
												<div class="errorBox" id="jqLoginError"><p>&nbsp;</p></div>
												<fieldset id="body">
													<fieldset>

														<input type="text" placeholder="Email Address" name="txtUsername" id="txtUsername" />
													</fieldset>
													<fieldset>

														<input type="password" placeholder="Password" name="txtPassword" id="txtPassword" />
													</fieldset>
													<input type="submit" id="login" value="Sign in" class="sign_btn_new" />
													<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>
												</fieldset>
												<span class="frgtpwd"><a href="<?php echo ConfigUrl::base(); ?>index/forgotpwd">Forgot your password?</a></span>
											</form>
								  </ul>
								  <?php } ?>
							</li>-->
							<?php } ?>
							<?php if($sessionPageObject->get('userID')!=""){ ?>
								<li class="no_margin">
									 <a href="<?php echo ConfigUrl::base(); ?>user/dashboard">My Account </a>
								 </li>
								 <li class="no_margin">
									 <a href="<?php echo ConfigUrl::base(); ?>index/logout">Logout</a>
								 </li>
							<?php } ?>
		                </ul>
		            </div>
		            <!-- /.navbar-collapse -->
		            </div>
		    </nav>
		</div>
		</div>
		<div class="clear"></div>
	</div>



	</div>
	<div class="clear"></div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$( "#scrollLink1" ).click(function( event ) {
		event.preventDefault();
		$("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 500);
	});
	$( "#scrollLink2" ).click(function( event ) {
		event.preventDefault();
		$("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 500);
	});
	$( "#scrollLink3" ).click(function( event ) {
		event.preventDefault();
		$("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 500);
	});
});
</script>
