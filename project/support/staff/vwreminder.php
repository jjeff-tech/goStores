<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>		                          |
// |          									                          |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/vwreminder.php");



    $conn = getConnection();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo HEADING_VW_REMINDER?></title>
<?php include("./includes/headsettings.php"); ?>
<style type="text/css">
@import url("../styles/calendar.css");
</style>
<script type="text/javascript" src="../scripts/calendar.js"></script>
<script type="text/javascript" src="../scripts/calendar-setup.js"></script>
<script type="text/javascript" src="languages/calendar.js"></script>
<script>
<!--
        function add() {
                if (document.frmReminder.id.value.length <= 0) {
                        if (validateForm(0) == true) {
                                document.frmReminder.postback.value="A";
                                document.frmReminder.method="post";
                                document.frmReminder.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function edit() {
                if (document.frmReminder.id.value.length > 0) {
                        if (validateForm(1) == true) {
                                document.frmReminder.postback.value="U";
                                document.frmReminder.method="post";
                                document.frmReminder.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmReminder.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmReminder.postback.value="D";
                                document.frmReminder.method="post";
                                document.frmReminder.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }

        function validateForm(i)
        {
        var frm = window.document.frmReminder;
                var flag = false;
                if (frm.txtTitle.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtTitle.focus();
                }
                else if (frm.txtDesc.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtDesc.focus();
        }
                else {
                        flag = true;
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
                var frm = document.frmReminder;
                frm.txtTitle.value = "";
                frm.txtDesc.value = "";
                frm.txtStaff.value = frm.uname.value;
                frm.id.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
        }
-->
</script>

</head>

<body bgcolor="#EDEBEB">
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
          </td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="20%" valign="top" >
                          <!-- sidelinks -->

                          <?php
                                include("./includes/staffside.php");
                        ?>

                   <!-- End of side links -->
                  </td>
          <td width="79%" valign="top"  class="whitebasic">
				<!-- staff header -->
				<?php
						include("./includes/staffheader.php");
				?>
				<!--  end staff header -->
  
                <!-- Detail Section -->
                  <?php
                         include("./includes/vwreminder.php");
                 ?>
          <!-- End Detail section -->
          </td>
        </tr>
      </table>
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    </td>
  </tr>
</table>
</body>
</html>