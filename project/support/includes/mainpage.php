<?php
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
}
?>


<div class="icons_main_container">
    <div class="icons_left_column">
        <div class="icon1">
            <?php $ticketUrl = SITE_URL."postticket.php?mt=y&stylename=POSTTICKETS&styleminus=twominus&styleplus=twoplus&"; ?>
            <a class="icon_image1" href="<?php echo $ticketUrl;?>"></a>
            <h2><a href="<?php echo $ticketUrl;?>" > <?php echo CREATE_NEW_TCKT; ?></a></h2>
            <p><?php echo CREATE_NEW_DEF ?></p>
        </div>
        <?php if ($var_livechat_enb == '1') { ?>
        <div class="icon2">
            <a href="javascript:void(0)" onClick="javascript:window.open('index_client_chat.php?comp=<?php echo $_SESSION["sess_usercompid"];?>&ref=visitorChat','LiveChat','width=475,height=500,resizable=yes,location=no');" class="icon_image2"></a>
            <h2>  <a href="javascript:void(0)" onClick="javascript:window.open('index_client_chat.php?comp=<?php echo $_SESSION["sess_usercompid"];?>&ref=visitorChat','LiveChat','width=475,height=500,resizable=yes,location=no');"><?php echo TEXT_LAUNCH_CHAT ?></a></h2>
            <p><?php echo TEXT_LAUNCH_CHAT_DEF ?></p>
        </div>
        <?php }?>
        <div class="icon3">
            <?php 
            //$kbUrl = SITE_URL."knowledgebase.php?mt=y&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&";
            $kbUrl = SITE_URL."kb/";
            ?>
            <a href="<?php echo $kbUrl;?>" class="icon_image3"></a>
            <h2> <a href="<?php echo $kbUrl;?>"><?php echo TEXT_SIDE_KNOWLEDGEBASE ?></a></h2>
            <p><?php echo KNOWLEDGE_BASE_DEF ?></p>
        </div>
    </div>
    
    <div class="icons_right_column">
        <div class="icon4">
            <?php $viewTicketsUrl = SITE_URL."tickets.php?mt=y&tp=a&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&"; ?>
            <a href="<?php echo $viewTicketsUrl;?>" class="icon_image4"></a>
            <h2> <a href="<?php echo $viewTicketsUrl;?>" ><?php echo VIEW_MY_TCKT ." (".$var_cntall.")";?></a></h2>
            <p><?php echo VIEW_TICKETS_DEF ?></p>
        </div>
        <!-- <div class="icon5"><h2>All Projects</h2>
        <p>Manage all your existing projects here. You may view the project status, participate in discussions and pay your invoices. You can easily track all the development phase of your project.</p>
        </div> -->
        <div class="icon5">
            <?php $profileUrl = SITE_URL."editprofile.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&"; ?>
            <a href="<?php echo $profileUrl;?>" class="icon_image5"></a>
            <h2> <a href="<?php echo $profileUrl;?>" ><?PHP echo TEXT_SIDE_SETTINGS ?> </a></h2>
            <p><?php echo SETTING_DEF ?></p>
        </div>
        <!--<div class="icon6"><h2>>> Question Bank</h2>
        <p>Are you looking for a quick help. Before posting a support help, you may search your question in our question bank
        whether a similar question is already added to the question bank or not. It will usually help you to get an immediate solution.</p>
        </div>-->
    </div>
    <div class="clear"></div>
</div>