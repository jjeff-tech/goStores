<?php
  $status_arr=array();
  $status_arr[0]="open";
  $status_arr[1]="closed";
  $status_arr[2]="escalated";
  $sql=" select vLookUpValue from sptbl_lookup where vLookUpName='ExtraStatus'";
  $rs = executeSelect($sql,$conn);
  if(mysql_num_rows($rs)>0){
    while($row=mysql_fetch_array($rs)){
	 array_push($status_arr,$row['vLookUpValue'] );
	} 
  }
  mysql_free_result($rs);
        if($var_deptid !="")
		    $lst_dept= $var_deptid;
		if($var_year =="0" and $var_month =="0")	{
		  $datefilter="";
		}else if($var_month =="0"){
		  $datefilter="$var_year"."-";
		}else if($var_year =="0"){
		  $datefilter="%"."-"."$var_month"."-";
		}else if($var_year !="" and $var_month !=""){
		  $datefilter="$var_year"."-"."$var_month";
		}else{
		  $datefilter=date("Y-m");
		}
		
		/*if($var_deptid !="")
		    $lst_dept= $var_deptid;
		if($var_year !="" and $var_month !="")	{
		  $datefilter="$var_year"."-"."$var_month";
		}else{
		  $datefilter=date("Y-m");
		}*/
		 
			
		$sql=" select count(*) as cnt from sptbl_tickets where nDeptid in($lst_dept) and  dPostDate like '$datefilter%' ";
		
    	$rs_cnt = executeSelect($sql,$conn);
		$row = mysql_fetch_array($rs_cnt);
		$totlnum_tickets=$row['cnt'];
		mysql_free_result($rs_cnt);
		
		$ticket_status_name=array();
		$ticket_value_cnt=array();
		$ticket_status_clr=array();
		foreach($status_arr as $key=>$value) {
		  $sql=" select count(*) as cnt from sptbl_tickets where  dPostDate like '$datefilter%' and nDeptid in($lst_dept) and vStatus='".mysql_real_escape_string($value)."'";
		  $rs_cnt = executeSelect($sql,$conn);
		  $row = mysql_fetch_array($rs_cnt);
		  
		  array_push($ticket_value_cnt,$row['cnt'] );
		  array_push($ticket_status_name,$value);
		   array_push($ticket_status_clr,colorCode());
		  
		  
		  mysql_free_result($rs_cnt);
		}  
		
?>

<table   border="0" cellpadding="0" cellspacing="3" width="100%" >
      <?php if($totlnum_tickets >0){ ?>
          <tr align="left"  class="toplinks" valign="bottom" > <td colspan=<?php echo count($ticket_value_cnt)*3 ?>>
		        <table border="0" cellpadding="0" cellspacing="2"  width="100%">
				 	  
		          <tr align="left"  class="toplinks" valign="bottom"><td colspan=<?php echo count($ticket_value_cnt)*3; ?> >
				  <table border="0" cellpadding="0" cellspacing="0"  >
				  
				           <tr align="left" >
                                      <td class="listingmaintext">&nbsp;</td>
                           </tr>
						   <tr >
						      <td  colspan=<?php echo count($ticket_value_cnt)*3 ?>>
							    <table border=0 width="100%" align=center>
								   <tr>
								     <td class="listingmaintext"><b><?php echo TEXT_TOTAL_TICKETS ?></b></td>
									 <td class="listingmaintext">&nbsp;<?php echo $totlnum_tickets ?> </td>
								   </tr>
								   <?php foreach($ticket_status_name as $key=>$value) { ?>
								       <tr>
									      <td class="listingmaintext" ><?php echo $value ?></td>
										  <td class="listingmaintext" >&nbsp;<?php echo $ticket_value_cnt[$key] ?></td>
										</tr>  
								  <?php } ?>			
								</table>
							  </td>
						   </tr>
						  <tr align="left" >
                                      <td  class="listingmaintext">&nbsp;</td>
                           </tr>
						    <tr>
									       <?php
											     foreach($ticket_status_name as $key=>$value) {
												   $ht=round((($ticket_value_cnt[$key])/$totlnum_tickets)*100);
												   
												   $ht_td=$ht;
											      
											 ?> 
							     		 <td valign="bottom"  >
								      
						                 <table border="0" cellpadding="0" cellspacing="0"  >
										 		<tr class="listingmaintext"><td valign="bottom" width="30"><?php echo $ht ?>%</td></tr>
											   <tr>
											       <?php if($ht==0){ ?>
												     <td><table width="30" border=1 cellpadding=0 cellspacing=0 height='<?php echo 2*$ht;?>' bgcolor="<?php echo $ticket_status_clr[$key]; ?>"><tr>
												     <td height='<?php echo 2*$ht;?>' width="30" valign="bottom"></td>
													 </tr></table></td>
												     
												   <?php } else { ?>
												     <td><table width="30" border=1 cellpadding=0 cellspacing=0 height='<?php echo 2*$ht;?>'><tr>
												     <td bgcolor="<?php echo $ticket_status_clr[$key]; ?>" height='<?php echo 2*$ht;?>' width="10" valign="bottom"></td>
													 </tr></table></td>
												   <?php } ?>
											        
							 				  </tr>
												
										
										 </table>
								        </td> 
										<td>&nbsp;</td> 
								<?php } ?>  
		          </tr>
				  </td></table></tr>
				  <tr align="left" >
                      <td colspan="5" class="listingmaintext">&nbsp;</td>
                 </tr>
				  <tr class="listingmaintext">
				      <?php foreach($ticket_status_name as $key=>$value) { ?>
		               
						  <td bgcolor="<?php echo $ticket_status_clr[$key]; ?>"  width="30">&nbsp;</td>
						   <td class="listingmaintext"><?php echo $value ?></td>
						   <td>&nbsp;</td>
					 <?php } ?>	
					 </tr>
		        </table>
		       </td></tr>	
          <tr align="left" >
            <td colspan="5" class="listingmaintext">&nbsp;</td>
          </tr>
		<?php } else {?>
		  <tr align="left" >
            <td colspan="5" class="listingmaintext">&nbsp;</td>
          </tr>
		  <tr align="left" >
            <td colspan="5" class="listingmaintext"><?php echo TEXT_NO_TICKETS; ?></td>
          </tr>
		  <tr align="left" >
            <td colspan="5" class="listingmaintext">&nbsp;</td>
          </tr>
		<?php } ?>
                                  
 </table>