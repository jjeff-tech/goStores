<div class="main_container_inner">
		
		<!-- Header starts -->
		<div class="header_container">
                    <div class="header_contents">
                        <div class="header_left">
                        <h1 class="logo"><a href="<?php echo BASE_URL; ?>" title="iScripts GoStores">iScripts GoStores</a></h1>
                        </div>

                        <div class="logout_container"> 
                         
                            <div class="logout">
                                <div class="site_home">
                            <a href="<?php echo BASE_URL; ?>" target="_blank"><img src="<?php echo BASE_URL; ?>project/styles/images/admin/toplink_home.gif" alt="" class="header_image_border"/><br/>
                            Site Home</a>
                                </div>
                                <div  class="site_logout">
                        <?php if (!empty($_SESSION['adminUser']['userID'])) { ?>
                         
                        <a href="<?php echo ConfigUrl::base()?>login/logout"><img src="<?php echo BASE_URL; ?>project/styles/images/admin/toplink_logout.gif" alt="" class="header_image_border"/><br/>Logout</a>
                       
                            <?php } ?>
                                </div>
                         </div>
                             <div  class="r_float header_text">
                             <?php if (!empty($_SESSION['adminUser']['userID'])) { ?>
                            Logged in as <?php  echo $_SESSION['adminUser']['username']; ?>
                        <?php } ?>
                             </div>
                    </div>
		</div>
		<!-- Header ends -->
		
		<!-- Center content starts -->
		<div class="inner_center_container">
        	<div class="left_column">
		<?php 
                if(!empty($this->leftMenu))
                {
                  include 'menu/'.$this->leftMenu.'.php';
                }
                ?>
		</div>
		
		<div class="right_column">
		 <?php echo $this->_content; ?>
		</div>
		<div style="clear:both;"></div>
		</div>
		
		<!-- Center content ends -->
		
		
		<!-- footer starts -->
		<div class="footer_container">
		<div class="footer_content">
                    &copy; iScriptsCloud. All rights reserved.
                </div>
		</div>		
		
		<!-- footer  ends -->
		
		
		</div>
		
</div>

<!--<table width="1191" border="1">
  <tr>
    <td colspan="2">Admin Module </td>
  </tr>
  <tr>
      <td width="166" height="213" valign="top"><!--Left Menu <br />-->
	<!--<a href="<?php echo ConfigUrl::base()?>index/register">Register</a><br /> 

	  	</td>
    <td width="967" valign="top">
   </td>
  </tr>
  <tr>
    <td height="23" colspan="2">Page Footer </td>
  </tr>
</table>-->


