<!-- Main wrapper starts -->
<div class="outer_wrapper">

    <!-- Footer starts Here -->
     <?php
      //PageContext::renderPostAction('cloudtopmenu');
     PageContext::renderPostAction('cloudtopmenupage','index');
     ?>
    <div class="content_whitebg">
    <!-- Header ends -->
			<div class="user_login_center_container">
			<div class="container">
    		<div class="row">
			<div class="content_area_inner">
				<div class="col-xs-12 col-sm-3 col-md-3">
				 
				  <div class="nav_bar_bgr">
					<ul class="nav_bar left_navmenu">
					  <li><a href="<?php echo ConfigUrl::base(); ?>user/dashboard">Dashboard</a></li>
					<!--  <li><a href="<?php echo BASE_URL; ?>user/products">My Products</a></li>-->
					  <!--
					  <li><a href="<?php echo BASE_URL; ?>user/subscriptions">My Subscriptions</a></li> -->
					  <li><a href="<?php echo BASE_URL; ?>user/profile">My Profile</a></li>         
					  <li><a href="<?php echo BASE_URL; ?>user/payments">Billing History / Receipts</a></li>
                                          <li><a href="<?php echo BASE_URL; ?>user/settlements">Pending settlements</a></li>
					  <!--<li><a href="<?php echo BASE_URL; ?>support">SupportDesk</a></li>
					  <li><a href="<?php echo BASE_URL; ?>forum">Forum</a></li>-->
					</ul>
				  </div>
				 
				</div>
			<div class="col-xs-12 col-sm-9 col-md-9">
			<?php echo $this->_content; ?>
				<div class="clear"></div>
			</div>
			</div>
  			</div>
  		</div>	
		</div>

    
  <div style="clear:both;"></div>
</div>

    <?php
    //PageContext::renderPostAction('cloudfooter');
        PageContext::renderPostAction('cloudfooterpage','index');
   
    ?>

</div>
<!-- Main wrapper ends -->
</div>