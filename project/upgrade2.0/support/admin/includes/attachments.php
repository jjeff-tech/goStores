<?php
$var_userid = $_SESSION["sess_adminid"];


if ($_POST["postback"] == "D") {
        if (validateDeletion(addslashes($_POST["id"])))  {
            $sql = "delete from  sptbl_lookup  where vLookUpValue='" . addslashes($_POST["id"]) . "' and vLookUpName='Attachments'";
                executeQuery($sql,$conn);
           //Insert the actionlog
		   		if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Lookup/Attachment','" . addslashes($_POST["id"]) . "',now())";
					executeQuery($sql,$conn);
				}
                $var_message = MESSAGE_RECORD_DELETED;
                $flag_msg    = 'class="msg_success"';
        }
        else {
                $var_message = MESSAGE_RECORD_ERROR ;
                $flag_msg    = 'class="msg_error"';
        }
}
elseif ($_POST["postback"] == "DA") {
        $var_list = "";
        for($i=0;$i<count($_POST["chk"]);$i++) {
                if($_POST["chk"][$i]!="en"){
                        $var_list .= "'" . addslashes($_POST["chk"][$i]) . "',";
                }
        }
        $var_list = substr($var_list,0,-1);

        if (validateDeletion($var_list) == true and $var_list!="") {


                        $sql = "delete from  sptbl_lookup where vLookUpName='Attachments' and vLookUpValue  IN(" . $var_list . ")";
                        executeQuery($sql,$conn);
                        //Insert the actionlog
						if(logActivity()) {
							for($i=0;$i<count($_POST["chk"]);$i++) {
									$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Lookup/Attachment','" . addslashes($_POST["chk"][$i]) . "',now())";
									executeQuery($sql,$conn);
							}
						}

                        $var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';


       }else{
                        $var_message = MESSAGE_RECORD_ERROR;
                        $flag_msg    = 'class="msg_error"';
       }

}elseif ($_POST["postback"] == "A") {
                  $file_iploaded=1;
				  
				 if(strpos(addslashes($_POST["txtExtension"]),"|")>0){
				   $var_message =  TEXT_INVALID_EXT;
                                   $flag_msg    = 'class="msg_error"';
				   $file_uploaded=0;
				 }else if (!is_uploaded_file($_FILES['txtExtensionFile']['tmp_name']) or trim(addslashes($_POST["txtExtension"]))==""){
			      $var_message =  TXT_FILE_NOT_UPLOADED ;
                              $flag_msg    = 'class="msg_error"';
				  $file_uploaded=0;
			    }else{
				  $file_type=$_FILES['txtExtensionFile']['type'];
				  
				  //echo "filetype==$file_type";
				  $file_uploaded=1;
				}
				if($file_uploaded==1){
						$fileext_type=addslashes($_POST["txtExtension"])."|".$file_type;
						if (validateAddition(addslashes($fileext_type)) ) {
		                                
		                                $sql = "Insert into sptbl_lookup(nLookUpId,vLookUpName,vLookUpValue) values('','Attachments','".$fileext_type."')";
		                                executeQuery($sql,$conn);
		                                //Insert the actionlog
										if(logActivity()) {
											 $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Lookup/Attachment','" . addslashes($_POST["txtExtension"]) . "',now())";
											 executeQuery($sql,$conn);
										 }
		
		                                $var_message = MESSAGE_RECORD_ADDED;
                                                $flag_msg    = 'class="msg_success"';
		
		               }else{
		
		                                $var_message = MESSAGE_RECORD_DUPLICATE;
                                                $flag_msg    = 'class="msg_error"';
		              }
             }
}







function validateDeletion($var_list) {

                return true;
}

function validateAddition($var_list) {
              global $conn;
			   
              $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='Attachments' and vLookUpValue='$var_list'";
			  
              $var_result = executeSelect($sql,$conn);
              if (mysql_num_rows($var_result) > 0) {
                  return false;
              }else{
                  return true;
              }
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

$sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='Attachments' ";


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

if($var_search != "" and $var_cmbSearch == "filetype" ){

                $qryopt .= " and vLookUpValue like '" . addslashes($var_search) . "%'";

}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By vLookUpValue";

?>

<form name="frmAttachments" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_ATTACHMENT_DETAILS ?></h3>
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
								<?php echo(TEXT_SEARCH); ?>
							</div>
							<div class="left rightmargin">
								<select name="cmbSearch" class="selectstyle">
								<option value="filetype" <?php echo(($var_cmbSearch == "type" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_FILE_TYPE?></option>
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
						
						<tr><td colspan="2" align="center" class="errormessage">
                                                        <?php
                                                        if ($var_message != ""){?>
							<div <?php echo $flag_msg; ?>>
						<?php echo($var_message); ?>
							</div>
                                                        <?php
                                                        }?>
						</td></tr>
						</table>
						</td>
						</tr>
						<tr>
						  <td class="whitebasic" >
						  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
							  <tr align="left"  class="listing">
								<th colspan="3"><?php echo "<b>".TEXT_ATTACHMENT_EXTENSION."</b>"; ?></th>
								<th width="15%" align="center"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
					if ($totalrows <= 0) {
					$var_num = 0;
					$var_numBegin = 0;
					$var_begin = 0;
					$var_start="";
					}
					elseif ($var_num > $var_numBegin) {
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
					$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEATTACHMENTS&styleminus=minus4&styleplus=plus4&",$var_numBegin,$var_start,$var_begin,$var_num);
					
					//execute the new query with the appended SQL bit returned by the function
					$sql = $sql.$navigate[0];
					//echo "sql==$sql";
					//echo $sql;
					//echo "<br>".time();
					//$rs = mysql_query($sql,$conn);
					$rs = executeSelect($sql,$conn);
					$cnt = 1;
					while($row = mysql_fetch_array($rs)) {
					$file_ext_namearr=explode("|",$row["vLookUpValue"]);
					
					$file_ext_name=$file_ext_namearr[0];
					$file_ext_type=$file_ext_namearr[1];
					?>
					
							  <tr align="left"  class="listingmaintext">
								<td width="3%"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["vLookUpValue"]); ?>" class="checkbox" ></td>
								<td width="48%"><?php echo htmlentities(trim_the_string($file_ext_name)); ?></td>
								 <td width="34%"  align="center"><?php echo htmlentities(trim_the_string($file_ext_type)); ?></td>
								<td  align="center"><a href="javascript:deleted('<?php echo $row["vLookUpValue"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo(TITLE_DELETE_ATTACHMENT); ?>"></a></td>
							  </tr>
					<?php
					$cnt++;
					}
					mysql_free_result($rs);
					?>
							  <tr align="left"  class="listingmaintext">
								<td colspan="5">
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
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor">



						<table width="100%"  border="0" cellspacing="0" cellpadding="5">
						<tr>
						<td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
						<tr align="center" class="listingbtnbar">
						<td align="right">
						<input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:clickDelete();">                                    </td></tr>
						</table></td>
						</tr>
						</table>
                        </td>
                        <td width="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>

                   

</td>
            </tr>
          </table>

                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>

					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="list_tbl">
						<tr>
						<th width="93%" class="heading" align="left"><?php echo "<b>".TEXT_ADD_ATTACHMENT."</b>"; ?>
						</th>
						</tr>
						</table>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="list_tbl">
						<tr align="center" class="whitebasic" >
						
						<td width="50%" style="padding:0 20px;" align="left" ><?php echo TEXT_EXTENSION ?><font style="color:#FF0000; font-size:9px">*</font></td>
						<td align=left>
						<input name="txtExtension" id="txtExtension" type="textbox" class="comm_input input_width1a"  maxlength=100 size=25>
										  
										  </td>
										  </tr>
										  
						<tr align="center" class="whitebasic">
						<td style="padding:0 20px; " align="left"><?php echo TEXT_SELECT_FILE ?><font style="color:#FF0000; font-size:9px">*</font></td>
						<td align=left>
						<input name="txtExtensionFile" id="txtExtensionFile" type="file" class="comm_input input_width1a"   size=25>                                                                       
						</td>
						</tr>  																		  
						</table>
						</td>
						</tr>
						<tr align="center" class="listingbtnbar">
						
						<td colspan=2 style="padding:10px 10px; " align="right">
						<input name="btnAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick=javascript:clickAdd()  >
						</td>
						</tr>
						</table>



                                        

                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table>

                                  </td>
              </tr>
            </table>
			</div>
</form>