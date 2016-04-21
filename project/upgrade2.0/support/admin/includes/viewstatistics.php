<?php
           $var_deptid = $_POST["cmbDepartment"];
		   $var_month = $_POST["cmbMonth"];
		   $var_year = $_POST["cmbYear"];
	
	      if($var_year ==""){
		     $var_year=date("Y"); 
		   }
	       if($var_month ==""){
		     $var_month=date("m"); 
		   }
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
	   
        $var_staffid = $_SESSION["sess_staffid"];
        $lst_dept = "'',";
        $sql = "Select nDeptId from sptbl_depts";
        $rs_dept = executeSelect($sql,$conn);
        if (mysql_num_rows($rs_dept) > 0) {
                while($row = mysql_fetch_array($rs_dept)) {
                        $lst_dept .= $row["nDeptId"] . ",";
                }
        }
        $lst_dept = substr($lst_dept,0,-1);
       
        mysql_free_result($rs_dept);
	

?>
<div class="content_section">
<form name="frmTicketStatistics" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<Div class="content_section_title"><h3><?php echo TEXT_STATISTICS ?></h3></Div>
   <Div class="content_section_data">               
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tr><td colspan="4">&nbsp;</td></tr>
													<tr>
														<td width="10%">&nbsp;</td>
														<td width="39%"  align="left">
														<select name="cmbDepartment" class="comm_input input_width1" onChange="javascript:changeDepartment();" style="width:220px; ">
                              										<option value=""><?php echo(TEXT_DEPT_FILTER);?></option>
										                               <?php
																	  	$sql = "Select d.nDeptId,CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')')) as 'description' from 
																		sptbl_depts d  inner join sptbl_companies c
																		 on d.nCompId = c.nCompId   ";
																		
																		$rs_dept = executeSelect($sql,$conn);
																		if (mysql_num_rows($rs_dept) > 0) {
																			while($row = mysql_fetch_array($rs_dept)) {
																		?>
																							<option value="<?php echo($row["nDeptId"]); ?>" <?php echo(($row["nDeptId"] == $var_deptid)?"Selected":"ww"); ?>><?php echo(htmlentities($row["description"])); ?></option>
																		 <?php	
																					}
																			}
							 											 ?>
                           								 </select>
	
														</td>
													   <td width="23%"  align="left">
														<select name="cmbMonth" class="comm_input input_width1" onChange="javascript:changeMonth();" style="width:100px ">
														        <option value="0"><?php echo TEXT_ALL ?></option>	
                              									<?php 
																$yr = date("Y");
																for($i=1;$i<=12;$i++) {
																  if($i<10)
																    $j="0".$i;
																  else
																    $j=$i;	
																 ?>
                              										<option value="<?php echo $j ?>" <?php if($var_month==$j) echo "selected";?>><?php echo date("M",mktime("0","0","0","$i","1","$yr")) ?></option>	
														            <?php } ?>	
                           								 </select>
	
														</td>
														<td width="28%"  align="left">
														<select name="cmbYear" class="comm_input input_width1" onChange="javascript:changeYear();" style="width:100px">
														            <option value="0"><?php echo TEXT_ALL ?></option>	
														            <?php for($i=2005;$i<=2060;$i++) { ?>
                              										<option value="<?php echo $i ?>" <?php if($var_year==$i) echo "selected";?>><?php echo $i ?></option>	
														            <?php } ?>			
                           								 </select>
	
														</td>
												    </tr>
												</table>
											</td>
										</tr>	
										<tr><td class="bodycolor">&nbsp;</td></tr>
									    <tr>
                                          <td class="bodycolor" align=center >
										     <fieldset style="width:700px">
											 <legend class="listingmaintext" ><b><?php 
														
															if($var_year =="0" and $var_month =="0"){
															  $datedisp="";
															  $for_or_till=TICKET_STATISTICS_2."&nbsp;".date("j S F Y");
															}else if($var_month =="0"){
															  $datedisp=date("Y",mktime("0","0","0","1","1","$var_year"));
															  $for_or_till=TICKET_STATISTICS_1;
															}else if($var_year =="0"){
															  $datedisp=date("F",mktime("0","0","0","$var_month","1","0"));
															   $for_or_till=TICKET_STATISTICS_1;
															}else if($var_year !="" and $var_month !=""){
															  $datedisp=date("F Y",mktime("0","0","0","$var_month","1","$var_year") );
															   $for_or_till=TICKET_STATISTICS_1;
															}else{
															  $datedisp=date("Y-F");
															   $for_or_till=TICKET_STATISTICS_1;
															}
											  echo TICKET_STATISTICS."&nbsp;".$for_or_till."&nbsp;";echo "&nbsp;&nbsp;";echo $datedisp ;?>
											  
											  </b></legend>
										     <?php require("./includes/staticsdetail.php"); ?>
											 </fieldset>
										  </td>
                                        </tr>
										
                                    </table>
                  
          <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">	
</form>
</div>
</div>