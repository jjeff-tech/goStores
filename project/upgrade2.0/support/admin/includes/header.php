<?php
 include("./languages/".$_SP_language."/main.php");
?>
<div class="header_row">
	<div class="header_cnt sitewidth">
		
		<div class="header_left">
		<div class="mainlogo"><?php 
              $img_logo = $_SESSION["sess_logourl"];
               $img_logo =str_replace('%','%25',$img_logo);
                ?>
			<a href="<?php echo BASE_URL."index"; ?>"><img src="<?php echo $_SESSION["banner_img"]; ?>"></a>
		</div>
		<div class="clear"></div>
	</div>
	
			<div class="left">
             <?php
             include("includes/inteligentsearch.php");
             ?>
		    </div>
	
	
	<div class="header_right">
		<div class="header_info right">
			<h4><span><?php echo(htmlentities($_SESSION["sess_helpdesktitle"])); ?></span>&nbsp;-&nbsp;<?php //echo TEXT_ADMIN_MAIN?>ADMIN PANEL</h4>
			<h5 align="right">
				<?php
						if(isset($_SESSION["sess_staffname"]) and $_SESSION["sess_staffname"]!="" ){
								echo TEXT_LOGGED_IN_AS;
								echo "&nbsp;<b>".stripslashes($_SESSION["sess_staffname"])."</b>&nbsp;&nbsp;";
						}else{
						echo "&nbsp;";
						}
				?>				
				
			</h5>
		</div>
	
	</div>
	<div class="clear"></div>
        
	</div>
<div class="clear"></div>
</div>

<!-- Top menu -->
	<div class="menu_row">
		<div class="menu_cnt sitewidth">
			<div class="menu_left">			
				<?php  include("./includes/adminheader.php");
					?>
			
			</div>
			<div class="menu_right">
				<ul class="topmenu">
                                        <li><a href="<?php echo BASE_URL?>cms" >Main Site</a></li>
					<li><a href="adminmain.php" <?php if($page == 'adminmain' || $page == '' ){ ?> class="selected" <?php } ?>><?php echo HEADING_HOME ?></a></li>
					<li><a href="kbentries.php" <?php if($page == 'kbentries'){ ?> class="selected" <?php } ?> ><?php echo HEADING_KNOWLEDGEBASE ?></a></li>
					<li><a href="editconfig.php" <?php if($page == 'editconfig'){ ?> class="selected" <?php } ?> ><?php echo HEADING_CONFIGURATION ?></a></li>
					<li><a href="news.php" <?php if($page == 'news'){ ?> class="selected" <?php } ?> ><?php echo TEXT_SIDE_NEWS_SMALL ?></a></li>
					<li><a href="repticketcomp.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&" <?php if($page == 'repticketcomp'){ ?> class="selected" <?php } ?>><?php echo HEADING_REPOPRTS ?></a></li>
					<li><a href="search.php" <?php if($page == 'search'){ ?> class="selected" <?php } ?>><?php echo HEADING_SEARCH ?></a></li>
					<li><a href="editprofile.php" <?php if($page == 'editprofile'){ ?> class="selected" <?php } ?>><?php echo HEADING_PREFERENCES ?></a></li>
				</ul>
			</div>
		</div>	
		
	</div>
<!-- Top menu ends -->





<!-- Content area starts-->
<div class="content_row">
	<div class="content_area sitewidth">
