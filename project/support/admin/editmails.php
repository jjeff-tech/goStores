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
// |                                                                                                            |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editmails.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_MAILS?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function edit() {

                        if (validateForm() == true) {

                                document.frmMails.postback.value="U";
                                document.frmMails.method="post";
                                document.frmMails.submit();

                        }
        }


        function validateForm()
        {

                var frm = window.document.frmMails;
                var flag = false;
                if (frm.txtMailAdmin.value.length == 0) {

                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.txtMailAdmin.focus();

                }
                else if (frm.txtMailTechnical.value == ""){

                     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                     frm.txtMailTechnical.focus();

                }else if(frm.txtMailEscalation.value == ""){

                     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                     frm.txtMailEscalation.focus();

                }else if(frm.txtMailFromName.value == ""){
                      alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                      frm.txtMailFromName.focus();
                }else if(frm.txtMailFromMail.value == ""){

                      alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                      frm.txtMailFromMail.focus();

                }else if(frm.txtMailReplyName.value == ""){

                      alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                      frm.txtMailReplyName.focus();

                }else if(frm.txtMailReplyMail.value == ""){

                      alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                      frm.txtMailReplyMail.focus();

                }
                else if(!checkMail(frm.txtMailAdmin.value)){

                     alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                     frm.txtMailAdmin.select();
                     frm.txtMailAdmin.focus();

                }
                else if(!checkMail(frm.txtMailTechnical.value)){

                     alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                     frm.txtMailTechnical.select();
                     frm.txtMailTechnical.focus();

                }
                else if(!checkMail(frm.txtMailEscalation.value)){

                     alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                     frm.txtMailEscalation.select();
                     frm.txtMailEscalation.focus();

                }
                else if(!checkMail(frm.txtMailFromMail.value)){

                     alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                     frm.txtMailFromMail.select();
                     frm.txtMailFromMail.focus();

                }
                else if(!checkMail(frm.txtMailReplyMail.value)){

                     alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                     frm.txtMailReplyMail.select();
                     frm.txtMailReplyMail.focus();

                }

                else {

                     flag = true;
                }

                if (flag == false) {

                    return false;

                }else {

                    return true;

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

        function cancel()
        {
                var frm = document.frmMails;
                frm.txtMailAdmin.value = "";
                frm.txtMailTechnical.value = "";
                frm.txtMailEscalation.value = "";
                frm.txtMailFromName.value = "";
                frm.txtMailFromMail.value = "";
                frm.txtMailReplyName.value = "";
                frm.txtMailReplyMail.value = "";
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
                          include("./includes/editmails.php");
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