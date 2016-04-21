<?php
//$var_userid = $_SESSION["sess_staffid"];
$var_staffid = $_SESSION["sess_staffid"];
 $flag_msg = "";
if ($_POST["postback"] == "D") {
//	if (validateDeletion(addslashes($_POST["id"])) == true) {
	    $sql = "delete from  sptbl_rules  where nRuleId='" . addslashes($_POST["id"]) . "'";
		executeQuery($sql,$conn);
	   //Insert the actionlog
	   if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Department','" . addslashes($_POST["id"]) . "',now())";
			executeQuery($sql,$conn);
		}
		$var_message = MESSAGE_RECORD_DELETED;
                 $flag_msg = "class='msg_success'";
//	}
//	else {
//		$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
//	}
}
elseif ($_POST["postback"] == "DA") {
	$var_list = "";
	for($i=0;$i<count($_POST["chk"]);$i++) {
		$var_list .= "'" . addslashes($_POST["chk"][$i]) . "',"; 
/*		        $qry="select * from sptbl_rules where nRuleId='".addslashes($_POST["chk"][$i])."'";
			    $rsgetdept = mysql_query($qry);
				$deptrow=mysql_fetch_array($rsgetdept);
			    $oldparentid[$i]=$deptrow['nDeptParent'];		
*/	}
	
	$var_list = substr($var_list,0,-1);
	
	if ($var_list != "") {
		$sql = "delete from  sptbl_rules  where nRuleId  IN(" . $var_list . ")";
		executeQuery($sql,$conn);
		
		//Insert the actionlog
		if(logActivity()) {
			for($i=0;$i<count($_POST["chk"]);$i++) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Rules','" . addslashes($_POST["chk"][$i]) . "',now())";
				executeQuery($sql,$conn);
			}	
		}
		$var_message = MESSAGE_RECORD_DELETED;
	}
	else {
		$var_message = MESSAGE_RECORD_ERROR;
                 $flag_msg = "class='msg_error'";
	}
}
/*
function validateDeletion($var_list) {
	global $conn;
	   $sql="select nTicketId from sptbl_tickets where nRuleId IN($var_list)";
	   $rs = executeSelect($sql,$conn);
		if(mysql_num_rows($rs)>0){
			  return false;
		}else{
		   $sqlparentcheck="select nRuleId from sptbl_rules where nDeptParent IN($var_list)";
		   $rs1 = executeSelect($sqlparentcheck,$conn);
		   if(mysql_num_rows($rs1)>0){
		        return false;
			}  
		}
		//check category table
		   $sqlcattcheck="select nRuleId from sptbl_categories where nRuleId IN($var_list)";
		   $rs2 = executeSelect($sqlcattcheck,$conn);
		   if(mysql_num_rows($rs2)>0){
		        return false;
			}  
			
		$sqlstaffdeptcheck="select nRuleId from sptbl_staffdept where nRuleId IN ($var_list) ";
		$rs3 = executeSelect($sqlstaffdeptcheck,$conn);
		if(mysql_num_rows($rs3)>0){
		        return false;
		}  
		return true;
}

*/
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
}else{
  $var_styleminus = $_GET["styleminus"];
	$var_stylename = $_GET["stylename"];
	$var_styleplus = $_GET["styleplus"];
}
 
$sql = "Select r.nRuleId,r.vRuleName,r.nStaffId,r.nDeptId,date_format(r.dDateCreated,'%m-%d-%Y') as dDate,s.vStaffName from sptbl_rules as r,sptbl_staffs as s ";
$sql .=" where r.nStaffId=s.nStaffId ";

$qryopt="";
if($_POST["txtSearch"] != ""){
		$var_search = $_POST["txtSearch"];
}else if($_GET["txtSearch"] != ""){
		$var_search = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != ""){
		$var_cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != ""){
		$var_cmbSearch = $_GET["cmbSearch"];
}

if($var_search != ""){
	if($var_cmbSearch == "name"){
	        $qryopt .= " AND r.vRuleName like '" . addslashes($var_search) . "%'";
	}elseif($var_cmbSearch == "staff"){
			$qryopt .= " AND s.vStaffName like '" . addslashes($var_search) . "%'";
	}
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By r.vRuleName,s.vStaffName Asc ";
?>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_RULE_DETAILS ?></h3>
			</div>
			
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								
								<tr>
									<td width="61%" align="right" class="whitebasic">
									<div class="content_search_container" style="background-color:#ffffff; ">
										<div class="left rightmargin topmargin">
											<?php echo TEXT_SEARCH ?>
										</div>
										<div class="left rightmargin">
											 <select name="cmbSearch" class="selectstyle">
												<option value="name" <?php echo(($var_cmbSearch == "name" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_RULE ?></option>
												<option value="staff" <?php echo(($var_cmbSearch == "staff")?"Selected":""); ?>><?php echo TEXT_STAFF ?></option>
											  </select>
										</div>
										<div class="left">
										<input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="inputstyle" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
										&nbsp;&nbsp;</div>
										<div class="left">
										<a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
										</div>
										<div class="clear"></div>
									</div>
									</td>
								</tr>
								
								<tr><td align="center" colspan="2" class="errormessage">
								<?php

								if ($var_message != ""){
								?>
									<div  <?php echo $flag_msg; ?>>
								<b><?php echo($var_message); ?></b>
								</div>
								<?php
								}
								?>	
								</td></tr>
							</table>
						</td>
					</tr>	
					<tr>
					  <td class="whitebasic" ><table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl" >
						  <tr align="left"  class="listing">
						  <th width="5%">&nbsp;</th>
							<th width="45%"><?php echo "<b>".TEXT_RULE."</b>"; ?></th>
							<th width="23%"><?php echo "<b>".TEXT_STAFF."</b>"; ?></th>
							<th width="15%"><?php echo "<b>".TEXT_DATE."</b>"; ?></th>
							<th colspan="2" align="center"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
				$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLERULES&styleminus=minus23&styleplus=plus23&",$var_numBegin,$var_start,$var_begin,$var_num);
				
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
				
						  <tr align="left"  class="whitebasic">
							<td align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nRuleId"]); ?>" class="checkbox">                                                                                                 
							<td width="45%"><?PHP echo htmlentities(trim_the_string($row["vRuleName"])); ?></td>
							<td><?php echo htmlentities(trim_the_string($row["vStaffName"])); ?></td>
							<td><?php echo $row["dDate"]; ?></td>
							<td width="6%" align="center"><a href="editrules.php?id=<?php echo $row["nRuleId"]; ?>&stid=<?php echo $row["nStaffId"]; ?>&stylename=STYLERULES&styleminus=minus23&styleplus=plus23&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_EDIT ?>"></a></td>
							<td width="6%" align="center"><a href="javascript:deleted('<?php echo $row["nRuleId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE ?>"></a></td>
						  </tr>
				<?php
				$cnt++;
				}
				mysql_free_result($rs);
				?>
						  <tr align="left"  class="listingmaintext">
							<td colspan="6">
								<div class="pagination_info">
								<?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?>
								</div>
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
										  <input type="hidden" name="id" value="">
								</div>
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
                                      <input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:clickDelete();">                                    </td></tr>
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
</form>