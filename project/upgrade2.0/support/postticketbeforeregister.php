<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                |
// +----------------------------------------------------------------------+

require_once("./includes/applicationheader.php");
include_once("./FCKeditor/fckeditor.php") ;
include "languages/" . $_SP_language . "/postticketbeforeregister.php";

$conn = getConnection();

?>
<?php include("./includes/docheader.php");  ?>
        <title><?php echo HEADER_POST_TICKET; ?></title>
        <?php include("./includes/headsettings.php"); ?>
        <script language="javascript" type="text/javascript">
            <!--
            function addticket(){
                var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;
                var  strFCKEditorText =  EditorInstance.EditorDocument.body.innerHTML;
                if (validateForm() == true) {

                    document.frmPostTicket.txtname.value=document.frmInfo.txtName.value;
                    document.frmPostTicket.txtemail.value=document.frmInfo.txtEmail.value;

                    document.frmPostTicket.deptid.value=document.frmInfo.cmbDept.value;
                    //document.frmPostTicket.prty.value=document.frmInfo.cmbPriority.value;
                    document.frmPostTicket.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                    document.frmPostTicket.tcktdesc.value = strFCKEditorText;
                    document.frmPostTicket.uploadfiles.value=document.frmAttach.uploadedfiles.value;
                    document.frmPostTicket.postback.value="S";
                    document.frmPostTicket.method="post";
                    document.frmPostTicket.submit();
                }
            }

            function validateForm()
            {
                var flag = true;
			
                if(document.frmInfo.txtName.value==""){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    document.frmInfo.txtName.focus();
                    flag = false;
                }else if(document.frmInfo.txtEmail.value==""){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    document.frmInfo.txtEmail.focus();
                    flag = false;
                }else if (!isValidEmail(document.frmInfo.txtEmail.value)){
                    alert('<?php echo MESSAGE_JS_INVALID_EMAIL; ?>');
                    document.frmInfo.txtEmail.focus();
                    flag = false;
                }else if(document.frmInfo.cmbDept.value==""){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    document.frmInfo.cmbDept.focus();
                    flag = false;
                }else if (document.frmInfo.txtTcktTitle.value.length == 0) {
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    document.frmInfo.txtTcktTitle.focus();
                    flag = false;
                }
                else{
                    var oEditor = FCKeditorAPI.GetInstance('txtMatter');
                    var oDOM = oEditor.EditorDocument;
                    var strFCKEditorText = "";
                    if (document.all){
                        strFCKEditorText = oDOM.body.innerText;
                    }
                    else{
                        var r = oDOM.createRange();
                        r.selectNodeContents(oDOM.body);
                        strFCKEditorText = r.toString();
                    }
                    if(strFCKEditorText==""){
                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR ?>');
                        oEditor.Focus();
                        //document.frmInfo.txtMatter.focus();
                        flag = false;
                    }
                }
                
                if (flag == false) {
                    return false;
                }else {
                    return true;
                }
            }
	  
            <!-- Begin email validation function
            function isValidEmail(email){
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

            function attach(){
                if(document.frmAttach.txtRef.value.indexOf('"') >= 0) {
                    alert('" <?php echo MESSAGE_JS_NOT_PERMITTED; ?>');
                    document.frmAttach.txtRef.focus();
                }else {
                    document.frmAttach.postback.value="A";
                    document.frmAttach.method="post";

                    document.frmAttach.txtname.value=document.frmInfo.txtName.value;
                    document.frmAttach.txtemail.value=document.frmInfo.txtEmail.value;

                    document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
                   // document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
                    document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                    document.frmAttach.tcktdesc.value=document.frmInfo.txtMatter.value
                    document.frmAttach.submit();
                }
            }

            function clickRemove()
            {
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
                        document.frmAttach.postback.value="RA";
                        document.frmAttach.method="post";
					  
                        document.frmAttach.txtname.value=document.frmInfo.txtName.value;
                        document.frmAttach.txtemail.value=document.frmInfo.txtEmail.value;
					  
                        document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
                        //document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
                        document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                        document.frmAttach.tcktdesc.value=document.frmInfo.txtMatter.value
                        document.frmAttach.submit();
                    }
                }else {
                    alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
            }

            function remove(id) 
            {
                if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                    document.frmAttach.rid.value=id;
                    document.frmAttach.postback.value="R";

                    document.frmAttach.txtname.value=document.frmInfo.txtName.value;
                    document.frmAttach.txtemail.value=document.frmInfo.txtEmail.value;

                    document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
                    //document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
                    document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                    document.frmAttach.tcktdesc.value=document.frmInfo.txtMatter.value
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
                    include("./includes/userside.php");
                    ?>
                    <!-- End of side links -->
	</div>


<div class="content_column_big">
 <!-- admin header -->
                    <?php
                   // include("./includes/userheader.php");
                    ?>
                    <!--  end admin header -->
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                    <?php
                    include("./includes/postticketbeforeregister.php");
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