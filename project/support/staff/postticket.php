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
include "./languages/" . $_SP_language . "/postticket.php";
//include_once("../FCKeditor/fckeditor.php");
$conn = getConnection();
?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADER_POST_TICKET; ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
    <!--


    function addticket(){        
        var oForma = document.forms['frmAttach'];
        /*if(oForma.txtUrl.value != ''){
            alert("Please attach the selected file before submit")
            return false;
        }


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
        //var strFCKEditorText = EditorInstance.EditorDocument.body.innerHTML;
*/
        if (validateForm() == true) {
            var strFCKEditorText =  CKEDITOR.instances['txtMatter'].getData();
            var selectedValue = $("input[name=radioUserType]:checked").val();           
            if(selectedValue == "select") {
                document.frmPostTicket.cmbUser.value=document.frmInfo.txtUserid.value;
            } else {
                document.frmPostTicket.cmbUser.value=0;
            }
            document.frmPostTicket.deptid.value=document.frmInfo.cmbDept.value;
            document.frmPostTicket.prty.value=document.frmInfo.cmbPriority.value;
            document.frmPostTicket.tckttitle.value=document.frmInfo.txtTcktTitle.value;
            document.frmPostTicket.tcktdesc.value=strFCKEditorText;            
            document.frmPostTicket.newUserEmail.value=document.frmInfo.txtNewUserEmail.value;  
            document.frmPostTicket.newUserLogin.value=document.frmInfo.txtNewUserLogin.value;
            document.frmPostTicket.newUserPassword.value=document.frmInfo.txtNewUserPassword.value;
            //document.frmPostTicket.txtUsername.value=document.frmInfo.txtUsername.value;
            document.frmPostTicket.uploadfiles.value=document.frmAttach.uploadedfiles.value;
            document.frmPostTicket.postback.value="S";
            document.frmPostTicket.method="post";            
            document.frmPostTicket.submit();
        }
    }
    
    function validateForm()
    {
        var flag = false;
        

      /*  var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;
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
        } */
        var strFCKEditorText =  CKEDITOR.instances['txtMatter'].getData();

        var oForm1 = document.forms['frmInfo'];
        var oForm2 = document.forms['frmAttach'];
        var flag = false;
        if(document.frmInfo.txtUserid.value == "" && document.frmPostTicket.cmbUser.value == "0") {
            if(document.frmInfo.txtNewUserEmail.value == "") {
                alert('<?php echo ENTER_EMAIL; ?>');
                document.frmInfo.txtNewUserEmail.focus();
            } else if(!checkMail(document.frmInfo.txtNewUserEmail.value)) {
                alert('<?php echo ENTER_EMAIL; ?>');
                document.frmInfo.txtNewUserEmail.focus();
            }
            else if(document.frmInfo.txtNewUserLogin.value == "") {
                alert('<?php echo ENTER_LOGIN_NAME; ?>');
                document.frmInfo.txtNewUserLogin.focus();
            }
            else if(document.frmInfo.txtNewUserPassword.value == "") {
                alert('<?php echo ENTER_PASSWORD; ?>');
                document.frmInfo.txtNewUserPassword.focus();
            }
        }
			   
        if(document.frmInfo.txtUserid.value=="" && document.frmPostTicket.cmbUser.value == ""){
            alert('<?php echo MESSAGE_USER_NOT_EXISTS; ?>');
            document.frmInfo.cmbUser.focus();
        }
        else if(document.frmInfo.cmbDept.value==""){
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            document.frmInfo.cmbDept.focus();
        }else if (document.frmInfo.txtTcktTitle.value.length == 0) {
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            document.frmInfo.txtTcktTitle.focus();
            //                        return false;
        }
        else if(strFCKEditorText=="")
        {
            alert("<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>");
            document.frmInfo.txtMatter.focus();
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
    function changeUser() {
        if(document.frmInfo.cmbUser.value==""){
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            document.frmInfo.cmbUser.focus();
        }
        else {						
            document.frmAttach.postback.value="CU";
            document.frmAttach.method="post";
            document.frmAttach.cmbUser.value=document.frmInfo.cmbUser.value;
            document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
            document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
            document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
            document.frmAttach.tcktdesc.value=document.frmInfo.txtMatter.value;

            document.frmAttach.txtUsername.value=document.frmInfo.txtUsername.value
            document.frmAttach.submit();
        }		
    }
	  
    function attach(){ console.log(document.frmInfo.cmbUser);

     /*   var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

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
        }*/
        var strFCKEditorText =  CKEDITOR.instances['txtMatter'].getData();
        if(document.frmAttach.txtRef.value.indexOf('"') >= 0) {
            alert('" <?php echo MESSAGE_JS_NOT_PERMITTED; ?>');
            document.frmAttach.txtRef.focus();
        }
        else {
            document.frmAttach.postback.value="A";
            document.frmAttach.method="post";
            document.frmAttach.cmbUser.value=document.frmPostTicket.cmbUser.value;
            document.frmAttach.deptid.value=document.frmInfo.cmbDept.value;
            document.frmAttach.prty.value=document.frmInfo.cmbPriority.value;
            document.frmAttach.tckttitle.value=document.frmInfo.txtTcktTitle.value;
            document.frmAttach.tcktdesc.value=strFCKEditorText;
            document.frmAttach.txtUsername.value=document.frmAttach.txtUsername.value;
            document.frmAttach.submit();
        }
    }
    function clickRemove() {

    /*    var EditorInstance = FCKeditorAPI.GetInstance('txtMatter') ;

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

*/
var strFCKEditorText =  CKEDITOR.instances['txtMatter'].getData();
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
            alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
        }
    }
    function remove(id) {
/*
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
*/
var strFCKEditorText =  CKEDITOR.instances['txtMatter'].getData();
        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
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
// include("./includes/staffheader.php");
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