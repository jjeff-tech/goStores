<!--<link type="text/css" href="../styles/DropdownMenu/format.css" rel="stylesheet" />
<script type="text/javascript" src="../scripts/jquery.min.js"></script>-->
<script type="text/javascript" src="../scripts/javascript.js"></script>
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
        return false;
    }

    function windowOpener(windowHeight, windowWidth, windowName, windowUri)
    {
        var centerWidth = (window.screen.width - windowWidth) / 2;
        var centerHeight = (window.screen.height - windowHeight) / 2;

        newWindow = window.open(windowUri, windowName, 'resizable=0,width=' + windowWidth + 
            ',height=' + windowHeight + 
            ',left=' + centerWidth + 
            ',top=' + centerHeight);

        newWindow.focus();
        return newWindow.name;
    }

</script>
<?php
$var_staffid = $_SESSION["sess_staffid"];

$sql = "Select nSpamTicketId from sptbl_spam_tickets";
$var_cntspam = mysql_num_rows(executeSelect($sql, $conn));
$sql = "Select nTicketId from sptbl_tickets where vStatus='open' and vDelStatus='0'";
$var_cntopen = mysql_num_rows(executeSelect($sql, $conn));
$sql = "Select nTicketId from sptbl_tickets where vStatus='closed' and vDelStatus='0'";
$var_cntclosed = mysql_num_rows(executeSelect($sql, $conn));
$sql = "Select nTicketId from sptbl_tickets where vStatus='escalated' and vDelStatus='0'";
$var_cntescalated = mysql_num_rows(executeSelect($sql, $conn));
$sql = "Select nTicketId from sptbl_tickets where vDelStatus='0'";
$var_cntall = mysql_num_rows(executeSelect($sql, $conn));
$sql = "Select nFollowId from sptbl_follow_tickets where nStaffId IN (" . $var_staffid . ") AND vStaffType = 'A' ";
$var_cntfollow = mysql_num_rows(executeSelect($sql, $conn));
$sql = "Select DISTINCT t.nTicketId from sptbl_replies r LEFT JOIN sptbl_tickets t ON t.nTicketId = r.nTicketId where r.nHold = '1' and t.vDelStatus='0' ";
$var_cnthold = mysql_num_rows(executeSelect($sql, $conn));
/* Newly Addedby Amaldev starts */
$sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='LiveChat'";
$rs_chat = executeSelect($sql, $conn);
if (mysql_num_rows($rs_chat) > 0) {
    $var_row = mysql_fetch_array($rs_chat);
    $var_livechat_enb = $var_row["vLookUpValue"];
} else {
    $var_livechat_enb = '0';
}

// Select Extra Status of Tickets for menu Listing
// Commented on 26/11/2012
/* $sqlExtraStat = "SELECT count(st.nTicketId ) AS tCount, sl.`vLookUpValue` , st.vRefNo
  FROM `sptbl_lookup` sl
  LEFT JOIN sptbl_tickets st ON st.vStatus = sl.`vLookUpValue`
  WHERE `vLookUpName` LIKE 'ExtraStatus'
  GROUP BY st.vStatus "; */

$sqlExtraStat = "SELECT count(st.nTicketId ) AS tCount, sl.`vLookUpValue` , st.vRefNo
                                            FROM `sptbl_lookup` sl
                                                LEFT JOIN sptbl_tickets st ON st.vStatus = sl.`vLookUpValue`
                                                    WHERE `vLookUpName` LIKE 'ExtraStatus' AND st.vDelStatus ='0'
                                                    GROUP BY sl.`vLookUpValue` ";
$rsExtraStat = executeSelect($sqlExtraStat, $conn);

$var_statusRow = mysql_num_rows($rsExtraStat);



// End Status

/* Newly Addedby Amaldev ends */
?>

<div class="left_section_block">  
    <div class="leftMenu">
        <ul>

            <!-- General -->
            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_GENERAL ?></a></li>
            <?php ($_GET['stylename'] == 'STYLEGENERAL') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editconfig.php?mt=y&stylename=STYLEGENERAL&styleminus=minus&styleplus=plus&">
                            <?php echo TEXT_SIDE_CONFIGURE ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editspamblock.php?mt=y&stylename=STYLEGENERAL&styleminus=minus&styleplus=plus&"><?php echo TEXT_SIDE_EMAIlSPAM ?></a>
                    </li>

                </ul>       
            </li>

            <!-- Tickets -->
            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_TICKETS ?></a></li>
            <?php ($_GET['stylename'] == 'STYLETICKETS') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=f&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_SIDE_FOLLOW . " (<span id='follow_count'>" . $var_cntfollow . "</span>)"; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=o&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_SIDE_TICKETS_OPEN . " (" . $var_cntopen . ")"; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=c&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_SIDE_TICKETS_CLOSED . " (" . $var_cntclosed . ")"; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=e&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_SIDE_TICKETS_ESCAlATED . " (" . $var_cntescalated . ")"; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=h&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_SIDE_TICKETS_HOLD . " (" . $var_cnthold . ")"; ?></a>
                    </li>

                    <?php
                    // Include  Additional Ticket Sttaus Links Modified By Asha On 26-09-2012
                    if ($var_statusRow > 0) {

                        while ($tRow = mysql_fetch_array($rsExtraStat)) {
                            $status = $tRow['vLookUpValue'];
                            ?>
                            <li>
                                <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=<?php echo $status ?>&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo $tRow['vLookUpValue'] . " (" . $tRow['tCount'] . ")"; ?></a>
                            </li>
        <?php
    }
}
// End Include Extra Links for Ticket Status
?>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/tickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_SIDE_TICKETS_ALL . " (" . $var_cntall . ")"; ?></a>
                    </li>

                    <li>
                        <a href="<?php echo SITE_URL ?>admin/status.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&" ><?php echo TEXT_SIDE_TICKETS_STATUS ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/priority.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&" ><?php echo TEXT_SIDE_TICKETS_PRIORITY ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/postticket.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><?php echo TEXT_POST_NEW ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/spamtickets.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&" ><?php echo TEXT_SIDE_TICKETS_SPAM . " (" . $var_cntspam . ")"; ?></a>
                    </li>


                </ul>
            </li>

            <!-- Language -->
            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_LANGUAGE ?></a></li>
<?php ($_GET['stylename'] == 'STYLELANGUAGE') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/languages.php?mt=y&stylename=STYLELANGUAGE&styleminus=minus1&styleplus=plus1&"><?php echo TEXT_SIDE_LIST ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editlang.php?mt=y&stylename=STYLELANGUAGE&styleminus=minus1&styleplus=plus1&"><?php echo TEXT_SIDE_ADD ?></a>
                    </li>

                </ul>       
            </li>

            <!-- Mail -->
            <li class="accordionButton2"><a href="<?php echo SITE_URL ?>admin/editmails.php?mt=y&stylename=STYLEMAIL&styleminus=minus2&styleplus=plus2&" ><?php echo TEXT_SIDE_MAIL ?></a></li>

            <!--Display -->
            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_DISPLAY ?></a></li>
<?php ($_GET['stylename'] == 'STYLEDISPLAY1') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/display.php?mt=y&stylename=STYLEDISPLAY1&styleminus=minus3&styleplus=plus3&"><?php echo TEXT_SIDE_LIST_THEME ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editdisplay.php?mt=y&stylename=STYLEDISPLAY1&styleminus=minus3&styleplus=plus3&"><?php echo TEXT_SIDE_ADD_THEME ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/edimisce.php?mt=y&stylename=STYLEDISPLAY1&styleminus=minus3&styleplus=plus3&"><?php echo TEXT_SIDE_LIST_MISC ?></a>
                    </li>

                </ul>       
            </li>

            <!-- Attachment -->
            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_ATTACHMENTS ?></a></li>
<?php ($_GET['stylename'] == 'STYLEATTACHMENTS') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/attachments.php?mt=y&stylename=STYLEATTACHMENTS&styleminus=minus4&styleplus=plus4&"><?php echo TEXT_SIDE_LIST_EXT ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editmaxfilesize.php?mt=y&stylename=STYLEATTACHMENTS&styleminus=minus4&styleplus=plus4&"><?php echo TEXT_SIDE_MAX_FILE_SIZE ?></a>
                    </li>

                </ul>       
            </li>


            <!-- Companies -->
            <!--<li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_COMPANIES ?></a></li>
<?php ($_GET['stylename'] == 'STYLECOMPANIES') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/companies.php?mt=y&stylename=STYLECOMPANIES&styleminus=minus5&styleplus=plus5&" ><?php echo TEXT_SIDE_LIST ?></a>
                    </li>
                    <li>
                        <a href="<?php //echo SITE_URL ?>admin/editcompany.php?mt=y&stylename=STYLECOMPANIES&styleminus=minus5&styleplus=plus5&"><?php //echo TEXT_SIDE_ADD ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/escalations.php?mt=y&stylename=STYLECOMPANIES&styleminus=minus9&styleplus=plus9&" ><?php echo TEXT_SIDE_TICKETS_ESCALATION; ?></a>
                    </li>

                </ul>       
            </li>-->

            <!-- Departments -->
            <!-- <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_DEPARTMENTS ?></a></li> -->
<?php //($_GET['stylename'] == 'STYLEDEPARTMENTS') ? $style = 'list-item' : $style = 'none'; ?>
            <!-- <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/departments.php?stylename=STYLEDEPARTMENTS&styleminus=minus6&styleplus=plus6&"><?php echo TEXT_SIDE_LIST ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editdepartments.php?stylename=STYLEDEPARTMENTS&styleminus=minus6&styleplus=plus6&"><?php echo TEXT_SIDE_ADD ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/assignstaff.php?stylename=STYLEDEPARTMENTS&styleminus=minus6&styleplus=plus6&" ><?php echo TEXT_SIDE_ASSIGN_STAFFS ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/assigndepartments.php?stylename=STYLEDEPARTMENTS&styleminus=minus6&styleplus=plus6&"><?php echo TEXT_SIDE_ASSIGN_DEPARTMENT ?></a>
                    </li>

                </ul>       
            </li> -->


            <!-- Staff -->
            
            <!--<li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_STAFF ?></a></li>-->
<?php //($_GET['stylename'] == 'STYLESTAFF') ? $style = 'list-item' : $style = 'none'; ?>
            <!--<li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/staff.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&"><?php echo TEXT_SIDE_LIST ?></a>
                    </li>
                    <!-- <li>
                        <a href="<?php echo SITE_URL ?>admin/editstaff.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&"><?php echo TEXT_SIDE_ADD ?></a>
                    </li> -->
                    <!--<li>
                        <a href="<?php echo SITE_URL ?>admin/emailstaff.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&"><?php echo TEXT_SIDE_EMAIL_ALL ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/activitylog.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&"><?php echo TEXT_SIDE_ACTIVITY_LOG ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/rating.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&"><?php echo TEXT_SIDE_RATING ?></a>
                    </li>

                </ul>   --> 
           <!-- </li>-->


            <!-- Users -->
            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_USERS ?></a></li>
<?php ($_GET['stylename'] == 'STYLEUSERS') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/users.php?mt=y&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&"><?php echo TEXT_SIDE_LIST ?></a>
                    </li>
                    <!-- <li>
                        <a href="<?php echo SITE_URL ?>admin/edituser.php?mt=y&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&"><?php echo TEXT_SIDE_ADD ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/addmultipleusers.php?mt=y&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&" ><?php echo TEXT_SIDE_MULTIPLE_ADD ?></a>
                    </li> -->
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/mailauser.php?mt=y&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&" ><?php echo TEXT_SIDE_EMAIL_USER ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/emailuser.php?mt=y&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&"><?php echo TEXT_SIDE_EMAIL_ALL ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/activityloguser.php?mt=y&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&"><?php echo TEXT_SIDE_ACTIVITY_LOG ?></a>
                    </li>

                </ul>
            </li>



        </ul>



        <!-- rules -->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_RULES ?></a></li>
<?php ($_GET['stylename'] == 'STYLERULES') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/rules.php?mt=y&tp=o&stylename=STYLERULES&styleminus=minus23&styleplus=plus23&"><?php echo TEXT_SIDE_RULES_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/editrules.php?mt=y&tp=c&stylename=STYLERULES&styleminus=minus23&styleplus=plus23&"><?php echo TEXT_SIDE_RULES_ADD ?></a>
                </li>
            </ul>
        </li>


        <!-- pop -->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_POP3 ?></a></li>
<?php ($_GET['stylename'] == 'STYLEPOP3') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/pop3.php?mt=y&tp=o&stylename=STYLEPOP3&styleminus=minus24&styleplus=plus24&"><?php echo TEXT_SIDE_POP3_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/editpop3.php?mt=y&tp=c&stylename=STYLEPOP3&styleminus=minus24&styleplus=plus24&" ><?php echo TEXT_SIDE_POP3_ADD ?></a>
                </li>
            </ul>
        </li>



        <!-- Knowledge base -->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_KNOWLEDGE_BASE ?></a></li>
<?php ($_GET['stylename'] == 'STYLEKNOWLEDGEBASE') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/kbcategories.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&"><?php echo TEXT_SIDE_VIEW_CATEGORIES ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/editkbcategory.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&" ><?php echo TEXT_SIDE_ADD_CATEGORIES ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/kbentries.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&"><?php echo TEXT_SIDE_VIEW_KB_ENTRIES ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/editkbentry.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&"><?php echo TEXT_SIDE_ADD_KB_ENTRIES ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/approvekbentries.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&" ><?php echo TEXT_SIDE_APPROVE_KB_ENTRIES ?></a>
                </li>

            </ul>
        </li>


        <!-- news -->
        <!-- commenting this section from admin, alreay removed from user side
       <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_NEWS ?></a></li>
<?php ($_GET['stylename'] == 'STYLENEWS') ? $style = 'list-item' : $style = 'none'; ?>
       <li class="accordionContent" style="display:<?php echo $style; ?>">
               <ul>
                       <li>
                       <a href="<?php echo SITE_URL ?>admin/news.php?mt=y&stylename=STYLENEWS&styleminus=minus11&styleplus=plus11&"><?php echo TEXT_SIDE_LIST ?></a>
                       </li>
                       <li>
                       <a href="<?php echo SITE_URL ?>admin/editnews.php?mt=y&stylename=STYLENEWS&styleminus=minus11&styleplus=plus11&"><?php echo TEXT_SIDE_ADD ?></a>
                       </li>
               </ul>
       </li>
        -->

        <!-- downloads -->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_DOWNLOADS ?></a></li>
<?php ($_GET['stylename'] == 'STYLEDOWNLOADS') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/downloads.php?mt=y&stylename=STYLEDOWNLOADS&styleminus=minus12&styleplus=plus12&" ><?php echo TEXT_SIDE_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/editdownloads.php?mt=y&stylename=STYLEDOWNLOADS&styleminus=minus12&styleplus=plus12&"><?php echo TEXT_SIDE_ADD ?></a>
                </li>
            </ul>
        </li>


        <!-- private messages -->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_PRIVATE_MESSAGES ?></a></li>
<?php ($_GET['stylename'] == 'STYLEPRIVATEMESSAGES') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus13&styleplus=plus13&"><?php echo TEXT_SIDE_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/addpvtmessage.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus13&styleplus=plus13&"><?php echo TEXT_SIDE_ADD ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/pvtmessagesall.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus13&styleplus=plus13&"><?php echo TEXT_SIDE_ALL ?></a>
                </li>
            </ul>
        </li>

        <!-- Reminders-->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_PRIVATE_REMINDERS ?></a></li>
<?php ($_GET['stylename'] == 'STYLEREMINDERS') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/reminders.php?mt=y&stylename=STYLEREMINDERS&styleminus=minus14&styleplus=plus14&"><?php echo TEXT_SIDE_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/vwreminder.php?stylename=STYLEREMINDERS&styleminus=minus14&styleplus=plus14&"><?php echo TEXT_SIDE_ADD ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/reminders.php?mt=a&stylename=STYLEREMINDERS&styleminus=minus14&styleplus=plus14&"  class="sidemenulink"><?php echo TEXT_SIDE_ALL ?></a>
                </li>
            </ul>
        </li>


        <!-- statistics -->
        <li class="accordionButton2"><a href="<?php echo SITE_URL ?>admin/viewstatistics.php?mt=y&stylename=STYLESTATISTICS&styleminus=minus15&styleplus=plus15&" ><?php echo TEXT_SIDE_PRIVATE_STATISTICS ?></a></li>

        <!-- personal notes-->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_PERSONAL_NOTES ?></a></li>
<?php ($_GET['stylename'] == 'STYLEPERSONALNOTES') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/personalnotes.php?stylename=STYLEPERSONALNOTES&styleminus=minus16&styleplus=plus16&" ><?php echo TEXT_SIDE_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/personalnotesall.php?stylename=STYLEPERSONALNOTES&styleminus=minus16&styleplus=plus16&"  class="sidemenulink"><?php echo TEXT_SIDE_ALL ?></a>
                </li>

            </ul>
        </li>

        <!--  Profile Preferences-->
        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_PREFERANCES ?></a></li>
<?php ($_GET['stylename'] == 'STYLEPREFERANCES') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/<?php echo "editprofile.php?stylename=STYLEPREFERANCES&styleminus=minus17&styleplus=plus17&"; ?>" ><?php echo TEXT_SIDE_EDIT_PROFILE ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/<?php echo "assignfields.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus17&styleplus=plus17&"; ?>"  ><?php echo TEXT_SIDE_ASSIGN_FIELDS ?></a>
                </li>

            </ul>
        </li>
        <!--System test-->


        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_SYSTEM_TEST ?></a></li>
<?php ($_GET['stylename'] == 'STYLESYSTEMTEST') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/uploadtest.php?stylename=STYLESYSTEMTEST&styleminus=minus18&styleplus=plus18&"><?php echo TEXT_SIDE_UPLOAD_TEST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/mailtest.php?stylename=STYLESYSTEMTEST&styleminus=minus18&styleplus=plus18&" ><?php echo TEXT_SIDE_MAIL_TEST ?></a>
                </li>

            </ul>
        </li>

        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_TEMPLATES ?></a></li>
<?php ($_GET['stylename'] == 'STYLETEMPLATES') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/template.php?stylename=STYLETEMPLATES&styleminus=minus19&styleplus=plus19&"><?php echo TEXT_SIDE_TEMPLATES_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/edittemplate.php?stylename=STYLETEMPLATES&styleminus=minus19&styleplus=plus19&"><?php echo TEXT_SIDE_TEMPLATES_ADD ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/approvetemplate.php?stylename=STYLETEMPLATES&styleminus=minus19&styleplus=plus19&"><?php echo TEXT_SIDE_TEMPLATES_APPROVE ?></a>
                </li>

            </ul>
        </li>


        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_LABELS ?></a></li>
<?php ($_GET['stylename'] == 'STYLELABELS') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/label.php?stylename=STYLELABELS&styleminus=minus22&styleplus=plus22&"><?php echo TEXT_SIDE_LABELS_LIST ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/editlabel.php?stylename=STYLELABELS&styleminus=minus22&styleplus=plus22&" ><?php echo TEXT_SIDE_LABELS_ADD ?></a>
                </li>

            </ul>
        </li>

        <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_REPORTS ?></a></li>
<?php ($_GET['stylename'] == 'STYLEREPORTS') ? $style = 'list-item' : $style = 'none'; ?>
        <li class="accordionContent" style="display:<?php echo $style; ?>">
            <ul>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/repticketcomp.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_SIDE_REPORT_LIST1 ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/repstaffsummary.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&" ><?php echo TEXT_SIDE_REPORT_LIST2 ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/repstaff.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_SIDE_REPORT_LIST4 ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/repusersummary.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_SIDE_REPORT_LIST3 ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/repstaffperfomance.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_STAFF_PERFOMANCE_REPORT ?></a>
                </li>
                <li>
                    <a href="<?php echo SITE_URL ?>admin/conversationsummary.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_SIDE_REPORT_LIST5 ?></a>
                </li>

                <!--<li>
                    <a href="<?php echo SITE_URL ?>admin/departmentsummary.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_SIDE_REPORT_LIST6 ?></a>
                </li>-->

                <!-- <li>
                <a href="<?php echo SITE_URL ?>admin/repstaff.php?stylename=STYLEREPORTS&styleminus=minus20&styleplus=plus20&"><?php echo TEXT_SIDE_REPORT_LIST4 ?></a>
                </li> -->
            </ul>
        </li>


        <!--  Profile Preferences-->
        <li class="accordionButton2"><a href="<?php echo SITE_URL ?>admin/purgeoldtickets.php?stylename=STYLEMAINTENANCE&styleminus=minus21&styleplus=plus21&" ><?php echo TEXT_SIDE_MAINTENANCE ?></a></li>

<?php if ($var_livechat_enb == '1') { ?>


            <li class="accordionButton"><a href="#" onClick="return false;"><?php echo TEXT_SIDE_CHAT ?></a></li>
    <?php ($_GET['stylename'] == 'STYLECHAT') ? $style = 'list-item' : $style = 'none'; ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li>
                        <a href="" onClick="windowOpener(600, 1024, 'LiveChat', '../staff/chat/chat.php?staffid=<?php echo $_SESSION['sess_staffid']; ?>&staffname=<?php echo $_SESSION['sess_stafffullname']; ?>')" class="sidemenulink"><?php echo TEXT_SIDE_LAUNCHCHAT ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/editchat_settings.php?stylename=STYLECHAT&styleminus=minus25&styleplus=plus25&"><?php echo TEXT_SIDE_CHATSETTINGS ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/cannedmessages.php?stylename=STYLECHAT&styleminus=minus25&styleplus=plus25&"><?php echo TEXT_SIDE_LIST_CANNED_MESSAGES ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/addcannedmessage.php?stylename=STYLECHAT&styleminus=minus25&styleplus=plus25&" ><?php echo TEXT_SIDE_ADD_CANNED_MESSAGES ?></a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL ?>admin/chatlogs.php?stylename=STYLECHAT&styleminus=minus25&styleplus=plus25&">   <?php echo TEXT_SIDE_CHATLOGS ?></a>

                    </li>
                </ul>
            </li>

<?php } ?>



        <!--  Profile Preferences-->
      <li class="accordionButton2"><a href="#" onClick="javascript:window.open('<?php echo SITE_URL ?>/languages/<?php echo $_SP_language ?>/help/index.php','Help','width=680,height=610');"><?php echo TEXT_SIDE_HELP ?></a></li>



    </div>
</div>

