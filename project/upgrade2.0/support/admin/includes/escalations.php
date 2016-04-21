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

if ($_POST["postback"] == "D") {
                        $var_id = $_POST['id'];
			$sql = "DELETE FROM sptbl_escalationrules where nERId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);                        
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
	}
        elseif ($_POST["postback"] == "S") {
                        $var_id = $_POST['id'];
                        $status = $_POST['status'];
			$sql = "Update sptbl_escalationrules set nStatus='" . addslashes($status) . "'
                                        where nERId='" . addslashes($var_id) . "'";
				executeQuery($sql,$conn);
			$var_message = MESSAGE_RECORD_UPDATED;
                        $flag_msg    = 'class="msg_success"';
	}

$sql = "Select e.nERId, e.vRuleName, e.nStatus from  sptbl_escalationrules e ";

$qryopt="";

if($_POST["vRuleName"] != ""){
		$var_Search = $_POST["vRuleName"];
}

if($var_Search != ""){
	$qryopt .= " WHERE e.vRuleName like '" . addslashes($var_Search) . "%'";
}

$sql .= $qryopt . " Order By e.nERId DESC ";
//echo $sql;

?>
<!--<a href="cron_ticket.php">Test Cron</a>-->
<div class="content_section">
<form name="frmDetail" id="frmDetail" action="escalations.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&" method="post">
<Div class="content_section_title"><h3><?php echo HEADING_ESCLATION_RULES ?></h3></Div>

               
                       
                                              <div class="content_section_data" align="right">
                                                  <span style="float: left;width: 60%" <?php echo $flag_msg; ?>><?php echo($var_message); ?></span>
                                                            <input type="button" class="selectbox1" value="<?php echo TEXT_ADD_RULE?>" style="cursor: pointer" onclick="javascript:window.location.href='editescalation.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&'">
                                            </div>     
                                                 
												 
												 <div class="content_search_container">
												 <div class="left rightmargin topmargin">
												 <?php echo   TEXT_SEARCH?>
						                         </div>
												    <div class="left rightmargin">
                                                          <select name="cmbSearch" class="comm_input input_width1">
                                                            <option value="name"><?php echo TEXT_RULE ?></option>
                                                          </select>
                                                     </div>
						   &nbsp;
                                                        <div class="left">
						&nbsp;&nbsp;<input type="text" name="vRuleName" value="<?php echo(htmlentities($var_Search)); ?>" class="comm_input input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
														
						</div>  <div class="left">
                                                        &nbsp;&nbsp; <a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
														</div>
                                                   <div class="clear"></div>
													</div>
                                                        

                                              
                                        <tr>
                                          <td class="whitebasic" ><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
                                              <tr align="left"  class="listing">
						<th width="4%">&nbsp;</th>
                                                <th width="36%" ><?php echo "<b>".TEXT_RULE."</b>"; ?></th>
                                                <th width="30%"><?php echo "<b>".TEXT_STATUS."</b>"; ?></th>
                                                <th width="30%" align="center" colspan="2"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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

//echo $totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLESTAFF&styleminus=minus5&styleplus=plus5&",$var_numBegin,$var_start,$var_begin,$var_num;
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLESTAFF&styleminus=minus5&styleplus=plus5&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
$rs = executeSelect($sql,$conn);
$cnt = 1;

if(mysql_num_rows($rs) > 0){
    
    while($row = mysql_fetch_array($rs)) {

?>

                                              <tr align="left"  class="whitebasic">
                                                <td width="4%" align="center"><?PHP echo $cnt; ?></td>
                                                <td><?PHP echo htmlentities($row["vRuleName"]); ?></td>
                                                <td><a href="javascript:changestatus('<?php echo $row["nERId"]; ?>','<?php echo (($row["nStatus"] == '0')?"1":"0"); ?>');"><?php echo (($row["nStatus"] == '0')?"Active":"Inactive"); ?></a></td>
                                                <td width="6%" align="center"><a href="editescalation.php?id=<?php echo $row["nERId"]; ?>&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_EDIT_RULE ?>"></a></td>
                                                <td width="6%" align="center"><a href="javascript:deleted('<?php echo $row["nERId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_DELETE_RULE ?>"></a></td>
                                              </tr>
                                                <?php
                                                    $cnt++;
                                                    }//end while
                                                    mysql_free_result($rs);
                                                }//end if                                               
                                                ?>                                              
                                              <tr align="left"  class="listingmaintext">
                                                <td colspan="7">
												<div class="content_section_data">
											<div class="pagination_container">
																		<div class="pagination_info">
                                                        <?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?>
														</div>
                                                                     <div class="pagination_links">  <?php echo($navigate[2]); ?>
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
                                                                          <input type="hidden" name="status" value="">
																		  </div>
<div class="clear">
</div>
</div>
                                                                 
                                                </td>
                                             </tr>
                                          </table>

                  

                  
</form>
</div>