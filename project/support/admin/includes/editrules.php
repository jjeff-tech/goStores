<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:   */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

    $addOredit = 'Add Rule';
    if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}
	else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}	
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
		$addOredit = 'Edit Rule';
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
		$addOredit = 'Edit Rule';
	} 

	if ($_GET["stid"] != "") {
		$var_stid = $_GET["stid"];
	}
	elseif ($_POST["stid"] != "") {
		$var_stid = $_POST["stid"];
	}
	
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "select d.vDeptDesc,r.vRuleName,c.vCompName,sd.*,s.vStaffName,r.nSearchTitle,r.nSearchBody,r.vSearchWords,c.nCompId
				from sptbl_staffdept sd,sptbl_staffs s,sptbl_rules r
				left join sptbl_depts as d on d.nDeptId= r.nDeptId 
				left join sptbl_companies c on c.nCompId = d.nCompId
				where sd.nDeptId= r.nDeptId and r.nStaffId= s.nStaffId and sd.nStaffId='".mysql_real_escape_string($var_stid)."' and r.nRuleId='".mysql_real_escape_string($var_id)."'";
                
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			$var_ruleid = $var_row["nRuleId"];
			$var_rulename = $var_row["vRuleName"];
			$var_searchtitle = $var_row["nSearchTitle"];
			$var_searchbody = $var_row["nSearchBody"];
			$var_keywords = $var_row["vSearchWords"];
			$var_staffid = $var_row["nStaffId"];
			$var_staffname = $var_row["vStaffName"];
			$var_parentid = $var_row["nDeptId"];
			$var_deptname = $var_row["vDeptDesc"];
			$var_companyid = $var_row["nCompId"];
			$var_companyname = $var_row["vCompName"];
			$var_datecreated = $var_row["dDate"];
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "A") {
			$var_rulename   = trim($_POST["txtRuleName"]);
			$var_keywords   = trim($_POST["txtKeywords"]);
	      	$var_companyid  = trim($_POST["cmbCompany"]);
			$var_parentid   = trim($_POST["cmbParentDepartment"]);
			$var_stid       = trim($_POST["cmbStaff"]);
/*
			if($_POST['chkSearchTitle']=="on")
				$var_searchtitle = 1;
			else
				$var_searchtitle = 0;	
			if($_POST['chkSearchBody']=="on")
				$var_searchbody = 1;
			else
				$var_searchbody = 0;
*/
			if (validateRulename() == true) {
			   if(validateKeyword() == true){
					//Insert into the rules table
					$sql  = "Insert into sptbl_rules(nRuleId,vRuleName,vSearchWords,nStaffId,nDeptId,dDateCreated";
					$sql .= ") Values('','" . mysql_real_escape_string($var_rulename). "','" . mysql_real_escape_string($var_keywords). "','" . mysql_real_escape_string($var_stid). "','" . mysql_real_escape_string($var_parentid) . "',now())";

					executeQuery($sql,$conn);
					$var_insert_id = mysql_insert_id($conn);
					//Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Rules','" . mysql_real_escape_string($var_insert_id) . "',now())";
						executeQuery($sql,$conn);
					}

					$var_message = MESSAGE_RECORD_ADDED;
                                        $flag_msg    = 'class="msg_success"';
					$var_rulename    = "";
					$var_keywords    = "";
					$var_searchtitle = "";
					$var_searchbody  = "";
			      	$var_companyid   = "";
					$var_parentid    = "";
					$var_stid        = "";
				}else {
					$var_message = MESSAGE_DUPLICATE_KEY;
                                        $flag_msg    = 'class="msg_error"'; }
			}else {
					$var_message = MESSAGE_DUPLICATE_NAME;
                                        $flag_msg    = 'class="msg_error"'; }
	}
	elseif ($_POST["postback"] == "D") {
			$sql = "delete from  sptbl_rules where nRuleId='" . mysql_real_escape_string($var_id) . "'";
			executeQuery($sql,$conn);

			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Department','" . mysql_real_escape_string($var_id) . "',now())";			
				executeQuery($sql,$conn);
			}
			
			$var_rulename="";
			$var_keywords = "";
			$var_searchtitle = 0;
			$var_searchbody = 0;
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
	}
	elseif ($_POST["postback"] == "U") {
			$var_rulename    = trim($_POST["txtRuleName"]);
			$var_keywords    = trim($_POST["txtKeywords"]);
	      	$var_companyid   = trim($_POST["cmbCompany"]);
			$var_parentid    = trim($_POST["cmbParentDepartment"]);
			$var_stid        = trim($_POST["cmbStaff"]);
/*
			if($_POST['chkSearchTitle']=="on")
			    $var_searchtitle = 1;
			else
			 	$var_searchtitle = 0;
			if($_POST['chkSearchBody']=="on")
			    $var_searchbody = 1;
			else
				$var_searchbody = 0;
*/			
			$dup_flag=0;

			//check duplicate rule name
			$sqlDuplicate = "SELECT nRuleId FROM sptbl_rules WHERE vRuleName='". mysql_real_escape_string($var_rulename) ."' and nDeptId='$var_parentid' and nRuleId !='$var_id'";
			$rsDuplicate  = executeSelect($sqlDuplicate,$conn);

			if(mysql_num_rows($rsDuplicate)>0){
			  $dup_flag=1;
			}
						
			if ($dup_flag==0) {				
						$sql = "Update sptbl_rules set vRuleName='" . mysql_real_escape_string($var_rulename) . "',
								nSearchTitle='" . mysql_real_escape_string($var_searchtitle) . "',
								nSearchBody='" . mysql_real_escape_string($var_searchbody) . "',
								vSearchWords='" . mysql_real_escape_string($var_keywords) . "',
								nStaffId='" . mysql_real_escape_string($var_stid). "',
								nDeptId='" . mysql_real_escape_string($var_parentid). "'
								where nRuleId='" . mysql_real_escape_string($var_id) . "'";
						executeQuery($sql,$conn);
						
					//Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Rules','" . mysql_real_escape_string($var_id) . "',now())";			
						executeQuery($sql,$conn);
					}
					$var_message = MESSAGE_RECORD_UPDATED;
                                        $flag_msg    = 'class="msg_success"';
			}
			else {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
			}
	}elseif ($_POST["postback"] == "CC") {
			$var_rulename    = trim($_POST["txtRuleName"]);
			$var_keywords    = trim($_POST["txtKeywords"]);
	      	$var_companyid   = trim($_POST["cmbCompany"]);
			$var_parentid    = trim($_POST["cmbParentDepartment"]);
			$var_stid        = trim($_POST["cmbStaff"]);
/*
			if($_POST['chkSearchTitle']=="on")
				$var_searchtitle = 1;
			else
				$var_searchtitle = 0;	
			if($_POST['chkSearchBody']=="on")
				$var_searchbody = 1;
			else
*/				$var_searchbody = 0;
	}elseif ($_POST["postback"] == "CP") {
			$var_rulename    = trim($_POST["txtRuleName"]);
			$var_keywords    = trim($_POST["txtKeywords"]);
	      	$var_companyid   = trim($_POST["cmbCompany"]);
			$var_parentid    = trim($_POST["cmbParentDepartment"]);
			$var_stid 		 = trim($_POST["cmbStaff"]);
/*
			if($_POST['chkSearchTitle']=="on")
				$var_searchtitle = 1;
			else
				$var_searchtitle = 0;
			if($_POST['chkSearchBody']=="on")
				$var_searchbody = 1;
			else
				$var_searchbody = 0;
*/	}
	
	function validateKeyword() 
	{
		global $conn,$var_parentid,$var_keywords;
		
		if (trim($_POST["cmbParentDepartment"]) <= 0 || trim($_POST["txtKeywords"]) =="" || trim($_POST["cmbCompany"])<=0 ||
			trim($_POST["txtRuleName"]) =="") {
			return false;
		}
		else {
// to check duplicate keywords
			$arr_keyword_exist = array();
			$arr_keyword_new = array();

			$keywords = "";
			$dup_key = 0;

                        /*
			$sqlDuplicate = "select distinct r.vSearchWords from sptbl_rules r,sptbl_staffdept sd,sptbl_staffs s
				left join sptbl_depts as d on d.nDeptId= r.nDeptId 
				left join sptbl_companies c on c.nCompId = d.nCompId
				where sd.nDeptId= r.nDeptId and r.nStaffId= s.nStaffId and r.nDeptId='".mysql_real_escape_string($var_parentid)."'";
                        */
                        $sqlDuplicate = "select distinct r.vSearchWords from sptbl_rules r
                                            inner join sptbl_staffdept sd on sd.nDeptId= r.nDeptId
                                            inner join sptbl_staffs s on r.nStaffId= s.nStaffId
                                            left join sptbl_depts as d on d.nDeptId= r.nDeptId
                                            left join sptbl_companies c on c.nCompId = d.nCompId
                                            where r.nDeptId='".mysql_real_escape_string($var_parentid)."'";
                               
			$rsDuplicate = executeSelect($sqlDuplicate,$conn);

			if(mysql_num_rows($rsDuplicate)>0){
			  while($row = mysql_fetch_array($rsDuplicate))
			  		$keywords = $keywords.",".$row['vSearchWords'];
			}
			
			$keywords = substr($keywords,1);
			$arr_keyword_exist = explode(',',$keywords);
			$arr_keyword_new = explode(',',$var_keywords);

			$result = array_diff($arr_keyword_new,array_diff($arr_keyword_new, $arr_keyword_exist));

			if(count($result)>0){
				$dup_key = 1;
			}
			if($dup_key==0)
			   	return true;
			else
				return false;
		}
	}

	function validateRulename() 
	{
		global $conn,$var_parentid,$var_rulename;
		
		if (trim($_POST["cmbParentDepartment"]) <= 0 || trim($_POST["txtKeywords"]) =="" || trim($_POST["cmbCompany"])<=0 ||
			trim($_POST["txtRuleName"]) =="") {
			return false;
		}
		else {
			//check duplicate rule name
			$dup_flag=0;
			$sqlDuplicate = "SELECT nRuleId FROM sptbl_rules WHERE vRuleName='". mysql_real_escape_string($var_rulename) ."' and nDeptId='$var_parentid'";
			$rsDuplicate  = executeSelect($sqlDuplicate,$conn);

			if(mysql_num_rows($rsDuplicate)>0){
				$dup_flag=1;
			}
			if($dup_flag==0)
			   	return true;
			else
				return false;
		}
	}
	
	function validateUpdation($dept_id,$dest_companyid,$parentdeptid) 
	{
		if (trim($_POST["txtDepartmentName"]) == "" || trim($_POST["txtKeywords"]) =="" || trim($_POST["cmbCompany"]<=0)) {
			return false;
		}
		else {

			 $retflag=0;
			 
			 $qry="select * from sptbl_depts where nDeptId='".$dept_id."'";
			 $rsgetcompany = mysql_query($qry);
			 $deptrow=mysql_fetch_array($rsgetcompany);
			 $sourcecompanyid=$deptrow['nCompId'];
			 
			 $qry="select * from sptbl_tickets where nDeptId='".$parentdeptid."'";
			 $qry1="select * from sptbl_temp_tickets where nTDeptId='".$parentdeptid."'";
			
			 //check parent has tickets
			 if(mysql_num_rows(mysql_query($qry))>0 or mysql_num_rows(mysql_query($qry1))>0){
			    $retflag=1;
			 }else{
			     if($sourcecompanyid !=$dest_companyid){
				      //check whether source department has ticket
					 /* $qry="select * from sptbl_tickets where nDeptId='".$dept_id."'";
					  $qry1="select * from sptbl_temp_tickets where nTDeptId='".$dept_id."'";
					  
					  if(mysql_num_rows(mysql_query($qry))>0 or mysql_num_rows(mysql_query($qry1))>0){
						    $retflag=1;
					  }*/
					  $retflag=1;
				 }
			 }
			
			 if($retflag==0){
			   return true;
			 }else{
			    return false;
			 }
			 
			return true;
		}
	}
function getdepartmentlink($compid,$deptparentid)
{
         global $conn;
		 $link=array();
		 $cnt=0;
		 if($compid<=0){
		  $link[0]="";
		 }else if($deptparentid==0){ 
    	   $sql="SELECT vCompName FROM sptbl_companies WHERE nCompId=$compid ";
		   $rs = executeSelect($sql,$conn);
		   $rowcompanyname=mysql_fetch_array($rs);
		   $link[0]=$rowcompanyname['vCompName'];
		   
		}else{
		   /* to change later */
		   
		   while(1){
		      $sql="SELECT nDeptId,vDeptDesc,nDeptParent FROM sptbl_depts WHERE nDeptId=$deptparentid";
		      $rs = executeSelect($sql,$conn);
		      $rw=mysql_fetch_array($rs);
			  $link[$cnt]=$rw['vDeptDesc'];
			  if($rw['nDeptParent']=="0"){
			     $cnt++;	
			     $sql="SELECT vCompName FROM sptbl_companies WHERE nCompId=$compid ";
		         $rs = executeSelect($sql,$conn);
		         $rowcompanyname=mysql_fetch_array($rs);
		         $link[$cnt]=$rowcompanyname['vCompName'];
			     break;
			  }
			  $deptparentid=$rw['nDeptParent']; 	 
			  $cnt++;	 
		   }
		}
    return $link;    
}	
function make_selectlist($current_dept_id, $count,$cmpid) {
         static $option_results;
		
         if (!isset($current_dept_id)) {
              $current_dept_id =0;
         }
		 if (!isset($cmpid)) {
              $cmpid =0;
         }
         $count = $count+1;
		 
         $sql = "SELECT nDeptId as id, vDeptDesc as name from sptbl_depts where nDeptParent = '$current_dept_id' and nCompId=$cmpid order by name asc ";
  
		 $get_options = mysql_query($sql);
         $num_options = mysql_num_rows($get_options);
		
         if($num_options > 0)
         {
             while (list($dept_id, $dept_name) = mysql_fetch_row($get_options)) {
			    $dept_name = htmlentities($dept_name);
				$option_results[$dept_id] = $dept_name;
                make_selectlist($dept_id, $count,$cmpid );				   
             }
         }
         return $option_results;
}
?>
<form name="frmRules" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo $addOredit; ?></h3>
			</div>
<table width="100%"  border="0">
	<tr>
    	<td width="76%" valign="top">
    		<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
									 	<tr>
									 		<td>
									   			 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
													<tr><td colspan="3">&nbsp;</td></tr>
													<tr>
														<td align="center" colspan=3 >
														<?php

														if ($var_message != ""){
														?>
															<div <?php echo $flag_msg; ?>>
														<b><?php echo($var_message); ?></b>
														</div>
														<?php
														}
														?>		
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
														<td align="left" colspan=2 class="toplinks"><?php echo TEXT_FIELDS_MANDATORY ?></td>
													</tr>
							          	            <tr><td colspan="3">&nbsp;</td></tr>
												    <tr>
														  <td align="left">&nbsp;</td>
														  <td align="left" class="toplinks"><?php echo TEXT_RULE_NAME ?> <font style="color:#FF0000; font-size:9px">*</font></td>
														  <td width="59%" align="left">
															<input name="txtRuleName" type="text" class="comm_input input_width1a" id="txtRuleName" size="50" maxlength="100" value="<?php echo htmlentities($var_rulename); ?>">
														  </td>
														  </tr>
														  <tr><td colspan="3">&nbsp;</td></tr>														  
														  <tr>
														  <td align="left">&nbsp;</td>
														  <td align="left" class="toplinks" valign="top"><?php echo TEXT_RULE_KEYWORDS ?> <font style="color:#FF0000; font-size:9px">*</font></td>
														  <td width="59%" align="left">
															<textarea cols="30" rows="5" name="txtKeywords" class="comm_input input_width1a" id="txtKeywords"><?php echo htmlentities($var_keywords); ?></textarea>
														  </td>
														  </tr>
														  <tr><td colspan="3">&nbsp;</td></tr>
												  <tr>
												 <td width="2%" align="left">&nbsp;</td>
												 <td width="39%" align="left" class="toplinks"><?php echo TEXT_COMPANY_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
												 <td width="59%" align="left">
													   <?php
														 $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";			
														 $rs = executeSelect($sql,$conn);
														 $cnt = 1;
														?>
														<input type=hidden name="cmbCompanyhidden" value="<?php echo   $var_companyid?>">
													   <select name="cmbCompany" size="1" class="comm_input input_width1a" id="cmbCompany" onchange="changecompany();">
														 <?php
																   $options ="<option value='0'";
																   $options .=">Select Company</option>\n"; 
																	echo $options;
																	while($row = mysql_fetch_array($rs)) {
																		  $options ="<option value='".$row['nCompId']."'";
																		  if ($var_companyid == $row['nCompId']){
																			   $options .=" selected=\"selected\"";
																		  }
																		  $options .=">".htmlentities($row['vCompName'])."</option>\n";
																		  echo $options;
																	}                                            
														 ?>						     
														</select>
												  </td>
												  </tr>
												  <tr><td colspan="3">&nbsp;</td></tr>
												  <tr>
												 <td width="2%" align="left">&nbsp;</td>
												 <td width="39%" align="left" class="toplinks"><?php echo TEXT_RULE_DEPT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
												 <td width="59%" align="left" class="listingmaintext">
												
													   <select name="cmbParentDepartment" size="1" class="comm_input input_width1a" id="cmbParentDepartment"  onchange="changedept();">
														 <?php
																$options="";
																$get_options = make_selectlist(0,0,$var_companyid);
								
																if (count($get_options) > 0)
																{
																	  $departments = $_POST['dept_id'];
																	  $options ="<option value='0'";
																	  $options .=">" . TEXT_DEPT_SELECT . "</option>\n";
																	  foreach ($get_options  as $key => $value) {
																		  $options .= "<option value=\"$key\"";
																		  if ($var_parentid == "$key")
																		  {
																			   $options .=" selected=\"selected\"";
																		  }
																		  $options .=">" . $value . "</option>\n";
																	 }
																}else{
																   $options ="<option value='0'";
																   $options .=">Parent level</option>\n";
																}
																echo $options;
														 ?>
												       </select>
			        	    	          </td>
            		    	    	  </tr>
				                      <tr><td colspan="3">&nbsp;</td></tr>
									  <tr>
					                     <td width="2%" align="left">&nbsp;</td>
                    					 <td width="39%" align="left" class="toplinks"><?php echo TEXT_RULE_STAFF?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					                     <td width="59%" align="left" class="listingmaintext">
											<?php
								// to set the staff  combo
											  $sql = "select distinct s.vStaffName,s.nStaffId from sptbl_staffs s inner join sptbl_staffdept sd on s.nStaffId=sd.nStaffId where sd.nDeptId='$var_parentid'";
											  $rs  = mysql_query($sql) or die(mysql_error());
											
											  $cmbStaff ="";
											  $cmbStaff ="<option value='0'";
											  $cmbStaff .=">" . TEXT_STAFF_SELECT . "</option>\n";

											  if($var_parentid >0){
												  if( mysql_num_rows($rs)>0){
													while($row = mysql_fetch_array($rs)){
														  $cmbStaff .= "<option value='".$row["nStaffId"]."' " ;
												
														  if($row["nStaffId"] == $var_stid){
															 $cmbStaff .= " selected ";
														  }
														  $cmbStaff .= " >".htmlentities($row["vStaffName"])."</option>";
													}
												 }
											 }else
											 {
												   $cmbStaff ="<option value='0'";
												   $cmbStaff .=">Parent level</option>\n";
											 }
											?>
										 <select name="cmbStaff" size="1" class="comm_input input_width1a" id="cmbStaff">
											 <?php
													echo $cmbStaff;
											  ?>
										 </select>	
									  </td>
									  </tr>
									  <tr><td>&nbsp;</td></tr>
								</table>
                        </td>
                            </tr>
                        </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td class="btm_brdr"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="javascript:cancel();"></td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
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
            </td>
  </tr>
</table>
</div>
</form>