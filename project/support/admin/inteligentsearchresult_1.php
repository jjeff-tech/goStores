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
        include("./languages/".$_SP_language."/inteligentsearchresult.php");
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_ADVANCED_RESULT ?></title>
<?php include("./includes/headsettings.php"); ?>
<?php $var_maxposts = (int)$_SESSION["sess_maxpostperpage"];?>
<script language="javascript" type="text/javascript">
<!--
        function clickSearch() {
                if (validateSearch() == true) {
                        document.frmSearch.method="post";
                        document.frmSearch.submit();
                }
        }

        function validateSearch() {
                var frm = document.frmSearch;
                if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
                        return false;
                }
                else {
                        return true;
                }
        }
		function deleteTickets(chk) {

			document.frmDetail.cmbCompLp.value=document.frmSearch.cmbCompLp.value;

	document.frmDetail.cmbCompOp.value=document.frmSearch.cmbCompOp.value;

	document.frmDetail.cmbCompany.value=document.frmSearch.cmbCompany.value;

	document.frmDetail.cmbDeptLp.value=document.frmSearch.cmbDeptLp.value;

	document.frmDetail.cmbDeptOp.value=document.frmSearch.cmbDeptOp.value;

	document.frmDetail.txtDepartment.value=document.frmSearch.txtDepartment.value;

	document.frmDetail.cmbStatusLp.value=document.frmSearch.cmbStatusLp.value;

	document.frmDetail.cmbStatusOp.value=document.frmSearch.cmbStatusOp.value;

	document.frmDetail.txtStatus.value=document.frmSearch.txtStatus.value;

	document.frmDetail.cmbOwnerLp.value=document.frmSearch.cmbOwnerLp.value;

	document.frmDetail.cmbOwnerOp.value=document.frmSearch.cmbOwnerOp.value;

	document.frmDetail.txtOwner.value=document.frmSearch.txtOwner.value;

	document.frmDetail.cmbUserLp.value=document.frmSearch.cmbUserLp.value;

	document.frmDetail.cmbUserOp.value=document.frmSearch.cmbUserOp.value;

	document.frmDetail.txtUser.value=document.frmSearch.txtUser.value;

	document.frmDetail.cmbTktLp.value=document.frmSearch.cmbTktLp.value;

	document.frmDetail.cmbTktOp.value=document.frmSearch.cmbTktOp.value;

	document.frmDetail.txtTicketNo.value=document.frmSearch.txtTicketNo.value;

	document.frmDetail.cmbQstLp.value=document.frmSearch.cmbQstLp.value;

	document.frmDetail.cmbQstOp.value=document.frmSearch.cmbQstOp.value;

	document.frmDetail.txtQuestion.value=document.frmSearch.txtQuestion.value;

	document.frmDetail.cmbTitleLp.value=document.frmSearch.cmbTitleLp.value;

	document.frmDetail.cmbTitleOp.value=document.frmSearch.cmbTitleOp.value;

	document.frmDetail.txtTitle.value=document.frmSearch.txtTitle.value;
	/*Newly Added*/
	document.frmDetail.cmbEmailLp.value=document.frmSearch.cmbEmailLp.value;

	document.frmDetail.cmbEmailOp.value=document.frmSearch.cmbEmailOp.value;

	document.frmDetail.txtEmail.value=document.frmSearch.txtEmail.value;

	/*Newly Added*/
	document.frmDetail.txtFrom.value=document.frmSearch.txtFrom.value;

	document.frmDetail.txtTo.value=document.frmSearch.txtTo.value;
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="DM";
			  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
			  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }
			}
			else {
				alert('<?php echo MESSAGE_JS_SELECT_ONE; ?>');
			}
		}

		function clickUpdate(chk) { 
				document.frmDetail.cmbCompLp.value=document.frmSearch.cmbCompLp.value;
				document.frmDetail.cmbCompOp.value=document.frmSearch.cmbCompOp.value;
				document.frmDetail.cmbCompany.value=document.frmSearch.cmbCompany.value;
				document.frmDetail.cmbDeptLp.value=document.frmSearch.cmbDeptLp.value;
				document.frmDetail.cmbDeptOp.value=document.frmSearch.cmbDeptOp.value;
				document.frmDetail.txtDepartment.value=document.frmSearch.txtDepartment.value;
				document.frmDetail.cmbStatusLp.value=document.frmSearch.cmbStatusLp.value;
				document.frmDetail.cmbStatusOp.value=document.frmSearch.cmbStatusOp.value;
				document.frmDetail.txtStatus.value=document.frmSearch.txtStatus.value;
				document.frmDetail.cmbOwnerLp.value=document.frmSearch.cmbOwnerLp.value;
				document.frmDetail.cmbOwnerOp.value=document.frmSearch.cmbOwnerOp.value;
				document.frmDetail.txtOwner.value=document.frmSearch.txtOwner.value;
				document.frmDetail.cmbUserLp.value=document.frmSearch.cmbUserLp.value;
				document.frmDetail.cmbUserOp.value=document.frmSearch.cmbUserOp.value;
				document.frmDetail.txtUser.value=document.frmSearch.txtUser.value;
				document.frmDetail.cmbTktLp.value=document.frmSearch.cmbTktLp.value;
				document.frmDetail.cmbTktOp.value=document.frmSearch.cmbTktOp.value;
				document.frmDetail.txtTicketNo.value=document.frmSearch.txtTicketNo.value;
				document.frmDetail.cmbQstLp.value=document.frmSearch.cmbQstLp.value;
				document.frmDetail.cmbQstOp.value=document.frmSearch.cmbQstOp.value;
				document.frmDetail.txtQuestion.value=document.frmSearch.txtQuestion.value;
				document.frmDetail.cmbTitleLp.value=document.frmSearch.cmbTitleLp.value;
				document.frmDetail.cmbTitleOp.value=document.frmSearch.cmbTitleOp.value;
				document.frmDetail.txtTitle.value=document.frmSearch.txtTitle.value;
				/*Newly Added*/
				document.frmDetail.cmbEmailLp.value=document.frmSearch.cmbEmailLp.value;
				document.frmDetail.cmbEmailOp.value=document.frmSearch.cmbEmailOp.value;
				document.frmDetail.txtEmail.value=document.frmSearch.txtEmail.value;
				/*Newly Added*/
				document.frmDetail.txtFrom.value=document.frmSearch.txtFrom.value;
				document.frmDetail.txtTo.value=document.frmSearch.txtTo.value;

			var flag = false;
			if(chk == 0) {
				if(document.frmDetail.cmbAction.value=='delete')
					document.frmDetail.del.value="DM";
			 	if(document.frmDetail.cmbAction.value=='spam')
					document.frmDetail.del.value="MS";
				if(document.frmDetail.cmbLabel.value>0)
					document.frmDetail.labelup.value="LABELUP";
				if(document.frmDetail.cmbStatus.value !="")
					document.frmDetail.del.value="UP";

			  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
			  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT_1; ?>')) {
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }
			}
			else {
				alert('<?php echo MESSAGE_JS_SELECT_ONE_1; ?>');
			}
		}

			function checkallfn(){
            if(document.frmDetail.checkall.checked){
	             for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
	               try{

                     document.getElementById('chkDeleteTickets' + i ).checked=true;
	               }catch(e) {}
	             }

            }else{
                  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
	               try{

                     document.getElementById('chkDeleteTickets' + i ).checked=false;
	               }catch(e) {}
	             }
            }
		}
-->
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
	<script type="text/javascript" src="./includes/functions/ajax.js"></script>
</head>

<body>
<!-- Ajax tool tip-->
<div id="tooltipBox" onMouseOver="clearAdInterval();" onMouseOut="hideAd();" style="z-index:5000;position:absolute;cursor:pointer;"></div>
<!--end  Ajax tool tip-->

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
					//	include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Tickets Assigned Section -->
                  <?php
                          include("./includes/inteligentsearchresult.php");
                  ?>
                <!-- End Tickets Assigned  section -->
                <!-- Advanced Search -->
                 <?php
                          include("./includes/advancedsearch.php");
                  ?>

                  <!-- End Advanced Search -->

        </div>
        
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
<script>
<!--
        var frm = document.frmSearch;
        frm.cmbCompany.value=cmp;
        frm.txtDepartment.value=dept;
        frm.txtStatus.value=status;
        frm.txtOwner.value=owner;
        frm.txtUser.value=user;
        frm.txtTicketNo.value=ticketno;
        frm.txtTitle.value=title;
/*Newly Added*/
        frm.txtEmail.value=email;
 /*Newly Added*/
        frm.txtLabel.value=label;		
        frm.txtQuestion.value=qst;
        frm.txtFrom.value=from;
        frm.txtTo.value=to;

		frm.cmbCompOp.value=cop;
		frm.cmbDeptOp.value=dop;
		frm.cmbDeptLp.value=dlp;
		frm.cmbOwnerOp.value=oop;
		frm.cmbOwnerLp.value=olp;
		frm.cmbUserOp.value=uop;
		frm.cmbUserLp.value=ulp;
		frm.cmbTktOp.value=tkop;
		frm.cmbTktLp.value=tklp;
		frm.cmbQstOp.value=qop;
		frm.cmbQstLp.value=qlp;
		frm.cmbTitleOp.value=top;
		frm.cmbTitleLp.value=tlp;
		/*Newly Added*/
		frm.cmbEmailOp.value=eop;
		frm.cmbEmailLp.value=elp;		
		/*Newly Added*/
		frm.cmbLabelOp.value=lop;
		frm.cmbLabelLp.value=llp;
		frm.cmbStatusOp.value=sop;
		frm.cmbStatusLp.value=slp;
-->
</script>
</html>