<?php
$var_staffid = $_SESSION["sess_staffid"];
if ($_POST["postback"] == "R") {
    $var_staffcmbid = trim($_POST["cmbStaff"]);

    $var_sdate = trim($_POST["txtSdate"]);
    $var_edate = trim($_POST["txtEdate"]);
}
if($_GET["startdate"] != "") {
    $var_sdate = $_GET["startdate"];
}
if($_GET["enddate"] != "")
    $var_edate = $_GET["enddate"];



if ($var_sdate == "") {
    //$var_sdate=date("m-d-Y H:i");
    $var_sdate = date("m/d/Y", strtotime("$from_date -30 days"));
}
if ($var_edate == "") {
    $var_edate = date("m/d/Y");
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
    <form name="frmrepCompTicket" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

        <div class="content_section_title"><h3><?php echo TEXT_REPORTS_STAFF_SUMMARY ?></h3></div>

        <div class="content_search_container">
            <!--<div class="left rightmargin topmargin">
            <?php //echo TXT_SELECT_STAFF; ?>
						</div>-->

            <div class="left rightmargin">
                <?php
                /*$sql = "SELECT nStaffId,vLogin   FROM `sptbl_staffs` where vDelStatus=0 order by vLogin";
$rs = executeSelect($sql, $conn);*/
                $cnt = 1;
?>
                                                  <!--  <select name="cmbStaff" size="1" class="comm_input input_width1" id="cmbStaff" >
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
                                                    </select>-->
            </div>
            <div class="left">
                <div class="left rightmargin topmargin">
                <?php echo TXT_SELECT_DATE_RANGE ?>
                </div>

                <div class="left rightmargin">
                    <input name="txtSdate" type="text" class="comm_input_txtbx left" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>" style="margin-right: 5px;" readonly >
                    <input type="button" id="button1" name="button1"  class="secondary_btn left" value="" onClick="" style="margin-right: 50px;">
                    <script type="text/javascript">
                        Calendar.setup({
                            inputField    	: "txtSdate",
                            button              : "button1",
                            ifFormat      	: "%m/%d/%Y",       // format of the input field
                            showsTime      	: true,
                            timeFormat     	: "24"
                        });
                    </script>&nbsp;&nbsp;
                </div>



                <div class="left">
                  <input name="txtEdate" type="text" class="comm_input_txtbx left" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>" style="margin-right: 5px;"  readonly >                    
                  <input type="button" id="button2" name="button2" class="secondary_btn left" value="" onClick="">
                    <script type="text/javascript">
                        Calendar.setup({
                            inputField    	: "txtEdate",
                            button              : "button2",
                            ifFormat      	: "%m/%d/%Y",       // format of the input field
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
                        <input name="btnDelete" type="button" class="report_comm_btn" value="<?php echo BUTTON_TEXT_RUN_REPORT; ?>" onClick="javascript:clickRunreport();"> </td></tr>
                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                        <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                        <input type="hidden" name="postback" value="">
                    </table></td>
            </tr>
        </table>

        <?php
//if ($_POST["postback"] == "R") {
        require("./includes/repticketstaffperformancedetail.php");
//}
?>
</div>

</form>

