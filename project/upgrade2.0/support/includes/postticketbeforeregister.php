<?php 
  $var_compid=$_SESSION["sess_usercompid"];
  $var_userid=$_SESSION["sess_userid"];
   $flag_msg="";
  if ($_GET["stylename"] != "") {
			$var_styleminus = $_GET["styleminus"];
			$var_stylename = $_GET["stylename"];
			$var_styleplus = $_GET["styleplus"];
  }else {
			$var_styleminus = $_POST["styleminus"];
			$var_stylename = $_POST["stylename"];
			$var_styleplus = $_POST["styleplus"];
  }
  

	if(!isset($_POST['varrefresh']))
		$var = "";
	else
		$var = $_POST['varrefresh'];


  if ($_POST["postback"] == "A") {

	$var_name=$_POST['txtname']; 
	$var_email=$_POST['txtemail'];

	$var_title=$_POST['tckttitle'];
	$var_deptid=$_POST['deptid'];
	$var_prty=$_POST['prty'];
	$var_desc=$_POST['tcktdesc'];
	$var_refname=$_POST['txtRef'];
	$var_list = "";
	$var_uploaded_files=$_POST['uploadedfiles'];
	//check reference name is duplicate
	$pos=0;
	$not_allowed_pos_star=0;
	$not_allowed_pos_pipe=0;
	//check whether the refernce name contains | or *

	if($var_refname !=""){
	   $pos=strpos($var_uploaded_files,$var_refname);
	   $not_allowed_pos_star=strpos($var_refname,"*");
	   $not_allowed_pos_pipe=strpos($var_refname,"|");
	}else{
	  $pos=1;
	  $not_allowed_pos_star=1;
	  $not_allowed_pos_pipe=1;
	}

	$sql ="select * from sptbl_attachments where vAttachReference='".addslashes($_POST['txtRef'])."'";
	$var_result = executeSelect($sql,$conn);
	if(mysql_num_rows($var_result)>0 or $pos > 0 or $not_allowed_pos_star>0 or $not_allowed_pos_pipe>0){
	   $var_message=MESSAGE_REFNAME_ERROR;
             $flag_msg="class='msg_error'";
	   mysql_free_result($var_result);
	}else if($var_deptid<=0){
		  $var_message=MESSAGE_DEPT_NOT_SELECTED;
                    $flag_msg="class='msg_error'";
	}else{
			
	    if($_SESSION['ses_test']==$var or $var==""){
				$sql="select vLookUpValue from sptbl_lookup where vLookUpName='Maxfilesize'";
				$var_result = executeSelect($sql,$conn);
				if (mysql_num_rows($var_result) > 0) {
					$var_row = mysql_fetch_array($var_result);
					$var_maxfilesize = $var_row["vLookUpValue"];
				}else{
					$var_maxfilesize="100000";
				}
					$uploadstatus=upload("txtUrl","./attachments/","","all",$var_maxfilesize);
					$file_name="";
					switch ($uploadstatus) {
					   case "FNA":
							$errorcode=MESSAGE_UPLOAD_ERROR_0;
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
	
					if($file_name==""){
					  $var_message=$errorcode;
                                            $flag_msg="class='msg_error'";
					}else{
							$var_refname="";
							if($var_uploaded_files==""){
								  $var_uploaded_file_name=$_POST['txtRef'];
								  $var_uploaded_files=$file_name."*".$_POST['txtRef'];
							}else{
								  $var_uploaded_files .="|".$file_name."*".$_POST['txtRef'];
							}
					}
// newly added
			 }else{
				     $file_name=$_FILES['txtUrl']['name'];
				     if($var_uploaded_files==""){
						  $var_uploaded_file_name=$_POST['txtRef'];
						  $var_uploaded_files=$file_name."*".$_POST['txtRef'];
					 }else{
						  $var_uploaded_files .="|".$file_name."*".$_POST['txtRef'];
				   }
			 }
	}
  }else if ($_POST["postback"] == "RA") {

		$var_name=$_POST['txtname']; 
		$var_email=$_POST['txtemail'];

        $var_title=$_POST['tckttitle'];
        $var_deptid=$_POST['deptid'];
        $var_prty=$_POST['prty'];
        $var_desc=$_POST['tcktdesc'];
        $var_uploaded_files=$_POST['uploadedfiles'];
	    $var_list = "";
        
		for($i=0;$i<count($_POST["chk"]);$i++) {
                $var_list .=  $_POST["chk"][$i] . "|";
        }
        $var_list = substr($var_list,0,-1);
  }else if ($_POST["postback"] == "R") {

		$var_name=$_POST['txtname']; 
		$var_email=$_POST['txtemail'];

        $var_title=$_POST['tckttitle'];
        $var_deptid=$_POST['deptid'];
        $var_prty=$_POST['prty'];
        $var_desc=$_POST['tcktdesc'];
        $var_uploaded_files=$_POST['uploadedfiles'];
        $var_list = "";
        $var_uploaded_files=$_POST['uploadedfiles'];
        $var_list=$_POST["rid"];
  }else if ($_POST["postback"] == "S") {

		$var_name=$_POST['txtname']; 
		$var_email=$_POST['txtemail'];

		$var_title=$_POST['tckttitle'];
		$var_deptid=$_POST['deptid'];
		$var_prty=$_POST['prty'];
		$var_desc=$_POST['tcktdesc'];
 //header("location:postticketkb.php")
  }else{
	   $sql="select * from sptbl_temp_tickets where nTpUserId='$var_userid' and vStatus=0";
	   
	   $rs = executeSelect($sql,$conn);

	
	  $var_deptid=$row['nTDeptId'];
	  $var_title=$row['vTpTitle'];
	  $var_desc=$row['tTpQuestion'];
	  $var_uploaded_files=$row['vAtt'];
	  $tempticketid=$row['nTpTicketId'];
	  $var_prty=$row['vTpPriority'];
  }
  if(isset($_GET['var_message']) && $_GET['var_message']!='')
      {

      $var_message=$_GET['var_message'];
       $flag_msg="class='msg_error'";
      }
?>

<div class="content_section">

<div class="content_section_title">
<h3><?php echo TEXT_POST_TICKET?></h3>
</div>
    
<div <?php echo $flag_msg;?>> <?php echo $var_message; ?></div>
                
							
                            
									
										  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
												
    	                                        <tr>
        	                                        <td class="bodycolor">
														<table width="100%"  border="0" cellpadding="0" cellspacing="3" class="pagecolor">
    	        	                                       <tr align="center" class="pagecolor">
        		                                             <td class="maintext">
																<table width="95%"  border="0" align="center">
																  <tr>
																	 <td width="100%" align="right" colspan=3 class="listing">
																		 <?php echo TEXT_FIELDS_MANDATORY ?>
																	 </td>
																  </tr>
																	  <!-- %%%%%%%%%%%%%%%%%%%%%General Info %%%%%%%%%%%%%%%%%% -->
																	  <form name="frmInfo" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
																	  <tr>
																	  	<td>
																		
			                                                             <div class="content_section_subtitle"><h3><?php echo TEXT_GENERAL_INFO?></h3></Div>
                                                                              <table border="0" width="100%" border="0">
																				  <tr>
																				   <td colspan="3">&nbsp;</td>
																				  </tr>
																				  <tr>
																					<td width="19%" align="left" class="listing"><?php echo TEXT_NAME?>&nbsp;<span class="required">*</span></td>
																					<td width="1%">&nbsp;</td>
																					<td align="left"><input name="txtName" type="text" size="30" maxlength="100" class="comm_input input_width1" value="<?php echo htmlentities($var_name);?>"></td>
																				  </tr>
																				  <tr>
																				   <td colspan="3">&nbsp;</td>
																				  </tr>
																				  <tr>
																					<td align="left" class="listing"><?php echo TEXT_EMAIL?>&nbsp;<span class="required">*</span></td>
																					<td>&nbsp;</td>
																					<td align="left"><input name="txtEmail" type="text" size="30" maxlength="100" class="comm_input input_width1" value="<?php echo htmlentities($var_email);?>"></td>
																				  </tr>
																				  <tr>
																					<td colspan="3">&nbsp;</td>
																				  </tr>
                                                                                 <tr>
                                                                                     <td align="left" class="listing"><?php echo TEXT_DEPT?>&nbsp;<span class="required">*</span></td>
                                                                                     <td>&nbsp;</td>
                                                                                     <td width="80%" align="left">
																					   <?php
																								 $leafdeptarr=getLeafDepts();
																								 if($leafdeptarr !=""){
																									 $leaflvldeptids=implode(",",$leafdeptarr);

																								 }else{
																								   $leaflvldeptids=0;
																								 }
																								 $sql = "SELECT dept.nDeptId,dept.vDeptDesc,dept.vDeptCode,dept.nCompId, comp.nCompId,comp.vCompName  ";
																								 $sql .= " FROM sptbl_depts AS dept INNER JOIN sptbl_companies AS comp ON dept.nCompId = comp.nCompId WHERE nDeptId  in($leaflvldeptids) ";
																								 $sql .= " ORDER BY dept.nCompId,dept.vDeptDesc ";

																								 $rs = executeSelect($sql,$conn);
																								?>
                                                                                                 <select name="cmbDept" size="1" class="comm_input input_width1" id="cmbDept" >
                                                                                                   <?php
																										while($row = mysql_fetch_array($rs)) {
																											  $options ="<option value='".$row['nDeptId']."'";
																											  if ($var_deptid == $row['nDeptId']){
																												   $options .=" selected=\"selected\"";
																											  }
																											  $options .=">[".htmlentities($row['vDeptCode'])."]&nbsp;".htmlentities($row['vDeptDesc'])."&nbsp;[".htmlentities($row['vCompName'])."]</option>\n";
																											  echo $options;
                                                                                                        }
		                                                                                                mysql_free_result($rs) ;
                               		                                                               ?>
                                                                                                  </select>
                                                                                       </td>
                                                                                   </tr>
                                                                                   <tr>
																					   <td colspan="3">&nbsp;</td>
																				   </tr>
																				   <!--<tr>
																						<td align="left" class="listing"><?php /* echo TEXT_PRIORITY?>&nbsp;<span class="required">*</span></td>
																						<td>&nbsp;</td>
																						<td width="80%" align="left">
																						   <?php
																								 $sql = "select nPriorityValue ,vPriorityDesc  from sptbl_priorities order by nPriorityValue";
																								 $rs = executeSelect($sql,$conn);
																						   ?>
																						   <select name="cmbPriority" size="1" class="comm_input input_width1" id="cmbPriority">
																							 <?php
																								while($row = mysql_fetch_array($rs)) {
																										$options ="<option value='".$row['nPriorityValue']."'";
																								  		if ($var_prty == $row['nPriorityValue']){
																											$options .=" selected=\"selected\"";
																						  				}
																						  			$options .=">".$row['vPriorityDesc']."</option>\n";
 																							    	echo $options;
																								}
																								mysql_free_result($rs) ;*/
																							 ?>
																							</select>
																						</td>
                                                                                  </tr>-->
                                                                                  <tr>
                                                                                        <td colspan="3">&nbsp;</td>
                                                                                  </tr>
                                                                             </table>
                                                                       
                                                                       </td>
																	 </tr>
                                                                <!-- %%%%%%%%%%%%%%%%%%%%%TICKET INFO %%%%%%%%%%%%%%%%%% -->
                                                              <tr><td>
															  
															  
															  <div class="content_section_subtitle"><h3><?php echo TEXT_TICKET_INFO?></h3></Div>
                                                             
                                                           
															   <table width="100%">
															  <tr>
															   <td colspan="3">&nbsp;</td>
															  </tr>
															  <tr>
																<td width="19%" align="left" class="listing"><?php echo TEXT_TICKET_TITLE?>&nbsp;<span class="required">*</span></td>
																<td width="1%">&nbsp;</td>
																<td width="80%" align="left"><input name="txtTcktTitle" type="text" size="70" maxlength="100" class="comm_input input_width1" value="<?php echo htmlentities($var_title);?>" style="width:500px"></td>
															  </tr>
															  <tr>
															   <td colspan="3">&nbsp;</td>
															  </tr>
															  <tr>
																<td align="left" class="listing"><?php echo TEXT_TICKET_MATTER?>&nbsp;<span class="required">*</span></td>
																<td>&nbsp;</td>
																<td align="left">
                                                                                                                                    <?php

                                                                                                                                        $sBasePath              = "./FCKeditor/";
                                                                                                                                        $oFCKeditor 		= new FCKeditor('txtMatter') ;
                                                                                                                                        $oFCKeditor->BasePath	= $sBasePath ;
                                                                                                                                        $oFCKeditor->Value	= stripslashes(htmlentities($var_desc));
                                                                                                                                        $oFCKeditor->Width      = '530' ;
                                                                                                                                        $oFCKeditor->Height     = '350' ;
                                                                                                                                        $oFCKeditor->ToolbarSet="Basic";
                                                                                                                                        $oFCKeditor->Create() ;
                                                                                                                                    ?>
                                                                                                                                </td>
															  </tr>
															  <tr>
																<td colspan="3">&nbsp;</td>
															  </tr>
															  </table>
															
															  
															  </td></tr>
															  </form>
																<!-- %%%%%%%%%%%%%%%%%%%%%Attachments %%%%%%%%%%%%%%%%%% -->
															  <tr><td>
															 
															 <div class="content_section_subtitle"><h3><?php echo TEXT_ATTACHMENTS?></h3></Div>
                                                              
																		  <form name="frmAttach" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
																		  <table width="100%" border="0">
																		 <tr>
																		   <td colspan="3">&nbsp;</td>
																		  </tr>
																		  <tr>
																			<td colspan="3" align="left" class="listing">
																				<?php echo TEXT_FIELDS_SEMI_MANDATORY ?> <br>&nbsp;
																			</td>
																		  </tr>
																		<!--  <tr>
																			<td align="left" class="listing" width="20%"><?php echo TEXT_ATTACH_REFERENCE?>&nbsp;<span class="semirequired">*</span></td>
																			<td width="2%">&nbsp;</td>
																			<td width="48%" align="left"><input name="txtRef" type="text" size="60" maxlength="100" class="comm_input input_width1" value="<?php echo htmlentities($var_refname);?>"></td>
																		  	<td width="30%">&nbsp;</td>
																		  </tr>
																		  <tr>
																		   <td colspan="4">&nbsp;</td>
																		  </tr>-->
																		  <tr>
                                                                            <td align="left" width="16%"><?php echo TEXT_ATTACH_URL?>&nbsp;</td>
																			<td width="0%"  ></td>
																			 <td align="left" width="35%"  >
																						  <?php
																						$var_refname = time(). rand(1,90000);
																						?>
																						<input name="txtRef" type="hidden"  value="<?php echo htmlentities($var_refname);?>">
		<input name="txtUrl" type="file" class="comm_input input_width1" id="txtUrl" maxlength="100" >

																			 </td>
																			
																			<td align=left>
																			<?php 
																				if($var==""){ 
																					$var=0;
																				}else{
																					$var=$var+1;
																				}
																				$_SESSION['ses_test'] = $var ; 
																			?>
																				<input type=hidden name=varrefresh value="<?php echo   $var?>">
																				<input name="btnSubmit" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ATTACH?>" onClick="javascript:attach();">
                                                                                                                                                           
                                                                                                                                                        </td>
																		  </tr>
																		  <tr>
                                                                                                                                                      <td colspan="2">&nbsp;</td>
                                                                                                                                                      <td align="left" colspan="2">
                                                                                                                                                          <?php
                                                                                                                                                            $allowedsql = "SELECT * FROM sptbl_lookup WHERE vLookUpName='Attachments'";
                                                                                                                                                            $allowedtype = mysql_query($allowedsql);
                                                                                                                                                            $str_start = "<br>".ALLOWED_TYPES.": ";
                                                                                                                                                            $str = "";
                                                                                                                                                            if(mysql_num_rows($allowedtype)) {
                                                                                                                                                                while($allowed=mysql_fetch_array($allowedtype)) {
                                                                                                                                                                    $arr = explode('|',$allowed['vLookUpValue']);
                                                                                                                                                                    $str.=$arr[0].',&nbsp;';
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                              echo     $str_start. $str;
                                                                                                                                                           // $str.="";
                                                                                                                                                           // echo $str_start.wordwrap($str, 35,"\n", true);

                                                                                                                                                            ?>

                                                                                                                                                      </td>
																		  </tr>
																		  <?php
																						   $total_uploaded_file=explode("|",$var_uploaded_files);
																										   //remove list not empty
																										   if($var_list !=""){
																												 $remove_array=explode("|",$var_list);
																												 foreach($remove_array as $key=>$value){
																													$picarry=explode("*",$value);
																													
																													unlink("./attachments/".$picarry[0]);
																												 }
																												 $var_uploaded_files_arr = array_diff($total_uploaded_file,$remove_array);
																												 $total_uploaded_file = array_diff($total_uploaded_file,$remove_array);
																												 $var_uploaded_files = implode("|",$var_uploaded_files_arr);
																										   }

																					   if($var_uploaded_files !=""){
																		   ?>
																							 <tr><td colspan=4>
																							 <table width='70%' border=0 align="center">
																		   <?php
																									   foreach($total_uploaded_file as $key=>$value){
																									   
																									   $spli_name_file=explode("*",$value);
																									   $disp_name_file=$spli_name_file[1]."(".$spli_name_file[0].")";

																		  ?>
																													 <tr>
																													  <td width="6%" align="center">
																															 <input type="checkbox" name="chk[]" id="u<?php echo($key); ?>" value="<?php echo htmlentities($value) ?>" class="checkbox">

																														  </td>
																													  <td width="89%" class="listing"><?php  echo htmlentities($disp_name_file); ?></td>
																														  <td width="5%" align="center"><a href="javascript:remove('<?php  echo addslashes(htmlentities($value)); ?>');"><img src="./images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE ?>"></a></td>
																													 </tr>
																		  <?php             }
																		   ?>

																							<tr>
																												  <td colspan=3 align=center>
																												   <input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_REMOVE; ?>" onClick="javascript:clickRemove();">
																												  </td>
																												</tr>
																							</table></td></tr>
																		   <?php
																			}
																		   ?>

																		  <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
																	      <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
																	      <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
																		  <input type="hidden" name="postback" value="">
																		  <input type="hidden" name="rid" value="">

																		  <input type="hidden" name="txtname">
																		  <input type="hidden" name="txtemail">															  

																		  <input type="hidden" name="deptid">
																		  <input type="hidden" name="prty">
																		  <input type="hidden" name="tckttitle">
																		  <input type="hidden" name="tcktdesc">
																		  <input type="hidden" name="uploadfiles">
																		  <input type="hidden" name="id" value="<?php echo($var_id); ?>">

																		  <input type="hidden" name="uploadedfiles" value="<?php echo htmlentities($var_uploaded_files); ?>">
																		  <input type="hidden" name="uploadedfile_name" value="<?php echo $var_uploaded_file_name; ?>">
																		  </table>
                                                                                                                                                  </form>
																		 
																	  </td>
																  </tr>
															</table>
														  <!-- ##########################################- -->
													  </td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                      </table>
                  

            <form name="frmPostTicket" method="POST" action="postticketbeforeregistersave.php" enctype="multipart/form-data">
             <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
				  <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="maintext">
                                  <tr align="center" class="pagecolor">
                                    <td width="34%">&nbsp;</td>
                                    <td width="16%">
									<input name="btnSubmit" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SUBMIT?>" onClick="javascript:addticket();"></td>
                                    <td width="34%">&nbsp;</td>
										<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
										<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
										<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">

  									    <input type="hidden" name="txtname">
									    <input type="hidden" name="txtemail">															  

										<input type="hidden" name="deptid">
										<input type="hidden" name="prty">
										<input type="hidden" name="tckttitle">
										<input type="hidden" name="tcktdesc">
										<input type="hidden" name="uploadfiles">
										<input type="hidden" name="id" value="<?php echo($var_id); ?>">
										<input type="hidden" name="postback" value="">
										<input type="hidden" name="notlogin" value="NOTLOGIN">
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
          </form>
		  
		  </div>