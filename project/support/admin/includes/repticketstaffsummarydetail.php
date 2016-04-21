 <!--- report detail-->
			           <?php
					  $var_msdate= datetimetomysql($var_sdate,"/");
					  $var_medate =datetimetomysql($var_edate,"/");

					   /*$sql  =" select s.nStaffId,s.vLogin,count(r.nReplyId) as rpcnt,";
					   $sql .=" count(r.nReplyId)-count(r1.tPvtMessage) as cmtcnt,count(p.nStaffId) as pntcnt,";
					   $sql .=" sum(r3.vReplyTime)/count(r.nReplyId) as avgtimecnt from sptbl_staffs  s ";
					   $sql .=" inner join dummy d on (d.num<4) left join sptbl_replies r on(d.num=0 and";
					   $sql .=" s.nStaffId=r.nStaffId) left join sptbl_replies r1 on(d.num=1 and s.nStaffId=r1.nStaffId ";
					   $sql .=" and r1.tPvtMessage='') left join sptbl_personalnotes p on(d.num=2 and ";
					   $sql .=" s.nStaffId=p.nStaffId ) left join sptbl_replies r3 on(d.num=3 and s.nStaffId=r3.nStaffId and r3.vReplyTime>0)";*/

					   $sql  =" select s.nStaffId,s.vLogin,count(r.nReplyId) as rpcnt,";
					   $sql .=" count(r.nReplyId)-count(r1.tPvtMessage) as cmtcnt,count(p.nStaffId) as pntcnt,";
					   $sql .=" sum(r3.vReplyTime)/count(r.nReplyId) as avgtimecnt from sptbl_staffs  s ";
					   $sql .=" inner join dummy d on (d.num<4) left join sptbl_replies r on(d.num=0 and";
					   $sql .=" s.nStaffId=r.nStaffId and (r.dDate>='$var_msdate' and r.dDate<='$var_medate')) left join sptbl_replies r1 on(d.num=1 and s.nStaffId=r1.nStaffId ";
					   $sql .=" and r1.tPvtMessage='' and (r1.dDate>='$var_msdate' and r1.dDate<='$var_medate')) left join sptbl_personalnotes p on(d.num=2 and ";
					   $sql .=" s.nStaffId=p.nStaffId and  (p.dDate>='$var_msdate' and p.dDate<='$var_medate') ) left join sptbl_replies r3 on(d.num=3 and s.nStaffId=r3.nStaffId and r3.vReplyTime>0 and (r3.dDate>='$var_msdate' and r3.dDate<='$var_medate'))";

					   if($var_staffcmbid >0){
					    $sql .= " where s.nStaffId='".addslashes($var_staffcmbid)."' ";
					   }else{
					      $sql .=" where s.nStaffId>0 ";
					   }
					   $sql .=" group by(s.nStaffId);";

					   //echo "sql==$sql <br>";
					  /* $sql =" Select t.nTicketId,t.nDeptId,t.vUserName,t.vTitle,t.vRefNo,t.dPostDate,t.tQuestion,";
                       $sql .="t.vPriority,t.vStatus,t.nOwner, t.nLockStatus,t.vMachineIp,t.vStaffLogin,d.vDeptDesc,c.vCompName from sptbl_tickets t";
                       $sql .=" inner join sptbl_depts d on t.nDeptId = d.nDeptId inner join sptbl_companies c on c.nCompId=d.nCompId  ";
                       $sql .=" where  (t.dPostDate >='".addslashes($var_msdate)."'";
                       $sql .=" and   t.dPostDate<='". addslashes($var_medate)."') ";
                        echo "sql==".$sql; */



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
			                   <td  colspan="5" align="center"><?php echo TEXT_LBL1."&nbsp;<b>".$var_sdate."</b>&nbsp;".TEXT_LBL2."&nbsp;<b>".$var_edate."</b>";?></td>
			                 </tr>
						 <tr class="listing"><td colspan="5" align=center>&nbsp;</td></tr>
						     <tr class="heading">
									   <td ><?php echo TEXT_STAFF; ?> </td>
									  <td align=right> <?php echo TEXT_REPLIES; ?></td>
									  <td align=right> <?php echo TEXT_COMMENTS; ?></td>
									  <td align=right> <?php echo TEXT_PNNOTES; ?></td>
									  <td align=right> <?php echo TEXT_AVGREPTIME; ?></td>
						   </tr>
				             <?php while($row=mysql_fetch_array($rs)){
							  if($row['avgtimecnt']=="")
							     $row['avgtimecnt']="0.00";
							  ?>
						        <tr class="listing">
									   <td align=left><?php echo $row['vLogin'];?></td>
									  <td align=right> <?php echo $row['rpcnt'];?></td>
									  <td align=right> <?php echo $row['cmtcnt'];?></td>
									  <td align=right> <?php echo $row['pntcnt'];?></td>
									  <td align=right> <?php echo $row['avgtimecnt'];?></td>
									</tr>
							 <?php } ?>
		                     </table>
						   </div>
						    </td>
						 </tr>

				  <?php } else { ?>
					  <tr><td><?php echo   TEXT_NO_RECORDS?></td></tr>
				<?php } ?>

				     <?php  if(mysql_num_rows($rs)>0){ ?>
						  <tr>
			                <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
			             </tr>
					  <?php } ?>