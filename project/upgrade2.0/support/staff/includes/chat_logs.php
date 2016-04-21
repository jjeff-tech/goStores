<?php
$var_staffid = $_SESSION["sess_staffid"];
 $flag_msg="";
if ($_POST["postback"] == "D") {
	if (validateDeletion(addslashes($_POST["id"])) == true) {
	    $sql = "delete from  sptbl_personalnotes   where nPNId='" . addslashes($_POST["id"]) . "'";
		executeQuery($sql,$conn);
	   //Insert the actionlog
	   if(logActivity()) {
		$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Personal Notes','" . addslashes($_POST["id"]) . "',now())";					
		executeQuery($sql,$conn);
		}
		$var_message = MESSAGE_RECORD_DELETED;
                 $flag_msg="class='msg_success'";
	}
	else {
		$var_message = MESSAGE_RECORD_ERROR;
                 $flag_msg="class='msg_error'";
	}
}
elseif ($_POST["postback"] == "DA") {
	$var_list = "";
	for($i=0;$i<count($_POST["chk"]);$i++) {
		$var_list .= "'" . addslashes($_POST["chk"][$i]) . "',"; 
	}
	$var_list = substr($var_list,0,-1);
	
	if (validateDeletion($var_list) == true) {
		$sql = "delete from  sptbl_personalnotes   where nPNId  IN(" . $var_list . ")";
		executeQuery($sql,$conn);
		
		
		//Insert the actionlog
		if(logActivity()) {
			for($i=0;$i<count($_POST["chk"]);$i++) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Personal Notes','" . addslashes($_POST["chk"][$i]) . "',now())";			
				executeQuery($sql,$conn);
			}	
		}
		$var_message = MESSAGE_RECORD_DELETED;
                 $flag_msg="class='msg_success'";
	}
	else {
		$var_message = MESSAGE_RECORD_ERROR;
                 $flag_msg="class='msg_error'";
	}
}


function validateDeletion($var_list) {
	
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

if($_POST["txtSdate"] != "") {
 $var_sdate = trim($_POST["txtSdate"]);
 $var_msdate= datetimetomysql($var_sdate);
 $var_msdate = dateFormat($var_msdate,"Y-m-d", "Y-m-d");
  //$var_msdate= datetimetomysql($var_sdate);
}
if($_POST["txtEdate"] != "") {
  $var_edate = trim($_POST["txtEdate"]);
  $var_medate =datetimetomysql($var_edate);
}
if($_POST["txtKwd"] != "") {
  $var_kwd = $_POST["txtKwd"] ;
}


$sql1 ="Select c.nChatId,c.dTimeStart,c.dTimeEnd,u.vUserName as user_staff,s.vStaffname as staffname,u.vEmail as user_staff_email, s.vMail as staff_email, 'c' as chat_flg from sptbl_chat c inner join sptbl_users u on c.nUserId = u.nUserId inner join sptbl_staffs s on c.nStaffId=s.nStaffId";
$sql1 .= " where c.nStaffId='".addslashes($var_staffid)."'";
if($var_sdate != "" && $var_edate !="") {
  // $sql1 .= " and  (c.dTimeStart >='".addslashes($var_msdate)."' and   c.dTimeStart <='". addslashes($var_medate)."') ";
   $sql1 .= " and  (DATE_FORMAT(c.dTimeStart,'%Y-%m-%d') >='".addslashes($var_msdate)."' and  DATE_FORMAT(c.dTimeStart,'%Y-%m-%d') <='". addslashes($var_medate)."') ";
}
if ( $var_kwd != "" )   $sql1 .= " and  c.tMatter like '%".addslashes($var_kwd)."%'";
			
$sql2 ="Select o.nChatId,o.dTimeStart,o.dTimeEnd,(select vStaffname from sptbl_staffs where nStaffId=o.nFirstStaffID) as user_staff,(select vStaffname from sptbl_staffs where nStaffId=o.nSecondStaffID) as staffname, ( select  vMail from sptbl_staffs where nStaffId=o.nFirstStaffID ) as user_staff_email,( select vMail from sptbl_staffs where nStaffId=o.nSecondStaffID ) as staff_email,'o' as chat_flg  from sptbl_operatorchat o ";
$sql2 .= " where ( o.nFirstStaffID='".addslashes($var_staffid)."' or  o.nSecondStaffID='".addslashes($var_staffid)."')";
if($var_sdate != "" && $var_edate !="") {
  $sql2 .= " and  (o.dTimeStart >='".addslashes($var_msdate)."' and   o.dTimeStart <='". addslashes($var_medate)."') ";
}
if ( $var_kwd != "" )   $sql2 .= " and  o.tMatter like '%".addslashes($var_kwd)."%'";
$sql = $sql1." UNION ".$sql2." order by dTimeStart desc"; 



/*
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
	        $qryopt .= " AND p.vPNTitle like '" . addslashes($var_search) . "%'";
	}elseif($var_cmbSearch == "refno"){
			$qryopt .= " AND t.vRefNo like '" . addslashes($var_search) . "%'";
	}
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By p.vPNTitle,p.dDate Asc ";
*/
//echo $sql;
?>

<form name="frmChatLogs" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">











<div class="content_section">
 <div class="content_section_title">
	<h3><?php echo TEXT_CHAT_LOGS ?></h3>
	</div>
           
   
                          
                           
                              <td>
                                  <tr>
                                    <td align="left">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                        <tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                                    <tr><td colspan="6">&nbsp;</td></tr>
													<tr>
													    <td width="12%" align="left" class="listingmaintext">&nbsp;&nbsp;<?php echo(TXT_SELECT_DATE_RANGE); ?></td>
													    <td width="20%" align=left  class="whitebasic" >
														 <input name="txtSdate" type="text" class="comm_input input_width1a" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>" style="width:110px" readonly >
                                                                                                                 <input type="button" value="V" id="button1" name="button1"  class="button" style=" width:30px;height:28px">
												   		 <script type="text/javascript">
													            Calendar.setup({
													            inputField    	: "txtSdate",
													            button        : "button1",
																ifFormat      	: "%m-%d-%Y %H:%M:%S",       // format of the input field
													        	showsTime      	: true,
													        	timeFormat     	: "24"
															    });
                                                                                                                  </script>
													     </td>
													     <td width="20%" align=left  class="whitebasic">
														 <input name="txtEdate" type="text" class="comm_input input_width1a" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>" style="width:110px" readonly >
                                                                                                                <input type="button" value="V" id="button2" name="button2"  class="button" style=" width:30px;height:28px">
												   		 <script type="text/javascript">
													            Calendar.setup({
													            inputField    	: "txtEdate",
													            button          : "button2",
																ifFormat      	: "%m-%d-%Y %H:%M:%S",       // format of the input field
													        	showsTime      	: true,
													        	timeFormat     	: "24"
															    });
                                                                                                                </script>
                                                                                                            </td>
                                                                                                            <td width="5%" align="right" class="whitebasic" ><?php echo TEXT_KEYWORD ?>&nbsp;</td>
                                                                                                            <td width="12%" align="left"  class="whitebasic" >
														 <input name="txtKwd" type="text" class="comm_input input_width1a" id="txtKwd" size="20" maxlength="100" value="<?php echo htmlentities($var_kwd); ?>" style="width:190px;">
                                                                                                            </td>
                                                                                                            
                                                                                                            <td align="center"><input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SHOW_LOGS; ?>" onClick="javascript:clickShowLogs();"></td>

      												   </tr>
													<tr><td colspan="6">&nbsp;</td></tr>
													<tr><td colspan="6" width="18%" align="center" class="errormessage"><div <?php echo $flag_msg; ?>><?php echo($var_message); ?></div></td></tr>
												</table>
											</td>
										</tr>										  
									    
										
									     
									    <tr>
                                          <td class="whitebasic" ><table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl" >
                                              <tr align="left"  class="listing">
											  <th width="4%">&nbsp;</th>
                                                <th width="20%" ><?php echo "<b>".TEXT_START_TIME."</b>"; ?></th>
                                                <th width="20%"><?php echo "<b>".TEXT_END_TIME."</b>"; ?></th>
                                                <th width="20%"><?php echo "<b>".TEXT_USER_STAFF."</b>"; ?></th>
                                                <th width="20%"><?php echo "<b>".TEXT_STAFF."</b>"; ?></th>
												<th width="8%"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEPERSONALNOTES&styleminus=minus4&styleplus=plus4&",$var_numBegin,$var_start,$var_begin,$var_num);

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

                                              <tr align="left"  class="whitebasic"><td align="center"><!--input type="checkbox" name="chk[]" id="c<?php //echo($cnt); ?>" value="<?php //echo($row["nChatId"]); ?>" class="checkbox"-->
											  </td>
											  <td>
											   <?php 
                                                                                          // echo htmlentities(trim_the_string($row["dTimeStart"]));
                                                                                            echo  date("m-d-Y H:i:s", strtotime($row["dTimeStart"]));
                                                                                           ?>
                                                </td>
                                                <td><?php if ($row['dTimeEnd'] =='0000-00-00 00:00:00') echo "Not Completed"; else {echo  date("m-d-Y H:i:s", strtotime($row['dTimeEnd']));}// echo $row['dTimeEnd'];}?></td>
                                                <td><?php echo htmlentities(trim_the_string($row["user_staff"])); ?></td>
												 <td><?php echo htmlentities(trim_the_string($row["staffname"])); ?></td>
                                                <td ><a href="javascript:viewChatLog(<?php echo $row['nChatId']?>,'<?php echo $row['chat_flg']?>');"><?php echo TEXT_VIEW_LOG;?></a></td>
                                              </tr>
<?php
$cnt++;
}
mysql_free_result($rs);
?>
                                              
													<table cellpadding="0" cellspacing="0" border="0" width="100%">
														<tr class="whitebasic">
															<td width="40%">
                                                                                                                            <div class="pagination_info">
                                                                                                                                <?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?>
                                                                                                                            </div>
                                                                                                                            </td>
                                                                                                                        
                                                                                                                            <td><br>
                                                                                                                            <div class="pagination_links">
                                                                                                                        <?php echo($navigate[2]); ?>
                                                                                                                            </div>
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
														 
												
                                          </table></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                              </table>
                           
							
							
							
							
							
							
							
							
							
							
                        </td>
                      
                    </tr>

                
                  </td>
            </tr>
          
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  

                  <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center" class="whitebasic">
                                    <td> 
                                      <!--input name="btnDelete" type="button" class="button" value="<?php //echo BUTTON_TEXT_DELETE ?>" onClick="javascript:clickDelete();">                                    </td--></tr>
                                </table></td>
                              </tr>
                          </table></td>
                        
                      </tr>
                    </table>
                    </td>
              </tr>
            </table>

			</div>
</form>