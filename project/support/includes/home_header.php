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


	<div class="header_row_home">
	<div class="header_cnt_outer_home sitewidth">
		<div class="outer_home_left">
		<div class="outer_logo_container">
                     <?php
              $img_logo = $_SESSION["sess_logourl"];
              $img_logo =str_replace('%','%25',$img_logo);
                ?>
                    <a href="<?php echo SITE_URL;?>"><img src="<?php echo(SITE_URL.$img_logo); ?>"></a></div>
		</div>
		<div class="outer_home_right">
		
		
		
		
            <?php if ( $var_livechat_enb == '1' ) { ?>
		<!-- Live chat section -->
			<div class="livechat_box right">
			<?php if ( $var_livechat_enb == '1' ) { ?><td>
                            <!-- BEGIN LivePerson Button Code -->		 
			<a id=id="lpChatBtn" href="" onClick="window.open('<?php echo SITE_URL; ?>index_client_chat.php?comp=1&ref=visitorChat','LiveChat','width=475,height=400,resizable=yes');"> 
                
                <script language="JavaScript" type="text/javascript" src="<?php echo SITE_URL; ?>scripts/chatVisiting.js"></script>
                <script type="text/javascript">
                    var pg = window.location.href;
                    document.write("<img id=\"lCIcon\" src='<?php echo SITE_URL; ?>getChatIcon.php?comp=1&page="+pg+"'  height=\"35\"></img>");

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
            </a>
			<!-- END LivePerson Button code -->
            </td><?php }?>
				<!--<div class="livechat_box_left">&nbsp;</div>
				<div class="livechat_box_cnt">
	<a href="#" class="livechat_on" onClick="window.open('<?php echo SITE_URL;?>index_client_chat.php?comp=1&ref=visitorChat','LiveChat','width=475,height=635,resizable=yes,location=no');"><?php echo LIVE_CHAT ?> <span><?PHP echo ONLINE ?></span></a>
                                        <script type="text/javascript">
                    var pg = window.location.href;
                    //document.write("<img id=\"lCIcon\" src='getChatIcon.php?comp=1&page="+pg+"' width=\"100\" height=\"35\" border=\"0\"></img>");
                </script>
                <script language="JavaScript" type="text/javascript" src="scripts/chatVisiting.js"></script>
                <script language="JavaScript" type="text/javascript">
                    var tmr_g;
                    tmr_g =setTimeout("updateIcon(1)",2000);
                    function updateIcon(cmp) {
                        var pg = window.location.href;
                        if ($("#lCIcon").length > 0){
                        document.getElementById('lCIcon'). src="getChatIcon.php?comp="+cmp+"&page="+pg;
                        }
                        chatInvoke(cmp,null, pg );
                        if (tmr_g) clearTimeout(tmr_g);
                        tmr_g =setTimeout("updateIcon("+cmp+")",2000);}</script>

                </div>
				<div class="livechat_box_right">&nbsp;</div>-->
			<div class="clear"></div>
			</div>
               <?php } ?>
		<!-- Live chat section ends -->


		</div>
	<div class="clear"></div>
	</div>
	</div>
	<!-- post ticket old--
	<div class="menu_row_outer">
		<div class="menu_cnt sitewidth">
			<div class="menu_left">
			<div class="lang_selector">
			
			<?php
                        if ($_SESSION["sess_langchoice"] == "1") {
                        $sql="Select vLangCode,vLangDesc from sptbl_lang order by vLangDesc ";
                        $result=mysql_query($sql,$conn);
        ?>
                        <form name="frmLanguage" action="index.php" method="post">

                            <label><?php echo(TEXT_SELECT_LANGUAGE); ?></label>&nbsp;&nbsp;<select name="cmbLan" class="selectbox1"  style="width:80px;" id="clsLanguage" onchange="changeLanguage();">
                                <?php
                                        if (mysql_num_rows($result) > 0) {
                                                while($row = mysql_fetch_array($result)) {
                                                        echo("<option value=\"" . htmlentities($row["vLangCode"]) . "\">" . $row["vLangDesc"] . "</option>");
                                                }
                                        }
                                ?>
                                </select>&nbsp;
                        <script>
                                var lc = '<?php echo($_SESSION["sess_language"]); ?>';
                                document.frmLanguage.cmbLan.value=lc;

                                function changeLanguage() {
                                        document.frmLanguage.method="post";
                                                                                document.frmLanguage.post_back.value ="CL";
                                        document.frmLanguage.submit();
                                }
                        </script>
                             <input type="hidden" name="post_back" value="">
                        </form>
                <?php
                        }
                 ?>
				</div>
			</div>
			<div class="menu_right">
				<ul class="topmenu_outer">
                                <!-- <li><a <?php if($page == 'userhome' || $page == '' ){ ?> class="selected" <?php } ?> href="<?php echo SITE_URL;?>index.php"><?php echo HEADING_HOME;?></a></li>--
				<?php
				if(!userLoggedIn() && $_SESSION["sess_postticket_before_register"]==1){
				?>
					<!--<li><a  href="<?php echo SITE_URL;?>register.php" <?php if($page == 'register'){ ?> class="selected" <?php } ?> > <?php echo TEXT_REGISTER;?></a></li>--
					<?php
					}else if(!userLoggedIn()){
					?>
					<li><a  href="<?php echo SITE_URL;?>postticketbeforeregister.php" <?php if($page == 'postticketbeforeregister'){ ?> class="selected" <?php } ?>><?php echo TEXT_POSTTICKET;?></a></li>
					<?php
					}
					if(userLoggedIn()){
					?>
					<li><a href="<?php echo SITE_URL;?>editprofile.php" <?php if($page == 'editprofile'){ ?> class="selected" <?php } ?> ><?php echo TEXT_EDIT_PROFILE;?></a></li>
					<?php
					}
					?>
					<!--<li><a href="<?php echo SITE_URL;?>getrefinfo.php" <?php if($page == 'getrefinfo'){ ?> class="selected" <?php } ?> ><?php echo TEXT_GET_REF_NO;?></a></li>--
				</ul>
			</div>
		</div>
	</div>
	<div class="header_row_home">
	<div class="header_cnt_outer_home sitewidthpadding">
		<div class="outer_home_left">
		<div class="outer_logo_container">
                     <?php
              $img_logo = $_SESSION["sess_logourl"];
              $img_logo =str_replace('%','%25',$img_logo);
                ?>
                    <a href="<?php echo SITE_URL;?>"><img src="<?php echo(SITE_URL.$img_logo); ?>"></a></div>
		</div>
		<div class="outer_home_right">
		
		
		
		
            <?php if ( $var_livechat_enb == '1' ) { ?>
		<!-- Live chat section -->
			<div class="livechat_box right">
			<?php if ( $var_livechat_enb == '1' ) { ?><td>
            <a href="" onClick="window.open('<?php echo SITE_URL; ?>index_client_chat.php?comp=1&ref=visitorChat','LiveChat','width=475,height=635,resizable=yes,location=no');">
                
                <script language="JavaScript" type="text/javascript" src="<?php echo SITE_URL; ?>scripts/chatVisiting.js"></script>
                <script type="text/javascript">
                    var pg = window.location.href;
                    document.write("<img id=\"lCIcon\" style=\" margin: 5px 300px 0 0;\" src='<?php echo SITE_URL; ?>getChatIcon.php?comp=1&page="+pg+"' width=\"100\" height=\"52\" border=\"0\"></img>");

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
            </a></td><?php }?>
				<!--<div class="livechat_box_left">&nbsp;</div>
				<div class="livechat_box_cnt">
	<a href="#" class="livechat_on" onClick="window.open('<?php echo SITE_URL;?>index_client_chat.php?comp=1&ref=visitorChat','LiveChat','width=475,height=635,resizable=yes,location=no');"><?php echo LIVE_CHAT ?> <span><?PHP echo ONLINE ?></span></a>
                                        <script type="text/javascript">
                    var pg = window.location.href;
                    //document.write("<img id=\"lCIcon\" src='getChatIcon.php?comp=1&page="+pg+"' width=\"100\" height=\"35\" border=\"0\"></img>");
                </script>
                <script language="JavaScript" type="text/javascript" src="scripts/chatVisiting.js"></script>
                <script language="JavaScript" type="text/javascript">
                    var tmr_g;
                    tmr_g =setTimeout("updateIcon(1)",2000);
                    function updateIcon(cmp) {
                        var pg = window.location.href;
                        if ($("#lCIcon").length > 0){
                        document.getElementById('lCIcon'). src="getChatIcon.php?comp="+cmp+"&page="+pg;
                        }
                        chatInvoke(cmp,null, pg );
                        if (tmr_g) clearTimeout(tmr_g);
                        tmr_g =setTimeout("updateIcon("+cmp+")",2000);}</script>

                </div>
				<div class="livechat_box_right">&nbsp;</div>-->
			<div class="clear"></div>
			</div>
               <?php } ?>
		<!-- Live chat section ends -->


		</div>
	<div class="clear"></div>
	</div>
	</div>
<!-- Header section ends-->
