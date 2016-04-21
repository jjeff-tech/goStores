 <?php
 /*Newly Addedby Amaldev starts*/
	$sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='LiveChat'";
	$rs_chat = executeSelect($sql,$conn);
	if ( mysql_num_rows($rs_chat) > 0) {
	   $var_row = mysql_fetch_array($rs_chat);
       $var_livechat_enb=$var_row["vLookUpValue"];
	} else {
	  $var_livechat_enb = '0';
	}
	/*Newly Addedby Amaldev ends*/
?>
<!-- Header section-->
<div class="header_row">
	<div class="header_cnt sitewidth">
	<div class="header_left">
		<div class="mainlogo">
                    <?php
              $img_logo = $_SESSION["sess_logourl"];
              $img_logo =str_replace('%','%25',$img_logo);
                ?>
			<a href="<?php echo BASE_URL ; ?>"><img src="<?php echo $_SESSION["banner_img"]; ?>" border="0" ></a>
		</div>
		<div class="header_info left">
                    <h4><span><?php echo(htmlentities($_SESSION["sess_helpdesktitle"])); ?></span>&nbsp;-&nbsp;<?php echo TEXT_USER_PANEL;?></h4>
			<h5>

				<?php
					if(isset($_SESSION["sess_username"]) and $_SESSION["sess_username"]!="" ){
							echo TEXT_LOGGED_IN_AS;
							echo "&nbsp;<b>".stripslashes($_SESSION["sess_username"])."</b>";
					}else{
					echo "&nbsp;";
					}
			  ?>


			</h5>
		</div>
		<div class="clear"></div>
	</div>
	<div class="header_right">

            <?php
            if(!userLoggedIn()){
            ?>
            <div class="menu_right" style="float: left;">
                
                <div class="btn_box right">
                <div class="btn_box_left">&nbsp;</div>
                <div class="btn_box_cnt">
                <a href="<?php echo SITE_URL; ?>postticketbeforeregister.php"><?php echo CREATE_TICKET ?></a>
                </div>
                <div class="btn_box_right">&nbsp;</div>
                <div class="clear"></div>
                </div>

            </div>
            <?php
            }
            ?>

				<?php if ( $var_livechat_enb == '1' ) { ?>
            <div class="livechat_box right" style="padding-top:4px;">
            
            <a href="" onClick="window.open('<?php echo SITE_URL; ?>index_client_chat.php?comp=1&ref=visitorChat','LiveChat','width=475,height=635,resizable=yes,location=no');">
                
                <script language="JavaScript" type="text/javascript" src="<?php echo SITE_URL; ?>scripts/chatVisiting.js"></script>
                <script type="text/javascript">
                    var pg = window.location.href;
                    document.write("<img id=\"lCIcon\" src='<?php echo SITE_URL; ?>getChatIcon.php?comp=1&page="+pg+"' width=\"100\" height=\"52\" border=\"0\"></img>");

                </script>
                <script language="JavaScript" type="text/javascript">
                    var tmr_g;
                    tmr_g =setTimeout("updateIcon(1)",8000);
                    function updateIcon(cmp) {
                        var pg = window.location.href;
                        document.getElementById('lCIcon'). src="<?php echo SITE_URL;?>getChatIcon.php?comp="+cmp+"&page="+pg;
                        chatInvoke(cmp, '<?php echo SITE_URL;?>', pg );
                        if (tmr_g) clearTimeout(tmr_g);
                        tmr_g =setTimeout("updateIcon("+cmp+")",8000);}</script>
            </a></div><?php }?>


	</div>
	<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>
<!-- Header section ends-->

<!-- Top menu -->
	<div class="menu_row">
		<div class="menu_cnt sitewidth">
			<div class="menu_left">
			<?php
						include("./includes/userheader.php");
				?>
			</div>
			<div class="menu_right">
				<ul class="topmenu">
                                    <li><a href="<?php echo BASE_URL?>index" >Main Site</a></li>
                                    <li><a href="<?php echo SITE_URL?>index.php" <?php if($page == 'userhome' || $page == ''){ ?> class="selected" <?php } ?> ><?php echo HEADING_HOME;?></a></li>
				<?php
				if(!userLoggedIn() && $_SESSION["sess_postticket_before_register"]==1){
				?>
					<li><a  href="<?php echo SITE_URL?>register.php" <?php if($page == 'register'){ ?> class="selected" <?php } ?> ><?php echo TEXT_REGISTER;?></a></li>
					<?php
				}else if(!userLoggedIn()){
					?>
					<li><a  href="<?php echo SITE_URL?>postticketbeforeregister.php"><?php echo TEXT_POSTTICKET;?></a></li>
				<?php
				}if(userLoggedIn()){?>
                                        <li><a href="<?php echo SITE_URL?>editprofile.php" <?php if($page == 'editprofile'){ ?> class="selected" <?php } ?> ><?php echo TEXT_PREFERENCE;?></a></li>
				<?php }	?>
					<li><a href="<?php echo SITE_URL?>getrefinfo.php" <?php if($page == 'getrefinfo'){ ?> class="selected" <?php } ?> ><?php echo TEXT_GET_TICKET_DETAILS;?></a></li>
				</ul>
			</div>
		</div>
	</div>
<!-- Top menu ends -->

<!-- Content area starts-->
<div class="content_row">
	<div class="content_area sitewidth">