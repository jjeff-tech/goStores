<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>  		                          |
// |          									                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/search.php");
        $conn = getConnection();
        $page = 'Search';
?>

<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_SEARCH ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
        function clickSearch() {
                if (validateSearch() == true) {
                        document.frmSearch.method="post";
                        document.frmSearch.submit();
                }
        }

        function changeDepartment() {
                document.frmDetail.method="post";
                document.frmDetail.submit();
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
        function changeRefresh() {
                var tm = parseInt(document.frmDetail.cmbRefresh.value);
                if(!isNaN(tm)) {
                setTimeout("callMe()", (tm*60*1000));
                }
        }
        function callMe() {
                if(document.frmDetail.cmbRefresh.value.length > 0) {
                        document.frmDetail.method="post";
                        document.frmDetail.submit();
                }
        }

-->
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
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
						include("./includes/staffheader.php");
				?>
				<!--  end admin header -->

                <!-- Advanced Search -->

				<?php
					$var_staffid = $_SESSION["sess_staffid"];
					//Block - I (populate the allowed departments for the user)
						/*$lst_dept = "'',";
						$sql = "Select nDeptId from sptbl_staffdept where nStaffId='$var_staffid'";
						$rs_dept = executeSelect($sql,$conn);
						if (mysql_num_rows($rs_dept) > 0) {
							while($row = mysql_fetch_array($rs_dept)) {
								$lst_dept .= $row["nDeptId"] . ",";
							}
						
						}
						$lst_dept = substr($lst_dept,0,-1);
						
						mysql_free_result($rs_dept);
					//End Of Block - I*/
					$lst_dept = $_SESSION['department_ids'] ;
					$sql = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from 
							sptbl_depts d  inner join sptbl_companies c on d.nCompId = c.nCompId  WHERE d.nDeptId IN($lst_dept) ";
							$lst_dept_opt = "";
							$rs_dept = executeSelect($sql,$conn);
							if (mysql_num_rows($rs_dept) > 0) {
								while($row = mysql_fetch_array($rs_dept)) {
									$lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\">" . $row["description"] . "</option>";
								}
							}
							mysql_free_result($rs_dept);
				?>
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
</html>