<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
             //
        include("./languages/".$_SP_language."/viewticket.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_VIEW_TICKET ?></title>
<?php include("./includes/headsettings.php"); ?>


<script language="javascript" type="text/javascript">
<!--
        function clickSearch() {
                if (validateSearch() == true) {
                        document.frmDetail.method="post";
                        document.frmDetail.submit();
                }
        }

        function validateSearch() {
                var frm = document.frmDetail;
                if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
                        return false;
                }
                else {
                        return true;
                }
        }
        function clickUpdate() {
                var frm = document.frmDetail;
                frm.mt.value="u";
                frm.method="post";
                frm.submit();
        }
        function clickForward() {
            var frm = document.frmDetail;
			
	        if(!checkMail(frm.txtForward.value)){
				alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                frm.txtForward.focus();
			}else {
    	        frm.mt.value="f";
        	    frm.method="post";
            	frm.submit();
			}
        }

       function deleteTickets(chk) {
              var frm = document.frmDetail;
			  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
			    frm.mt.value="D";
			    frm.delid.value=chk;
				document.frmDetail.method="post";
				document.frmDetail.submit();
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
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
	<script type="text/javascript" src="./includes/functions/ajax.js"></script>
</head>

<body bgcolor="#EDEBEB">
<!-- Ajax tool tip-->

<div id="tooltipBox" onMouseOver="clearAdInterval();" onMouseOut="hideAd();" style="z-index:5000;position:absolute;cursor:pointer;"></div>
<!--end  Ajax tool tip-->
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

                  <!-- Tickets Assigned Section -->
                  <?php  
                          include("./includes/viewticket.php"); exit;  
                  ?>
                  <!-- End Tickets Assigned  section -->
	</div>
		
          <!-- Main footer -->
          <?php 
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
<script type="text/javascript">
        document.frmDetail.cmbOwner.value=own;
        document.frmDetail.cmbDepartment.value=dept;
        document.frmDetail.txtCreated.value=ctd;
        document.frmDetail.txtUpdate.value=lstupdate;
        document.frmDetail.txtReplier.value=lstreplier;
        document.frmDetail.cmbStatus.value=st;
        document.frmDetail.cmbLock.value=lck;
</script>
</html>