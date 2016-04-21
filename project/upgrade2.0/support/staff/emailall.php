<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                          |
// |                           |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
		
        include("./languages/".$_SP_language."/emailall.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EMAIL_ALL ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script>
<!--
		function clickSend(){
			alert("test");
		} 
        function sendMail() {
	                if(validateForm() == true) {
						if(document.getElementById('ddlEmail').selectedIndex < 0) {
		                        alert('<?php echo MESSAGE_JS_SELECT_EMAIL; ?>');
						}
						else {	
							document.frmMail.postback.value="SA";
							document.frmMail.method = "post";
							document.frmMail.submit();
						}
                }
                else {
                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                }
        }
		function emailSelected(){
			var frm = window.document.frmMail;
			if(eval("document.getElementById('ddlEmails" + [] + "').selected") == true){
				alert("");
			}
		}
		
		function validateProfileForm(){
			var frm = window.document.frmMail;
			var errors="";
			if(frm.txtSubject.value.length == 0){
				errors += "<?php echo MESSAGE_LOGIN_NAME_REQUIRED; ?>"+ "\n";
			}
			if(frm.txtBody.value.length == 0){
				errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>"+ "\n";
			}
			if(frm.ddlCompany.selectedIndex == 0){
				errors += "<?php echo MESSAGE_COMPANY_REQUIRED; ?>"+ "\n";
			}
			if(errors !=""){
				errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors; 
				alert(errors);
				return false;
			}else{
				frm.postback.value = "Save Changes";
				frm.submit();
			}
		}
			
        function validateForm() {
                var frm = document.frmMail;
                if(frm.txtSubject.value.length == 0) {
                        frm.txtSubject.focus();
                        return false;
                }
                else if(frm.txtBody.value.length == 0) {
                        frm.txtBody.focus();
                        return false;
                }
				//emailSelected();
                return true;
        }

        function cancel() {
                var frm = document.frmMail;
                frm.txtSubject.value="";
                frm.txtBody.value="";

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
		
		<div class="content_column_small">

		<!-- sidelinks -->
                          <?php
                                include("./includes/staffside.php");
                        ?>
                   <!-- End of side links -->


		</div>


		<div class="content_column_big">

		
				<!-- admin header -->
				<?php
						//include("./includes/staffheader.php");
				?>
				<!--  end admin header -->
                <!-- Personal notes Section -->
                 <?php
                          include("./includes/emailall.php");
                  ?>

                  <!-- End Personal notes Section  -->
         
		</div>
		
		
		
		
         
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>