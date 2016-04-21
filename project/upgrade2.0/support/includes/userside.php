<!--<link type="text/css" href="<?php echo SITE_URL ?>styles/DropdownMenu/format.css" rel="stylesheet" />-->
<script type="text/javascript" src="<?php echo SITE_URL ?>scripts/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL ?>scripts/javascript.js"></script>

<?php
//include("./languages/".$_SP_language."/tickets.php");
if (userLoggedIn()) {
    /* Newly Addedby Amaldev starts */
    $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='LiveChat'";
    $rs_chat = executeSelect($sql, $conn);
    if (mysql_num_rows($rs_chat) > 0) {
        $var_row = mysql_fetch_array($rs_chat);
        $var_livechat_enb = $var_row["vLookUpValue"];
    } else {
        $var_livechat_enb = '0';
    }
    /* Newly Addedby Amaldev ends */

    $var_userid = $_SESSION["sess_userid"];
    $sql = "Select nTicketId from sptbl_tickets where vStatus='open' and nUserId IN (" . $var_userid . ")";
    $var_cntopen = mysql_num_rows(executeSelect($sql, $conn));
    $sql = "Select nTicketId from sptbl_tickets where vStatus='closed' and nUserId IN (" . $var_userid . ")";
    $var_cntclosed = mysql_num_rows(executeSelect($sql, $conn));
    $sql = "Select nTicketId from sptbl_tickets where vStatus='escalated' and nUserId IN (" . $var_userid . ")";
    $var_cntescalated = mysql_num_rows(executeSelect($sql, $conn));
    $sql = "Select nTicketId from sptbl_tickets where nUserId IN (" . $var_userid . ")";
    $var_cntall = mysql_num_rows(executeSelect($sql, $conn));


    // Select Extra Status of Tickets for menu Listing

    $sqlExtraStat = "SELECT count(st.nTicketId ) AS tCount, sl.`vLookUpValue` , st.vRefNo
                                            FROM `sptbl_lookup` sl
                                                LEFT JOIN sptbl_tickets st ON st.vStatus = sl.`vLookUpValue`
                                                    WHERE sl.`vLookUpName` LIKE 'ExtraStatus' AND st.nUserId='" . $var_userid . "'
                                                    GROUP BY st.vStatus ";
    $rsExtraStat = executeSelect($sqlExtraStat, $conn);

    $var_statusRow = mysql_num_rows($rsExtraStat);
// End Status Check
    ?>
    <!-- ------       Side Links Start               ------------->
    <div class="left_section_block">                  

        <div class="leftMenu">
            <ul>
                <li class="accordionButton "><a href="javascript:void(0)"><?php echo TEXT_SIDE_VIEW_TICKETS ?></a></li>
    <?php ($_GET['stylename'] == 'VIEWTICKETS') ? $style = 'list-item' : $style = 'none'; ?>
                <li class="accordionContent" style="display:<?php echo $style; ?>">
                    <ul>
                        <li><a href="<?php echo SITE_URL ?>tickets.php?mt=y&tp=c&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_CLOSED . " (" . $var_cntclosed . ")"; ?></a></li>
                        <li><a href="<?php echo SITE_URL ?>tickets.php?mt=y&tp=o&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_OPEN . " (" . $var_cntopen . ")"; ?></a></li>
    <?php
    // Include  Additional Ticket Status Links Modified By Asha On 26-09-2012
    if ($var_statusRow > 0) {

        while ($tRow = mysql_fetch_array($rsExtraStat)) {
            $status = $tRow['vLookUpValue'];
            ?>
                                <li>
                                    <a href="<?php echo SITE_URL ?>tickets.php?mt=y&tp=<?php echo $status; ?>&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo $tRow['vLookUpValue'] . " (" . $tRow['tCount'] . ")"; ?></a>
                                </li>
            <?php
        }
    }

    @mysql_data_seek($rsExtraStat, 0);
    // End Include Extra Links for Ticket Status
    ?>
                        <li><a href="<?php echo SITE_URL ?>tickets.php?mt=y&tp=a&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_ALL . " (" . $var_cntall . ")"; ?></a></li>


                        <li><a href="<?php echo SITE_URL ?>search.php?mt=y&tp=a&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_SEARCH ?></a></li>
                    </ul>		
                </li>		




                <li class="accordionButton2"><a href="<?php echo SITE_URL ?>knowledgebase.php?mt=y&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&"><?php echo TEXT_SIDE_KNOWLEDGEBASE ?></a></li>

                <li class="accordionButton2"><a href="<?php echo SITE_URL ?>postticket.php?mt=y&stylename=POSTTICKETS&styleminus=twominus&styleplus=twoplus&"><?php echo TEXT_SIDE_POST_TICKETS ?></a></li>







                <li class="accordionButton"><a href="javascript:void(0)"><?php echo TEXT_SIDE_SETTINGS ?></a></li>
    <?php ($_GET['stylename'] == 'SETTINGS') ? $style = 'list-item' : $style = 'none';
    ?>
                <li class="accordionContent" style="display:<?php echo $style; ?>">
                    <ul>
                            <!--<li><a href="<?php echo SITE_URL ?>selectstyle.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&" ><?php echo TEXT_SELECT_COLOR_SCHEME ?></a></li>-->
                        <li><a href="<?php echo SITE_URL ?>editprofile.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&" ><?php echo TEXT_EDIT_PROFILE_MENU ?></a></li>
                        <li><a href="<?php echo SITE_URL ?>emailsettings.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&" ><?php echo TEXT_MY_EMAIL ?></a></li>
                        <!-- <li><a href="<?php echo SITE_URL ?>changepassword.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&"><?php echo TEXT_SIDE_CHANGE_PASSWORD ?></a></li> -->
                    </ul>		
                </li>


    <?php if ($var_livechat_enb == '1') { ?>
                    <li class="accordionButton2"><a href="javascript:void(0)" onClick="javascript:window.open('index_client_chat.php?comp=<?php echo $_SESSION["sess_usercompid"]; ?>&ref=visitorChat','LiveChat','width=475,height=500,resizable=yes,location=no');"><?php echo TEXT_LAUNCH_CHAT ?></a></li>
                <?php } ?>
                <li class="accordionButton2">
                    <a href="#" onClick="javascript:window.open('./languages/<?php echo $_SP_language ?>/help/index.php','Help','width=710,height=500');"  class="sidemenulink">
    <?php echo TEXT_SIDE_HELP ?>
                    </a>
                </li>


            </ul>
            <div class="clear"></div>
        </div>     


        <!-- ------       Side Links End                  ------------->
        <div class="clear"></div>
    </div> 



    <div class="left_section_block">  
    <?php //include "includes/newsbox.php";  ?>
    </div>


    <?php
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $arrurl = ($url != NULL) ? explode("/", $url) : 0;
    $pageurl = end($arrurl);

    if (!preg_match('/^editfeedback.php.*/', $pageurl) && !preg_match('/^rating.php.*/', $pageurl) && !preg_match('/^replies.php.*/', $pageurl)) {
        ?>
        <div class="left_section_block">  
            <?php //  include "includes/newsbox.php"; ?>
        </div> <?php } ?>


<?php } else { ?>
    <!----if not logged in--->

    <div class="left_section_block">  
        <?php include "includes/loginbox.php"; ?>
    </div>

<?php } ?>




