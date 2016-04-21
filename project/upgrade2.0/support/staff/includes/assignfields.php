<?php

$var_staffid = $_SESSION["sess_staffid"];

if($_GET["mt"] == "y") {
        $var_styleminus = $_GET["styleminus"];
        $var_stylename = $_GET["stylename"];
        $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
        $var_styleminus = $_POST["styleminus"];
        $var_stylename = $_POST["stylename"];
        $var_styleplus = $_POST["styleplus"];
}
elseif($_POST["mt"] == "u") {
        $var_styleminus = $_POST["styleminus"];
        $var_stylename = $_POST["stylename"];
        $var_styleplus = $_POST["styleplus"];
		$var_tosave = $_POST["tosave"];
		$arr_tosave = explode(",",$var_tosave);
		$sql = "Delete from sptbl_stafffields where nStaffId='$var_staffid'  AND nFieldId NOT IN('1','2','3','4')";
		executeQuery($sql,$conn); 
			
		for($i = 0;$i < count($arr_tosave);$i++) {
			if (!is_null($arr_tosave[$i]) && $arr_tosave[$i] > 0) {
				$sql = "Insert into sptbl_stafffields(nStaffId,nFieldId) Values('$var_staffid','" . addslashes($arr_tosave[$i]) . "')";
				executeQuery($sql,$conn);
			}	
		}
		//Insert the actionlog
		if(logActivity()) {
		$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','StaffField','0',now())";			
		executeQuery($sql,$conn);
		}
		
		
		$sql  = "Select F.vFieldName,F.vFieldDesc from sptbl_stafffields SF inner join sptbl_fields F
					 ON SF.nFieldId = F.nFieldId WHERE nStaffId='$var_staffid' ";
		$rs = executeSelect($sql,$conn);
	
		if (mysql_num_rows($rs) > 0) {
				$cnt = 0;
				while($row = mysql_fetch_array($rs)) {
						$fld_arr[$cnt][0] = $row["vFieldName"];
						$fld_arr[$cnt][1] = $row["vFieldDesc"];
						$cnt++;
				}
		}
		$_SESSION["sess_fieldlist"] = $fld_arr;
		mysql_free_result($rs);
		
		
		
}
	//Modification on November 03, 2005
	//The sql statement is modified. The previous version of sql is commented 
	//$sql = "Select sf.nStaffId,f.nFieldId,f.vFieldName,f.vFieldDesc from sptbl_fields f left outer join sptbl_stafffields sf on f.nFieldId = sf.nFieldId Where f.nFieldId NOT IN('1','2','3','4') AND (sf.nStaffId='$var_staffid' OR ISNULL(sf.nStaffId))";
	$sql = "Select sf.nStaffId,f.nFieldId,f.vFieldName,f.vFieldDesc from sptbl_fields f left outer join sptbl_stafffields sf on f.nFieldId = sf.nFieldId AND sf.nStaffId='$var_staffid' Where f.nFieldId NOT IN('1','2','3','4')";
	$rs = executeSelect($sql,$conn);
	$i = 0;
	$j = 0;
	if (mysql_num_rows($rs) > 0) {
		while($row = mysql_fetch_array($rs)) {
			if(!is_null($row["nStaffId"])) { 		
				$arr_stafffield[$i][0] = $row["nFieldId"];
				$arr_stafffield[$i][1] = $row["vFieldDesc"];
				$i++;
			}
			else {
				$arr_field[$j][0] = $row["nFieldId"];
				$arr_field[$j][1] = $row["vFieldDesc"];
				$j++;
			}
		}
	}

//echo($sql);
?>

<form name="frmAssign" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<div class="content_section">
 <div class="content_section_title">
	<h3><?php echo HEADING_ASSIGN_FIELDS ?></h3>
	</div>
                 
                          <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="column1">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0"  class="whitebasic">
                                  <tr>
                                    <td align="right">
<!-- Selection of fields -->									
									<table width="68%" align=center cellspacing="0" cellpadding="0"  class="whitebasic" border="0">
					  

					  <tr>
                      <td width="38%" align="left" class="toplinks"><?php echo TEXT_AVAILABLE ?><br>
					                 <select name="cmbAvailable" multiple size=20 style="width:200px;height:200px;" class="textarea">
									 <?php
									 		$cnt = 0;
						     				while($cnt < count($arr_field)) {
											  $options ="<option value='".$arr_field[$cnt][0]."'";
											 
                                              $options .=">".constant($arr_field[$cnt][1]) ."</option>\n";
											  echo $options;
											  $cnt++;
											}
                                     ?>    
						</select>
					  </td>
					  <td width="18%">
							 <table align="center">
							        
							 	      <tr>
									      <td>
										  <input type="button" value=">"  class="button" onclick="alloted(this.form);" style="width:40px;">
										  </td>
							      </tr>
									   <tr>
									      <td>
										  <input type="button" class="button"  value="<" onclick="availbaletoalloted(this.form);" style="width:40px;">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="button" value=">>" onclick="makeavailableall(this.form);" style="width:40px;">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="button"  value="<<" onclick="makeallottedall(this.form);" style="width:40px;">
										  </td>
									   </tr>
							    </table>
					  </td>
					 <td width="44%" align="left" class="toplinks"><?php echo TEXT_ASSIGNED ?><br>
                         <select multiple name="cmbSelected"  size=20 style="width:200px;height:200px;" class="textarea">
						 <?php
									 		$cnt = 0;
						     				while($cnt < count($arr_stafffield)) {
											  $options ="<option value='".$arr_stafffield[$cnt][0]."'";
											 
                                              $options .=">".constant($arr_stafffield[$cnt][1]) ."</option>\n";
											  echo $options;
											  $cnt++;
											}
                         ?>  
						</select>
					  </td>
                      </tr>
					  <tr><td colspan="3">&nbsp;</td></tr>
					 </table>

									
									
<!-- End of selection fields -->									
									
									
									
									
									
									
									
									
  	    <input type="hidden" name="mt" value="y">
		<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
		<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
		<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
		<input type=hidden name="tosave" value="">
									
									</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table>

                  
                  <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center" class="whitebasic">
                                    <td><input name="btnSave" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SAVE; ?>" onClick="javascript:saveMe(this.form);"></td>
                                  </tr>
                                </table></td>
                              </tr>
                          </table>
                          <div align="center">                          </div></td>
                        
                      </tr>
                    </table>
                    </td>
              </tr>
            </table>
			</div>
</form>