<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

	$addOredit = 'Add Department';
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
		$addOredit = 'Edit Department';
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
		$addOredit = 'Edit Department';
	} 
	
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "Select d.nDeptId,d.vDeptDesc,d.vDeptMail,d.nDeptParent,d.vDeptCode,d.nResponseTime,c.nCompId,c.vCompName from sptbl_depts as d,sptbl_companies as c ";
        $sql .=" where d.nCompId=c.nCompId and d.nDeptId='".addslashes($var_id)."'";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			$var_companyid= $var_row["nCompId"];
			$var_parentid = $var_row["nDeptParent"];
			$var_deptname = $var_row["vDeptDesc"];
			$var_email = $var_row["vDeptMail"];
			$var_deptcode=$var_row["vDeptCode"];
			$var_responsetime = $var_row["nResponseTime"];
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "A") {
	      	$var_companyid= trim($_POST["cmbCompany"]);
			$var_parentid = trim($_POST["cmbParentDepartment"]);
			$var_deptname = trim($_POST["txtDepartmentName"]);
			$var_email = trim($_POST["txtEmail"]);
			$var_deptcode = trim($_POST["txtDeptCode"]);
			$var_responsetime = trim($_POST["txtResponseTime"]);
			$dup_flag=0;
			//check duplicate name department name
			//$sql="SELECT nDeptId  FROM sptbl_depts WHERE nCompId=$var_companyid and  nDeptParent=$var_parentid and vDeptDesc='".addslashes($var_deptname) . "'";
			
			$sql="SELECT nDeptId  FROM sptbl_depts WHERE (nCompId=$var_companyid and  nDeptParent=$var_parentid and vDeptDesc='".addslashes($var_deptname) . "') or ";
			$sql .=" (nCompId=$var_companyid and  vDeptCode='".addslashes($var_deptcode) . "')  ";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}

		if (validateAddition($var_parentid) == true and $dup_flag==0) {
			if(!isUniqueEmail($var_email)) {
				$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                $flag_msg    = 'class="msg_error"';
			}
			else {
				//Insert into the company table
				$sql = "Insert into sptbl_depts(nDeptId,nCompId,vDeptDesc,nDeptParent,vDeptMail,vDeptCode,nResponseTime";
				$sql .= ") Values('','" . addslashes($var_companyid). "','" . addslashes($var_deptname) . "','" . addslashes($var_parentid) . "',
						'" . addslashes($var_email) . "','" . addslashes($var_deptcode) . "','" . addslashes($var_responsetime)  . "')";
				executeQuery($sql,$conn);
				$var_insert_id = mysql_insert_id($conn);
				//Insert the actionlog
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Department','" . addslashes($var_insert_id) . "',now())";			
				executeQuery($sql,$conn);
				}
				
				
				//insert into staff assign
				 $var_admins = "";
				 $sql ="select nStaffId,vLogin,vType from sptbl_staffs where vType='A' ";
				 $var_result = executeSelect($sql,$conn); 
				 while($row=mysql_fetch_array($var_result)) {
				   $var_admins .= "'" . $row['nStaffId'] . "',"; 
				 }
				 $var_admins = substr($var_admins,0,-1);
	
				 $sql_insert_admins="insert into sptbl_staffdept(nStaffId,nDeptId) values ";
					if($var_admins !=""){
						$vAdminarr=explode(",",$var_admins);
						foreach($vAdminarr as $key=>$value){
						   $sql_insert_admins .= "($value,'$var_insert_id'),";
						}
						$sql_insert_admins= substr($sql_insert_admins,0,-1);
						
						
						executeQuery($sql_insert_admins,$conn);
						
						
					  }
				
				 //delete from staff depts
				 $qry="delete from sptbl_staffdept where nDeptId='".$var_parentid."'";
				 mysql_query($qry);
				
				$var_message = MESSAGE_RECORD_ADDED;
                                $flag_msg    = 'class="msg_success"';
				$var_email = "";
				$var_deptname="";
				$var_deptcode="";
				$var_responsetime="";
			}
		}
		else {
			$var_message = MESSAGE_RECORD_PARENTDEPT_ERROR ; //added on October 28, 2006 by Roshith
                        $flag_msg    = 'class="msg_error"';
		}
		if ( $dup_flag == 1) {
                    $var_message = MESSAGE_NONUNIQUE_DEPARTMENT ; //added on July 29, 2009
                    $flag_msg    = 'class="msg_error"';
                }
	}
	elseif ($_POST["postback"] == "D") {	       
			
		if (validateDeletion() == true) {
		        $qry="select * from sptbl_depts where nDeptId='".$var_id."'";
			    $rsgetdept = mysql_query($qry);
				$deptrow=mysql_fetch_array($rsgetdept);
			    $oldparentid=$deptrow['nDeptParent'];

			$sql = "delete from  sptbl_staffdept  where nDeptId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
				
			$sql = "delete from  sptbl_depts  where nDeptId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);

			// delete from sptbl_pop3settings table
			$sqlPop3 = "delete from  sptbl_pop3settings  where nDeptId='" . addslashes($var_id) . "'";
			executeQuery($sqlPop3,$conn);

			//assign staff dept where parent dept is leaf
				   $qry="select  * from sptbl_depts where nDeptParent='".$oldparentid."'";
				   if(mysql_num_rows(mysql_query($qry))<=0){
				       $sql_insert_admins="insert into sptbl_staffdept(nStaffId,nDeptId) values('".$var_staffid."','".$oldparentid."')";
					   mysql_query($sql_insert_admins);
				   } 
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Department','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			
			$var_email = "";
			$var_deptname="";
			$var_deptcode = "";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
		}
		else {
		    $var_companyid= trim($_POST["cmbCompany"]);
			$var_parentid = trim($_POST["cmbParentDepartment"]);
			$var_deptname = trim($_POST["txtDepartmentName"]);
			$var_email = trim($_POST["txtEmail"]);
			$var_deptcode = trim($_POST["txtDeptCode"]);
			$var_responsetime = trim($_POST["txtResponseTime"]);
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "U") {
	       
			$var_companyid= trim($_POST["cmbCompany"]);
			$var_parentid = trim($_POST["cmbParentDepartment"]);
			$var_deptname = trim($_POST["txtDepartmentName"]);
			$var_email = trim($_POST["txtEmail"]);
			$var_deptcode = trim($_POST["txtDeptCode"]);
			$var_responsetime = trim($_POST["txtResponseTime"]);				
			$dup_flag=0;
			//check duplicate name department name
			//$sql="SELECT nDeptId  FROM sptbl_depts WHERE nCompId=$var_companyid and  nDeptParent=$var_parentid and vDeptDesc='".addslashes($var_deptname) . "'";
			//$sql .=" and nDeptId !=$var_id";
			
			 $sql ="SELECT nDeptId FROM sptbl_depts WHERE ((nCompId=$var_companyid and nDeptParent=$var_parentid and vDeptDesc='".addslashes($var_deptname) . "')";
			 $sql .=" or (nCompId=$var_companyid and vDeptCode='". addslashes($var_deptcode) ."')) and nDeptId !=$var_id";
             
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}
			
			$childlist=makeChildList($var_id,0);
			$childlist = substr($childlist,0,-1);
			if($childlist !=""){
			  
			  $charr=explode(",",$childlist);
			  array_push($charr,$var_id);
			  $charr=array_unique($charr);
			  array_push($charr,$var_parentid);
			  $cnt_arr1=count($charr);
			  $charr=array_unique($charr);
			  $cnt_arr2=count($charr);
			}
        
			if($cnt_arr1 !=$cnt_arr2){
			 $dup_flag=1;
			}else if($var_id ==$var_parentid){
			  $dup_flag=1;
			}
			
			if (validateUpdation($var_id,$var_companyid,$var_parentid) == true and $dup_flag==0) {
				if(!isUniqueEmail($var_email,$var_id,"d")) {
					$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                        $flag_msg    = 'class="msg_error"';

				}
				else {
						//fetch the old parent
						$qry="select * from sptbl_depts where nDeptId='".$var_id."'";
						$rsgetdept = mysql_query($qry);
						$deptrow=mysql_fetch_array($rsgetdept);
						$oldparentid=$deptrow['nDeptParent'];
					
						$sql = "Update sptbl_depts set nCompId='" . addslashes($var_companyid) . "',
								vDeptDesc='" . addslashes($var_deptname) . "',
								nDeptParent='" . addslashes($var_parentid). "',
								vDeptCode='" . addslashes($var_deptcode). "',
								vDeptMail='" . addslashes($var_email) . "',
								nResponseTime='" . addslashes($var_responsetime) . "' 
								where nDeptId='" . addslashes($var_id) . "'";
								
						executeQuery($sql,$conn);
						
					 $qry="delete from sptbl_staffdept where nDeptId='".$var_parentid."'";
					 mysql_query($qry);
					 
					 $updatePop3 = "Update sptbl_pop3settings set vDeptEMail='" . addslashes($var_email) . "'
								where nDeptId='" . addslashes($var_id) . "'";
					 executeQuery($updatePop3,$conn);
												 
					 //assign staff dept where parent dept is leaf
					   $qry="select  * from sptbl_depts where nDeptParent='".$oldparentid."'";
					   if(mysql_num_rows(mysql_query($qry))<=0){
						   $sql_insert_admins="insert into sptbl_staffdept(nStaffId,nDeptId) values('".$var_staffid."','".$oldparentid."')";
						   mysql_query($sql_insert_admins);
					   } 
					//Insert the actionlog
					if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Department','" . addslashes($var_id) . "',now())";			
					executeQuery($sql,$conn);
					}
					
					$var_message = MESSAGE_RECORD_UPDATED;
                                        $flag_msg    = 'class="msg_success"';
				}		
			}
			else {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
			}
	}elseif ($_POST["postback"] == "CC") {
	
	  $var_companyid= trim($_POST["cmbCompany"]);
	  $var_parentid = trim($_POST["cmbParentDepartment"]);
	  $var_deptname = trim($_POST["txtDepartmentName"]);
	  $var_email = trim($_POST["txtEmail"]);	
	  $var_deptcode = trim($_POST["txtDeptCode"]);			 
	  $var_responsetime = trim($_POST["txtResponseTime"]);
	 
	}elseif ($_POST["postback"] == "CP") {
	  $var_companyid= trim($_POST["cmbCompany"]);
	  $var_parentid = trim($_POST["cmbParentDepartment"]);
	  $var_deptname = trim($_POST["txtDepartmentName"]);
	  $var_email = trim($_POST["txtEmail"]);	
	  $var_deptcode = trim($_POST["txtDeptCode"]);		
	  $var_responsetime = trim($_POST["txtResponseTime"]); 
	}
	
	function validateAddition($parentdeptid) 
	{
		if (trim($_POST["txtDepartmentName"]) == "" || trim($_POST["txtEmail"]) == "" || trim($_POST["txtDeptCode"]) =="" || trim($_POST["cmbCompany"])<=0 || trim($_POST["txtResponseTime"]) <= 0) {
			return false;
		}
		else {
		     $retflag=0 ;
		     $qry="select * from sptbl_tickets where nDeptId='".$parentdeptid."'";
			 $qry1="select * from sptbl_temp_tickets where nTDeptId='".$parentdeptid."'";
			
			 //check parent has tickets
			 if(mysql_num_rows(mysql_query($qry))>0 or mysql_num_rows(mysql_query($qry1))>0){
				$retflag=1;
			 }
			
			 
			 if($retflag==0){
			   return true;
			 }else{
			    return false;
			 }
			
		}
	}
	
	function validateDeletion() 
	{
		//implement logic here
		global $conn,$var_id;
		
		$sql="select nTicketId from sptbl_tickets where nDeptId='" . addslashes($var_id) . "'";
		
		$rs = executeSelect($sql,$conn);
		if(mysql_num_rows($rs)>0){
			  return false;
		}else{
		   $sqlparentcheck="select nDeptId from sptbl_depts where nDeptParent='" . addslashes($var_id) . "'";
		   $rs1 = executeSelect($sqlparentcheck,$conn);
		   if(mysql_num_rows($rs1)>0){
		        return false;
			}  
		}
		$sqlcattcheck="select nDeptId from sptbl_categories where nDeptId='" . addslashes($var_id) . "'";
		$rs2 = executeSelect($sqlcattcheck,$conn);
		if(mysql_num_rows($rs2)>0){
		        return false;
		}  
/*		$sqlstaffdeptcheck="select nDeptId from sptbl_staffdept where nDeptId ='" . addslashes($var_id) . "'";
		
		$rs3 = executeSelect($sqlstaffdeptcheck,$conn);
		if(mysql_num_rows($rs3)>0){
		        return false;
		}  
*/
		return true;
	}
	
	function validateUpdation($dept_id,$dest_companyid,$parentdeptid) 
	{
		if (trim($_POST["txtDepartmentName"]) == "" || trim($_POST["txtEmail"]) == "" || trim($_POST["txtDeptCode"]) =="" || trim($_POST["cmbCompany"]<=0  || trim($_POST["txtResponseTime"]) <= 0)) {
			return false;
		}
		else {
		     //
			 //$var_id;
			 $retflag=0;
			 
			 $qry="select * from sptbl_depts where nDeptId='".$dept_id."'";
			 $rsgetcompany = mysql_query($qry);
			 $deptrow=mysql_fetch_array($rsgetcompany);
			 $sourcecompanyid=$deptrow['nCompId'];
			 
			 $qry="select * from sptbl_tickets where nDeptId='".$parentdeptid."'";
			 $qry1="select * from sptbl_temp_tickets where nTDeptId='".$parentdeptid."'";
			
			 //check parent has tickets
			 //if(mysql_num_rows(mysql_query($qry))>0 or mysql_num_rows(mysql_query($qry1))>0){
                         if(mysql_num_rows(mysql_query($qry))>0 and mysql_num_rows(mysql_query($qry1))>0){
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
         $count = $count+1;
		 
         $sql = "SELECT nDeptId as id, vDeptDesc as name from sptbl_depts where nDeptParent = '$current_dept_id' and nCompId=$cmpid order by name asc";
         $get_options = mysql_query($sql);
         $num_options = mysql_num_rows($get_options);
         if ($num_options > 0)
         {
             while (list($dept_id, $dept_name) = mysql_fetch_row($get_options)) {
                    if ($current_dept_id!=0) {
                        $indent_flag = "&nbsp;&nbsp;";
                        for ($x=2; $x<=$count; $x++) {
                             $indent_flag .= "--&gt;&nbsp;";
                        }
                    }
                    $dept_name = $indent_flag.htmlentities($dept_name);
					$option_results[$dept_id] = $dept_name;
                    make_selectlist($dept_id, $count,$cmpid );
             }
         }
         return $option_results;
}
?>
<form name="frmDepartment" method="POST" action="editdepartments.php?<?php if($var_id != ''){?>id=<?php echo $var_id; ?>&<?php } ?>stylename=STYLEDEPARTMENTS&styleminus=minus6&styleplus=plus6&">
			<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo $addOredit; ?></h3>
			</div>
			
			
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
					<tr>
					 <td align="center" colspan=3 >
                                             <?php
                                             if ($var_message != ""){?>
                                                <div <?php echo $flag_msg; ?>> <?php echo $var_message ?></div>
                                             <?php
                                             }?>
					</td>
					 </tr>
					<tr>
					  <td>&nbsp;</td>
					 <td align="left" colspan="2" class="fieldnames">
					 <?php echo TEXT_FIELDS_MANDATORY ?></td>
					 </tr>
					  <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
					 <td width="7%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="fieldnames"><?php echo TEXT_COMPANY_NAME?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
					 <td width="55%" align="left">
						   <?php
							 $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";			
							 $rs = executeSelect($sql,$conn);
							 $cnt = 1;
							?>
							<input type=hidden name="cmbCompanyhidden" value="<?php echo   $var_companyid?>">
						   <select name="cmbCompany" size="1" class="comm_input input_width1a" id="cmbCompany" onchange="changecompany();" >
							 <?php
										   $options ="<option value='0'";
										   $options .=">Select</option>\n"; 
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
								 <td width="38%" align="left" class="fieldnames"><?php echo TEXT_PARENT_DEPARTMENT?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
								 <td width="60%" align="left" class="listingmaintext">
									   <select name="cmbParentDepartment" size="1" class="comm_input input_width1a" id="cmbParentDepartment"  >
										 <?php
														$options="";
														$get_options =    make_selectlist(0,0,$var_companyid);
														if (count($get_options) > 0)
														{
															  $departments = $_POST['dept_id'];
															   $options ="<option value='0'";
															   $options .=">" . TEXT_PARENT_LEVEL . "</option>\n";
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
								  <td align="left">&nbsp;</td>
								  <td align="left" class="fieldnames"><?php echo TEXT_DEPARTMENT_NAME ?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
								  <td width="60%" align="left">
									<input name="txtDepartmentName" type="text" class="comm_input input_width1" id="txtDepartmentName" size="30" maxlength="100" value="<?php echo htmlentities($var_deptname); ?>">
									</td>
								  </tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
								  
								  <tr>
								  <td align="left">&nbsp;</td>
								  <td align="left" class="fieldnames"><?php echo TEXT_DEPARTMENT_DEPTCODE ?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
								  <td width="60%" align="left">
									<input name="txtDeptCode" type="text" class="comm_input input_width1" id="txtDeptCode" size="30" maxlength="6" value="<?php echo htmlentities($var_deptcode); ?>">
								  </td>
								  </tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
								  <tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="fieldnames"><?php echo TEXT_DEPARTMENT_EMAIL?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
											  <td width="60%" align="left">
												  <input name="txtEmail" type="text" class="comm_input input_width1" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
											  </td>
											</tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
								  <tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="fieldnames"><?php echo TEXT_DEPARTMENT_RESPONSE_TIME?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
											  <td width="60%" align="left">
												  <input name="txtResponseTime" type="text" class="comm_input input_width1" id="txtResponseTime" size="30" maxlength="4" value="<?php echo htmlentities($var_responsetime); ?>">&nbsp;<?php echo TEXT_DEPARTMENT_TIME?>
											  </td>
											</tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
											</table>
											
											
						<table width="100%"  border="0" cellspacing="10" cellpadding="0">
						  <tr>
							<td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
								<tr>
								  <td class="btm_brdr" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							  </table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr >
									<td width="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
									<td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
										<tr>
										  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr align="center"  class="listingbtnbar">
												<td align="center" colspan="5">
												<?php if(!isset($_GET['id'])){?>
												<input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();">&nbsp;&nbsp;
												<?php }?>
												<?php if(isset($_GET['id'])){?>
												<input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();">&nbsp;&nbsp;
												<?php }?>
												<input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();">&nbsp;&nbsp;
												<input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();">
												
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
								</td>
						  </tr>
						</table>
						</td>
			  </tr>
			</table>
			<div class="clear"></div>
			</div>
			
</form>
<script type="text/javascript">
document.frmDepartment.cmbCompany.focus();
</script>