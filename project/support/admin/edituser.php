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
        include("./languages/".$_SP_language."/edituser.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADING_EDIT_USER?></title>
<?php include("./includes/headsettings.php"); ?>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script>
<!--
        function add() {
                if (document.frmUser.id.value.length <= 0) {
                        if (validateForm(0) == true) {
                                document.frmUser.postback.value="A";
                                document.frmUser.method="post";
                                document.frmUser.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function edit() {
                if (document.frmUser.id.value.length > 0) {
                        if (validateForm(1) == true) {
                                document.frmUser.postback.value="U";
                                document.frmUser.method="post";
                                document.frmUser.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmUser.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmUser.postback.value="D";
                                document.frmUser.method="post";
                                document.frmUser.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }

        function validateForm(i)
        {
        var frm = window.document.frmUser;
                var flag = false;
                if (frm.txtUserName.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtUserName.focus();
//                        return false;
                }
                else if (frm.txtUserLogin.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtUserLogin.focus();
//                return false;
        }else if(i == 0 && $.trim(frm.txtPassword.value) == ""){
                                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                frm.txtPassword.focus();
//                return false;
        }else if($.trim(frm.txtPassword.value) != "" && frm.txtPassword.value != frm.conPassword.value){
                                alert('Passwords do not match.');
                                frm.conPassword.value = '';
								frm.conPassword.focus();
//                return false;
        }else if(frm.txtEmail.value == ""){
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
                var frm = document.frmUser;
                frm.txtUserName.value = "";
                frm.txtUserLogin.value = "";
                frm.txtPassword.value = "";
                frm.txtEmail.value = "";
                frm.rdBanned[1].checked = true;
                frm.id.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
                document.getElementById('showError').style.visibility = 'hidden';
                document.getElementById('star').style.visibility='visible';
                document.frmUser.txtUserLogin.readOnly=false;
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
                          include("./includes/edituser.php");
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