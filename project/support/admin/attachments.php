<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                                                           |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
include("./includes/functions/miscfunctions.php");
include("./languages/" . $_SP_language . "/attachments.php");
$conn = getConnection();
?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ATTACHMENTS ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
    <!--

    function clickDelete() {
        var i=1;
        var flag = false;
        for(i=1;i<=10;i++) {
            try{
                if(eval("document.getElementById('c" + i + "').checked") == true) {
                    flag = true;
                    break;
                }
            }catch(e) {}
        }
        if(flag == true) {
            if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                document.frmAttachments.postback.value="DA";
                document.frmAttachments.method="post";
                document.frmAttachments.submit();
            }
        }
        else {
            alert('Please select the file extensions to be deleted.');
        }
    }



    function deleted(id) {
        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
            document.frmAttachments.id.value=id;
            document.frmAttachments.postback.value="D";
            document.frmAttachments.method="post";
            document.frmAttachments.submit();
        }
    }
    function clickAdd() {
        val=document.frmAttachments.txtExtension.value;
        dot=false;
        if(val==""){
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
        }else{

            for(i=0;i<val.length;i++){
                if(val.charAt(i)=="."){
                    dot=true
                }

            }
            if(dot==true){
                alert('<?php echo MESSAGE_JS_DOT_ERROR; ?>');
            }else{
                document.frmAttachments.postback.value="A";
                document.frmAttachments.method="post";
                document.frmAttachments.submit();
            }


        }

    }
    function clickSearch() {
        document.frmAttachments.numBegin.value=0;
        document.frmAttachments.begin.value=0;
        document.frmAttachments.start.value=0;
        document.frmAttachments.num.value=0;
        document.frmAttachments.method="post";
        document.frmAttachments.submit();
    }


    -->
</script>
</head>

<body>
    <!--  Top Part  -->
    <?php
    include("./includes/top.php");
    ?>
    <!--  Top Ends  -->
    <!-- header  -->
    <?php
    include("./includes/header.php");
    ?>
    <!-- end header -->


    <div class="content_column_small">

        <!-- sidelinks -->

        <?php
        include("./includes/adminside.php");
        ?>

        <!-- End of side links -->


    </div>


    <div class="content_column_big">


        <!-- admin header -->
        <?php
        //include("./includes/adminheader.php");
        ?>
        <!--  end admin header -->
        <!-- Detail Section -->
        <?php
        include("./includes/attachments.php");
        ?>
        <!-- End Detail section -->



    </div>



    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>