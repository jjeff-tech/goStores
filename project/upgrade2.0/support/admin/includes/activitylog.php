<?php
if($_GET["mt"] == "y") {
	$var_numBegin = $_GET["numBegin"];
	$var_start = $_GET["start"];
	$var_begin = $_GET["begin"];
	$var_num = $_GET["num"];
	$var_styleminus = $_GET["styleminus"];
	$var_stylename = $_GET["stylename"];
	$var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	$var_styleminus = $_POST["styleminus"];
	$var_stylename = $_POST["stylename"];
	$var_styleplus = $_POST["styleplus"];
}

$sql = "Select nStaffId,vStaffname from sptbl_staffs where vDelStatus='0'";
$result = executeSelect($sql,$conn);
$lst_staff="";
if (mysql_num_rows($result) > 0) {
	while($row = mysql_fetch_array($result)) {
		$lst_staff .= "<option value=\"" . htmlentities($row["nStaffId"]) . "\">" . htmlentities($row["vStaffname"]) . "</option>";
	}
}
mysql_free_result($result);

$sql = "Select a.vAction,a.nStaffId,a.vArea,a.dDate,s.vStaffname from sptbl_actionlog a inner join sptbl_staffs s
on a.nStaffId = s.nStaffId Where s.vDelStatus='0' ";

$qryopt="";
if(trim($_POST["txtSearch"]) != ""){
		$var_search = trim($_POST["txtSearch"]);
}else if(trim($_GET["txtSearch"]) != ""){
		$var_search = trim($_GET["txtSearch"]);
}

if(trim($_POST["cmbSearch"]) != ""){
		$var_cmbSearch = trim($_POST["cmbSearch"]);
}else if(trim($_GET["cmbSearch"]) != ""){
		$var_cmbSearch = trim($_GET["cmbSearch"]);
}
$var_cmbStaff="";
if(trim($_POST["cmbStaff"]) != ""){
		$var_cmbStaff = trim($_POST["cmbStaff"]);
}else if(trim($_GET["cmbStaff"]) != ""){
		$var_cmbStaff = trim($_GET["cmbStaff"]);
}


if ($var_search != ""){
	if($var_cmbSearch == "activity") {
			$qryopt .= " AND a.vAction like '" . addslashes($var_search) . "%' " . (($var_cmbStaff != "")?(" AND a.nStaffId='" . addslashes($var_cmbStaff) . "' "):"  AND a.nStaffId!='0' ");
			
	}elseif($var_cmbSearch == "area") {
			$qryopt .= " AND a.vArea like '" . addslashes($var_search) . "%' " . (($var_cmbStaff != "")?(" AND a.nStaffId='" . addslashes($var_cmbStaff) . "' "):" AND a.nStaffId!='0' ");
	}
}
elseif ($var_cmbStaff != "") {
		$qryopt .= " AND a.nStaffId='" . addslashes($var_cmbStaff) . "' ";
}
else {
		$qryopt .= " AND a.nStaffId != '0' ";
}


//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By a.dDate DESC ";
?>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_ACTIVITY_LOG ?></h3>
			</div>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                  <tr>
                                    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
											<td width="100%">
											
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													
													<tr class="whitebasic">
													
													<td align="left">
													<div class="content_search_container" style="background-color:#ffffff; width:610px;">
														<div class="left rightmargin topmargin">
															<?php echo TEXT_SELECT_STAFF?>
														</div>
														<div class="left rightmargin">
															<select name="cmbStaff" class="selectstyle" onchange="javascript:submitForm();">
															<?php echo($lst_staff); ?>
															<option value="">-<?php echo TEXT_ALL?>-</option>
															</select>

														</div>
														<div class="clear"></div>
													</div>

													</td>
													
													<td align="right">
													<div class="content_search_container right" style="background-color:#ffffff; width:330px; ">
														<div class="left rightmargin topmargin">
															<?php echo TEXT_SEARCH ?> 
														</div>
														<div class="left rightmargin">
															<select name="cmbSearch" class="selectstyle">
                                                            <option value="activity" <?php echo(($var_cmbSearch == "action" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_ACTIVITY ?></option>
                                                            <option value="area" <?php echo(($var_cmbSearch == "area")?"Selected":""); ?>><?php echo TEXT_AREA ?></option>
                                                          </select>
														</div>
														<div class="left">
														<input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="inputstyle" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
														</div>
														<div class="left">
														<a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
														</div>
														<div class="clear"></div>
													</div>

													</td>
													<td>&nbsp;</td>
												    </tr>
																				
												</table>
											</td>
										</tr>	
									    <tr>
                                          <td class="whitebasic" width="100%" colspan="2" >
										  
										  <table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl" >
                                              <tr align="left"  class="listing">
                                                <th width="25%" ><?php echo "<b>".TEXT_ACTIVITY."</b>"; ?></th>
                                                <th width="25%"><?php echo "<b>".TEXT_STAFF."</b>"; ?></th>
                                                <th width="28%"><?php echo "<b>".TEXT_AREA."</b>"; ?></th>
                                                <th width="20%"><?php echo "<b>".TEXT_DATE."</b>"; ?></th>
                                              </tr>
<?php
//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
$totalrows = mysql_num_rows(executeSelect($sql,$conn));
settype($totalrows,integer);
settype($var_begin,integer);
settype($var_num,integer);
settype($var_numBegin,integer);
settype($var_start,integer);

$var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
 if(($totalrows <= $var_calc_begin)) {
         $var_nor = 10;
        $var_nol = 10;
         if($var_num > $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin;
                $var_begin = $var_begin - $var_nor;
        }
        elseif($var_num == $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin - $var_nol;
                $var_begin = $var_calc_begin - $var_nor;
                $var_start="";
        }
 }

//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbStaff=$var_cmbStaff&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
$rs = executeSelect($sql,$conn);
$cnt = 1;
while($row = mysql_fetch_array($rs)) {
?>

                                              <tr align="left"  class="listingmaintext">
                                                <td  width="25%"><?PHP echo htmlentities($row["vAction"]); ?>
                                                </td>
                                                <td width="25%"><?php echo htmlentities($row["vStaffname"]); ?></td>
                                                <td width="28%"><?php echo htmlentities($row["vArea"]); ?></td>
                                                <td width="20%"><?php echo date("m-d-Y",strtotime($row["dDate"])); ?></td>
                                              </tr>
<?php
$cnt++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="listingmaintext">
                                                <td colspan="4" width="100%">
                                                <div class="pagination_info"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></div>
                                                <div class="pagination_links">
                                                <?php echo($navigate[2]); ?>
                                                  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
                                                  <input type="hidden" name="start" value="<?php echo   $var_start?>">
                                                  <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
                                                  <input type="hidden" name="num" value="<?php echo   $var_num?>">
                                                  <input type="hidden" name="mt" value="y">
                                                  <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                  <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                  <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                  <input type="hidden" name="postback" value="">
                                                  <input type="hidden" name="id" value=""></div>
                                                </td>
                                             </tr>
                                          </table>
					</td>
                                        </tr>
                                    </table></td>
                                  </tr>
                              </table>

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
                                      <input name="btnCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_RESET ?>" onClick="javascript:clickCancel();">                                    </td></tr>
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
			</div>
<script>
<!--
	staffId ='<?php echo($var_cmbStaff); ?>';
	document.frmDetail.cmbStaff.value=staffId;
 -->
</script>			
</form>