<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>                                  |
// +----------------------------------------------------------------------+
    require_once("./includes/applicationheader.php");
        include "languages/".$_SP_language."/editnote.php";
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo TEXT_ADD_NOTE;?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
       function add() {
		if (document.frmNote.id.value.length <= 0) {	
			if (validateForm(0) == true) {
				document.frmNote.postback.value="A";
				document.frmNote.method="post";
				document.frmNote.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function edit() {
		if (document.frmNote.id.value.length > 0) {	
			if (validateForm(1) == true) {
				document.frmNote.postback.value="U";
				document.frmNote.method="post";
				document.frmNote.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function deleted() {
		if (document.frmNote.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmNote.postback.value="D";
				document.frmNote.method="post";
				document.frmNote.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}
			
	}
	
	function validateForm(i)
	{
        var frm = window.document.frmNote;
		var flag = false; 
		if (frm.txtTitle.value.length == 0) {
		   alert('<?php echo MESSAGE_JS_MANDATORY_TITLE; ?>');
			frm.txtTitle.focus();
		}
		else if (frm.txtDesc.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_DESCRIPTION; ?>');
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
		var frm = document.frmNote;
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

    </td>
    <td width="1" rowspan="2"><img src="images/spacerr.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="21%" valign="top">
			   <!-- sidelinks -->
			   <?php
					  include("./includes/userside.php");
			   ?>
			   <!-- End of side links -->
          </td>
          <td width="79%" valign="top" class="whitebasic">

          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                 <?php

                  if(userLoggedIn()){
                  //************************************** User Logged In *************************************************** -->
                          include("./includes/editnote.php");
                 //************************************** User Logged In ******************************************* -->

                  }else{
                   ;
                  }
                  ?>
          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
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