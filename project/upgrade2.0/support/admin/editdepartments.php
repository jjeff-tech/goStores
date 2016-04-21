<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
        
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editdepartment.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_DEPARTMENT ?></title>
<?php include("./includes/headsettings.php"); ?>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript" src="../scripts/alphanumeric.js"></script>
<script>
$(function(){
	$('#txtResponseTime').numeric({allow:"+"});
});
<!--
        function add() {
                if (document.frmDepartment.id.value.length <= 0) {
                        if (validateForm() == true) {
                                document.frmDepartment.postback.value="A";
                                document.frmDepartment.method="post";
                                document.frmDepartment.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

function edit() {
                if (document.frmDepartment.id.value.length > 0) {
                        if (validateForm() == true) {
                                document.frmDepartment.postback.value="U";
                                document.frmDepartment.method="post";
                                document.frmDepartment.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmDepartment.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmDepartment.postback.value="D";
                                document.frmDepartment.method="post";
                                document.frmDepartment.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
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
function validateForm()
        {
        var frm = window.document.frmDepartment;
		var flag = false;
		if (frm.cmbCompany.value <= 0) {
		   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.cmbCompany.focus();
//                        return false;
		}else if ($.trim(frm.txtDepartmentName.value) == "") {
		   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.txtDepartmentName.focus();
//                        return false;
		}else if($.trim(frm.txtDeptCode.value) == "" ){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                frm.txtDeptCode.focus();
//                return false;
		}else if($.trim(frm.txtEmail.value) == "" || !checkMail(frm.txtEmail.value)){
				alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
						frm.txtEmail.focus();
//                return false;
        }else if($.trim(frm.txtResponseTime.value) == "" ){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                frm.txtResponseTime.focus();
//                return false;
		}else if(isNaN(frm.txtResponseTime.value)) {
			alert('<?php echo MESSAGE_JS_POS_NUMERIC_ERROR; ?>');
            frm.txtResponseTime.focus();
        }else {
				flag = true;
		}
		if (flag == false) {
				return false;
		}
		else {
			frm.txtResponseTime.value = parseInt(frm.txtResponseTime.value);	
			return true;
		}
}
        function cancel()
        {
                var frm = document.frmDepartment;
                frm.txtDepartmentName.value = "";
				document.frmDepartment.id.value="";
                frm.txtEmail.value = "";
                frm.txtDeptCode.value = "";
                frm.txtResponseTime.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
                
        }
function changecompany(){
  if(document.frmDepartment.id.value.length >0){
     document.frmDepartment.cmbCompany.value=document.frmDepartment.cmbCompanyhidden.value;
	 alert('<?php echo MESSAGE_JS_INTER_COMPANY_TRANSFER_ERROR; ?>');
     
  }else{
		  document.frmDepartment.postback.value="CC";
		  document.frmDepartment.method="post";
		  document.frmDepartment.cmbParentDepartment.selectedIndex=0;
		  document.frmDepartment.submit();
  }

}
function changedept(){
  document.frmDepartment.postback.value="CP";
  document.frmDepartment.method="post";

  document.frmDepartment.submit();

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
                         include("./includes/editdepartment.php");
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