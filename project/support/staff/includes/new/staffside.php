<!--<link type="text/css" href="<?php echo SITE_URL?>styles/DropdownMenu/format.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo SITE_URL?>scripts/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo SITE_URL?>scripts/javascript.js"></script>





<!-- %%%%%%%%%%%%%%%%%%%%%%%%% LEFT SIDE MENU %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
<script>
function changeState(styp,stym,divstyle){
    if(document.getElementById(divstyle).style.display=="none"){
          document.getElementById(divstyle).style.display='';
          document.getElementById(stym).style.display='';
            document.getElementById(styp).style.display='none';
        }else{
          document.getElementById(divstyle).style.display='none';
          document.getElementById(styp).style.display='';
          document.getElementById(stym).style.display='none';
        }
}
</script>
<?php
//Newly Addedon 100609
    $sql_stf = "Select vStaffname from sptbl_staffs where nStaffId='".mysql_real_escape_string($_SESSION['sess_staffid'])."'";
    $rs_stf = executeSelect($sql_stf,$conn);
	$staff_rowcnt = mysql_num_rows($rs_stf);

	if ( $staff_rowcnt > 0 ) {
	  while($row = mysql_fetch_array($rs_stf)){
	     $var_staffname= $row["vStaffname"] ;
	  }
 	}

        $var_staffid = $_SESSION["sess_staffid"];
//end
	$sql = "Select nSpamTicketId from sptbl_spam_tickets where nDeptId IN (".$_SESSION['departmentids'].")";
	$var_cntspam  = mysql_num_rows(executeSelect($sql,$conn));
	$sql = "Select nTicketId from sptbl_tickets where vStatus='open' and vDelStatus='0' and nDeptId IN (".$_SESSION['departmentids'].")";
	$var_cntopen  = mysql_num_rows(executeSelect($sql,$conn));
	$sql = "Select nTicketId from sptbl_tickets where vStatus='closed' and vDelStatus='0' and nDeptId IN (".$_SESSION['departmentids'].")";
	$var_cntclosed  = mysql_num_rows(executeSelect($sql,$conn));
	$sql = "Select nTicketId from sptbl_tickets where vStatus='escalated' and vDelStatus='0' and nDeptId IN (".$_SESSION['departmentids'].")";
	$var_cntescalated  = mysql_num_rows(executeSelect($sql,$conn));
	$sql = "Select nTicketId from sptbl_tickets where vDelStatus='0' and nDeptId IN (".$_SESSION['departmentids'].")";
	$var_cntall  = mysql_num_rows(executeSelect($sql,$conn));
        $sql = "Select nFollowId from sptbl_follow_tickets where nStaffId IN (".$var_staffid.") AND vStaffType = 'S' ";
	$var_cntfollow  = mysql_num_rows(executeSelect($sql,$conn));
        $sql = "Select DISTINCT t.nTicketId from sptbl_replies r LEFT JOIN sptbl_tickets t ON t.nTicketId = r.nTicketId where r.nHold = '1' and t.nDeptId IN (".$_SESSION['departmentids'].") ";
	$var_cnthold  = mysql_num_rows(executeSelect($sql,$conn));
	/*Newly Addedby Amaldev starts*/
	$sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='LiveChat'";
	$rs_chat = executeSelect($sql,$conn);
	if ( mysql_num_rows($rs_chat) > 0) {
	   $var_row = mysql_fetch_array($rs_chat);
       $var_livechat_enb=$var_row["vLookUpValue"];
	} else {
	  $var_livechat_enb = '0';
	}

	/*Newly Added by ASha*/

        // Select Extra Status of Tickets for menu Listing

                        $sqlExtraStat = "SELECT count(st.nTicketId ) AS tCount, sl.`vLookUpValue` , st.vRefNo
                                              FROM `sptbl_lookup` sl
                                              LEFT JOIN sptbl_tickets st ON st.vStatus = sl.`vLookUpValue`
                                              WHERE `vLookUpName` LIKE 'ExtraStatus' AND st.vDelStatus ='0'
                                              GROUP BY st.vStatus ";
                        $rsExtraStat = executeSelect($sqlExtraStat,$conn);

                        $var_statusRow = mysql_num_rows($rsExtraStat);

        // End Status Check
?>

<div class="left_section_block">
	<div class="leftMenu">
	<ul>
		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_TICKETS ?></a></li>
                <?php ($_GET['stylename']=='STYLETICKETS')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>

                                <li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=f&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
				<?php echo TEXT_SIDE_FOLLOW  ." (<span id='follow_count'>".$var_cntfollow."</span>)";?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=o&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
                <?php echo TEXT_SIDE_OPEN  ." (".$var_cntopen.")";  ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=c&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
                <?php echo TEXT_SIDE_CLOSED  ." (".$var_cntclosed.")";?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=e&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&" >
				<?php echo TEXT_SIDE_ESCALATED  ." (".$var_cntescalated.")";?></a>
				</li>

                                <li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=h&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
                <?php echo TEXT_SIDE_HOLD  ." (".$var_cnthold.")";  ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
				<?php echo TEXT_SIDE_ALL  ." (".$var_cntall.")";?></a>
				</li>


                                 <?php
                             // Include  Additional Ticket Sttaus Links Modified By Asha On 26-09-2012
                             if($var_statusRow>0){

                                 while($tRow =mysql_fetch_array($rsExtraStat)) {
                                     $status= $tRow['vLookUpValue'];

                                 ?>
                                 <li>
				<a href="<?php echo SITE_URL?>staff/tickets.php?mt=y&tp=<?php echo $status; ?>&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"><?php echo $tRow['vLookUpValue']  ." (".$tRow['tCount'].")"; ?></a>
				</li>
                                <?php
                                    }
                                 }
                                 // End Include Extra Links for Ticket Status
                                 ?>

				<li>
				<a href="<?php echo SITE_URL?>staff/postticket.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
				<?php echo TEXT_POST_NEW ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/spamtickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&">
				<?php echo TEXT_SPAM_TICKET  ." (".$var_cntspam.")";?></a>
				</li>

			</ul>
		</li>


		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_PRIVATE_MESSAGES ?></a></li>
                <?php ($_GET['stylename']=='STYLEPRIVATEMESSAGES')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&">
                <?php echo TEXT_SIDE_LIST ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/addpvtmessage.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&">
                <?php echo TEXT_SIDE_ADD ?></a>
				</li>
			</ul>
		</li>


		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_REMINDERS ?></a></li>
                <?php ($_GET['stylename']=='STYLEREMINDERS')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/reminders.php?mt=y&stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3&">
                <?php echo TEXT_SIDE_LIST ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/editreminder.php?stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a>
				</li>
			</ul>
		</li>


		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_USERS ?></a></li>
                <?php ($_GET['stylename']=='STYLEUSERS')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/users.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&">
                <?php echo TEXT_SIDE_LIST ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/edituser.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&">
                <?php echo TEXT_SIDE_ADD ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/emailuser.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&">
                <?php echo TEXT_SIDE_MAIL ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/emailall.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&">
                <?php echo TEXT_SIDE_MAIL_ALL ?></a>
				</li>

			</ul>
		</li>


		<li class="accordionButton2"><a href="<?php echo SITE_URL?>staff/pernotes.php?mt=y&stylename=STYLEPERSONALNOTES&styleminus=minus4&styleplus=plus4&"><?php echo TEXT_SIDE_PERSONAL_NOTES ?></a></li>



		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_TEMPLATES ?></a></li>
                <?php ($_GET['stylename']=='STYLETEMPLATES')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/templates.php?mt=y&stylename=STYLETEMPLATES&styleminus=minus5&styleplus=plus5&">
                <?php echo TEXT_SIDE_LIST ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/edittemplate.php?mt=y&stylename=STYLETEMPLATES&styleminus=minus5&styleplus=plus5&">
                <?php echo TEXT_SIDE_ADD ?></a>
				</li>

			</ul>
		</li>


		<li class="accordionButton2"><a href="<?php echo SITE_URL?>staff/viewstatistics.php?mt=y&stylename=STYLESTATISTICS&styleminus=minus6&styleplus=plus6&"><?php echo TEXT_SIDE_STATISTICS ?></a></li>


		<li class="accordionButton2"><a href="<?php echo SITE_URL?>staff/downloads.php?mt=y&stylename=STYLEDOWNLOADS&styleminus=minus7&styleplus=plus7&"><?php echo TEXT_SIDE_DOWNLOADS ?></a></li>


		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_LABELS ?></a></li>
                <?php ($_GET['stylename']=='STYLELABELS')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/labels.php?mt=y&stylename=STYLELABELS&styleminus=minus11&styleplus=plus11&">
                <?php echo TEXT_SIDE_LIST ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/editlabels.php?mt=y&stylename=STYLELABELS&styleminus=minus11&styleplus=plus11&">
                <?php echo TEXT_SIDE_ADD ?></a>
				</li>

			</ul>
		</li>


		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_KNOWLEDGE_BASE ?></a></li>
                <?php ($_GET['stylename']=='STYLEKNOWLEDGEBASE')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/knowledgebase.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus8&styleplus=plus8&">
                <?php echo TEXT_SIDE_LIST ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/editkbentry.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus8&styleplus=plus8&">
                <?php echo TEXT_SIDE_ADD ?></a>
				</li>

			</ul>
		</li>



		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_PREFERANCES ?></a></li>
                <?php ($_GET['stylename']=='STYLEPREFERANCES')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="<?php echo SITE_URL?>staff/assignfields.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&">
                <?php echo TEXT_SIDE_ASSIGN_FIELDS ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/editprofile.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&">
                <?php echo TEXT_SIDE_EDIT_PROFILE ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/actionlog.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&">
				<?php echo TEXT_SIDE_ACTION_LOG ?></a>
				</li>

			</ul>
		</li>

		<?php if ($var_livechat_enb == '1') { ?>
		<li class="accordionButton"><a href="javascript:;"><?php echo TEXT_SIDE_CHAT ?></a></li>
                <?php ($_GET['stylename']=='STYLECHAT')? $style='list-item': $style='none';?>
		<li class="accordionContent" style="display:<?php echo $style;?>">
			<ul>
				<li>
				<a href="#" onClick="javascript:window.open('./chat/chat.php?staffid=<?php echo $_SESSION['sess_staffid']?>&staffname=<?php echo $var_staffname;?>','LiveChat','resizable=yes,width=1024,height=600');">
                <?php echo TEXT_SIDE_LAUNCH_CHAT ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/cannedmessages.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&">
                <?php echo TEXT_SIDE_LISTCANNED_MESSAGES ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/addcannedmessage.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&">
                <?php echo TEXT_SIDE_ADDCANNED_MESSAGE ?></a>
				</li>

				<li>
				<a href="<?php echo SITE_URL?>staff/chat_logs.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&">
                <?php echo TEXT_SIDE_CHAT_LOGS ?></a>
				</li>

			</ul>
		</li>
                <?php } ?>


		<li class="accordionButton2"><a href="../../help/staff/help.php" target="_blank" class="sidemenulink"><?php echo TEXT_SIDE_HELP ?></a></li>







	</ul>
</div>
</div>


<table width="204"  border="0" cellspacing="10" cellpadding="0" class="column1" style="display:none;">
<tr>
<td height="14" valign=top>

    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>

    <td valign=top>


    <!-- MENU TITLE -->

        <table width="100%"  border="0" cellspacing="0" cellpadding="5" class="heading">
        <tr>
        <td width="93%" class="mainheading"><?php echo TEXT_MENU ?></td>
        </tr>
        </table>

    <!-- /MENU TITLE -->

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td width="15%" >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus1" onclick="changeState('plus1','minus1','STYLETICKETS');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus1" onclick="changeState('plus1','minus1','STYLETICKETS');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus1','minus1','STYLETICKETS');" class="sidemenulink"><?php echo TEXT_SIDE_TICKETS ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLETICKETS">
                <table width="100%" cellspacing="0" cellpadding="0" >
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="tickets.php?mt=y&tp=o&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"  class="sidemenulink">
                <?php echo TEXT_SIDE_OPEN  ." (".$var_cntopen.")";  ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="tickets.php?mt=y&tp=c&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"  class="sidemenulink">
                <?php echo TEXT_SIDE_CLOSED  ." (".$var_cntclosed.")";?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img
                src="./../images/bullet.gif" width="10" height="11">&nbsp;
                <a href="tickets.php?mt=y&tp=e&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"  class="sidemenulink"><?php echo TEXT_SIDE_ESCALATED  ." (".$var_cntescalated.")";?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img
                src="./../images/bullet.gif" width="10" height="11">&nbsp;
                <a href="tickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"  class="sidemenulink"><?php echo TEXT_SIDE_ALL  ." (".$var_cntall.")";?></a></td>
                </tr>
				<tr>
                <td  width="20%"></td>
                <td ><img
                src="./../images/bullet.gif" width="10" height="11">&nbsp;
                <a href="postticket.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"  class="sidemenulink"><?php echo TEXT_POST_NEW ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img
                src="./../images/bullet.gif" width="10" height="11">&nbsp;
                <a href="spamtickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&"  class="sidemenulink"><?php echo TEXT_SPAM_TICKET  ." (".$var_cntspam.")";?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->
<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus2" onclick="changeState('plus2','minus2','STYLEPRIVATEMESSAGES');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus2" onclick="changeState('plus2','minus2','STYLEPRIVATEMESSAGES');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus2','minus2','STYLEPRIVATEMESSAGES');" class="sidemenulink"><?php echo TEXT_SIDE_PRIVATE_MESSAGES ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEPRIVATEMESSAGES">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="addpvtmessage.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->


<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus3" onclick="changeState('plus3','minus3','STYLEREMINDERS');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus3" onclick="changeState('plus3','minus3','STYLEREMINDERS');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus3','minus3','STYLEREMINDERS');" class="sidemenulink"><?php echo TEXT_SIDE_REMINDERS ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEREMINDERS">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="reminders.php?mt=y&stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="editreminder.php?stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->


<!-- ===================================================================== -->
<tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus10" onclick="changeState('plus10','minus10','STYLEUSERS');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus10" onclick="changeState('plus10','minus10','STYLEUSERS');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus10','minus10','STYLEUSERS');" class="sidemenulink"><?php echo TEXT_SIDE_USERS ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEUSERS">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="users.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="edituser.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a></td>
                </tr>
                                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="emailuser.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&"  class="sidemenulink">
                <?php echo TEXT_SIDE_MAIL ?></a></td>
                </tr>
                                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="emailall.php?mt=y&stylename=STYLEUSERS&styleminus=minus10&styleplus=plus10&"  class="sidemenulink">
                <?php echo TEXT_SIDE_MAIL_ALL ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>

<!-- ===================================================================== -->

<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus4" onclick="changeState('plus4','minus4','STYLEPERSONALNOTES');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus4" onclick="changeState('plus4','minus4','STYLEPERSONALNOTES');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus4','minus4','STYLEPERSONALNOTES');" class="sidemenulink"><?php echo TEXT_SIDE_PERSONAL_NOTES ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEPERSONALNOTES">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="pernotes.php?mt=y&stylename=STYLEPERSONALNOTES&styleminus=minus4&styleplus=plus4&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->


<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus5" onclick="changeState('plus5','minus5','STYLETEMPLATES');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus5" onclick="changeState('plus5','minus5','STYLETEMPLATES');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus5','minus5','STYLETEMPLATES');" class="sidemenulink"><?php echo TEXT_SIDE_TEMPLATES ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLETEMPLATES">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="templates.php?mt=y&stylename=STYLETEMPLATES&styleminus=minus5&styleplus=plus5&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="edittemplate.php?mt=y&stylename=STYLETEMPLATES&styleminus=minus5&styleplus=plus5&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->


<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus6" onclick="changeState('plus6','minus6','STYLESTATISTICS');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus6" onclick="changeState('plus6','minus6','STYLESTATISTICS');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus6','minus6','STYLESTATISTICS');" class="sidemenulink"><?php echo TEXT_SIDE_STATISTICS ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLESTATISTICS">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="viewstatistics.php?mt=y&stylename=STYLESTATISTICS&styleminus=minus6&styleplus=plus6&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>

                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->

<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus7" onclick="changeState('plus7','minus7','STYLEDOWNLOADS');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus7" onclick="changeState('plus7','minus7','STYLEDOWNLOADS');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus7','minus7','STYLEDOWNLOADS');" class="sidemenulink"><?php echo TEXT_SIDE_DOWNLOADS ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEDOWNLOADS">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="downloads.php?mt=y&stylename=STYLEDOWNLOADS&styleminus=minus7&styleplus=plus7&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>

                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->

<!--  labels ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus11" onclick="changeState('plus11','minus11','STYLELABELS');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus11" onclick="changeState('plus11','minus11','STYLELABELS');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus11','minus11','STYLELABELS');" class="sidemenulink"><?php echo TEXT_SIDE_LABELS ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLELABELS">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="labels.php?mt=y&stylename=STYLELABELS&styleminus=minus11&styleplus=plus11&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="editlabels.php?mt=y&stylename=STYLELABELS&styleminus=minus11&styleplus=plus11&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->

<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus8" onclick="changeState('plus8','minus8','STYLEKNOWLEDGEBASE');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus8" onclick="changeState('plus8','minus8','STYLEKNOWLEDGEBASE');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus8','minus8','STYLEKNOWLEDGEBASE');" class="sidemenulink"><?php echo TEXT_SIDE_KNOWLEDGE_BASE ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEKNOWLEDGEBASE">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="knowledgebase.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus8&styleplus=plus8&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LIST ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="editkbentry.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus8&styleplus=plus8&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADD ?></a></td>
                </tr>
                </table>
        </div>
    </td>
    </tr>
<!-- ===================================================================== -->

<!-- ===================================================================== -->
        <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus9" onclick="changeState('plus9','minus9','STYLEPREFERANCES');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus9" onclick="changeState('plus9','minus9','STYLEPREFERANCES');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus9','minus9','STYLEPREFERANCES');" class="sidemenulink"><?php echo TEXT_SIDE_PREFERANCES ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLEPREFERANCES">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="assignfields.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ASSIGN_FIELDS ?></a></td>
                </tr>

                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="editprofile.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&"  class="sidemenulink">
                <?php echo TEXT_SIDE_EDIT_PROFILE ?></a></td>
                </tr>
                <tr>
                <td  width="20%"></td>
                <td ><img
                src="./../images/bullet.gif" width="10" height="11">&nbsp;
                <a href="actionlog.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&"  class="sidemenulink"><?php echo TEXT_SIDE_ACTION_LOG ?></a></td>
                </tr>
               </table>
        </div>
    </td>
    </tr>
<!-- =====================Added by Amaldev for chat starts================================================ -->
      <?php if ($var_livechat_enb == '1') { ?>
	   <tr>
        <td width="15%"  >
        <img src="./../images/bullet.gif" width="10" height="11">
        <img id="plus12" onclick="changeState('plus12','minus12','STYLECHAT');" src="./../images/plus.gif" width="9" height="9">
        <img id="minus12" onclick="changeState('plus12','minus12','STYLECHAT');"  src="./../images/minus.gif" width="9" height="9">
        <a href="#" onClick="changeState('plus12','minus12','STYLECHAT');" class="sidemenulink"><?php echo TEXT_SIDE_CHAT ?></a>
        </td>
        </tr>
        <tr>
        <td>
        <div id="STYLECHAT">
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="#" onClick="javascript:window.open('./chat/chat.php?staffid=<?php echo $_SESSION['sess_staffid']?>&staffname=<?php echo $var_staffname;?>','LiveChat','resizable=yes,width=1024,height=600');"  class="sidemenulink">
                <?php echo TEXT_SIDE_LAUNCH_CHAT ?></a></td>
                </tr>

                <tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="cannedmessages.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&"  class="sidemenulink">
                <?php echo TEXT_SIDE_LISTCANNED_MESSAGES ?></a></td>
                </tr>

				<tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="addcannedmessage.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&"  class="sidemenulink">
                <?php echo TEXT_SIDE_ADDCANNED_MESSAGE ?></a></td>
                </tr>

				<tr>
                <td  width="20%"></td>
                <td ><img src="./../images/bullet.gif" width="10"
                height="11">&nbsp;<a href="chat_logs.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&"  class="sidemenulink">
                <?php echo TEXT_SIDE_CHAT_LOGS ?></a></td>
                </tr>

                </table>
        </div>
    </td>
    </tr>
	<?php } ?>
<!-- =====================================chat section ends================================ -->
	<tr>
            <td ><img src="./../images/bullet.gif" width="10" height="11">&nbsp;<a href="#" onClick="javascript:window.open('./languages/<?php echo $_SP_language ?>/help/index.html','Help','width=600,height=400');" class="sidemenulink"><?php echo TEXT_SIDE_HELP ?></a></td>
    </tr>
<!-- ===================================================================== -->
    </table>

</td>
<td width="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
</tr>
</table>

            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                 </table>
                          </td>
          </tr>
		  <form name="frmShowNews123" action="shownews.php" method="post">
		  	<input type="hidden" name="id" value="">
		  </form>
</table>



<!-- %%%%%%%%%%%%%%%%%%%%%%%%% /LEFT SIDE MENU %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

<!-- %%%%%%%%%%%%%%%%%%%%%%%%% SIDE BOXES %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

<BGSOUND id="BGSOUND_ID" LOOP=1 SRC="">


          <!--^^^^^^^^^^^^^^^^^^^^^^^^NEWS ^^^^^^^^^^^^^^^^^^^^^^^^^^^  -->

          <?php
                  /*
                          $heading = "JOhnson";
                        $bgcolor = "#FFFFFF";
                        $width = "100";
                        $height= "150";
                        $delay = "0";
                          displayNews($heading, $bgcolor, $width, $height, $delay);
                   */
                  ?>















          <!--    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="left_item_block">
              <tr> <div class="left_item_title"><?php echo TEXT_FIELDS_ANNOUNCEMENTS ?></div>
              </tr>
              <tr>
              <td bgcolor="#FFFFFF" style=height:100px;>
<script language=JavaScript1.2>
// === 1 === FONT, COLORS, EXTRAS...
v_font='verdana,arial,sans-serif';
v_fontSize='10px';
v_fontSizeNS4='11px';
v_fontWeight='normal';
v_fontColor='#4A49A8';
v_textDecoration='none';
v_fontColorHover='#ff0000';//                | won't work
v_textDecorationHover='underline';//        | in Netscape4
v_bgColor='url(images/bg.jpg)';
v_top=0;//        |
v_left=0;//        | defining
v_width=150;//        | the box
v_height=100;//        |
v_paddingTop=2;
v_paddingLeft=2;
v_position='relative';// absolute/relative
v_timeout=2500;//1000 = 1 second

v_slideDirection=0;//0=down-up;1=up-down
v_pauseOnMouseOver=true;
// v2.2+ new below
v_slideStep=1;//pixels
v_textAlign='left';// left/center/right
v_textVAlign='middle';// top/middle/bottom - won't work in Netscape4

// === 2 === THE CONTENT - ['href','text','target']

// Use '' for href to have no link item

<?php

 $sql = "SELECT nNewsId,vTitle   FROM `sptbl_news` where  dVaildDate >= CURDATE() and (vType='A' or vType='S')  order by dPostdate";

$rs = executeSelect($sql,$conn);
$vcontent="v_content=[";
if (mysql_numrows($rs) > 0){
         $xxx=0;
         while($row = mysql_fetch_array($rs)) {
         ($xxx=="1")?$vcontent.=",":$vcontent.="";
         //$vcontent.="['shownews.php?id=".$row['nNewsId']."','".$row['vTitle']."','_self']";
         $vcontent.="['javascript:showDetails(\"".$row['nNewsId']."\");','".mysql_real_escape_string(htmlentities($row['vTitle']))."','_self']";
         $xxx=1;

         }
}else{
         $vcontent.="['','" . NEWS_DEFAULT_MSG . "','_blank']";
}
$vcontent.="];";

echo $vcontent;
?>

// ===
v_ua=navigator.userAgent;
v_nS4=document.layers?1:0;
v_iE=document.all&&!window.innerWidth&&v_ua.indexOf("MSIE")!=-1?1:0;
v_oP=v_ua.indexOf("Opera")!=-1&&document.clear?1:0;
v_oP7=v_oP&&document.appendChild?1:0;
v_oP4=v_ua.indexOf("Opera")!=-1&&!document.clear;
v_kN=v_ua.indexOf("Konqueror")!=-1&&parseFloat(v_ua.substring(v_ua.indexOf("Konqueror/")+10))<3.1?1:0;
v_count=v_content.length;
v_cur=1;
v_cl=0;
v_d=v_slideDirection?-1:1;
v_TIM=0;
v_fontSize2=v_nS4&&navigator.platform.toLowerCase().indexOf("win")!=-1?v_fontSizeNS4:v_fontSize;
v_canPause=0;
function v_getOS(a){return v_iE?document.all[a].style:v_nS4?document.layers["v_container"].document.layers[a]:document.getElementById(a).style};
function v_start(){var o,px;
o=v_getOS("v_1");
px=v_oP&&!v_oP7||v_nS4?0:"px";if(parseInt(o.top)==v_paddingTop){v_canPause=1;if(v_count>1)v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return}o.top=(parseInt(o.top)-v_slideStep*v_d)*v_d>v_paddingTop*v_d?parseInt(o.top)-v_slideStep*v_d+px:v_paddingTop+px;if(v_oP&&o.visibility.toLowerCase()!="visible")o.visibility="visible";setTimeout("v_start()",30)};function v_slide(){var o,o2,px;o=v_getOS("v_"+v_cur);o2=v_getOS("v_"+(v_cur<v_count?v_cur+1:1));px=v_oP&&!v_oP7||v_nS4?0:"px";if(parseInt(o2.top)==v_paddingTop){if(v_oP)o.visibility="hidden";o.top=v_height*v_d+px;v_cur=v_cur<v_count?v_cur+1:1;v_canPause=1;v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return}if(v_oP&&o2.visibility.toLowerCase()!="visible")o2.visibility="visible";if((parseInt(o2.top)-v_slideStep*v_d)*v_d>v_paddingTop*v_d){o.top=parseInt(o.top)-v_slideStep*v_d+px;o2.top=parseInt(o2.top)-v_slideStep*v_d+px}else{o.top=-v_height*v_d+px;o2.top=v_paddingTop+px}setTimeout("v_slide()",30)};if(v_nS4||v_iE||v_oP||document.getElementById&&!v_kN&&!v_oP4){
document.write("<style>.vnewsticker,a.vnewsticker{font-family:"+v_font+";font-size:"+v_fontSize2+";color:"+v_fontColor+";text-decoration:"+v_textDecoration+";font-weight:"+v_fontWeight+"}a.vnewsticker:hover{font-family:"+v_font+";font-size:"+v_fontSize2+";color:"+v_fontColorHover+";text-decoration:"+v_textDecorationHover+"}</style>");v_temp="<div "+(v_nS4?"name":"id")+"=v_container style='position:"+v_position+";top:"+v_top+"px;left:"+v_left+"px;width:"+v_width+"px;height:"+v_height+"px;background:"+v_bgColor+";layer-background"+(v_bgColor.indexOf("url(")==0?"-image":"-color")+":"+v_bgColor+";clip:rect(0,"+v_width+","+v_height+",0);overflow:hidden'>"+(v_iE?"<div style='position:absolute;top:0px;left:0px;width:100%;height:100%;clip:rect(0,"+v_width+","+v_height+",0)'>":"");for(v_i=0;v_i<v_count;v_i++)
v_temp+="<div "+(v_nS4?"name":"id")+"=v_"+(v_i+1)+" style='position:absolute;top:"+(v_height*v_d)+"px;left:"+v_paddingLeft+"px;width:"+(v_width-v_paddingLeft*2)+"px;height:"+(v_height-v_paddingTop*2)+"px;clip:rect(0,"+(v_width-v_paddingLeft*2)+","+(v_height-v_paddingTop*2)+",0);overflow:hidden"+(v_oP?";visibility:hidden":"")+";text-align:"+v_textAlign+"' class=vnewsticker>"+(!v_nS4?"<table width="+(v_width-v_paddingLeft*2)+" height="+(v_height-v_paddingTop*2)+" cellpadding=0 cellspacing=0 border=0><tr><td width="+(v_width-v_paddingLeft*2)+" height="+(v_height-v_paddingTop*2)+" align="+v_textAlign+" valign="+v_textVAlign+" class=vnewsticker>":"")+(v_content[v_i][0]!=""?"<a href='"+v_content[v_i][0]+"' target='"+v_content[v_i][2]+"' class=vnewsticker"+(v_pauseOnMouseOver?" onmouseover='if(v_canPause&&v_count>1){clearTimeout(v_TIM);v_cl=1}' onmouseout='if(v_canPause&&v_count>1&&v_cl)v_TIM=setTimeout(\"v_canPause=0;v_slide();v_cl=0\","+v_timeout+")'":"")+">":"<span"+(v_pauseOnMouseOver?" onmouseover='if(v_canPause&&v_count>1){clearTimeout(v_TIM);v_cl=1}' onmouseout='if(v_canPause&&v_count>1&&v_cl)v_TIM=setTimeout(\"v_canPause=0;v_slide();v_cl=0\","+v_timeout+")'":"")+">")+v_content[v_i][1]+(v_content[v_i][0]!=""?"</a>":"</span>")+(!v_nS4?"</td></tr></table>":"")+"</div>";v_temp+=(v_iE?"</div>":"")+"</div>";document.write(v_temp);setTimeout("v_start()",1000);if(v_nS4)onresize=function(){location.reload()}}

ver=parseInt(navigator.appVersion)
ie4=(ver>3  && navigator.appName!="Netscape")?1:0
ns4=(ver>3  && navigator.appName=="Netscape")?1:0
ns3=(ver==3 && navigator.appName=="Netscape")?1:0

function playSound() {
 if (ie4) document.all['BGSOUND_ID'].src='./../sound/SOS.mid';
 if ((ns4||ns3)
  && navigator.javaEnabled()
  && navigator.mimeTypes['audio/x-midi']
  && self.document.Bach.IsReady()
 )
 {
  //self.document.Bach.play()
 }
}
</script>
              </td>
              </tr>
              </table>


			-->












          <!--^^^^^^^^^^^^^^^^^^^^^^^^/NEWS ^^^^^^^^^^^^^^^^^^^^^^^^^^^  -->
<?php  //include("./includes/dept_overview.php"); ?>
<?php // include("./includes/dataentries.php"); ?>

          <!--^^^^^^^^^^^^^^^^^^^^^^^^QUICK LINKS ^^^^^^^^^^^^^^^^^^^^^^^^^^^  -->


<!-- %%%%%%%%%%%%%%%%%%%%%%%%% /SIDE BOXES %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->