<div class="main_container_inner">		
		<!-- Header starts -->
                <div class="header_container">
                    <div class="header_contents">
                        <div class="header_left">
                        <h1 class="logo"><a href="<?php echo BASE_URL; ?>" title="iScripts GoStores">iScripts GoStores</a></h1>
                        </div>

                        <div class="logout_container">
                          <?php if (!empty($_SESSION['adminUser']['userID'])) { ?>
                            Logged in as <?php  echo $_SESSION['adminUser']['username']; ?>
                        <?php } ?>
                            <div class="logout">
                                <div class="site_home">
                            <a href="<?php echo BASE_URL; ?>" target="_blank"><img src="<?php echo BASE_URL; ?>project/styles/images/admin/toplink_home.gif" alt="" class="header_image_border"/><br/>
                            Site Home</a>
                                </div>
                                <div  class="r_float">
                        <?php if (!empty($_SESSION['adminUser']['userID'])) { ?>

                        <a href="<?php echo ConfigUrl::base()?>login/logout"><img src="<?php echo BASE_URL; ?>project/styles/images/admin/toplink_logout.gif" alt="" class="header_image_border"/><br/>Logout</a>

                            <?php } ?>
                                </div>
                         </div>
                    </div>
                    </div>
		</div>
		<!-- Header ends -->
		
		<!-- Center content starts -->
		<div class="inner_center_container">				
				<!--<div class="left_column">
						
						
				</div>-->
		
		<div class="login_container">
		
		
		 <?php echo $this->_content; ?>
		
		
		
		
		</div>
		<div style="clear:both;"></div>
		</div>
		
		<!-- Center content ends -->
		
		
		<!-- footer starts -->
		<div class="footer_container">
		<div class="footer_content">abc</div>
		</div>		
		
		<!-- footer  ends -->
				
		</div>
		

