<?php
$leafdeptarr=getLeafDepts();
if($leafdeptarr !="") {
    $leaflvldeptids=implode(",",$leafdeptarr);

}else {
    $leaflvldeptids=0;
}
/*
$sql = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from 
		sptbl_depts d  inner join sptbl_companies c
		 on d.nCompId = c.nCompId Where d.nDeptId IN($leaflvldeptids)"; */

$sql = "Select d.nDeptId,d.vDeptCode,d.vDeptDesc as 'description',c.vCompName  from
		sptbl_depts d  inner join sptbl_companies c
		 on d.nCompId = c.nCompId Where d.nDeptId IN($leaflvldeptids)";

if($_POST["cmbCompany"] != "") {
    $sql .= " AND c.nCompId='" . mysql_real_escape_string($_POST["cmbCompany"]) . "'";
}

$lst_dept_opt = "";
$rs_dept = executeSelect($sql,$conn);
if (mysql_num_rows($rs_dept) > 0) {
    while($row = mysql_fetch_array($rs_dept)) {
        $lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\">" . htmlentities($row["description"]) . "</option>";
    }
}
mysql_free_result($rs_dept);
?>

<div class="content_section">

    <form name="frmSearch" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

        <div class="content_section_title"><h3><?php echo HEADING_TICKET_PURGE ?></h3></div>

        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" style="border-top:0!important; ">
            <tr align="left" bgcolor="#FFFFFF" class="whitebasic">
                <td width="14%"><?php echo TEXT_COMPANY ?></td>
                <td width="20%" align="center">
                <!--<select name="cmbCompLp" class="textbox" style="width:70px; ">
				<option value=""> </option>
			</select> -->						
                </td>
                <td width="27%"><div align="center">
                        <select name="cmbCompOp" class="comm_input input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td width="39%"><select name="cmbCompany" class="comm_input input_width1" onChange="javascript:document.frmSearch.submit();">
                        <option value=""> </option>
                        <?php
                        $sql = "Select c.nCompId,c.vCompName from sptbl_companies c	 WHERE vDelStatus='0' ";
                        $rs = executeSelect($sql,$conn);
                        if (mysql_num_rows($rs) > 0) {
                            while($row = mysql_fetch_array($rs)) {
                                ?>
                        <option value="<?php echo($row["nCompId"]); ?>"><?php echo(htmlentities($row["vCompName"]));?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor">
                <td><?php echo TEXT_DEPARTMENT ?></td>
                <td align="center"><select name="cmbDeptLp" class="comm_input input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td><div align="center">
                        <select name="cmbDeptOp" class="comm_input input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td><select name="txtDepartment" class="comm_input input_width1">
                        <option value=""> </option>
                        <?php
                        echo($lst_dept_opt);
                        ?>
                    </select></td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor">
                <td><?php echo TEXT_STATUS ?></td>
                <td align="center"><select name="cmbStatusLp" class="comm_input input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td><div align="center">
                        <select name="cmbStatusOp" class="comm_input input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td>
                    <select name="txtStatus" class="comm_input input_width1">
                        <option value=""> </option>
                        <option value="open">open</option>
                        <option value="closed">closed</option>
                        <option value="escalated">escalated</option>
                        <?php
                        $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='ExtraStatus' ";
                        $rs = executeSelect($sql,$conn);
                        if (mysql_num_rows($rs) > 0) {
                            while($row = mysql_fetch_array($rs)) {
                                echo("<option value=\"" . $row["vLookUpValue"] . "\">" . htmlentities($row["vLookUpValue"]) . "</option>");
                            }
                        }
                        mysql_free_result($rs);
                        ?>
                    </select>
                </td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor">
                <td><?php echo TEXT_OWNER ?></td>
                <td align="center"><select name="cmbOwnerLp" class="comm_input input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td><div align="center">
                        <select name="cmbOwnerOp" class="comm_input input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td>
                    <select name="txtOwner" class="comm_input input_width1">
                        <option value=""> </option>
                        <?php
                        $sql = "Select nStaffId,vLogin from sptbl_staffs";
                        $rs = executeSelect($sql,$conn);
                        if (mysql_num_rows($rs) > 0) {
                            while($row = mysql_fetch_array($rs)) {
                                echo("<option value=\"" . $row["nStaffId"] . "\">" . htmlentities($row["vLogin"]) . "</option>");
                            }
                        }
                        mysql_free_result($rs);
                        ?>
                    </select>
                </td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor">
                <td><?php echo TEXT_USER ?></td>
                <td align="center"><select name="cmbUserLp" class="comm_input input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td><div align="center">
                        <select name="cmbUserOp" class="comm_input input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td>
                    <input type="text" name="txtUser" class="comm_input11btxt input_width1" size="37" maxlength="20">
                </td>
            </tr>

            <tr align="left">
                <td colspan=4 align="left">
                    <div class="content_section_title"><h4><?php echo TEXT_DATE_RANGE ?></h4></div>
                </td>
            </tr>


            <tr align="left" class="whitebasic">
                <td colspan="4" align="center" class="subtbl">

                    <div class="content_search_container">
                        <div class="left rightmargin topmargin">
                            <?php echo TEXT_FROM ?>
                        </div>

                        <div class="left rightmargin">
                            <input name="txtFrom" id="txtFrom" type="text" class="comm_input_txtbx left" size="30" readonly="true" style="margin-right: 5px;">
                            <input name="btFrom"  id="btFrom" type="button" class="secondary_btn left"  value="" onClick="" style="margin-right: 8px;">
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField    	: "txtFrom",
                                    button        	: "btFrom",
                                    ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                    showsTime      	: true,
                                    timeFormat     	: "24"
                                });
                            </script>
                        </div>
                        <div class="left rightmargin topmargin">
                            <?php echo TEXT_TO ?>
                        </div>

                        <div class="left">
                            <input name="txtTo" id="txtTo" type="text" class="comm_input_txtbx left" size="30" readonly="true" style="margin-right: 5px;">
                            <input name="btTo"  id="btTo" type="button" class="secondary_btn left"  value="" onClick="" style="margin-right: 8px;">
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField    	: "txtTo",
                                    button        	: "btTo",
                                    ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                    showsTime      	: true,
                                    timeFormat     	: "24"
                                });
                            </script>
                        </div>
						  <div>
            <input type="hidden" name="mt" value="y">
            <input type="reset" name="Reset" value="<?php echo BUTTON_TEXT_RESET; ?>" class="comm_btn">
            <input name="btSearch" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_PURGE; ?>" onClick="javascript:clickPurge();">
        </div>
                        <div class="clear"></div>
                    </div>

                </td>
            </tr>
            <tr class="whitebasic"><td colspan="5">&nbsp;</td></tr>
        </table>

      

    </form>
    <script>
<?php
if($_POST["cmbCompany"] != "") {
    echo("document.frmSearch.cmbCompany.value='" . $_POST["cmbCompany"] . "';");
}
?>
    </script>