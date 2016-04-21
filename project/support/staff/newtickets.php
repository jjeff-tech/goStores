<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
include("./includes/functions/miscfunctions.php");

include("./languages/".$_SP_language."/newtickets.php");
$conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_NEW_TICKETS ?></title>
<?php include("./includes/headsettings.php"); ?>

<?php $var_maxposts = (int)$_SESSION["sess_maxpostperpage"];?>

<!-- Tooltip -->

<link type="text/css" rel="stylesheet" media="screen" href="./../scripts/tooltip/jquery.qtip.css" />
<script type="text/javascript" src="./../scripts/tooltip/jquery.qtip-1.0.0-rc3.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        $('.tooltip').each(function()
        {

            var string = $(this).attr("id");
            var newarray = string.split('x');
            var tkid = newarray[0];
            var usid = newarray[1];
            $(this).qtip(
            {

                content: {
                    text: 'Loading...',
                    url: 'ajax.php?act=ticketdetails&id='+ tkid,
                    data: ''
                },
                position: {
                    corner: {
                        target: 'rightBottom',
                        tooltip: 'topLeft'
                    }
                },
                style: {
                    tip: true, // No need to specify the corner explicitly if you want it to default to the tooltip corner
                    name: 'defaults'
                }
            });

        });

    });
</script>

<!-- Tooltip -->

<style>
    .imgFollow{ cursor: pointer;}
</style>

<script language="javascript" type="text/javascript">
    /*  For Ticket Follow   */
    $(document).ready(function(){

        $('img.imgFollow').each(function(){

            var id  =   $(this).attr('id');

            var dataString = {"ticketId":id};

            $.ajax({
                url	        :"ajax_response.php",
                type	:"post",
                data	:dataString,
                dataType 	:"json",
                success	:function(data){

                    if(data.count == 0){
                        $('#'+ data.id).attr('src','./../images/star-grey.png');
                    }
                    else{
                        $('#'+ data.id).attr('src','./../images/star-yellow.png');
                    }

                }
            });

        });

        $('img.imgFollow').click(function(){

            var img  =   $(this).attr('src');
            var id  =   $(this).attr('id');
            var follow  =   '';

            if(img.indexOf('yellow') == -1){

                $('#'+id).attr('src','./../images/star-yellow.png');
                follow = 1;

            }else{

                $('#'+id).attr('src','./../images/star-grey.png');
                follow = 0;

            }

            var dataString = {"follow":follow,"ticketId":id};

            $.ajax({
                url	        :"ajax_response.php",
                type	:"post",
                data	:dataString,
                dataType 	:"json",
                success	:function(data){

                    followcount();
                    var pageUrl = $(location).attr('href');

                    if(pageUrl.indexOf('tp=f') != -1){
                        window.location.reload();
                    }

                }
            });

        });

    });

    function followcount(){

        var dataString = {"count":'followcount'};
        $.ajax({
            url	        :"ajax_response.php",
            type	:"post",
            data	:dataString,
            dataType 	:"json",
            success	:function(data){

                $('#follow_count').html(data.totCount);

            }
        });

    }

    <!--
    function clickSearch() {
        if (validateSearch() == true) {
            document.frmSearch.method="post";
            document.frmSearch.submit();
        }
    }

    function validateSearch() {
        var frm = document.frmSearch;
        if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
            return false;
        }
        else {
            return true;
        }
    }
    function changeDepartment() {
        document.frmDetail.numBegin.value="";
        document.frmDetail.num.value="";
        document.frmDetail.start.value="";
        document.frmDetail.begin.value="";
        document.frmDetail.method="post";
        document.frmDetail.submit();
    }
    function deleteTickets(chk) {
        var flag = false;
        if(chk == 0) {
            document.frmDetail.del.value="DM";
            for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
                try{
                    if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
                        flag = true;
                        break;
                    }
                }catch(e) {}
            }
        }
        if(flag == true) {
            if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                document.frmDetail.method="post";
                document.frmDetail.submit();
            }
        }
        else {
            alert('<?php echo MESSAGE_JS_SELECT_ONE; ?>');
        }
    }

    function clickUpdate(chk) { 
        var flag = false;
        var counter = 0;
        if(chk == 0) {
            if(document.frmDetail.cmbAction.value=='delete')
                document.frmDetail.del.value="DM";
            if(document.frmDetail.cmbAction.value=='spam')
                document.frmDetail.del.value="MS";
            if(document.frmDetail.cmbAction.value=='merge')
                document.frmDetail.del.value="MERGE";
            
            if(document.frmDetail.cmbStatus.value !="")
                document.frmDetail.del.value="UP";
            if(document.frmDetail.cmbLabel.value>0)
                document.frmDetail.labelup.value="LABELUP";


            if(document.frmDetail.cmbAction.value=='merge'){
                for(j=1;j<=<?php echo $var_maxposts?>;j++) {
                    try{
                        if(document.getElementById('chkDeleteTickets' + j ).checked == true) {
                            counter++;
                        }
                    }catch(e) {}
                }

                if(counter < 2){
                    alert('<?php echo MESSAGE_JS_SELECT_ONE_2; ?>');
                    return;
                }
            }
            

            for(i=1;i<=<?php echo $var_maxposts?>;i++) {

                try{
                    if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
                        flag = true; 
                        break;
                    }
                }catch(e) {}
            } 
        }  
        if(flag == true) {  

            if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT_1; ?>')) {
                document.frmDetail.method="post";
                document.frmDetail.submit();
            }
        }
        else {
            alert('<?php echo MESSAGE_JS_SELECT_ONE_1; ?>');
        }
    }

    <!-- to move labels -->
    function clickUpdateLabel(chk) {
        var flag = false;
        if(chk == 0) {
            document.frmDetail.del.value="LABELUP";
            for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
                try{
                    if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
                        flag = true;
                        break;
                    }
                }catch(e) {}
            }
        }
        if(flag == true) {
            if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT_1; ?>')) {
                document.frmDetail.method="post";
                document.frmDetail.submit();
            }
        }
        else {
            alert('<?php echo MESSAGE_JS_SELECT_ONE_1; ?>');
        }
    }
    <!-- end label update -->

    function checkallfn(){
        if(document.frmDetail.checkall.checked){
            for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
                try{

                    document.getElementById('chkDeleteTickets' + i ).checked=true;
                }catch(e) {}
            }

        }else{
            for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
                try{

                    document.getElementById('chkDeleteTickets' + i ).checked=false;
                }catch(e) {}
            }
        }
    }
    -->
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="./../scripts/calendar.js"></script>
<script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
<script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
<script type="text/javascript" src="./includes/functions/ajax.js"></script>
</head>

<body bgcolor="#EDEBEB">
    <!-- Ajax tool tip-->
    <div id="tooltipBox" onMouseOver="clearAdInterval();" onMouseOut="hideAd();" style="z-index:5000;position:absolute;cursor:pointer;"></div>
    <!--end  Ajax tool tip-->

    <!--  Top Part  -->
    <?php
    include("./includes/top.php");
    ?>
    <!--  Top Ends  -->

    <!--  Top links  -->

    <?php
//                 include("./includes/toplinks.php");
    ?>

    <!--  End Top Links -->

    <!-- header  -->
    <?php
    include("./includes/header.php");
    ?>
    <!-- end header -->

    <div class="content_column_small">    <!-- sidelinks -->

        <?php
        include("./includes/staffside.php");
        ?>

        <!-- End of side links -->

        <!-- admin header -->

    </div>
    <div class="content_column_big">
        <?php
        //include("./includes/staffheader.php");
        ?>
        <!--  end admin header -->
        <!-- Tickets Assigned Section -->

        <?php
        include("./includes/newtickets.php");
        ?>

        <!-- End Tickets Assigned  section -->
        <!-- Advanced Search -->


        <?php
        include("./includes/advancedsearch.php");
        ?>

        <!-- End Advanced Search -->
    </div>

    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
<script>
    var df = '<?php echo($var_deptid); ?>';
    document.frmDetail.cmbDepartment.value=df;
</script>
</html>