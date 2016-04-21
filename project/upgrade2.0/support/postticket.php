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
include "languages/".$_SP_language."/postticket.php";
include_once("FCKeditor/fckeditor.php") ;
$conn = getConnection();

?>
<?php include("./includes/docheader.php"); ?>

<title><?php echo HEADER_POST_TICKET;?></title>
<?php include("./includes/headsettings.php"); ?>
<script type="text/javascript">

    function addticket(){
        var oForma = document.forms['frmAttach'];
        /* if(oForma.txtUrl.value != ''){
          alert("Please attach the selected file before submit")
          return false;
      }*/

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
        /*alert('1111');
        alert(document.frmInfo.txtTcktTitle.value);
       alert('222');
       alert(document.frmInfo.txtMatter.value);
        alert('333');
        alert(strFCKEditorText);
        exit;*/
        if (validateForm() == true) {
            strFCKEditorText =  EditorInstance.EditorDocument.body.innerHTML;
            document.frmPostTicket.deptid.value=document.frmInfo.cmbDept.value;
            document.frmPostTicket.prty.value=document.frmInfo.cmbPriority.value;
            document.frmPostTicket.tckttitle.value=document.frmInfo.txtTcktTitle.value;
            document.frmPostTicket.tcktdesc.value=strFCKEditorText;
            document.frmPostTicket.uploadfiles.value=document.frmAttach.uploadedfiles.value;
            document.frmPostTicket.postback.value="S";
            document.frmPostTicket.method="post";
            document.frmPostTicket.submit();
        }
    }
    function validateForm()
    {

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

        var oForm1 = document.forms['frmInfo'];
        var oForm2 = document.forms['frmAttach'];
        var flag = false;


        if(oForm1.cmbDept.value==""){
            alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
            oForm1.cmbDept.focus();
        }else if ($.trim(oForm1.txtTcktTitle.value) == '') {
            alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
            oForm1.txtTcktTitle.value = '';
            oForm1.txtTcktTitle.focus();
            //                        return false;
        }else if(strFCKEditorText=="") {
            alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
            oForm1.txtMatter.value = '';
            oForm1.txtMatter.focus();
            //                        return false;
        }/*else if ($.trim(oForm2.txtRef.value) != '' && oForm2.txtUrl.value == '') {
                alert('Please select a file to upload.');
                                             oForm2.txtUrl.focus();
//                        return false;
             }*/else if ($.trim(oForm2.txtRef.value) == '' && oForm2.txtUrl.value != '') {
            alert('Please enter the reference name.');
            
            oForm2.txtRef.value = '';
            oForm2.txtRef.focus();
            //                        return false;
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

        if($.trim(document.frmAttach.txtRef.value) == '') {
            alert('Enter reference name.');
            document.frmAttach.txtRef.value = '';
            document.frmAttach.txtRef.focus();
        }
        else if($.trim(document.frmAttach.txtUrl.value) == '') {
             alert("<?php echo MESSAGE_JS_FILE_MANDATORY_ERROR; ?>");
            document.frmAttach.txtUrl.focus();
        }
        else {
            document.frmAttach.postback.value="A";
            document.frmAttach.method="post";
            document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
            document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
            document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
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
                document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
                document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
                document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
                document.frmAttach.tcktdesc.value=strFCKEditorText;
                document.frmAttach.submit();
            }
        }
        else {
            alert('Please select the attachments to be removed.');
        }
    }
    function remove(id) {
        if(confirm("<?php echo MESSAGE_JS_DELETE_TEXT; ?>")) {
            document.frmAttach.rid.value=id;
            document.frmAttach.postback.value="R";
            document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
            document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
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
        //include("./includes/userheader.php");
        ?>
        <!--  end admin header -->
        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
        <?php

        if(userLoggedIn()) {
            //************************************** User Logged In *************************************************** -->
            include("./includes/postticket.php");
            //************************************** User Logged In ******************************************* -->

        }else {
            ;
        }
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