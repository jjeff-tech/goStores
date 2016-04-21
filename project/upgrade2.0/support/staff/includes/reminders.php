<?php
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
                $var_message =MESSAGE_RECORD_ERROR;
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

$sql = "Select nRemId,vRemTitle,dRemAlert,dRemPost from sptbl_reminders ";
$sql .="where nStaffId ='$var_staffid'";

$qryopt='';
if($_POST["txtSearch"] != ""){
                $var_search = addslashes(trim($_POST["txtSearch"]));
}else if($_GET["txtSearch"] != ""){
                $var_search = addslashes(trim($_GET["txtSearch"]));
}

if($_POST["cmbSearch"] != ""){
                $var_cmbSearch = addslashes(trim($_POST["cmbSearch"]));
}else if($_GET["cmbSearch"] != ""){
                $var_cmbSearch = addslashes(trim($_GET["cmbSearch"]));
}

if ($var_search != ""){
        if ($var_cmbSearch == "alertdate") {
               list($month, $day, $year) = explode('-', $var_search);
               $qryopt .= " AND dRemAlert like '$year-$month-$day%'";

        }elseif ($var_cmbSearch == "title") {

               $qryopt .= " AND vRemTitle like '" . $var_search . "%'";
        }
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By dRemAlert DESC ";

?>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<div class="content_section">
	<div class="content_section_title">
	<h3><?php echo HEADING_REMINDERS ?></h3>
	</div>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                        <tr>
                                          <td width="100%">
											<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td width="61%" align="right" class="listingmaintext">
														<div style="background-color:#ffffff; " class="content_search_container">
															<div class="left rightmargin topmargin"> <?php echo(TEXT_SEARCH); ?></div>
															<div class="left rightmargin">
															<select name="cmbSearch" class="selectstyle">
															<option value="alertdate" <?php echo(($var_cmbSearch == "alertdate" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_ALERT ?></option>
															<option value="title" <?php echo(($var_cmbSearch == "title")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
															</select>
															</div>
															<div class="left">
															<input type="text" name="txtSearch" value="<?php echo(htmlentities(stripslashes($var_search))); ?>" class="inputstyle" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
															&nbsp;&nbsp;</div>
															<div class="left">
															<a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
															</div>
															<div class="clear"></div>
														</div>
														
														
														
											
											
											
											</td>
												</tr>
											</table>
                                                                                        </td>
                                                                                </tr>
																				
																				<tr><td width="100%" align="center" class="errormessage"><div <?php echo $flag_msg; ?>><?php echo($var_message); ?></div></td></tr>
                                                                            <tr>
                                          <td class="whitebasic" >
                                            <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
                                              <tr align="left"  class="listing">
											  <th width="4%">&nbsp;</th>
                                                <th><?php echo "<b>".TEXT_TITLE."</b>"; ?></th>
                                                <th width="19%"><?php echo "<b>".TEXT_ALERT."</b>"; ?></th>
                                                <th width="20%"><?php echo "<b>".TEXT_POST."</b>"; ?></th>
                                                <th colspan="2" align="left"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3&",$var_numBegin,$var_start,$var_begin,$var_num);

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
											    <td><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nRemId"]); ?>" class="checkbox">
								                </td> 
												<td width="45%"><?php echo htmlentities($row["vRemTitle"]); ?></td>
                                                <td width="19%"><?php echo date("m-d-Y",strtotime($row["dRemAlert"])); ?></td>
                                                <td width="20%"><?php echo date("m-d-Y",strtotime($row["dRemPost"])); ?></td>
                                                <td width="5%"><a href="editreminder.php?id=<?php echo $row["nRemId"]; ?>&stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo(TITLE_EDIT_REMINDER); ?>"></a></td>
                                                <td width="7%"><a href="javascript:deleted('<?php echo $row["nRemId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo(TITLE_DELETE_REMINDER); ?>"></a></td>
                                              </tr>
<?php
$cnt++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="whitebasic">
											  	<td colspan="7">
													
															 <div class="pagination_info"> <?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></div>
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
                                    </table></td>
                                  </tr>
                              </table>
				</td>
            </tr>
          </table>

                  
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center" class="whitebasic">
                                    <td><input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:clickDelete();"></td>
                                  </tr>
                                </table></td>
                              </tr>
                          </table>
                          <div align="center">                          </div></td>
                        
                      </tr>
                    </table>
                    
			</div>
</form>