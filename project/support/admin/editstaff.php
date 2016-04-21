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
        include("./languages/".$_SP_language."/editstaff.php");
        $conn = getConnection();

?>

<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_STAFF?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function add() {
                if (document.frmStaff.id.value.length <= 0) {
                        if (validateForm(0) == true) {
                                document.frmStaff.postback.value="A";
                                document.frmStaff.method="post";
                                document.frmStaff.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function edit() {
                if (document.frmStaff.id.value.length > 0) {
                        if (validateForm(1) == true) {
                                document.frmStaff.postback.value="U";
                                document.frmStaff.method="post";
                                document.frmStaff.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmStaff.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmStaff.postback.value="D";
                                document.frmStaff.method="post";
                                document.frmStaff.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }

        function validateForm(i)
        {
        var frm = window.document.frmStaff;
                var flag = false;
                if (frm.txtStaffName.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtStaffName.focus();
//                        return false;
                }
                else if (frm.txtStaffLogin.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtStaffLogin.focus();
//                return false;
        }else if(i == 0 && frm.txtPassword.value == ""){
                                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                frm.txtPassword.focus();
//                return false;
        }/*else if(frm.cmbRefresh.selectedIndex == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.cmbRefresh.focus();
//                return false;
        }*/else if(frm.txtEmail.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtEmail.focus();
//                return false;
        }else if(!checkMail(frm.txtEmail.value)){
                alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                frm.txtEmail.select();
                frm.txtEmail.focus();
//                return false;
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

function checkMail(email)
{
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
                eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
                        eFlag = false;
        }
        else
        {
                var dot=arr[1].split('.');
                if(dot.length < 2)
                {
                        eFlag = false;
                }
                else
                {
                        if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                        {
                                eFlag = false;
                        }

                        for(i=1;i < dot.length;i++)
                        {
                                if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1)
                                {
                                        eFlag = false;
                                }
                        }
						if(dot[i-1].length > 4)
							eFlag = false;
                }
        }
                return eFlag;
}

        function cancel()
        {
                var frm = document.frmStaff;
                frm.txtStaffName.value = "";
                frm.txtStaffLogin.value = "";
                frm.txtPassword.value = "";
                frm.txtEmail.value = "";
                frm.txtYim.value = "";
                frm.txtSmsMail.value = "";
                frm.txtMobile.value = "";
                frm.rdNotifyAssign[1].checked = true;
                frm.rdNotifyPvtMsg[1].checked = true;
                frm.rdNotifyKB[1].checked = true;
                frm.id.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
                document.getElementById('showError').style.visibility = 'hidden';
                document.getElementById('star').style.visibility='visible';
                document.frmStaff.txtStaffLogin.readOnly=false;
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
                          include("./includes/editstaff.php");
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