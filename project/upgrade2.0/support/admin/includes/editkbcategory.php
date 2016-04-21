<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
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
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	} 
	$var_country = "UnitedStates";
	$var_staffid = $_SESSION["sess_staffid"];
	
	$error = false;
	$message = false;
	
	if ($_POST["postback"] == "" && $var_id != "") {//if the page is not posted
		$sql = "Select c.nCompId, d.nDeptId, ca.vCatDesc, ca.nCatId, ca.nParentId from sptbl_depts as d,sptbl_companies as c , sptbl_categories as ca ";
        $sql .=" where d.nCompId=c.nCompId and d.nDeptId= ca.nDeptId  and ca.nCatId = '".addslashes($var_id)."' ";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			$var_companyid= $var_row["nCompId"];
			$var_parentcatid = $var_row["nParentId"];
			$var_departmentid = $var_row["nDeptId"];
			$var_catname = $var_row["vCatDesc"];
		}
		else {
			$error = true;
		}
	}elseif ($_POST["postback"] == "A") {//posted form for adding category
		$var_companyid= trim($_POST["cmbCompany"]);
		$var_departmentid = trim($_POST["cmbDepartment"]);
		$var_catname = trim($_POST["txtCategoryName"]);
		$var_parentcatid = $_POST["cmbParentCategory"];
		
		if(!validateFields()){
			$error = true;
			$errormessage = MESSAGE_FIELDS_MISSING;
		}else if(isDuplicateCategory($var_parentcatid, $var_catname,$var_departmentid)){
			$error = true;
			$errormessage = MESSAGE_DUPLICATE_CATEGORY;
		}	
		
		if(hasEntries($var_parentcatid)){
			$error = true;
			$errormessage = MESSAGE_DESTIANTION_CATEGORY_HAS_ENTRIES."<br>";
		}   
		if (!$error) {
			//Insert into the category table
			$sql = "Insert into sptbl_categories(nCatId, nDeptId, vCatDesc, nParentId, nCount";
			$sql .= ") Values('','" . addslashes($var_departmentid) . "','" . addslashes($var_catname) . "','" . addslashes($var_parentcatid) . "','0')";
			executeQuery($sql,$conn);
			$var_insert_id = mysql_insert_id($conn);
			updateRoute($var_insert_id,$var_parentcatid);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Categories','" . addslashes($var_insert_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			$var_catname="";
			$message = true;
			$infomessage = MESSAGE_RECORD_ADDED_SUCCESSFULLY;
                        $flag_msg     = 'class="msg_success"';
			
		}
	}elseif ($_POST["postback"] == "D") {
		$catid = $var_id;
		if (hasEntries($catid)) {
			$error = true;
			$errormessage .= MESSAGE_HAS_ENTRIES."<br>";
		}
		if (hasChildren($catid)) {
			$error = true;
			$errormessage .= MESSAGE_HAS_CHILDREN."<br>";
		}
		if(!$error){
			$sql = "delete from  sptbl_categories  where nCatId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Categories','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			$var_catname="";
			$var_catid="";
			$var_id = "";
			$var_companyid= trim($_POST["cmbCompany"]);
			$var_departmentid = trim($_POST["cmbDepartment"]);
			$var_parentcatid = $_POST["cmbParentCategory"];
			
			$message = true;
			$infomessage = MESSAGE_CATEGORY_DELETED ."<br>";
                        $flag_msg     = 'class="msg_success"';
		}else {
		    $var_companyid= trim($_POST["cmbCompany"]);
			$var_departmentid = trim($_POST["cmbDepartment"]);
			$var_catname = trim($_POST["txtCategoryName"]);
			$var_parentcatid = $_POST["cmbParentCategory"];
		}
	}elseif ($_POST["postback"] == "U") {
			$var_catid = $var_id;
			$var_companyid= trim($_POST["cmbCompany"]);
			$var_departmentid = trim($_POST["cmbDepartment"]);
			$var_catname = trim($_POST["txtCategoryName"]);
			$var_parentcatid = $_POST["cmbParentCategory"];
			if(!validateFields()){
				$error = true;
				$errormessage = MESSAGE_REQUIRED_FIELDS_MISSING;
			}
			if(isDuplicateCategory($var_parentcatid, $var_catname,$var_departmentid)){
				$error = true;
				$errormessage = MESSAGE_DUPLICATE_CATEGORY."<br>";
				$errormessage .= MESSAGE_RECORD_NOT_UPDATED."<br>";
			}
			if(categoryInParentRoute($var_catid,$var_parentcatid)){
				$error = true;
				$errormessage = MESSAGE_CATEGORY_CANNOT_BE_MOVED."<br>";
				$errormessage .= MESSAGE_RECORD_NOT_UPDATED."<br>";
			}
			if(hasEntries($var_parentcatid)){
				$error = true;
				$errormessage = MESSAGE_DESTIANTION_CATEGORY_HAS_ENTRIES."<br>";
				$errormessage .= MESSAGE_RECORD_NOT_UPDATED."<br>";
			}
			if($var_parentcatid == $var_catid){
					$error = true;
					$errormessage = MESSAGE_CURRENT_CATEGORY_SAME_AS_PARENT."<br>";
			}
			if(!$error){
				$sql = "UPDATE sptbl_categories SET nDeptId = '".$var_departmentid."', vCatDesc='".$var_catname."', nParentId = '".$var_parentcatid."' ";
				$sql .= "WHERE nCatId= '".addslashes($var_catid)."'";
				executeQuery($sql,$conn);
				updateRoute($var_catid,$var_parentcatid);
				//Insert the actionlog
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Categories','" . addslashes($var_id) . "',now())";			
				executeQuery($sql,$conn);
				}
				$message = true;
				$infomessage = MESSAGE_RECORD_UPDATED;
                                $flag_msg     = 'class="msg_success"';
			}else {
				$var_parentcatid = getParentCategoryId($var_catid);
			}
	}elseif ($_POST["postback"] == "CC") {//change company
	  $var_companyid = trim($_POST["cmbCompany"]);
	  $sql = "SELECT d.nDeptId FROM sptbl_depts d ";
	  $sql .=" WHERE d.nCompId = '".addslashes($var_companyid)."' ";
	  $var_result = executeSelect($sql,$conn); 
	  if (mysql_num_rows($var_result) > 0) {
		  $var_row = mysql_fetch_array($var_result);
		  $var_departmentid = $var_row["nDeptId"];
	  }
	  $var_catname = trim($_POST["txtCategoryName"]);
	}elseif ($_POST["postback"] == "CD") {
	  $var_companyid= trim($_POST["cmbCompany"]);
	  $var_departmentid = trim($_POST["cmbDepartment"]);
	  $var_catname = trim($_POST["txtCategoryName"]);
	}
	
	if($error){
		$errormessage = MESSAGE_ERRORS_FOUND. "<br>".$errormessage;
                $flag_msg     = 'class="msg_error"';
	}
	
	//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	function getParentCategoryId($catid){
		global $conn;
		$sql = "SELECT nParentId FROM sptbl_categories  ";
	    $sql .=" WHERE nCatId = '".addslashes($catid)."' ";
		$result = executeSelect($sql,$conn); 
		if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				$parentid = $row["nParentId"];
		}else{
			$parentid = "";
		}
		return $parentid;
	}
	function categoryInParentRoute($catid,$parentcat){
		global $conn;
		
		$sql = "SELECT vRoute FROM sptbl_categories  ";
	    $sql .=" WHERE nCatId = '".addslashes($parentcat)."' ";
		$result = executeSelect($sql,$conn); 
		if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				$parentroute = $row["vRoute"];
		}
		$arr = explode(",",$parentroute);
		if(in_array($catid, $arr)){
			return true;
		}
		return false;
	}
	
	function updateRoute($catid,$parentcat){
		global $conn;
		/*$sql = "SELECT vRoute FROM sptbl_categories  ";
	    $sql .=" WHERE nCatId = '".addslashes($parentcat)."' ";
		$result = executeSelect($sql,$conn); 
		if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				$parentroute = $row["vRoute"];
		}
		$arr = split(",",$parentroute);
		if(in_array($catid, $arr)){
			echo "<br>Category Present in the Route: <br>";
		}
		
		echo "<br>Parent Category: ".$parentcat."<br>";
		echo "<br>Parent Route: ".$parentroute."<br>";&*/
		
		$sql = "SELECT c.vRoute, c.nParentId, parentcat.vRoute as parentroute FROM sptbl_categories c LEFT OUTER JOIN  sptbl_categories parentcat ON c.nParentId = parentcat.nCatId ";
	    $sql .=" WHERE c.nCatId = '".addslashes($catid)."' ";
		$result = executeSelect($sql,$conn); 
		if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				$parentroute = $row["parentroute"];
				if($parentroute =="" ){
					$route = ($row["vRoute"] == "")?"":$row["vRoute"].",";
				}else{
					$route = $parentroute.",";
				}
				
		}
		$newroute = $route.$catid;
		//echo "<br>New Route: ".$newroute."<br>";
		$sql = "UPDATE sptbl_categories SET vRoute = '".$newroute."' WHERE  nCatId = '".$catid."' ";
		
		//echo "<br>".$sql."<br>";
		executeQuery($sql,$conn);
	}
	
	function validateFields() 
	{
		if (trim($_POST["txtCategoryName"]) == "" || trim($_POST["cmbCompany"]) <= "" || trim($_POST["cmbDepartment"]<=0 ) || trim($_POST["cmbDepartment"]<=0 ) ) {
			return false;
		}
		else {
		      
			return true;
		}
	}
	
	
	
	function hasChildren($catid){
		global $conn;
		$sqlparentcheck="select nCatId from sptbl_categories where nParentId='" . addslashes($catid) . "'";
		$rs = executeSelect($sqlparentcheck,$conn);
		if(mysql_num_rows($rs)>0){
		    return true;
		}else{
			return false;
		}  
		
	}
	
	function hasEntries($catid){
		global $conn;
		$sql="select nKBID from sptbl_kb where nCatId='" . addslashes($catid) . "'";
		$rs = executeSelect($sql,$conn);
		if(mysql_num_rows($rs)>0){
			  return true;
		}else{
			return false;
		}
	}
	
	function validateCategoryUpdation() 
	{
		if (trim($_POST["txtCategoryName"]) == "" || trim($_POST["cmbCompany"]) <= "" || trim($_POST["cmbDepartment"]<=0 ) || trim($_POST["cmbDepartment"]<=0 ) ) {
			return false;
		}
		else {
			return true;
		}
	}
	
	function isDuplicateCategory($parentcatid, $catname,$deptid){
		global $conn;
		//check duplicate category name
		$sql="SELECT vCatDesc FROM sptbl_categories WHERE nParentId = '$parentcatid' and nDeptId = '$deptid'  and vCatDesc = '".addslashes($catname)."' ";
		$rs = executeSelect($sql,$conn);
		
		if(mysql_num_rows($rs)>0){//there are child categories with same name, so return true
			return true;
		}else{//no child categories for the parent category with same name
			return false;
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

//function to display categories in nested manner

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

?>
<form name="frmCategory" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_EDIT_CATEGORY ?></h3>
			</div>
			
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
    	<tr>
         <td align="left" colspan=3 >
		 	<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
			<!--
			<?php
		  if($error){?>
		 
		  <table width="100%"  border="0" cellspacing="10" cellpadding="0" align="center">
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
                                  <tr>
                                    <td align="left" class="errormessage"><p><?php echo $errormessage;?></p></td>
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
		  <?php}
		  if($message){ ?>
		  <table width="100%"  border="0" cellspacing="10" cellpadding="0" align="center">
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
                                  <tr>
                                    <td align="center" class="message"><p><?php echo $infomessage;?></p></td>
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
		 <?php }?>
			-->
		 	<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
		 </td>
         </tr>
		 <tr>
         <td align="center" colspan=3 >&nbsp;</td>
         </tr>
		<tr>
		<td>&nbsp;</td>
         <td align="left" colspan="2" class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>
			<td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $errormessage;  
		 		echo $infomessage; ?>
			</td>
         </tr>

			          <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="2%" align="left">&nbsp;</td>
                     <td width="39%" align="left" class="toplinks"><?php echo TEXT_COMPANY?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="59%" align="left">
					       <?php
						     $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";			
 							 $rs = executeSelect($sql,$conn);
						  	 $cnt = 1;
							?>
		                   <select name="cmbCompany" size="1" class="comm_input input_width1a" id="cmbCompany" onchange="changecompany();">
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
                     <td width="39%" align="left" class="toplinks"><?php echo TEXT_DEPARTMENT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                     <td width="59%" align="left" class="listingmaintext">
					       <select name="cmbDepartment" size="1" class="comm_input input_width1a" id="cmbDepartment" onchange="changedepartment();">
						     <?php
							                $options="";
                                            $get_options =    make_selectlist(0,0,$var_companyid);
											$options ="<option value='0'";
                                               $options .=">Select</option>\n";
                                            if (count($get_options) > 0)
                                            {
                                                  //$departments = $_POST['dept_id'];
												   
                                                  
                                                 foreach ($get_options  as $key => $value) {
                                                      $options .="<option value=\"$key\"";
                                                      if ($var_departmentid == "$key")
                                                      {
                                                           $options .=" selected=\"selected\"";
                                                      }
                                                      $options .=">" . $value . "</option>\n";
                                                 }
                                            }
                                            echo $options;
                              ?>
					       </select>	
						   
						   	
                      </td>
                      </tr>				
					   <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                     <td width="2%" align="left">&nbsp;</td>
                     <td width="39%" align="left" class="toplinks"><?php echo TEXT_PARENT_CATEGORY?> </td>
                     <td width="59%" align="left" class="listingmaintext">
					       <select name="cmbParentCategory" size="1" class="comm_input input_width1a" id="cmbParentCategory" >
						     <?php
							                $categories =    makeCategoryList(0,0,$var_departmentid);
											//print_r($categories);
											$catoptions ="<option value='0'";
                                            $catoptions .=">Select</option>\n";
							                  if (count($categories) > 0)
							                  {
							                      foreach ($categories  as $key => $value) {
							                               $catoptions .="<option value=\"$key\"";
							                               if ($var_parentcatid == "$key")
							                               {
							                                           $catoptions .=" selected=\"selected\"";
							                               }
							                               $catoptions .=">" . $value . "</option>\n";
							                      }
							                  }
							                  echo $catoptions;
                              ?>
					       </select>	
						   
						   	
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_CATEGORY ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="59%" align="left">
                        <input name="txtCategoryName" type="text" class="comm_input input_width1" id="txtCategoryName" size="30" maxlength="100" value="<?php echo htmlentities($var_catname); ?>">
					  </td>
                      </tr>
                     <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr>
								</table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
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
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD_NEW ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SAVE_CHANGES ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" ></td>
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
<script>
	var setValue = "<?php echo trim($var_country); ?>";

	<?php
		if ($var_id == "") {
			echo("document.frmCategory.btAdd.disabled=false;");
			echo("document.frmCategory.btUpdate.disabled=true;");
			echo("document.frmCategory.btDelete.disabled=true;");
		}
		else {
			echo("document.frmCategory.btAdd.disabled=true;");
			echo("document.frmCategory.btUpdate.disabled=false;");
			echo("document.frmCategory.btDelete.disabled=false;");
		}
	?>
</script>
</form>