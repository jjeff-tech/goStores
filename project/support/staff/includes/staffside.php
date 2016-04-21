
<link type="text/css" href="<?php echo SITE_URL?>styles/DropdownMenu/format.css" rel="stylesheet" />
<!--<script type="text/javascript" src="<?php echo SITE_URL?>scripts/jquery.min.js"></script>-->
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


		<li class="accordionButton2"><a href="#" onClick="javascript:window.open('./languages/<?php echo $_SP_language ?>/help/index.php','Help','width=600,height=400');" class="sidemenulink"><?php echo TEXT_SIDE_HELP ?></a></li>







	</ul>
</div>

</div>

<?php //echo "Cricket"; /* ?>
<?php  ?>
 



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




<?php //echo "sadfdf" ;?>












<!-- %%%%%%%%%%%%%%%%%%%%%%%%% /SIDE BOXES %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->