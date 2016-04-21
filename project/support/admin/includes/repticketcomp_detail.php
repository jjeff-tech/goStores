 <!--- report detail-->
			           <?php
					  $var_msdate= datetimetomysql($var_sdate);
					  $var_medate =datetimetomysql($var_edate);
					
					   $sql =" Select t.nTicketId,t.nDeptId,t.vUserName,t.vTitle,t.vRefNo,t.dPostDate,t.tQuestion,";
                       $sql .="t.vPriority,t.vStatus,t.nOwner, t.nLockStatus,t.vMachineIp,t.vStaffLogin,d.vDeptDesc,c.vCompName from sptbl_tickets t";
                       $sql .=" inner join sptbl_depts d on t.nDeptId = d.nDeptId inner join sptbl_companies c on c.nCompId=d.nCompId  ";
                       $sql .=" where  (t.dPostDate >='".addslashes($var_msdate)."'";
                       $sql .=" and   t.dPostDate<='". addslashes($var_medate)."') ";
                        //echo "sql==".$sql;
					   if($var_companyid >0){
					    $sql .= " and d.nCOmpId='".addslashes($var_companyid)."'";
					   }
					   $sql .="  order by c.vCompName asc,d.vDeptDesc,t.vStatus asc ";
                
					    $rs = executeSelect($sql,$conn); 
					   ?>
					  <?php  if(mysql_num_rows($rs)>0){ ?>
						  <tr>
			                <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
			             </tr>
					  <?php } ?>   
			       <tr><td width="100%">
				   <div id="printReady">
			       <table width="100%" border=0>
				             <tr>
			                   <td  colspan="5" align="center" class="listingmaintext"><?php echo TEXT_LBL1."&nbsp;<b>".$var_sdate."</b>&nbsp;".TEXT_LBL2."&nbsp;<b>".$var_edate."</b>";?></td>
			                 </tr>
				            <tr><td colspan="3" align=center>&nbsp;</td></tr> 
					 
				   <?php
				          
						  $cur_cmp_name="";
						  $cur_dept_name="";
						  $cur_status="";
						  $cnt=0;
						  $statuscnt=0;
						  $cut_status_cnt=0;
						  
				      if(mysql_num_rows($rs)>0){
					    while($row=mysql_fetch_array($rs)){
						  $cnt++;
						  $cmp_flag=0;
						  $dept_flag=0;
						  $status_flag=0;
						  if(strcmp($cur_cmp_name,$row['vCompName'])!=0 or $cnt==1){
						         $cur_cmp_name=$row['vCompName'];
								 $cmp_flag=1;
								 $cmp_displayed=0;
						  }else{
						    $cmp_displayed=1;
						  }
						  if(strcmp($cur_dept_name,$row['vDeptDesc'])!=0 or $cnt==1){
						         $cur_dept_name=$row['vDeptDesc'];
								 $dept_flag=1;
								 $dept_displayed=0;
						  }else{
						    $dept_displayed=1;
						  }	
						  if(strcmp($cur_status,$row['vStatus'])!=0 or $cnt==1){
						         $cur_status=$row['vStatus'];
								 $status_flag=1;
								 $cut_status_cnt=1;
								 $status_displayed=0;
							     $sqlstatuscount="select vStatus from sptbl_tickets where nDeptId='".$row['nDeptId']."'and  vStatus='$cur_status'";
                                 $rs1 = executeSelect($sqlstatuscount,$conn); 
								$cut_status_cnt=mysql_num_rows($rs1);
						  }else{
						    $status_displayed=1;
						  }		 
						
						 if($cmp_displayed==1 and $dept_displayed==1 and $status_displayed==1){
						 			   continue;
						 }
						
				   ?>	  
					               
							       <?php if($cmp_flag==1) { ?>
							        <tr class="attachband">
									   <td colspan=3><?php echo TXT_COMPANY_NAME ;?>&nbsp;:<?php echo htmlentities($cur_cmp_name);?></td>
									  
									</tr> 
									<?php } if($dept_flag==1) {?>
									  <tr class="commentband">
									   <td colspan=3 ><?php echo TXT_DEPT_NAME ;?>&nbsp;:<?php echo htmlentities($cur_dept_name);?></td>
									   
									</tr> 
									 <tr><td colspan=3>&nbsp;</td></tr>
									<?php }  ?>
									   <tr class="listingmaintext">
									   <td width="50%">&nbsp;</td>
									   <td ><?php echo $cur_status ?></td>
									   <td>&nbsp;&nbsp;&nbsp;<?php echo $cut_status_cnt ?></td>
									  </tr>
									
									
									
								
									
						
					  
				 <?php  } //end of while  ?> 
				     
				  <?php   } else { ?>
					  <tr><td><?php echo   TEXT_NO_RECORDS?></td></tr>	 
				<?php } ?>	  
				     
				   </table>
			      </div>
			
			         <?php  if(mysql_num_rows($rs)>0){ ?>
						  <tr>
			                <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
			             </tr>
					  <?php } ?>