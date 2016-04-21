<?php
//$var_userid = $_SESSION["sess_staffid"];
$var_staffid=$_SESSION["sess_staffid"];
$var_new_flag = false;
if ($_POST["postback"] == "D") {
	if (validateDeletion(addslashes($_POST["id"])) == true and addslashes($_POST["id"]) !="1") {
	   	$sql="SELECT vCSSURL    FROM sptbl_css WHERE   nCSSId ='" . addslashes($_POST["id"]) . "'";
		$rs_oldurl = executeSelect($sql,$conn);
	    $rowoldurl=mysql_fetch_array($rs_oldurl);
		$oldurl=$rowoldurl['vCSSURL'];
		//chmod("../".$oldurl,0777);
		unlink("../".$oldurl);
	    executeQuery($sql,$conn);
		$sql = "delete from  sptbl_css   where   nCSSId  ='" . addslashes($_POST["id"]) . "'";
		
		executeQuery($sql,$conn);
	   //Insert the actionlog
	   if(logActivity()) {
		$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','CSS','" . addslashes($_POST["id"]) . "',now())";					
		executeQuery($sql,$conn);
		}
		$var_message = MESSAGE_RECORD_DELETED;
                $flag_msg    = 'class="msg_success"';
	}
	else {
		$var_message = MESSAGE_RECORD_ERROR;
                $flag_msg    = 'class="msg_error"';
	}
}
elseif ($_POST["postback"] == "DA") {
	$var_list = "'',";
	for($i=0;$i<count($_POST["chk"]);$i++) {
	   if(addslashes($_POST["chk"][$i]) !="1") {
			$var_list .= "'" . addslashes($_POST["chk"][$i]) . "',";
		} 
		else {
			$var_new_flag = true;
		}
	}
	$var_list = substr($var_list,0,-1);
	if ($var_list != "''" && validateDeletion($var_list) == true  ) {
	    $sql="SELECT vCSSURL    FROM sptbl_css WHERE   nCSSId IN(" . $var_list . ")";
		$rs_oldurl = executeSelect($sql,$conn);
	    while($rowoldurl=mysql_fetch_array($rs_oldurl)){
		   $oldurl=$rowoldurl['vCSSURL'];
		   //chmod("../".$oldurl,0777);
		   unlink("../".$oldurl);
		}   
		$sql = "delete from  sptbl_css   where nCSSId  IN(" . $var_list . ")";
		
		executeQuery($sql,$conn);
		
		
		//Insert the actionlog
		if(logActivity()) {
			for($i=0;$i<count($_POST["chk"]);$i++) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','CSS','" . addslashes($_POST["chk"][$i]) . "',now())";			
				executeQuery($sql,$conn);
			}	
		}
		$var_message = MESSAGE_RECORD_DELETED;
                $flag_msg    = 'class="msg_success"';
	}
	else {
		$var_message = MESSAGE_RECORD_ERROR;
                $flag_msg    = 'class="msg_error"';
	}
}

if($var_new_flag == true) {
	$var_message .= MESSAGE_DISPLAY_ERROR;
        $flag_msg    = 'class="msg_error"';
}
function validateDeletion($var_list) {
	        global $conn;
	        $sql=" update sptbl_users set nCSSId=1 where nCSSId in($var_list)";
			$rs=  executeQuery($sql,$conn);
            $sql=" update sptbl_staffs set nCSSId=1 where nCSSId in($var_list)";
            $rs=  executeQuery($sql,$conn);
	    	return true;
}


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
 
$sql = "Select * from sptbl_css  where 1=1  ";
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
	if($var_cmbSearch == "title"){
	        $qryopt .= " AND vCSSName like '" . addslashes($var_search) . "%'";
	}elseif($var_cmbSearch == "pdate"){
	      
			$qryopt .= " AND date_format(dDate,'%m-%d-%Y %H:%i:%s') like '" . addslashes($var_search) . "%'";
	}elseif($var_cmbSearch == "fname"){
			$qryopt .= " AND   vCSSURL  like 'styles/" . addslashes($var_search) . "%'";
	}
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By dDate ,vCSSName   Asc ";


?>

<form name="frmDisplay" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">

<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_DISPLAY_DETAILS ?></h3>
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
															<div class="left rightmargin topmargin"><?php echo(TEXT_SEARCH); ?></div>
															<div class="left rightmargin">
															<select name="cmbSearch" class="selectstyle">
                                                            <option value="title" <?php echo(($var_cmbSearch == "title" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
															<option value="pdate" <?php echo(($var_cmbSearch == "pdate" )?"Selected":""); ?>><?php echo TEXT_POSTED_DATE ?></option>
															<option value="fname" <?php echo(($var_cmbSearch == "fname" )?"Selected":""); ?>><?php echo TEXT_FILE_NAME ?></option>
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
													
													<tr><td colspan="2" align="center" >
                                                                                                                <?php
                                                                                                                if ($var_message != ""){?>
														<div <?php echo $flag_msg; ?>>
														<b><?php echo($var_message); ?></b>
														</div>
                                                                                                                <?php
                                                                                                                }?>
													</td></tr>
												</table>
											</td>
										</tr>	
									    <tr>
                                          <td class="whitebasic" ><table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl" >
                                              <tr align="left"  class="listing">
											  	<th width="5%">&nbsp;</th>
                                                <th width="33%"><?php echo "<b>".TEXT_TITLE."</b>"; ?></th>
                                                <th width="13%"><?php echo "<b>".TEXT_POSTED_DATE."</b>"; ?></th>
                                                <th width="35%"><?php echo "<b>".TEXT_FILE_NAME."</b>"; ?></th>
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
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEDISPLAY&styleminus=minus3&styleplus=plus3&",$var_numBegin,$var_start,$var_begin,$var_num);

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
											  	<td align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nCSSId"]); ?>" class="checkbox"></td>
											    <td><?PHP echo htmlentities(trim_the_string($row["vCSSName"])); ?></td>
                                                <td><?php echo datetimefrommysql($row["dDate"]); ?></td>
                                                <td><?php echo basename($row["vCSSURL"]);?></td>
                                                <td width="7%" align="center"><a href="editdisplay.php?id=<?php echo $row["nCSSId"]; ?>&stylename=STYLEDISPLAY1&styleminus=minus3&styleplus=plus3&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_EDIT ?>"></a></td>
                                                <td width="7%" align="center"><a href="javascript:deleted('<?php echo $row["nCSSId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE ?>"></a></td>
                                              </tr>
<?php
$cnt++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="listingmaintext">
											  	<td colspan="7">
												
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
                 </td>
              </tr>
            </table>
			</div>
</form>