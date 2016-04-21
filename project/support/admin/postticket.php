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
	include("./includes/functions/miscfunctions.php");
    include "./languages/".$_SP_language."/postticket.php";
    include_once("../FCKeditor/fckeditor.php") ;
        $conn = getConnection();
?>
<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADER_POST_TICKET;?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
     function addticket(){
          var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtMatter');
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
                 
                         if (validateForm() == true) {
                                         strFCKEditorText =  EditorInstance.EditorDocument.body.innerHTML;
                                         //alert(strFCKEditorText);
                                         //exit;
					 document.frmPostTicket.cmbUser.value=document.frmInfo.cmbUser.value;
                                         document.frmPostTicket.deptid.value=document.frmInfo.cmbDept.value;
                                         document.frmPostTicket.prty.value=document.frmInfo.cmbPriority.value;
                                         document.frmPostTicket.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                                         document.frmPostTicket.tcktdesc.value=strFCKEditorText;
                                         document.frmPostTicket.uploadfiles.value=document.frmAttach.uploadedfiles.value;
                                         document.frmPostTicket.txtUsername.value=document.frmInfo.txtUsername.value;
                                         document.frmPostTicket.postback.value="S";
                                     document.frmPostTicket.method="post";
                                    /* alert('1111');
        alert(document.frmInfo.txtTcktTitle.value);
       alert('222');
       alert(document.frmInfo.txtMatter.value);
        alert('333');
        alert(strFCKEditorText);
        exit;*/
                                     document.frmPostTicket.submit();
                        }


         }
     function validateForm()
        {
        var flag = false;
        var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtMatter');
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
		       
			   
		        /*if(document.frmInfo.cmbUser.value==""){
                            var usermsg = '<?php echo MESSAGE_USER_NOT_EXISTS; ?>';
					  alert(usermsg);
					 document.frmInfo.cmbUser.focus();
				}
		  		else */
                                    if(document.frmInfo.cmbDept.value==""){
				  alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
                        document.frmInfo.cmbDept.focus();
				}else if (document.frmInfo.txtTcktTitle.value.length == 0) {
                    alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
                        document.frmInfo.txtTcktTitle.focus();
//                        return false;
                }
                else if(strFCKEditorText=="")
                {
                    alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
                   document.frmInfo.txtMatter.focus();
                }
                
                else{
                  flag=true;
                }

                if (flag == false) {

                        return false;
                }
                else {

                return true;
                }
      }
	  function changeUser() { 
		  if(document.frmInfo.cmbUser.value==""){
			  alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
			  document.frmInfo.cmbUser.focus();
		  }
		  else {						
			document.frmAttach.postback.value="CU";
			document.frmAttach.method="post";
			document.frmAttach.cmbUser.value=document.frmInfo.cmbUser.value;
			document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
			document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
			document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
			document.frmAttach.tcktdesc.value=document.frmInfo.txtMatter.value
			document.frmAttach.submit();
		  }		
	  }

        
           function attach(){

             var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtMatter');
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
					if(document.frmAttach.txtRef.value.indexOf('"') >= 0) {
						alert("<?php echo MESSAGE_JS_NOT_PERMITTED; ?>");
						document.frmAttach.txtRef.focus();
						return false;
					}else if(document.frmAttach.txtUrl.value.indexOf('"') >= 0) {
						alert("<?php echo MESSAGE_JS_NOT_PERMITTED; ?>");
						document.frmAttach.txtUrl.focus();
						return false;
					}
					else {
						document.frmAttach.postback.value="A";
						document.frmAttach.method="post";
						document.frmAttach.cmbUser.value=document.frmInfo.cmbUser.value;
						document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
						document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
						document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                                                document.frmAttach.txtUsername.value=document.frmInfo.txtUsername.value;
						document.frmAttach.tcktdesc.value=strFCKEditorText;
                                                
						document.frmAttach.submit();
					}
           }
           function clickRemove() {

               var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtMatter');
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
                                                        if(confirm("<?php echo MESSAGE_JS_DELETE_TEXT; ?>")) {
                                                                  document.frmAttach.postback.value="RA";
                                                                  document.frmAttach.method="post";
								  document.frmAttach.cmbUser.value=document.frmInfo.cmbUser.value;
                                                                  document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
                                                                  document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
                                                                  document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                                                                  document.frmAttach.tcktdesc.value=strFCKEditorText;
                                                                  document.frmAttach.txtUsername.value=document.frmInfo.txtUsername.value;
                                                                  document.frmAttach.submit();
                                                        }
                        }
                                                else {
                                                        alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
                                                }
        }
                function remove(id) {
                 var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

                ///////////////////
                var oEditor = FCKeditorAPI.GetInstance('txtMatter');
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
                        if(confirm("<?php echo MESSAGE_JS_DELETE_TEXT; ?>")) {
                                document.frmAttach.rid.value=id;
                                document.frmAttach.postback.value="R";
								document.frmAttach.cmbUser.value=document.frmInfo.cmbUser.value;
                                document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
                                document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
                                document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                                document.frmAttach.tcktdesc.value=strFCKEditorText;
                                document.frmAttach.txtUsername.value=document.frmInfo.txtUsername.value;
                                document.frmAttach.method="post";
                                document.frmAttach.submit();
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

          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                 <?php

                  //if(userLoggedIn()){
                  //************************************** User Logged In *************************************************** -->
                          include("./includes/postticket.php");
                 //************************************** User Logged In ******************************************* -->

                  //}else{
                   //;
                  //}
                  ?>
          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
         
		
		
		
		</div>

          
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>