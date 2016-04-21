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
        include("./languages/".$_SP_language."/edittemplates.php");
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_EDIT_TEMPLATES ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="styles/calendar.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" src="./includes/functions/calendar.js"></script>
    <script type="text/javascript" src="./includes/functions/calendar-setup.js"></script>
    <script type="text/javascript" src="./includes/functions/calendar-en.js"></script>

<script>
<!--
        function add() {

                if (document.frmNews.id.value.length <= 0) {

                        if (validateForm() == true) {
                                  document.frmNews.postback.value="A";
                                document.frmNews.method="post";
                                document.frmNews.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

function edit() {
                if (document.frmNews.id.value.length > 0) {
                        if (validateForm() == true) {
                                document.frmNews.postback.value="U";
                                document.frmNews.method="post";
                                document.frmNews.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmNews.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmNews.postback.value="D";
                                document.frmNews.method="post";
                                document.frmNews.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }




function validateForm()
        {
        var frm = window.document.frmNews;
                var flag = false;
                if (frm.txtNewsTitle.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtNewsTitle.focus();
//                        return false;
                }else if (frm.txtNews.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtNews.focus();
//                        return false;
                }else if(frm.chk_staff.checked==false && frm.chk_user.checked==false){
                    alert('<?php echo MESSAGE_JS_CHECK_ONE; ?>');
                        frm.chk_staff.focus();
                }
                else if(frm.txtDate.value.length == 0){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtDate.focus();
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
                var frm = document.frmDepartment;
                frm.txtDepartmentName.value = "";
                frm.txtEmail.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
        }
function changecompany(){
  document.frmDepartment.postback.value="CC";
  document.frmDepartment.method="post";
  document.frmDepartment.cmbParentDepartment.selectedIndex=0;
  document.frmDepartment.submit();

}
function changedept(){
  document.frmDepartment.postback.value="CP";
  document.frmDepartment.method="post";

  document.frmDepartment.submit();

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

          </td>
    <td width="1" rowspan="2" ><img src="../images/spacerr.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="20%" valign="top" >
                          <!-- sidelinks -->

                          <?php
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links -->
          </td>
          <td width="79%" valign="top"  class="whitebasic">
				<!-- admin header -->
				<?php
						include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                        include("./includes/edittemplates.php");
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