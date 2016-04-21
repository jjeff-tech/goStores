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
// +----------------------------------------------------------------------+

    require_once("./includes/applicationheader.php");
        include "languages/".$_SP_language."/replies.php";
        include_once("FCKeditor/fckeditor.php") ;
        $conn = getConnection();
        
?>
<?php include("./includes/docheader.php"); ?>
<title><?php echo HEADING_REPLIES;?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
function save(){

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
			   
			   document.frmReplies.postback.value="S";
		  	   document.frmReplies.method="post";
			   document.frmReplies.submit();
	 
	 }
	 function attach(){
           if(document.frmReplies.txtRef.value.indexOf('"') >= 0) {
				alert('<?php echo MESSAGE_JS_NOT_PERMITTED; ?>');
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
                                alert('Please select the attachment/s to remove.');
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
                          include("./includes/userside.php");
                   ?>
                   <!-- End of side links -->
				   </div>
				   <div class="content_column_big">
         
			<!-- admin header -->
			<?php
					//include("./includes/userheader.php");
			?>
			<!--  end admin header -->
            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                 <?php

                  if(userLoggedIn()){
                  //************************************** User Logged In *************************************************** -->
                          include("./includes/replies.php");
                 //************************************** User Logged In ******************************************* -->

                  }else{
                   ;
                  }
                  ?>
				  </div>
          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
          
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
  
</body>
</html>