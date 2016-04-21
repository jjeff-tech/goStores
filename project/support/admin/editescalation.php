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
        include("./languages/".$_SP_language."/editescalation.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADING_EDIT_ESCALATION ?></title>
<?php include("./includes/headsettings.php"); ?>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript" src="../scripts/alphanumeric.js"></script>
<script>
<!--
        function add() {
                if (document.frmEscalation.id.value.length <= 0) {
                        if (validateForm() == true) {
                                document.frmEscalation.postback.value="A";
                                document.frmEscalation.method="post";
                                document.frmEscalation.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function edit() {
                if (document.frmEscalation.id.value.length > 0) {
                        if (validateForm() == true) {
                                document.frmEscalation.postback.value="U";
                                document.frmEscalation.method="post";
                                document.frmEscalation.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmEscalation.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmEscalation.postback.value="D";
                                document.frmEscalation.method="post";
                                document.frmEscalation.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }

        }

        function validateForm()
        {
                var frm = window.document.frmEscalation;
                var flag = false;
                if (frm.txtRuleName.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtRuleName.focus();

                }
                else if (frm.txtCompany.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtCompany.focus();

                }
                else if(frm.txtDept.value == ""){
                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtDept.focus();
                }
                else if(frm.txtStaff.value == ""){
                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtStaff.focus();

                }
                else if(document.getElementById('sett_time').checked == false && document.getElementById('sett_count').checked == false) {


                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        document.getElementById('sett_time').checked=true;
                        $('#span_response').text('<?php echo TEXT_MIN ?>');
                        $('#ticketlabel').text('<?php echo TEXT_RESPONSE_TIME ?>');

                }
                else if(frm.txtResponseSetting.value == ""){

                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtResponseSetting.focus();

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
                var frm = document.frmEscalation;
                frm.txtRuleName.value = "";
                frm.txtCompany.value = "";
                frm.txtDept.value = "";
                frm.txtStaff.value = "";
                frm.txtTime.value = "";
                frm.txtCount.value = "";
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
                          include("./includes/editescalation.php");
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