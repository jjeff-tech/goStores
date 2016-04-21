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
		
        include("./languages/".$_SP_language."/edittemplate.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo $_REQUEST["id"]!='' ? HEADING_EDIT_TEMPLATES : HEADING_ADD_TEMPLATES;  ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--

	function add() {
	   
		if (document.frmTemplate.id.value.length <= 0) {	
		
			if (validateForm() == true) {
			  	document.frmTemplate.postback.value="A";
				document.frmTemplate.method="post";
				document.frmTemplate.submit();
			}
		}
		else {
			alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
		}	
	}
	
function edit() {
		if (document.frmTemplate.id.value.length > 0) {	
			if (validateForm() == true) {
				document.frmTemplate.postback.value="U";
				document.frmTemplate.method="post";
				document.frmTemplate.submit();
			}
		}
		else {
			alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
		}	
	}
	
	function deleted() {
		if (document.frmTemplate.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmTemplate.postback.value="D";
				document.frmTemplate.method="post";
				document.frmTemplate.submit();
			}
		}
		else {
			alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
		}
			
	}
function validateForm(){
        var frm = window.document.frmTemplate;
		var flag = false; 
		
		if (frm.txtTitle.value.length == 0) {
		   alert("<?php echo MESSAGE_JS_MANDATORY_TEMPLATE_TITLE; ?>");
			frm.txtTitle.focus();
//			return false;
		}else if (frm.txtDesc.value.length == 0) {
		   alert("<?php echo MESSAGE_JS_MANDATORY_TEMPLATE_DESC; ?>");
			frm.txtDesc.focus();
//			return false;
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
		var frm = document.frmTemplate;
		frm.txtTitle.value = "";
		frm.txtDesc.value = "";
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
                <!-- Template Section -->
                 <?php
                          include("./includes/edittemplate.php");
                  ?>

                  <!-- End Template  Section  -->
     
		</div>

      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>