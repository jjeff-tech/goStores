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
		
        include("./languages/".$_SP_language."/emailuser.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EMAIL_USER ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script>
<!--
        function sendMail() {
                if(validateForm() == true) {
                        document.frmMail.postback.value="SA";
                        document.frmMail.method = "post";
                        document.frmMail.submit();
                }
               /* else {
                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                }*/
        }

        function validateForm() {
                var frm = document.frmMail;
				if(frm.txtTo.value.length == 0) {
						alert("To address cannot be null");
                        frm.txtTo.focus();
                        return false;
                }else{
					if(checkMail(frm.txtTo.value) == false){
						alert("To address should be an email-Id");
						frm.txtTo.focus();
                        return false;
					}
				}
				if(frm.txtToCC.value.length != 0 && checkMail(frm.txtToCC.value) == false)
				{
						alert("CC address should be an email-Id");
						frm.txtToCC.focus();
                        return false;
				}
                if(frm.txtSubject.value.length == 0) {
						alert("Subject cannot be null");
                        frm.txtSubject.focus();
                        return false;
                }
                else if(frm.txtBody.value.length == 0) {
						alert("Email body cannot be null");
                        frm.txtBody.focus();
                        return false;
                }else
                return true;
        }

        function cancel() {
                var frm = document.frmMail;
                frm.txtSubject.value="";
                frm.txtBody.value="";

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

	function clearForm(oForm)
	{
		var frm_elements = oForm.elements;
		for (i = 0; i < frm_elements.length; i++)
		{
			field_type = frm_elements[i].type.toLowerCase();
			switch (field_type)
			{
			case "text":
			case "password":
			case "textarea":
				frm_elements[i].value = "";
				break;
			default:
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
                <!-- Personal notes Section -->
                  <?php
                          include("./includes/emailuser.php");
                  ?>
                  <!-- End Personal notes Section  -->
     


		</div>
		  
		  
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>