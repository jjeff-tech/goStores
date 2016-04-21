<?php
if($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
	$var_start = $_GET["start"];
	$var_begin = $_GET["begin"];
	$var_num = $_GET["num"];
	$styleminus = $_GET["styleminus"];
	$stylename = $_GET["stylename"];
	$styleplus = $_GET["styleplus"];
}else if($_POST["mt"] == "y") {
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	$styleminus = $_POST["styleminus"];
	$stylename = $_POST["stylename"];
	$styleplus = $_POST["styleplus"];
	
}


if($_POST["postback"] == "CC"){
	$ddlCategory = $_POST["ddlCategory"];
	$ddlDepartment = $_POST["ddlDepartment"];
}else if ($_POST["postback"] == "CD") {//change department
	$ddlDepartment = $_POST["ddlDepartment"];
}else if($_POST["postback"] == "S"){
	$ddlCategory = $_POST["ddlCategory"];
	$ddlDepartment = $_POST["ddlDepartment"];
}


if($_POST["ddlCategory"] == ""){
	$ddlCategory = $_GET["ddlCategory"];
}
if($_POST["ddlDepartment"] == ""){
	$ddlDepartment = $_GET["ddlDepartment"];
}
settype($ddlDepartment,integer);
settype($ddlCategory,integer);

$sql = " SELECT kb.nKBID, kb.vKBTitle ";
$sql .=" FROM  sptbl_kb kb LEFT OUTER JOIN sptbl_categories  ca ON kb.nCatId = ca.nCatId ";
$sql .=" INNER JOIN sptbl_depts d ON d.nDeptId = ca.nDeptId ";
$sql .=" WHERE kb.nCatId = '$ddlCategory' and vStatus = 'A' ";



$qryopt="";
//modified on November 26, 2005
//searching based on a criterea, then making the criteria null does not have any effect
//hence while checking the condition, we do check if that is not posted back when taking get parameter.   
$txtSearch="";
$cmbSearch="";
if($_POST["txtSearch"] != ""){
		$txtSearch = $_POST["txtSearch"];
}elseif($_GET["txtSearch"] != "" && $_POST["mt"] != "y"){
		$txtSearch = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != ""){
		$cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != "" && $_POST["mt"] != "y"){
		$cmbSearch = $_GET["cmbSearch"];
}
if($txtSearch != ""){
	if($cmbSearch == "title"){
	        $qryopt .= " AND kb.vKBTitle like '%" . mysql_real_escape_string($txtSearch) . "%'";
	}
}

$sql .= $qryopt . " ORDER BY kb.dDate DESC  ";

$_SESSION['sess_backurl'] = getPageAddress(); 

//echo "<br>Department  ". $ddlDepartment;
//echo "<br>Category  ". $ddlCategory;
?>
<script>
<!--

function validateKBForm(){
	var frm = window.document.frmKB;
	var errors="";
	if(errors !=""){
		errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors; 
		alert(errors);
		return false;
	}else{
		frm.postback.value = "Save Changes";
		frm.submit();
	}
}
function changeCategory(){
  document.frmKB.postback.value="CC";
  document.frmKB.method="post";
  document.frmKB.submit();
  
}	

function changeDepartment(){
  document.frmKB.postback.value="CD";
  document.frmKB.method="post";
  document.frmKB.submit();
}

function clickSearch() {
	document.frmKB.numBegin.value=0;
	document.frmKB.begin.value=0;
	document.frmKB.start.value=0;
	document.frmKB.num.value=0;
	document.frmKB.method="post";
	document.frmKB.postback.value="S";
	document.frmKB.submit();
}


-->
</script>
<div class="content_section">
<form action="" method="post" name="frmKB">
<div class="content_section_title"><h3><?php echo TEXT_KB?></h3></div>
                    
														  
         												 <?php
														  if($error){?>
														 <div class="content_section_data">  
														  <div class="msg_error">
														  <?php echo $errormessage;?>
														  </div>				
														</div>
															
														  <?php }
														  if($message){ ?>
														  <div class="content_section_data">  
														  <div class="msg_common">
														  <?php echo $messagetext;?>
														  </div>
														  </div>
														  
														 <?php }?>
								
														
														
														
<div class="content_section_title"><h4><?php echo TEXT_SELECT_DEPARTMENT_AND_CATEGORY; ?></h4></div>			
														
														<table width="100%" align="center" border="0"  cellpadding="0" cellspacing="0" class="comm_tbl">
																<tr>
																	<td align ="left">
																	<?php 
																		echo makeDropDownList("ddlDepartment",makeDepartmentList(0,0,$_SESSION['sess_usercompid']),$ddlDepartment, "comm_input width1", "\" style=\"width:270px;\" \"", "onChange=\"javascript:changeDepartment();\"" );
																		echo "&nbsp;&nbsp;";
																		echo makeDropDownList("ddlCategory",makeCategoryList(0,0,$ddlDepartment),$ddlCategory, "comm_input width1", "\" style=\"width:200px;\" \"","onChange=\"javascript:changeCategory();\"" );
																	?>
																	
																	</td>
																</tr>
															</table>
															
															
																	
					<div class="content_search_container">
						<div class="left rightmargin topmargin">
						<?php echo TEXT_SEARCH ?>
						</div>
						
						<div class="left rightmargin">
						<select name="cmbSearch" class="selectstyle input_width1">
						<option value="title" <?php echo(($cmbSearch == "title" || $cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
						</select>
						</div>
						<div class="left">
						<input type="text" name="txtSearch" value="<?php echo(htmlentities($txtSearch)); ?>" class="inputstyle input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }">
						</div>
						
						<div class="left">
						<a href="javascript:clickSearch();"><img src="languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
						</div>
														
					<div class="clear"></div>
					</div>
																		
																		
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
																														<tr>
																															<td width="100%" colspan="3" class="fieldnames"><b>Sl no. &nbsp;<?php echo TEXT_TITLE ?></b></td>
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
																					
																														$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$cmbSearch&ddlCategory=" . urlencode($ddlCategory) . "&ddlDepartment=" . urlencode($ddlDepartment) . "&txtSearch=" . urlencode($txtSearch) . "&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&",$var_numBegin,$var_start,$var_begin,$var_num);
																														
																														$sql = $sql.$navigate[0];
																														//echo "<br>".$sql;
																														$rs = executeSelect($sql,$conn);
																														$_SESSION['sess_backurl']= "knowledgebase.php?mt=y&cmbSearch=$cmbSearch&ddlCategory=" . urlencode($ddlCategory) . "&ddlDepartment=" . urlencode($ddlDepartment) . "&txtSearch=" . urlencode($txtSearch) . "&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&numBegin=".$var_numBegin."&start=".$var_start."&begin=".$var_begin."&num=".$var_num;
																														$cnt = $var_begin+1;
																														while($row = mysql_fetch_array($rs)) {
																														?>
																												
																														<tr align="left"  class="whitebasic">
																															<td colspan="3"><?php echo $cnt ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;?>
																															<!--<a href='viewkbentry.php?id=<?php echo $row["nKBID"];?>&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&' class="listing">
																															<?php// echo trimString(htmlentities($row["vKBTitle"]),80); ?>
																															</a>  -->
                                                                                                                                                                                                                                                        <?php
                                                                                                                                                                                                                                                       // $viewkbentry_seo_link = "viewkbentry.php/".stripslashes($row['vKBTitle']). "/".$row["nKBID"];
                                                                                                                                                                                                                                                        $viewkbentry_seo_link = "viewkbentry/".stripslashes($row['vKBTitle']). "/".$row["nKBID"]."/KNOWLEDGEBASE/threeminus/threeplus";

                                                                                                                                                                                                                                                        //$viewkbentry_seo_link =stripslashes($row['vKBTitle']).".html";
                                                                                                                                                                                                                                                        ?>
																															<a href="<?php echo $viewkbentry_seo_link?>" class="listing"><?php echo trimString(htmlentities($row["vKBTitle"]),80); ?></a>
																															</td>
																														</tr>
																														<tr align="left"  class="whitebasic">
																															<td colspan="3"  height="1"><img src="images/spacerr.gif" width="1" height="1"></td>
																														</tr>
																														<?php
																															$cnt++;
																														}
																														mysql_free_result($rs);
																														?>
																														<tr align="left"  class="whitebasic">
																															<td colspan="1"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></td>
																															<td colspan="2"><?php echo($navigate[2]); ?>
																															</td>
																														</tr>
																														
																													</table>
																												
																									
																						
																	
												 
                   
    		<input type="hidden" name="numBegin" value="<?php echo $var_numBegin; ?>">
			<input type="hidden" name="start" value="<?php echo $var_start; ?>">
			<input type="hidden" name="begin" value="<?php echo $var_begin; ?>">
			<input type="hidden" name="num" value="<?php echo $var_num; ?>">   
			<input type="hidden" name="mt" value="y">
			<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
			<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
			<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
			<input type="hidden" name="id" value="<?php echo($var_id); ?>">
			<input type="hidden" name="postback" value="">
			</form>
</div>