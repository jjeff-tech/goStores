<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                            |
// |                                                                                                            |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editdownloads.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_DOWNLOADS ?></title>
<?php include("./includes/headsettings.php"); ?>


<script>
<!--
        function add() {

                if (document.frmDownloads.id.value.length <= 0) {

                        if (validateForm() == true) {
                                  document.frmDownloads.postback.value="A";
                                document.frmDownloads.method="post";
                                document.frmDownloads.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

function edit() {
                if (document.frmDownloads.id.value.length > 0) {
                        if (validateFormEdit() == true) {
                                document.frmDownloads.postback.value="U";
                                document.frmDownloads.method="post";
                                document.frmDownloads.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmDownloads.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmDownloads.postback.value="D";
                                document.frmDownloads.method="post";
                                document.frmDownloads.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }




function validateForm()
        {
        var frm = window.document.frmDownloads;
                var flag = false;
                if (frm.txtDownDesc.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtDownDesc.focus();
//                        return false;
                }else if (frm.txtUrl.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtUrl.focus();
//                        return false;
                }else{
                  flag=true;
                }

                if (flag == false) {

                        return false;
                }
                else {

                return true;
                }
}
function validateFormEdit()
        {
        var frm = window.document.frmDownloads;
                var flag = false;
                if (frm.txtDownDesc.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtDownDesc.focus();
//                        return false;
                }else{
                  flag=true;
                }

                if (flag == false) {

                        return false;
                }
                else {

                return true;
                }
}
        function cancel()
        {
                var frm = document.frmDownloads;
                frm.txtDownDesc.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
        }

-->
</script>
</head>

<body  bgcolor="#EDEBEB">
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
                          include("./includes/editdownloads.php");
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