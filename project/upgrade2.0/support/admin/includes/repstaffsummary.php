<?php
$var_staffid = $_SESSION["sess_staffid"];
if ($_POST["postback"] == "R") {
    $var_staffcmbid = trim($_POST["cmbStaff"]);

    $var_sdate = trim($_POST["txtSdate"]);
    $var_edate = trim($_POST["txtEdate"]);
}

if ($var_sdate == "") {
    //$var_sdate=date("m-d-Y H:i");
    $var_sdate = date("m-d-Y H:i", strtotime("$from_date -30 days"));
}
if ($var_edate == "") {
    $var_edate = date("m-d-Y H:i");
}
if ($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
} elseif ($_POST["stylename"] != "") {

    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}
?>
<div class="content_section">
<form name="frmrepCompTicket" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

<div class="content_section_title"><h3><?php echo TEXT_REPORTS_STAFF_SUMMARY ?></h3></div>

<div class="content_search_container">
						<div class="left rightmargin topmargin">
						<?php echo TXT_SELECT_STAFF; ?>
						</div>
						
						<div class="left rightmargin">
						<?php
$sql = "SELECT nStaffId,vLogin   FROM `sptbl_staffs` where vDelStatus=0 order by vLogin";
$rs = executeSelect($sql, $conn);
$cnt = 1;
?>
                                                                                        <select name="cmbStaff" size="1" class="comm_input input_width1" id="cmbStaff" >
                                                                                        <?php
                                                                                        $options = "<option value='0'";
                                                                                        $options .=">" . TEXT_SELECT_ALL . "</option>\n";
                                                                                        echo $options;
                                                                                        while ($row = mysql_fetch_array($rs)) {
                                                                                            $options = "<option value='" . $row['nStaffId'] . "'";
                                                                                            if ($var_staffcmbid == $row['nStaffId']) {

                                                                                                $options .=" selected=\"selected\"";
                                                                                            }
                                                                                            $options .=">" . $row['vLogin'] . "</option>\n";
                                                                                            echo $options;
                                                                                        }
                                                                                        ?>
                                                                                        </select>	
						</div>
						<div class="right">
								<div class="left topmargin">
								<?php echo TXT_SELECT_DATE_RANGE ?>&nbsp;
								</div>
								
								<div class="left">
								<input name="txtSdate" type="text" class="comm_input input_width3" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>"  readonly >
                                                                                        <input type="button" value="v" id="button1" name="button1"  class="comm_btn">
                                                                                        <script type="text/javascript">
                                                                                            Calendar.setup({
                                                                                                inputField    	: "txtSdate",
                                                                                                button        : "button1",
                                                                                                ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
                                                                                                showsTime      	: true,
                                                                                                timeFormat     	: "24"
                                                                                            });
                                                                                        </script>&nbsp;&nbsp;
								</div>
								
								
								
								<div class="left">
								<input name="txtEdate" type="text" class="comm_input input_width3" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>"  readonly >
                                                                                        <input type="button" value="v" id="button2" name="button2"  class="comm_btn">
                                                                                        <script type="text/javascript">
                                                                                            Calendar.setup({
                                                                                                inputField    	: "txtEdate",
                                                                                                button          : "button2",
                                                                                                ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
                                                                                                showsTime      	: true,
                                                                                                timeFormat     	: "24"
                                                                                            });
                                                                                        </script>
								</div>
						
						<div class="clear"></div>
						</div>
						
						
														
					<div class="clear"></div>
					</div>

 
                                                                           
                                                                        

               
    
	<table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr align="center">
                                                <td> 
                                           	<input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_RUN_REPORT ?>" onClick="javascript:clickRunreport();">                                    </td></tr>
                                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
                                            <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                            <input type="hidden" name="postback" value="">
                                        </table></td>
                                </tr>
                            </table>
							
	
	

  
<?php
if ($_POST["postback"] == "R") {
    require("./includes/repticketstaffsummarydetail.php");
}
?>
   </div>
		
</form>

