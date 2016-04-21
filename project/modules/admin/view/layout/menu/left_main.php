<div class="nav_bar_container">
    <div class="nav_bar_top"></div>
    <div class="nav_bar_bgr">
        <ul class="nav_bar">
          <?php  if(PageContext::$response->activeLeftMenu=='home') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    } ?>
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>">Home</a></li>
            
            <?php if(in_array('Settings', $this->leftMenuArr)) { 
                if(PageContext::$response->activeLeftMenu=='Settings') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
            
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>settings">Settings</a></li>
            <?php } 
            if(PageContext::$response->activeLeftMenu=='Site Admin') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
                    
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>index/adminusers">Site Admins</a></li>
            
            <?php if(in_array('Module Management', $this->leftMenuArr)) { 
                if(PageContext::$response->activeLeftMenu=='Modules') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
           
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>index/module">Modules</a></li>
            <?php } ?>

            
            <?php if(in_array('Role Management', $this->leftMenuArr)) {
                if(PageContext::$response->activeLeftMenu=='Roles') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
           
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>role">Roles</a></li>
            <?php } ?>
            <?php if(in_array('Service Category', $this->leftMenuArr)) {
                if(PageContext::$response->activeLeftMenu=='Service Category') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>

         <!--   <li><a href="<?php echo ConfigUrl::base()?>plan">Plans</a></li>
            <li><a href="<?php echo ConfigUrl::base()?>plan/packages">Plan Packages</a></li>-->
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>plan/purchasecategory">Service Categories</a></li>
     <!--       <li><a href="<?php echo ConfigUrl::base()?>plan/purchasecategorydetails">Plan Purchase Category Detail</a></li>-->
            <?php } ?>
            <?php if(in_array('Products', $this->leftMenuArr)) {
                $style = (PageContext::$response->activeLeftMenu=='Products') ? 'class="active_menu"' : '';
            ?>            
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base(); ?>products">Products</a></li> 
            <?php } ?>

            
            
         


            <?php if(in_array('Users', $this->leftMenuArr)) { 
                if(PageContext::$response->activeLeftMenu=='Users') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
           
           
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>index/users" >Users</a></li>
            <?php } ?>


            <?php if(in_array('Invoices', $this->leftMenuArr)) { ?>
            <?php $style = (PageContext::$response->activeLeftMenu=='Invoice')? 'class="active_menu"' : ''; ?>
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>invoice">Invoices</a></li>
            <?php } ?>

            <?php if(in_array('Coupons', $this->leftMenuArr)) { 
                  if(PageContext::$response->activeLeftMenu=='Coupons') {
                    $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
            
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>coupon">Coupons</a></li>
            <?php } ?>
           
            <?php if(in_array('Reports', $this->leftMenuArr)) { 
                if(PageContext::$response->activeLeftMenu=='Reports') {
                        $style='class="active_menu"';
                    }else{
                        $style='';
                    }?>
            
            <li <?php echo $style; ?>><a href="<?php echo ConfigUrl::base()?>reports">Reports</a></li>
            <?php } ?>

            <?php if(in_array('Contents', $this->leftMenuArr)) {  
                if(PageContext::$response->activeLeftMenu=='cms') {
                        $cmsstyle='class="active_menu"';
                 }else if(PageContext::$response->activeLeftMenu=='emailcontent') {
                        $emailContentstyle='class="active_menu"';
                 }else if(PageContext::$response->activeLeftMenu=='emailcontentmgmt') {
                        $emailContentMgmtstyle='class="active_menu"';
                 }else{
                        $cmsstyle = '';
                        $emailContentstyle = '';
                        $emailContentMgmtstyle = '';
                 }?>
            
            <li <?php echo $cmsstyle; ?>><a href="<?php echo ConfigUrl::base()?>index/cms">Page Contents</a></li>
            <li <?php echo $emailContentstyle; ?>><a href="<?php echo ConfigUrl::base()?>index/emailcontent">Email Contents</a></li>
            <li <?php echo $emailContentMgmtstyle; ?>><a href="<?php echo ConfigUrl::base()?>index/emailsettings">Email Templates</a></li>

            <?php } ?>
            
            <li><a href="<?php echo BASE_URL; ?>support/admin">SupportDesk</a></li>
            
            <li><a href="<?php echo BASE_URL; ?>forum/admin.php">Forum</a></li>
            
            <?php //if(isset($_SESSION['adminUser']['userID']) && !empty($_SESSION['adminUser']['userID'])) { ?>
            <!-- <li><a href="<?php //echo ConfigUrl::base()?>login/logout">Logout</a></li> -->
            <?php // } ?>
        </ul>
    </div>
    <div class="nav_bar_bottom"></div>
</div>