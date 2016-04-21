<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        
        include_once("../FCKeditor/fckeditor.php") ;
        
        if ($_POST["postback"] == "BA"){  //back
			  $location=$_SESSION["sess_abackreplyurl"];
			  header("location:$location");
			  exit;
        }
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/replies.php");
        $conn = getConnection();
        error_reporting(E_ALL ^ E_NOTICE);
?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_REPLIES ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="./../scripts/calendar.js"></script>
<script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
<script type="text/javascript" src="./languages/en/calendar.js"></script>
<script language="javascript" type="text/javascript">
<!--
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
   function changetemplate(){
           document.frmReplies.postback.value="CT";
             document.frmReplies.method="post";
           document.frmReplies.submit();

         }
     function checkaddtokb(){
                 if(document.frmReplies.chkaddtokb.checked){
                    if(document.frmReplies.cmbCategory.value<=0){
                          document.frmReplies.chkaddtokb.checked=false;
                          alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                     }else{
					     if(isParentCategorySelected(document.frmReplies.cmbCategory.options[document.frmReplies.cmbCategory.selectedIndex].value)){
                                   document.frmReplies.chkaddtokb.checked=false;
                                   alert("<?php echo MESSAGE_PARENT_CATEGORY_CANNOT_BE_SELECTED ?>");

                        }
					 }
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



     function save(){

         var EditorInstance = FCKeditorAPI.GetInstance('txtRpMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtRpMatter');
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

                            if(strFCKEditorText==""){
                                             alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                                 document.frmReplies.txtRpMatter.focus();
                                                 return false;
                           }
                           if(document.frmReplies.txtTimeSpent.value<="0"){
                                             alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                                 document.frmReplies.txtTimeSpent.focus();
                                                 return false;
                           }
                           if(document.frmReplies.txtCC.value !=""){
                                  arr = document.frmReplies.txtCC.value.split(",");
                                                  for(i=0;i< arr.length ; i++ ){
                                                                        if(!checkMail(arr[i])){
                                                                                alert('<?php echo MESSAGE_JS_MANDATORY_EMAIL_ERROR; ?>');
                                                                                document.frmReplies.txtCC.focus();
                                                                                return false;
                                                                        }
                                                  }
                            }

                           document.frmReplies.postback.value="S";
                             document.frmReplies.method="post";
                           document.frmReplies.submit();

         }

         function hold_reply(){

         var EditorInstance = FCKeditorAPI.GetInstance('txtRpMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtRpMatter');
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

                            if(strFCKEditorText==""){
                                             alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                                 document.frmReplies.txtRpMatter.focus();
                                                 return false;
                           }
                           if(document.frmReplies.txtTimeSpent.value<="0"){
                                             alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                                                 document.frmReplies.txtTimeSpent.focus();
                                                 return false;
                           }
                             if(document.frmReplies.cmbStatus.value=="closed"){
                                             alert('<?php echo MESSAGE_JS_HOLD_ERROR; ?>');
                                                 document.frmReplies.cmbStatus.focus();
                                                 return false;
                           }
                            if(document.frmReplies.chkntuser.checked==true){
                                             alert('You Cant notify User on Hold');
                                                 document.frmReplies.chkntuser.focus();
                                                 return false;
                           }
                           if(document.frmReplies.chktkowner.checked==true){
                                             alert('You Cant Take Ownership on Hold');
                                                 document.frmReplies.chktkowner.focus();
                                                 return false;
                           }
                            if(document.frmReplies.chklock.checked==true){
                                             alert('You Cant Log Ticket on Hold');
                                                 document.frmReplies.chklock.focus();
                                                 return false;
                           }
                           if(document.frmReplies.txtCC.value !=""){
                                  arr = document.frmReplies.txtCC.value.split(",");
                                                  for(i=0;i< arr.length ; i++ ){
                                                                        if(!checkMail(arr[i])){
                                                                                alert('<?php echo MESSAGE_JS_MANDATORY_EMAIL_ERROR; ?>');
                                                                                document.frmReplies.txtCC.focus();
                                                                                return false;
                                                                        }
                                                  }
                            }

                           document.frmReplies.postback.value="Hold";
                             document.frmReplies.method="post";
                           document.frmReplies.submit();

         }
         function back(){



                           document.frmReplies.postback.value="BA";
                             document.frmReplies.method="post";
                           document.frmReplies.submit();

         }

         function attach(){
			if(document.frmReplies.txtRef.value.indexOf('"') >= 0) {
				alert('" <?php echo MESSAGE_JS_NOT_PERMITTED; ?>');
				document.frmReplies.txtRef.focus();
			}
			else {
                    document.frmReplies.postback.value="AT";
                    document.frmReplies.method="post";

                    document.frmReplies.submit();
			}
    }

         function clickRemove() {
                var i=0;
                var flag = false;
                          for(i=0;i<=10;i++) {
                                                        try{
                                if(eval("document.getElementById('u" + i + "').checked") == true) {
                                                                                flag = true;
                                        break;
                                }
                                        }catch(e) {}
                        }
                        if(flag == true) {
                               if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                   document.frmReplies.postback.value="RA";
                                   document.frmReplies.method="post";
                                   document.frmReplies.submit();
                                                        }
                        }
                        else {
                                alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                       }
          }


                  function remove(id) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmReplies.attrid.value=id;
                                document.frmReplies.postback.value="R";
                                document.frmReplies.method="post";
                                document.frmReplies.submit();
                        }
        }
         function removeFromDatabse(id) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmReplies.attachdb.value=id;
                                document.frmReplies.postback.value="DelDb";
                                document.frmReplies.method="post";
                                document.frmReplies.submit();
                        }
        }



                ///closed

-->

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
                          include("./includes/replies.php");
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