<?php
	$fld_prio = $_SESSION["sess_priority"];
	if ($_GET["mt"] == "y") {
		$var_stylename = $_GET["stylename"];
		$var_styleminus = $_GET["styleminus"];
		$var_styleplus = $_GET["styleplus"];
	}
	elseif ($_POST["mt"] == "y") {
		$var_stylename = $_POST["stylename"];
		$var_styleminus = $_POST["styleminus"];
		$var_styleplus = $_POST["styleplus"];
	}
?>

<div class="content_section">

<form name="frmSearch" action="advancedresult.php" method="post">

<div class="content_section_title"><h4><?php echo HEADING_SEARCH ?></h4></div>

<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="comm_tbl btm_brdr">
			
			<tr>
			
			<td align="right" valign="middle" width="20%"><?php echo TEXT_REFNO ?>&nbsp;<?php echo TEXT_MATCHES_TWO ?></td>
			<td align="left" valign="middle" width="25%"><input name="txtRefno" type="text" class="comm_input input_width1" id="txtRefno"  maxlength="50"></td>
			<td align="right" valign="middle" width="15%"><?php echo TEXT_TITLE ?>&nbsp;</td>
			<td align="left" valign="middle" width="40%"><input name="txtTitle" type="text" class="comm_input input_width1" id="txtTitle"  maxlength="50"></td>

                        <td align="right" valign="middle"><?php echo TEXT_STATUS ?>&nbsp;<?php echo TEXT_MATCHES ?></td>
			<td align="left" valign="middle">
			<select name="cmbStatus"  class="comm_input" style="width:136px;">
				<option value=""><?php echo TEXT_SELECT ?></option>
				<option value="open"><?php echo TEXT_OPEN ?></option>
				<option value="closed"><?php echo TEXT_CLOSED ?></option>
				<option value="escalated"><?php echo TEXT_ESCALATED ?></option>
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
			
			
			<tr>
			
			<td align="right"  style="display:none" valign="middle"><?php echo TEXT_PRIORITY ?>&nbsp;<?php echo TEXT_MATCHES ?></td>
			<td align="left"  style="display:none" valign="middle">
			<select name="cmbPriority" class="comm_input input_width1a">
				<option value="">Select</option>
				<?php
					for($j=0;$j < count($fld_prio);$j++) {
 						echo("<option value=\"" . $fld_prio[$j][0] . "\">" . $fld_prio[$j][2] . "</option>");
					}
				?>
			</select>
			</td>
			
			</tr>
			
			</table>
			<div class="content_section_title"><h4><?php echo TEXT_DATE_RANGE ?></h4></div>
			<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="comm_tbl btm_brdr">
			<tr>			
			<td align="right" valign="middle" width="20%"><?php echo TEXT_FROM ?>&nbsp;</td>
			<td align="left" valign="middle" width="25%"><input name="txtFrom" id="txtFrom" type="text" class="comm_input" size="19" readonly >
			<input name="btFrom"  id="btFrom" type="button" class="comm_btn" value="v" onClick="">
			 <script type="text/javascript">
				Calendar.setup({
				inputField    	: "txtFrom",
				button        	: "btFrom",
				ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
				showsTime      	: true,
				timeFormat     	: "24"
				});
			  </script>
			</td>
			<td align="right" valign="middle" width="1%"><?php echo TEXT_TO ?>&nbsp;</td>
			<td align="left" valign="middle" width="40%"><input name="txtTo" id="txtTo" type="text" class="comm_input" size="19" readonly style="width:150px">
			<input name="btTo"  id="btTo" type="button" class="comm_btn" value="v" onClick="">
			 <script type="text/javascript">
				Calendar.setup({
				inputField    	: "txtTo",
				button        	: "btTo",
				ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
				showsTime      	: true,
				timeFormat     	: "24"
				});
			  </script>
			</td>
			</tr>
			</table>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="comm_tbl btm_brdr">
			<tr>
			<td align="center">
			<input type="hidden" name="mt" value="y">
			<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
			<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
			<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
			
			<input name="btSearch" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SEARCH; ?>" onClick="javascript:clickSearch();">
			&nbsp;&nbsp;<input name="btSearch" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL; ?>"></td>
			</tr>	
			</table>

	



</form>
<div class="clear"></div>
</div>