 <!--- report detail-->
			           <?php
					  $var_msdate= datetimetomysql($var_sdate);
					  $var_medate =datetimetomysql($var_edate);
/*Query modified buy amaldev on 070709 since the rating was implemented for chatting also.  starts*/					   
					   /*$sql  =" Select s.nStaffId,s.vLogin,count(r.nStaffId) as replies,round(avg(vReplyTime),2) as 'reply',
								round(avg(sr.nMarks),2) as 'ratings',count(distinct(r.nTicketId)) as 'tickets' from sptbl_staffs s 
								inner join dummy d on(d.num<2)
								left outer join sptbl_replies r on(d.num=0 and s.nStaffId=r.nStaffId and
								r.dDate >='$var_msdate' and r.dDate <= '$var_medate')
								left outer join sptbl_staffratings sr on(d.num=1 and s.nStaffId=sr.nStaffId)";*/
					  $sql  =" Select s.nStaffId,s.vLogin,count(r.nStaffId) as replies,round(avg(vReplyTime),2) as 'reply',
								round(avg(sr.nMarks),2) as 'ratings',count(distinct(r.nTicketId)) as 'tickets' from sptbl_staffs s 
								inner join dummy d on(d.num<2)
								left outer join sptbl_replies r on(d.num=0 and s.nStaffId=r.nStaffId and
								r.dDate >='$var_msdate' and r.dDate <= '$var_medate')
								left outer join sptbl_staffratings sr on(d.num=1 and s.nStaffId=sr.nStaffId) where sr.vType='T'";
					  
					   if($var_staffcmbid >0){
					   // $sql .= " where s.nStaffId='".addslashes($var_staffcmbid)."' ";
					     $sql .= " and s.nStaffId='".addslashes($var_staffcmbid)."' ";
					   }
					   $sql .=" group by s.nStaffId order by s.vLogin";
/*ends*/					   
					    $rs = executeSelect($sql,$conn); 
					   ?>
					    
					  <?php  if(mysql_num_rows($rs)>0){ ?>
						  <tr>
			                <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
			             </tr>
						 
					     
			             <tr><td width="100%">
				         <div id="printReady">
			               <table width="100%" border=0 cellspacing=1 cellpadding=2 class="column1">
						     <tr class="listing">
			                   <td  colspan="5" align="center" class="fieldnames"><?php echo TEXT_LBL1."&nbsp;<b>".$var_sdate."</b>&nbsp;".TEXT_LBL2."&nbsp;<b>".$var_edate."</b>";?></td>
			                 </tr>
						 <tr class="listing"><td colspan="5" align=center>&nbsp;</td></tr> 
						     <tr class="heading">
									   <td><?php echo TEXT_STAFF; ?> </td>
									  <td align="right"> <?php echo TEXT_ATTENDED; ?></td>
									  <td align="right"> <?php echo TEXT_REPLIES; ?></td>
									  <td align="right"> <?php echo TEXT_TIME; ?></td>
									  <td align="right"> <?php echo TEXT_RATINGS; ?></td>
						   </tr> 
				             <?php while($row=mysql_fetch_array($rs)){
							  ?>
						        <tr class="listing">
									   <td><?php echo $row['vLogin'];?></td>
									  <td align="right"> <?php echo ((!is_null($row['tickets']))?$row['tickets']:"0.00");?></td>
									  <td align="right"> <?php echo ((!is_null($row['replies']))?$row['replies']:"0.00");?></td>
									  <td align="right"> <?php echo ((!is_null($row['reply']))?$row['reply']:"0.00");?></td>
									  <td align="right"> <?php echo ((!is_null($row['ratings']))?$row['ratings']:"0.00");?></td>
									</tr> 
							 <?php } ?>		
		                     </table>
						   </div>
						    </td>
						 </tr>  	     
				     
				  <?php } else { ?>
					  <tr><td align="center"><?php echo   TEXT_NO_RECORDS?></td></tr>	 
				<?php } ?>	  
		         <?php  if(mysql_num_rows($rs)>0){ ?>
						  <tr>
			                <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
			             </tr>
					  <?php } ?>