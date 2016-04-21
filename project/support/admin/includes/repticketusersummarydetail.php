<!--- report detail-->
<?php
$var_msdate= datetimetomysql($var_sdate,"/");
$var_medate =datetimetomysql($var_edate,"/");

/*Query modified buy amaldev on 070709 since the rating was implemented for chatting also.  starts*/
/*
					  $sql  =" select u.nUserId,u.vLogin,count(t.nTicketId) as tkcnt,count(r1.nUserId) as rpcnt,count(sr.nUserId)";
					  $sql .=" as rtcnt,count(fd.nTicketId) as fdcnt from sptbl_users u inner join dummy d on (d.num<4) left join sptbl_tickets t";
					  $sql .=" on(d.num=0 and u.nUserId=t.nUserId and (t.dPostDate>='$var_msdate' and t.dPostDate<='$var_medate'))";
					  $sql .=" left join sptbl_replies r1 on(d.num=1 and u.nUserId=r1.nUserId and (r1.dDate>='$var_msdate' and r1.dDate<='$var_medate'))";
					  $sql .=" left join sptbl_staffratings sr on(d.num=2 and u.nUserId=sr.nUserId ) ";
					  $sql .=" left join sptbl_tickets t2 on(d.num=3 and u.nUserId=t2.nUserId and (t2.dPostDate>='$var_msdate' and t2.dPostDate<='$var_medate'))";
					  $sql .=" left join sptbl_feedback fd on(d.num=3 and t2.nTicketId=fd.nTicketId and (fd.dDate>='$var_msdate' and fd.dDate<='$var_medate'))";
					  if($var_usercmbid >0){
					    $sql .= " where u.nUserId='".addslashes($var_usercmbid)."' ";
					   }else{
					   $sql .=" where u.nUserID>0 ";
					   }
					  $sql .=" group by(u.nUserId) ";
*/
$sql  =" select u.nUserId,u.vLogin,count(t.nTicketId) as tkcnt,count(r1.nUserId) as rpcnt,count(sr.nUserId)";
$sql .=" as rtcnt,count(fd.nTicketId) as fdcnt from sptbl_users u inner join dummy d on (d.num<4) left join sptbl_tickets t";
$sql .=" on(d.num=0 and u.nUserId=t.nUserId and (t.dPostDate>='$var_msdate' and t.dPostDate<='$var_medate'))";
$sql .=" left join sptbl_replies r1 on(d.num=1 and u.nUserId=r1.nUserId and (r1.dDate>='$var_msdate' and r1.dDate<='$var_medate'))";
$sql .=" left join sptbl_staffratings sr on(d.num=2 and u.nUserId=sr.nUserId ) ";
$sql .=" left join sptbl_tickets t2 on(d.num=3 and u.nUserId=t2.nUserId and (t2.dPostDate>='$var_msdate' and t2.dPostDate<='$var_medate'))";
$sql .=" left join sptbl_feedback fd on(d.num=3 and t2.nTicketId=fd.nTicketId and (fd.dDate>='$var_msdate' and fd.dDate<='$var_medate'))";
$sql .=" where sr.vType='T'";
if($var_usercmbid >0) {
    $sql .= " and u.nUserId='".addslashes($var_usercmbid)."' ";
}else {
    $sql .=" and u.nUserID>0 ";
}
$sql .=" group by(u.nUserId) ";
//echo "sql==$sql <br>";

/* ends */
$rs = executeSelect($sql,$conn);
?>

<?php  if(mysql_num_rows($rs)>0) { ?>
<tr>
    <td  colspan="5" align="center"><a href="javascript:printSpecial();">Print</a></td>
</tr>


<tr><td width="100%">
        <div id="printReady">
            <table width="100%" border=0 cellspacing=1 cellpadding=2 class="column1">
                <tr class="listing">
                    <td  colspan="5" align="center" class="listingmaintext"><?php echo TEXT_LBL1."&nbsp;<b>".$var_sdate."</b>&nbsp;".TEXT_LBL2."&nbsp;<b>".$var_edate."</b>";?></td>
                </tr>
                <tr class="listing"><td colspan="5" align=center>&nbsp;</td></tr>
                <tr class="heading">
                    <td><?php echo TEXT_USER; ?> </td>
                    <td align=right> <?php echo TEXT_TICKETS; ?></td>
                    <td align=right> <?php echo TEXT_REPLIES; ?></td>
                    <td align=right> <?php echo TEXT_FEEDBACKS; ?></td>
                    <td align=right> <?php echo TEXT_STAFF_RATINGS; ?></td>
                </tr>
                    <?php while($row=mysql_fetch_array($rs)) {  ?>
                <tr class="listing">
                    <td><?php echo htmlentities($row['vLogin']);?></td>
                    <td align=right> <?php echo $row['tkcnt'];?></td>
                    <td align=right> <?php echo $row['rpcnt'];?></td>
                    <td align=right> <?php echo $row['fdcnt'];?></td>
                    <td align=right> <?php echo $row['rtcnt'];?></td>
                </tr>
                        <?php } ?>
            </table>
        </div>
    </td></tr>

    <?php } else { ?>
<tr  class="listing"><td>No records</td></tr>
    <?php } ?>
<?php  if(mysql_num_rows($rs)>0) { ?>
<tr>
    <td  colspan="5" align="center"><a href="javascript:printSpecial();">Print</a></td>
</tr>
    <?php } ?>