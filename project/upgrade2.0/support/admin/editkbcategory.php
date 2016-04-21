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
// |                                                                                                           |
// +----------------------------------------------------------------------+


        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editkbcategory.php");
        $conn = getConnection();
         $page = 'kbentries';

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_CATEGORY ?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function add() {
                if (document.frmCategory.id.value.length <= 0) {
                        if (validateForm() == true) {
                                document.frmCategory.postback.value="A";
                                document.frmCategory.method="post";
                                document.frmCategory.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

function edit() {
                if (document.frmCategory.id.value.length > 0) {
                        if (validateForm() == true) {
                                document.frmCategory.postback.value="U";
                                document.frmCategory.method="post";
                                document.frmCategory.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

        function deleted() {
                if (document.frmCategory.id.value.length > 0) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmCategory.postback.value="D";
                                document.frmCategory.method="post";
                                document.frmCategory.submit();
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
        var frm = window.document.frmCategory;
                var flag = false;

                if (frm.cmbCompany.selectedIndex == 0) {
                   alert('<?php echo MESSAGE_JS_COMPANY_NULL; ?>');
                        frm.cmbCompany.focus();
                }else if (frm.cmbDepartment.selectedIndex == 0) {
                   alert('<?php echo MESSAGE_JS_DEPARTMENT_NULL; ?>');
                        frm.cmbDepartment.focus();
                }else if (frm.txtCategoryName.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtCategoryName.focus();
                }else /*if (frm.cmbParentCategory.selectedIndex != 0) {
                        alert();
                          if(isCategoryWithEntriesSelected(frm.cmbParentCategory.options[frm.cmbParentCategory.selectedIndex].value)){
                                alert('<?php echo MESSAGE_JS_CATEGORY_HAS_ENTRIES; ?>');
                                frm.cmbParentCategory.focus();
                        }
                }else*/ {
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
                var frm = document.frmCategory;
                frm.txtCategoryName.value = "";
                frm.btAdd.disabled = false;
                frm.btDelete.disabled = true;
                frm.btUpdate.disabled = true;
        }
function changecompany(){
  document.frmCategory.postback.value="CC";
  document.frmCategory.method="post";
  //document.frmCategory.cmbDepartment.selectedIndex=0;
  document.frmCategory.submit();

}

function changedepartment(){
  document.frmCategory.postback.value="CD";
  document.frmCategory.method="post";
  //document.frmCategory.cmbParentCategory.selectedIndex=0;
  document.frmCategory.submit();
}

function changedept(){
  document.frmCategory.postback.value="CP";
  document.frmCategory.method="post";
  document.frmCategory.submit();

}

function isCategoryWithEntriesSelected(catid){
        var catswithentries = "<?php echo getCategoriesWithEntries();?>";
        arr = catswithentries.split(",");
        for(i=0;i< arr.length ; i++ ){
                if(arr[i] == catid){
                        return true;
                        break;
                }
        }
        return false;
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
                          include("./includes/editkbcategory.php");
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