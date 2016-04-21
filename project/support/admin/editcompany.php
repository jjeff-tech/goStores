<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editcompany.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADING_EDIT_COMPANY?></title>
<?php include("./includes/headsettings.php"); ?>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript" src="../scripts/alphanumeric.js"></script>
<script>
$(function(){
	$('#txtPhone').numeric({allow:"(,),-,+"});
	$('#txtFax').numeric({allow:"(,),-,+"});
});
<!--
        function add() {
                if (document.frmCompany.id.value.length <= 0) {
                        if (validateForm() == true) {
                                document.frmCompany.postback.value="A";
                                document.frmCompany.method="post";
                                document.frmCompany.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function edit() {
                if (document.frmCompany.id.value.length > 0) {
                        if (validateForm() == true) {
                                document.frmCompany.postback.value="U";
                                document.frmCompany.method="post";
                                document.frmCompany.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmCompany.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmCompany.postback.value="D";
                                document.frmCompany.method="post";
                                document.frmCompany.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }

        function validateForm()
        {
        var frm = window.document.frmCompany;
                var flag = false;
                if (frm.txtCompanyName.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtCompanyName.focus();

                }
                else if (frm.txtAddress1.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtAddress1.focus();

        }else if(frm.txtCity.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtCity.focus();
        }else if(frm.txtEmail.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtEmail.focus();

        }else if(!checkMail(frm.txtEmail.value)){
                alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                frm.txtEmail.select();
                frm.txtEmail.focus();

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
                var frm = document.frmCompany;
                frm.txtCompanyName.value = "";
                frm.txtAddress1.value = "";
                frm.txtAddress2.value = "";
                frm.txtCity.value = "";
                frm.txtState.value = "";
                frm.txtZip.value = "";
                frm.txtPhone.value = "";
                frm.txtFax.value = "";
                frm.txtEmail.value = "";
                frm.txtContact.value = "";
                frm.id.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
        }
-->
</script>
</head>

<body bgcolor="#EDEBEB" topmargin="0"  bottommargin="0" leftmargin="10" rightmargin="10">
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
                          include("./includes/editcompany.php");
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