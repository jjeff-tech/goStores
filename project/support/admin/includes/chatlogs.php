<?php
$var_staffid=$_SESSION["sess_staffid"];
if ($_POST["postback"] == "R") {
    $var_staffid= trim($_POST["cmbStaff"]);
    $var_kwd = trim($_POST["txtKwd"]);
    $var_sdate = trim($_POST["txtSdate"]);
    $var_edate = trim($_POST["txtEdate"]);

}

if($var_sdate=="") {
    //$var_sdate=date("m-d-Y H:i");
    $var_sdate=date("m/d/Y H:i", strtotime("$from_date -30 days"));
}
if($var_edate=="") {
    $var_edate=date("m/d/Y H:i");
}
if($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["stylename"] !="") {

    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}

?>
<div class="content_section">
    <form name="frmChatLogs" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

        <div class="content_section_title">
            <h3><?php echo TEXT_CHAT_LOGS ?></h3>
        </div>

        <table border="0" width="100%" class="comm_tbl" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="" align="right" ><?php echo TXT_SELECT_STAFF; ?></td>
                <td width="13%" align="left"  class="whitebasic">
                    <?php
                    $sql = "SELECT nStaffId, 	vStaffname   FROM `sptbl_staffs` where vDelStatus=0 order by vStaffname";
                    $rs = executeSelect($sql,$conn);
                    $cnt = 1;
                    ?>
                    <select name="cmbStaff" size="1" class="comm_input" id="cmbStaff" >
                        <?php
                        $options ="<option value='0'";
                        $options .=">".TEXT_SELECT_ALL."</option>\n";
                        echo $options;
                        while($row = mysql_fetch_array($rs)) {
                            $options ="<option value='".$row['nStaffId']."'";
                            if ($var_staffid == $row['nStaffId']) {

                                $options .=" selected=\"selected\"";
                            }
                            $options .=">".htmlentities($row['vStaffname'])."</option>\n";
                            echo $options;
                        }

                        ?>
                    </select>
                </td>
                <td width="15%" align=right class="whitebasic" ><?php echo TEXT_KEYWORD ?></td>
                <td width="23%" align=left  class="whitebasic" >
                    <input name="txtKwd" type="text" class="comm_input" id="txtKwd" size="20" maxlength="100" value="<?php echo htmlentities($var_kwd); ?>" style="width:110px;">
                </td>
                <td width="11%" align=right class="whitebasic" >&nbsp;</td>
            </tr>
            <tr>
                <td width="16%" align="right" class="whitebasic" ><?php echo TXT_SELECT_DATE_RANGE ?></td>
                    <!--<td width="17%" align="right" class="whitebasic" ></td>-->
                <td width="13%" align="left"  class="whitebasic" ><?php echo TEXT_FROM ?>
                    <input name="txtSdate" type="text" class="comm_input" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>" style="width:110px" readonly >
                    <input type="button" value="V" id="button1" name="button1"  class="button" style=" width:30px;">
                    <script type="text/javascript">
                        Calendar.setup({
                            inputField    	: "txtSdate",
                            button        : "button1",
                            ifFormat      	: "%m/%d/%Y %H:%M:%S",       // format of the input field
                            showsTime      	: true,
                            timeFormat     	: "24"
                        });
                    </script>
                </td>
<!--														<td width="17%" align="right" class="whitebasic" ></td>-->
                <td width="37%" class="whitebasic" align=left colspan="3"><?php echo TEXT_TO ?>
                    <input name="txtEdate" type="text" class="comm_input" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>" style="width:110px" readonly >
                    <input type="button" value="V" id="button2" name="button2"  class="button" style=" width:30px;">
                    <script type="text/javascript">
                        Calendar.setup({
                            inputField    	: "txtEdate",
                            button          : "button2",
                            ifFormat      	: "%m/%d/%Y %H:%M:%S",       // format of the input field
                            showsTime      	: true,
                            timeFormat     	: "24"
                        });
                    </script>
                </td>
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
                                                        <input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SHOW_LOGS; ?>" onClick="javascript:clickShowLogs();"></td></tr>
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
                            require("./includes/chatlogs_detail.php");
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
</div>
