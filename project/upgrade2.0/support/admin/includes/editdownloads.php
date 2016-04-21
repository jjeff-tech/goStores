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
	
	//$var_userid = $_SESSION["sess_staffid"];
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "Select * from sptbl_downloads ";
        $sql .=" where nDLId='".addslashes($var_id)."'";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);  
			$var_desc = $var_row["vDescription"];
			$var_url= $var_row["vURL"];
			
    	}
		else {
			$var_id = "";
			$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
		}
	}
	elseif ($_POST["postback"] == "A") {
	         
	      	$var_desc= trim($_POST["txtDownDesc"]);
			$uploadstatus=upload("txtUrl","../downloads/","","all","100000000000000");
			$errorcode="";
			$dup_flag=0;
			switch ($uploadstatus) {
               case "FNA":
			              $errorcode=MESSAGE_UPLOAD_ERROR_1;
                          break;
               case "IS":
			               $errorcode=MESSAGE_UPLOAD_ERROR_3;
				   		  	break;
			   case "IT":
			            $errorcode=MESSAGE_UPLOAD_ERROR_2;
				         break;
			   case "NW":
			            $errorcode=MESSAGE_UPLOAD_ERROR_4; 
				         break;	
			   case "FE":
			            $errorcode=MESSAGE_UPLOAD_ERROR_5; 
				         break;				 
			   case "IF":
			            $errorcode=MESSAGE_UPLOAD_ERROR_6;
				         break;
			   default:
				         $file_name=$uploadstatus; 
				         break;	 			
  		    }
            $sql="SELECT nDLId   FROM sptbl_downloads WHERE   vDescription ='".addslashes($var_desc) . "'";
			
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  if($file_name !=""){
			    unlink("../downloads/".$file_name);
			  }
			  $dup_flag=1;
			} 
			if (validateAddition() == true and $errorcode=="" and $dup_flag==0) {
			  //Insert into the downloads table
		      $file_name ="downloads/".$file_name;
		      $sql = "Insert into sptbl_downloads(nDLId,vDescription,vURL,dPostdate,vType";
			  $sql .= ") Values('','" . addslashes($var_desc). "','" . addslashes($file_name) . "',now(),'1')";
			  executeQuery($sql,$conn);
			  $var_insert_id = mysql_insert_id($conn);
			  //Insert the actionlog
			  if(logActivity()) {
			  $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Downloads','" . addslashes($var_insert_id) . "',now())";			
			  executeQuery($sql,$conn);
			  }
			  $var_desc="";
			  $var_message = MESSAGE_RECORD_ADDED;
			
		}
		else {
		 	$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR .$errorcode ."</font>";
		}
	}
	elseif ($_POST["postback"] == "D") {
	       
			
		if (validateDeletion() == true) {
		         $sql="SELECT vURL    FROM sptbl_downloads WHERE   nDLId =$var_id";
		         $rs_oldurl = executeSelect($sql,$conn);
				 $rowoldurl=mysql_fetch_array($rs_oldurl);
				 $oldurl=$rowoldurl['vURL'];
				 unlink("../".$oldurl);
			$sql = "delete from  sptbl_downloads   where nDLId  ='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Downloads','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			$var_desc= "";
			$var_url="";
			$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
		}
		else {
		    $var_title= trim($_POST["txtNewsTitle"]);
			$var_news = trim($_POST["txtNews"]);
			$var_validdate = trim($_POST["txtDate"]);
			$var_stype = trim($_POST["chk_staff"]);
			$var_utype = trim($_POST["chk_user"]);
			if($var_stype!="" and $var_utype!=""){
			  $vtype="A";
			}elseif($var_stype!=""){
			  $vtype=$var_stype;
			  
			}else if($var_utype!=""){
			  $vtype=$var_utype;
			}
		}
	}
	elseif ($_POST["postback"] == "U") {
	   $sql = "SELECT nDLId   FROM sptbl_downloads WHERE nDLId='" . addslashes($var_id) . "'";   
	   if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {  
			$var_desc= trim($_POST["txtDownDesc"]);
			$var_url=$_POST['varurl'];
			$uploadstatus=upload("txtUrl","../downloads/","","all","100000000000000");
			$errorcode="";
			$dup_flag=0;
			$file_name="";
			switch ($uploadstatus) {
               case "FNA":
			              $errorcode="";
                          break;
               case "IS":
			               $errorcode=MESSAGE_UPLOAD_ERROR_3;
				   		  	break;
			   case "IT":
			            $errorcode=MESSAGE_UPLOAD_ERROR_2;
				         break;
			   case "NW":
			            $errorcode=MESSAGE_UPLOAD_ERROR_4; 
				         break;	
			   case "FE":
			            $errorcode=MESSAGE_UPLOAD_ERROR_5; 
				         break;				 
			   case "IF":
			            $errorcode=MESSAGE_UPLOAD_ERROR_6;
				         break;
			   default:
				         $file_name=$uploadstatus; 
				         break;	 			
  		    }
			
			  
           $sql="SELECT nDLId   FROM sptbl_downloads WHERE   vDescription ='".addslashes($var_desc) . "'";
		   $sql .="and nDLId !=$var_id";
		   $rs = executeSelect($sql,$conn);
		   if(mysql_num_rows($rs)>0){
			  if($file_name !=""){
			    @unlink("../downloads/".$file_name);
			  }
			  $dup_flag=1;
			} 
			
			if (validateUpdation() == true and $dup_flag==0 and $errorcode=="") {
			  if($file_name !=""){
			    $file_name ="downloads/".$file_name;
			    $seturlfld=" ,vURL='".addslashes($file_name)."' ";
				//unlink the old file
				 $sql="SELECT vURL    FROM sptbl_downloads WHERE   nDLId =$var_id";
		         $rs_oldurl = executeSelect($sql,$conn);
				 $rowoldurl=mysql_fetch_array($rs_oldurl);
				 $oldurl=$rowoldurl['vURL'];
				 @unlink("../".$oldurl);
				 $var_url=$file_name;
			  }else{
			     $seturlfld=" ";
			  }
			  
				 $sql = "Update sptbl_downloads  set vDescription  ='" . addslashes($var_desc) . "',
					     dPostdate =now()";
					    
				 $sql .=$seturlfld;
				 $sql .="where nDLId='" . addslashes($var_id) . "'";
				 executeQuery($sql,$conn);
				//Insert the actionlog
				if(logActivity()) {
			     $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Downloads','" . addslashes($var_id) . "',now())";			
			     executeQuery($sql,$conn);
				 }
			    
				$var_message = MESSAGE_RECORD_UPDATED;
			}
			else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR .$errorcode. "</font>";
			} 
		} 
		else {
			$var_id = "";
			$var_message = MESSAGE_INVALID_DOWNLOAD ."<br>";
		}	
	}
	
	function validateAddition() 
	{
	
		
 	    if (trim($_POST["txtDownDesc"]) == "" ) {
		    return false;
		}else {
		    return true;
		}
	}
	
	function validateDeletion() 
	{
	
		return true;
	}
	
	function validateUpdation() 
	{
		 if (trim($_POST["txtDownDesc"]) == "" ) {
		    return false;
		}else {
		    return true;
		}
	}

        /* To show max upload file size*/
        $alsize = 0;
        $sqlSize = "Select * from sptbl_lookup where vLookUpName IN('MaxfileSize')";
		$resultSize = executeSelect($sqlSize,$conn);
		if(mysql_num_rows($resultSize) > 0) {
			while($rowSize = mysql_fetch_array($resultSize)) {
				switch($rowSize["vLookUpName"]) {

				   case "MaxfileSize":
						$alsize = $rowSize["vLookUpValue"];
						break;
			   }
			}
		}


?>
<form name="frmDownloads" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
<div class="content_section">
			<div class="content_section_title">
				<h3>
				<?php     
     if(!empty($_GET['id'])) {
        echo TEXT_EDIT_DOWNLOADS;
     } else {
         echo TEXT_ADD_DOWNLOADS; 
     }
     ?>
				</h3>
			</div>
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    		<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>
    	<tr>
         <td align="center" colspan=3 class="errormessage">
         <?php echo $var_message ?></td>

         </tr>
		<tr>
		<td>&nbsp;</td>
         <td align="left" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>
		          <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="2%" align="left">&nbsp;</td>
                     <td width="38%" align="left" class="toplinks"><?php echo TEXT_DOWNLOAD_DESC?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					  <td width="60%" align="left">
                        <input name="txtDownDesc" type="text" class="comm_input input_width1a" id="txtDownDesc" size="30" maxlength="100" value="<?php echo htmlentities($var_desc); ?>">
					  </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                     <td width="2%" align="left">&nbsp;</td>
                     <td width="38%" align="left" class="toplinks"><?php echo TEXT_DOWNLOAD_URL?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                     <td width="60%" align="left" class="toplinks">
					    <input name="txtUrl" type="file" class="comm_input input_width1a" id="txtUrl" size="30" maxlength="100" value="<?php echo htmlentities($var_Url); ?>"></td></tr>
						<tr><td colspan="3">&nbsp;</td></tr>
						<tr>
						
						 <td width="2%" align="left" >&nbsp;</td>
                     <td width="38%" align="left" class="toplinks"></td>
                     <td width="60%" align="left" class="toplinks">
						<div class="msg_common">
						 <?php echo $var_url;
                        echo '['.TEXT_SIDE_MAX_FILE_SIZE. ' '. round($alsize/(1024*1024),2).' MB,<br>'.MESSAGE_UPLOAD_ERROR_2.'"php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe"]';
                        ?>
						
						</div>
                       
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
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%">
									<!-- <input name="btCancel" type="button" class="button" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"> -->
									<input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>">
									</td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="varurl" value="<?php echo($var_url); ?>">
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
			echo("document.frmDownloads.btAdd.disabled=false;");
			echo("document.frmDownloads.btUpdate.disabled=true;");
			echo("document.frmDownloads.btDelete.disabled=true;");
		}
		else {
			echo("document.frmDownloads.btAdd.disabled=true;");
			echo("document.frmDownloads.btUpdate.disabled=false;");
			echo("document.frmDownloads.btDelete.disabled=false;");
		}
	?>
</script>
</form>