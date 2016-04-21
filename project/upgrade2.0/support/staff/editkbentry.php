<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+

	require_once("./includes/applicationheader.php");
	include("./includes/functions/miscfunctions.php");
        include_once("../FCKeditor/fckeditor.php") ;
	include("./languages/".$_SP_language."/editkbentry.php");
	

    $conn = getConnection();
	
?>
<?php include("../includes/docheader.php"); ?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo HEADING_EDIT_KB_ENTRY ?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!-- 
	function add() {
		if (document.frmKBEntry.id.value.length <= 0) {	
			if (validateForm() == true) {
				document.frmKBEntry.postback.value="A";
				document.frmKBEntry.method="post";
				document.frmKBEntry.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
function edit() {
		if (document.frmKBEntry.id.value.length > 0) {	
			if (validateForm() == true) {
				document.frmKBEntry.postback.value="U";
				document.frmKBEntry.method="post";
				document.frmKBEntry.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function deleted() {
		if (document.frmKBEntry.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmKBEntry.postback.value="D";
				document.frmKBEntry.method="post";
				document.frmKBEntry.submit();
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
function validateForm(){
        var frm = window.document.frmKBEntry;
		var error = false;
		var errormessage = "";
		var flag = false; 
		
		if (frm.cmbDepartment.selectedIndex == 0) {
		   error = true;
		   errormessage += "<?php echo MESSAGE_DEPARTMENT_REQUIRED ?>" + "\n";
		}
		if (frm.cmbCategory.selectedIndex == 0) {
		   error = true;
		   errormessage += "<?php echo MESSAGE_CATEGORY_REQUIRED ?>" + "\n";
		}else{
			if(isParentCategorySelected(frm.cmbCategory.options[frm.cmbCategory.selectedIndex].value)){
				error = true;
		   		errormessage += "<?php echo MESSAGE_PARENT_CATEGORY_CANNOT_BE_SELECTED ?>" + "\n";
			}
		}
		if (frm.txtKBTitle.value.length == 0) {
		   error = true;
		   errormessage += "<?php echo MESSAGE_TITLE_REQUIRED ?>" + "\n";
		}
		var EditorInstance = FCKeditorAPI.GetInstance('txtKBDescription') ;

                ///////////////////

                var oEditor = FCKeditorAPI.GetInstance('txtKBDescription');
                var oDOM = oEditor.EditorDocument;
                var strFCKEditorText = "";
                if (document.all)// If I.E.
                {
                strFCKEditorText = oDOM.body.innerText;
                }
                else
                {
                var r = oDOM.createRange();
                r.selectNodeContents(oDOM.body);
                strFCKEditorText = r.toString();
                }
                if(strFCKEditorText=="")
                {
                 error = true;
                   errormessage += "<?php echo MESSAGE_DESCRIPTION_REQUIRED ?>" + "\n";
                }
		if(error){
			errormessage = "<?php echo MESSAGE_ERRORS_FOUND ?>" + "\n" + errormessage + "\n";
			alert(errormessage);
			return false;
		}else{
			return true;
		}
		
}

function isParentCategorySelected(catid){
	var parcats = "<?php echo getParentCategories("");?>";
	arr = parcats.split(",");
	for(i=0;i< arr.length ; i++ ){
		if(arr[i] == catid){
			return true;
			break;
		}
	}
	
	return false;
}
	function cancel()
	{
		var frm = document.frmKBEntry;
		frm.txtCategoryName.value = "";
		frm.btAdd.disabled = false;
		frm.btDelete.disabled = true;
		frm.btUpdate.disabled = true;
	}
function changeCategory(){
  document.frmKBEntry.postback.value="CC";
  document.frmKBEntry.method="post";
  document.frmKBEntry.submit();
  
}	

function changeDepartment(){
  document.frmKBEntry.postback.value="CD";
  document.frmKBEntry.method="post";
  document.frmKBEntry.submit();
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
                 <!-- Detail Section -->

                  <?php
                          include("./includes/editkbentry.php");
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