<?php
//$var_userid = $_SESSION["sess_staffid"];
 $flag_msg = "";
$var_staffid =  $_SESSION["sess_staffid"];

if ($_POST["postback"] == "D") {
        if (validateDeletion(addslashes($_POST["id"])) == true) {
                //Delete one company that is clicked on the icon
                $sql = "delete from sptbl_reminders where nRemId = '" . addslashes($_POST["id"]) . "'";
                executeSelect($sql,$conn);

                //Insert the actionlog
				if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Reminders','" . addslashes($_POST["id"]) . "',now())";
					executeQuery($sql,$conn);
				}

                $var_message = MESSAGE_RECORD_DELETED;
                 $flag_msg = "class='msg_success'";
        }
        else {
                $var_message = MESSAGE_RECORD_ERROR;
                 $flag_msg = "class='msg_error'";
        }
}
elseif ($_POST["postback"] == "DA") {
        $var_list = "";
        for($i=0;$i<count($_POST["chk"]);$i++) {
                $var_list .= "'" . addslashes($_POST["chk"][$i]) . "',";
        }
        $var_list = substr($var_list,0,-1);

        if (validateDeletion($var_list) == true) {
                //Delete one company that is clicked on the icon
                $sql = "delete from sptbl_reminders where nRemId IN(" . $var_list . ")";
                executeQuery($sql,$conn);

                //Insert the actionlog
				if(logActivity()) {
					for($i=0;$i<count($_POST["chk"]);$i++) {
							$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Reminders','" . addslashes($_POST["chk"][$i]) . "',now())";
							executeQuery($sql,$conn);
					}
				}
                $var_message = MESSAGE_RECORD_DELETED;
                 $flag_msg = "class='msg_success'";
        }
        else {
                $var_message = MESSAGE_RECORD_ERROR;
                 $flag_msg = "class='msg_error'";
        }
}


function validateDeletion($var_list) {
/*        global $conn;
        $sql = "Select nCompId from sptbl_depts where nCompId IN($var_list)";
        if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
                return false;
        }
        else {
                return true;
        }
*/
return true;
}

if($_GET["mt"] != "") {
		$var_mt = $_GET["mt"];
        $var_numBegin = $_GET["numBegin"];
        $var_start = $_GET["start"];
        $var_begin = $_GET["begin"];
        $var_num = $_GET["num"];
        $var_styleminus = $_GET["styleminus"];
        $var_stylename = $_GET["stylename"];
        $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] != "") {
		$var_mt = $_POST["mt"];
        $var_numBegin = $_POST["numBegin"];
        $var_start = $_POST["start"];
        $var_begin = $_POST["begin"];
        $var_num = $_POST["num"];
        $var_styleminus = $_POST["styleminus"];
        $var_stylename = $_POST["stylename"];
        $var_styleplus = $_POST["styleplus"];
}

$sql = "Select R.nRemId,R.nStaffid,R.vRemTitle,R.dRemAlert,R.dRemPost,S.vStaffname from sptbl_reminders R 
		inner join sptbl_staffs S on R.nStaffId = S.nStaffId  ";

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

if ($var_search != ""){
        if ($var_cmbSearch == "fromname") {
                        $qryopt .= " WHERE S.vStaffName like '" . addslashes($var_search) . "%'" . (($var_mt == "y")?(" AND R.nStaffId='$var_staffid' "):"");
        }elseif ($var_cmbSearch == "title") {
                        $qryopt .= " WHERE R.vRemTitle like '" . addslashes($var_search) . "%'" . (($var_mt == "y")?(" AND R.nStaffId='$var_staffid' "):"");
        }
}
elseif ($var_mt == "y") {
	$qryopt .= " WHERE R.nStaffId='$var_staffid' ";
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By R.dRemAlert DESC ";
?>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">

<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_REMINDERS ?></h3>
			</div>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="100%">
											<table width="100%" cellpadding="0" cellspacing="0" border="0">
												
													<tr>
														<td width="65%" align="right" class="whitebasic">
														<div class="content_search_container" style="background-color:#ffffff; ">
															<div class="left rightmargin topmargin">
																<?php echo TEXT_SEARCH ?>
															</div>
															<div class="left rightmargin">
																<select name="cmbSearch" class="selectstyle">
																<option value="fromname" <?php echo(($var_cmbSearch == "fromname" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_STAFF ?></option>
																<option value="title" <?php echo(($var_cmbSearch == "title")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
																</select>
															</div>
															<div class="left rightmargin">
															<input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="inputstyle" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
															</div>
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
													<div <?php echo $flag_msg;?>>
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
                                          <td class="whitebasic" >
                                           <table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl" >
                                              <tr align="left"  class="listing">
											    <th>&nbsp;</th>
                                                <th width="25%"><?php echo "<b>".TEXT_STAFF."</b>"; ?></th>
                                                <th width="35%"><?php echo "<b>".TEXT_TITLE."</b>"; ?></th>
                                                <th width="13%"><?php echo "<b>".TEXT_ALERT."</b>"; ?></th>
                                                <th width="11%"><?php echo "<b>".TEXT_POST."</b>"; ?></th>
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
$navigate = pageBrowser($totalrows,10,10,"&mt=$var_mt&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEREMINDERS&styleminus=minus14&styleplus=plus14&",$var_numBegin,$var_start,$var_begin,$var_num);

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
											  <td width="4%" align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nRemId"]); ?>" class="checkbox"></td>
                                              <td><?PHP echo htmlentities($row["vStaffname"]); ?></td>                                               
                                                <td width="35%"><?php echo htmlentities($row["vRemTitle"]); ?></td>
                                                <td width="13%"><?php echo date("m-d-Y",strtotime($row["dRemAlert"])); ?></td>
                                                <td width="11%"><?php echo date("m-d-Y",strtotime($row["dRemPost"])); ?></td>
                                                <td width="6%" align="center"><a href="vwreminder.php?id=<?php echo $row["nRemId"]; ?>&stylename=STYLEREMINDERS&styleminus=minus14&styleplus=plus14&"><img src="././../images/view.gif" width="13" height="13" border="0" title="<?php echo TEXT_VIEW_REMINDER; ?>"></a></td>
                                                <td width="6%" align="center"><a href="javascript:deleted('<?php echo $row["nRemId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_DELETE_REMINDER; ?>"></a></td>
                                              </tr>
<?php
$cnt++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="whitebasic">
											  	<td colspan="7">
												<div class="pagination_info"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></div>
												<div class="pagination_links">
												<?php echo($navigate[2]); ?>
															  <input type="hidden" name="numBegin" value="<?php echo ($var_numBegin); ?>">
															  <input type="hidden" name="start" value="<?php echo ($var_start); ?>">
															  <input type="hidden" name="begin" value="<?php echo ($var_begin); ?>">
															  <input type="hidden" name="num" value="<?php echo($var_num); ?>">
															   <input type="hidden" name="mt" value="<?php echo($var_mt); ?>">
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
                                    <td><input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:clickDelete();"></td>
                                  </tr>
                                </table></td>
                              </tr>
                          </table>
                          <div align="center">                          </div></td>
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