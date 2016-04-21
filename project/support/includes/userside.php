<!--<link type="text/css" href="<?php echo SITE_URL ?>styles/DropdownMenu/format.css" rel="stylesheet" />-->
<script type="text/javascript" src="<?php echo SITE_URL ?>scripts/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL ?>scripts/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL ?>scripts/javascript.js"></script>
<style>
    /* use a semi-transparent image for the overlay */
    #overlay {
        background-image:url(images/transparent.png);
        color:#ffffff;
        height:450px;
    }
    /* container for external content. uses vertical scrollbar, if needed */
    div.contentWrap {
        height:441px;
        overflow-y:auto;
    }

    /* the overlayed element */
    .apple_overlay {

        /* initially overlay is hidden */
        display:none;

        /* growing background image */
        background-image:url(images/white.png);

        /*
          width after the growing animation finishes
          height is automatically calculated
        */
        width:640px;

        /* some padding to layout nested elements nicely  */
        padding:35px;

        /* a little styling */
        font-size:11px;
    }

    /* default close button positioned on upper right corner */
    .apple_overlay .close {
        background-image:url(images/close.png);
        position:absolute; right:5px; top:5px;
        cursor:pointer;
        height:35px;
        width:35px;
    }
</style>
<script src="<?php echo SITE_URL ?>scripts/jquery.tools.min.js"></script>

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

       if($_SESSION["sess_userid"]){
        $var_userid = $_SESSION["sess_userid"];
    }else{
        $var_userid = $_SESSION["default_userID"];
    }
    $sql = "Select nTicketId from sptbl_tickets where vStatus='open' and vDelStatus='0' and nUserId IN (" . $var_userid . ")";
    $var_cntopen = mysql_num_rows(executeSelect($sql, $conn));
    $sql = "Select nTicketId from sptbl_tickets where vStatus='closed' and vDelStatus='0' and nUserId IN (" . $var_userid . ")";
    $var_cntclosed = mysql_num_rows(executeSelect($sql, $conn));
    $sql = "Select nTicketId from sptbl_tickets where vStatus='escalated' and vDelStatus='0' and nUserId IN (" . $var_userid . ")";
    $var_cntescalated = mysql_num_rows(executeSelect($sql, $conn));
    $sql = "Select nTicketId from sptbl_tickets where vDelStatus='0' and nUserId IN (" . $var_userid . ")";
    $var_cntall = mysql_num_rows(executeSelect($sql, $conn));


    // Select Extra Status of Tickets for menu Listing

    $sqlExtraStat = "SELECT count(st.nTicketId ) AS tCount, sl.`vLookUpValue` , st.vRefNo
                                            FROM `sptbl_lookup` sl
                                                LEFT JOIN sptbl_tickets st ON st.vStatus = sl.`vLookUpValue`
                                                    WHERE sl.`vLookUpName` LIKE 'ExtraStatus' AND st.vDelStatus ='0' AND st.nUserId='" . $var_userid . "'
                                                    GROUP BY st.vStatus ";
    $rsExtraStat = executeSelect($sqlExtraStat, $conn);

    $var_statusRow = mysql_num_rows($rsExtraStat);
// End Status Check
    ?>
<!-- ------       Side Links Start               ------------->
<script>
    function getSearchdata(email,ref){

        var txtEmail = email;
        var txtTicketRef = ref;

        var dataString = {"txtEmail":txtEmail,"txtTicketRef":txtTicketRef };

        $.ajax({

            url		:"ticketsearch.php",

            type		:"GET",

            data		:dataString,

            dataType            : "json",

            success		:function(data){ //alert(data);

                if(data.response=='success')
                {

                    //  $("#kbSearchResult").html(response);
                    // $("#txt_kbSearchResult").val(response);
                    var var_userid = data.var_userid;

                    var var_tid = data.var_tid;

                    var thehref='ticketpop.php?var_tid='+var_tid+'&var_userid='+var_userid;
                    // alert(thehref);
                    //loadDiv(thehref);
                    $("#clsclick").attr("href", thehref)

                    $("#clsclick").trigger("click");
                    //$('#clsclick').fireEvent('click');
                }
                else
                {
                    alert("No Ticket Details Found");
                    return false;
                }


            }

        });
    }

</script>
<?php
// Ticket Status Check From Email
if(isset($_GET['email']) && $_GET['ref'] && $_GET['email']!="" && $_GET['ref']!=""){
    $txtEmail       = $_GET['email'];
    $txtTicketRef   = $_GET['ref'];
    echo("<script>getSearchdata('".$txtEmail."','".$txtTicketRef."');</script>");
}
?>
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

            <li class="accordionButton2"><a href="<?php echo SITE_URL ?>kb/"><?php echo TEXT_SIDE_KNOWLEDGEBASE ?></a></li>
            <li class="accordionButton2"><a href="<?php echo SITE_URL ?>postticket.php?mt=y&stylename=POSTTICKETS&styleminus=twominus&styleplus=twoplus&"><?php echo TEXT_SIDE_POST_TICKETS ?></a></li>

            <li class="accordionButton"><a href="javascript:void(0)"><?php echo TEXT_SIDE_SETTINGS ?></a></li>
                <?php ($_GET['stylename'] == 'SETTINGS') ? $style = 'list-item' : $style = 'none';
                ?>
            <li class="accordionContent" style="display:<?php echo $style; ?>">
                <ul>
                    <li><a href="<?php echo SITE_URL ?>editprofile.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&" ><?php echo TEXT_EDIT_PROFILE_MENU ?></a></li>
                    <li><a href="<?php echo SITE_URL ?>emailsettings.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&" ><?php echo TEXT_MY_EMAIL ?></a></li>                    
                </ul>
            </li>
            
            <li class="accordionButton2">
                <a href="#" onClick="javascript:window.open('<?php echo SITE_URL ?>languages/<?php echo $_SP_language ?>/help/index.php','Help','width=710,height=500');"  class="sidemenulink">
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


<a href=" " rel="#overlay" style="text-decoration:none" id="clsclick"> </a>
<!-- overlayed element -->
<div class="apple_overlay" id="overlay">
    <!-- the external content is loaded inside this tag -->
    <div class="contentWrap"></div>
</div>
<script>

    $(function() {

        // if the function argument is given to overlay,
        // it is assumed to be the onBeforeLoad event listener
        $("a[rel]").overlay({

            mask: 'white',
            effect: 'apple',

            onBeforeLoad: function() {

                // grab wrapper element inside content
                var wrap = this.getOverlay().find(".contentWrap");

                // load the page specified in the trigger
                wrap.load(this.getTrigger().attr("href"));
            }

        });
    });
</script>

