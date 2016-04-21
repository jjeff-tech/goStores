<div class="container-fluid" style="margin-top:100px;">
	<div class="content border-fourside row-fixed span8 offset4"  align="left">
		<div class="framework_logo lfloat cmslogo">
		<?php
		if(PageContext::$response->cmsSettings['site_logo']){
				echo PageContext::$response->siteLogo;
		}
		else{
		?>
		<img src="<?php echo BASE_URL.PageContext::$response->cmsSettings['admin_logo']; ?>">
	  <?php } ?>
		</div>
		<div style="clear:both;"></div>
		<div class="admin-login-inner">
		<form class="form-horizontal" method="POST" action="<?php echo ConfigUrl::root();?>cms">
		<span class="legend">&nbsp;</span>
			<?php if(PageContext::$response->errorMsg){ ?>
						<div class="alert alert-error"><?php echo PageContext::$response->errorMsg;?> </div>
			<?php }?>

		  <div class="full-width admin-login">
		      <input type="text" id="username" class="form-control" name="username"  placeholder="Username">
		    
		  </div>
		  <div class="full-width admin-login">
		      <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password">
		  
		  </div>
		  <div class="full-width admin-login">
		    
		      <button type="submit" name="submit" value="submit" class="more-btn">Sign in</button>
		   
		  </div>
		  <span class="legend"></span>
		</form>
	</div>
		<div class="footer row-fluid">
			<p class="muted" style="color:#555555 !important;"><small><?php if(PageContext::$response->cmsSettings['admin_copyright']) { echo PageContext::$response->cmsSettings['admin_copyright']; } else { ?>&copy;Armia Systems <?php  } ?></small></p>
		</div>
	</div>
</div>
