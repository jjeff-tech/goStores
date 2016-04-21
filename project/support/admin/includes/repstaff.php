<?php
$var_staffid = $_SESSION["sess_staffid"];
if ($_POST["postback"] == "R") {
    $var_staffcmbid = trim($_POST["cmbStaff"]);

    $var_sdate = trim($_POST["txtSdate"]);
    $var_edate = trim($_POST["txtEdate"]);
}

if ($var_sdate == "") {
    //$var_sdate=date("m-d-Y H:i");
    $var_sdate = date("m/d/Y H:i", strtotime("$from_date -30 days"));
}
if ($var_edate == "") {
    $var_edate = date("m/d/Y H:i");
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

<form name="frmrepCompTicket" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr>
            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="1"  ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td width="93%" class="heading"><?php echo TEXT_REPORTS_STAFF_SUMMARY ?></td>
                                </tr>
                            </table>
                            <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="column1">
                                <tr>
                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                            <tr>
                                                <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                                                        <tr>
                                                            <td class="bodycolor" ><table width="100%"  border="0" cellpadding="0" cellspacing="3"  >

                                                                    <tr align="left" >
                                                                        <td colspan="5" class="listingmaintext">&nbsp;</td>
                                                                    </tr>
                                                                    <tr align="left" class="pagecolor">
                                                                        <td colspan="5"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <table border=0 width="100%">
                                                                                <tr>
                                                                                    <td align=right class="whitebasic" style="padding:10px 25px 0 0; "><?php echo(TEXT_SELECT_STAFF); ?></td>
                                                                                    <td width="24%" align=left  class="whitebasic">
                                                                                        <?php
                                                                                        $sql = "SELECT nStaffId,vLogin   FROM `sptbl_staffs` where vDelStatus=0 order by vLogin";
                                                                                        $rs = executeSelect($sql, $conn);
                                                                                        $cnt = 1;
                                                                                        ?>
                                                                                        <select name="cmbStaff" size="1" class="textbox11" id="cmbStaff" >
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
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="25%" align=right class="whitebasic" style="padding:10px 25px 0 0 " >Select Date Range</td>
                                                                                    <td width="24%" align=left  class="whitebasic">
                                                                                        <input name="txtSdate" type="text" class="textbox10" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>" readonly >
                                                                                        <input type="button" value="" id="button1" name="button1"  class="secondary_btn1">
                                                                                        <script type="text/javascript">
                                                                                            Calendar.setup({
                                                                                                inputField    	: "txtSdate",
                                                                                                button        : "button1",
                                                                                                ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                                                                                showsTime      	: true,
                                                                                                timeFormat     	: "24"
                                                                                            });
                                                                                        </script>
                                                                                    </td>
                                                                                    <td width="51%">
                                                                                        <input name="txtEdate" type="text" class="textbox10" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>" readonly >
                                                                                        <input type="button" value="" id="button2" name="button2"  class="secondary_btn1">
                                                                                        <script type="text/javascript">
                                                                                            Calendar.setup({
                                                                                                inputField    	: "txtEdate",
                                                                                                button          : "button2",
                                                                                                ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                                                                                showsTime      	: true,
                                                                                                timeFormat     	: "24"
                                                                                            });
                                                                                        </script>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>    
                                                                    <tr align="left"  class="listingmaintext">
                                                                        <td colspan="5"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                                                    </tr>

                                                                </table></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>

                </table>

                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
        </tr>
    </table>

    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr>
            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr align="center" class="listingbtnbar">
                                                <td> 
                                                    <input name="btnDelete" type="button" class="report_comm_btn" value="<?php echo BUTTON_TEXT_RUN_REPORT ?>" onClick="javascript:clickRunreport();">                                    </td></tr>
                                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
                                            <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                            <input type="hidden" name="postback" value="">
                                        </table></td>
                                </tr>
                            </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
        </tr>			   			 
    </table>

    <table border="0" cellspacing="10" cellpadding="0" width="100%">
        <tr>
            <td>
                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="column1">
                    <?php
                    if ($_POST["postback"] == "R") {
                        require("./includes/repstaffdetail.php");
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
</td>
</tr>
</table>			
</form>