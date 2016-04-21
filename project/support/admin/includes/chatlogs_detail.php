<!--- report detail-->
<?php
$var_msdate= datetimetomysql($var_sdate,"/");
$var_medate =datetimetomysql($var_edate,"/");
/*				   $sql =" Select c.nChatId,c.dTimeStart,c.dTimeEnd,u.vUserName,s.vStaffname,u.vEmail";
                       $sql .=" from sptbl_chat c";
                       $sql .=" inner join sptbl_users u on c.nUserId = u.nUserId inner join sptbl_staffs s on c.nStaffId=s.nStaffId  ";
                       $sql .=" where  (c.dTimeStart >='".mysql_real_escape_string($var_msdate)."'";
                       $sql .=" and   c.dTimeStart <='". mysql_real_escape_string($var_medate)."') ";
                        //echo "sql==".$sql;
					   if($var_staffid >0){
					    $sql .= " and c.nStaffId='".mysql_real_escape_string($var_staffid)."'";
					   }
					    $sql .="  order by c.dTimeStart desc ";
*/

$sql1 ="Select c.nChatId,c.dTimeStart,c.dTimeEnd,u.vUserName as user_staff,s.vStaffname as staffname,u.vEmail as user_staff_email, s.vMail as staff_email, 'c' as chat_flg from sptbl_chat c inner join sptbl_users u on c.nUserId = u.nUserId inner join sptbl_staffs s on c.nStaffId=s.nStaffId 
        					where  (c.dTimeStart >='".mysql_real_escape_string($var_msdate)."' and   c.dTimeStart <='". mysql_real_escape_string($var_medate)."') ";
//echo "sql==".$sql;
if($var_staffid >0) {
    $sql1 .= " and c.nStaffId='".mysql_real_escape_string($var_staffid)."'";
}
if($var_kwd !="") {
    $sql1 .= " and c.tMatter like '%".mysql_real_escape_string($var_kwd)."%'";
}
$sql2 ="Select o.nChatId,o.dTimeStart,o.dTimeEnd,(select vStaffname from sptbl_staffs where nStaffId=o.nFirstStaffID) as user_staff,(select vStaffname from sptbl_staffs where nStaffId=o.nSecondStaffID) as staffname, ( select  vMail from sptbl_staffs where nStaffId=o.nFirstStaffID ) as user_staff_email,( select vMail from sptbl_staffs where nStaffId=o.nSecondStaffID ) as staff_email,'o' as chat_flg  from sptbl_operatorchat o  
        				  where  (o.dTimeStart >='".mysql_real_escape_string($var_msdate)."' and   o.dTimeStart <='". mysql_real_escape_string($var_medate)."') ";
//echo "sql==".$sql;
if($var_staffid >0) {
    $sql2 .= " and ( o.nFirstStaffID='".mysql_real_escape_string($var_staffid)."' or  o.nSecondStaffID='".mysql_real_escape_string($var_staffid)."')";
}
if($var_kwd !="") {
    $sql2 .= " and o.tMatter like '%".mysql_real_escape_string($var_kwd)."%'";
}
$sql = $sql1." UNION ".$sql2." order by dTimeStart desc"; 
$rs = executeSelect($sql,$conn); 
?>
<?php  if(mysql_num_rows($rs)>0) { ?>
<tr><td width="100%">
        <div id="printReady">
            <table width="100%" border=0 class="list_tbl">
                <tr>
                    <td  colspan="5" align="center" class="listingmaintext"><?php echo TEXT_LBL1."&nbsp;<b>".$var_sdate."</b>&nbsp;".TEXT_LBL2."&nbsp;<b>".$var_edate."</b>";?></td>
                </tr>
                <tr><td colspan="5" align=center>&nbsp;</td></tr>

                <tr class="heading">
                    <th><?php echo TEXT_START_TIME; ?> </th>
                    <th > <?php echo TEXT_END_TIME; ?></th>
                    <th > <?php echo TEXT_USER_STAFF; ?></th>
                    <th > <?php echo TEXT_STAFF; ?></th>
                    <th > <?php echo TEXT_ACTION; ?></th>
                </tr>
                    <?php while($row=mysql_fetch_array($rs)) {
                        ?>
                <tr class="listing">
                    <td><?php echo $row['dTimeStart'];?></td>
                    <td> <?php if ($row['dTimeEnd'] =='0000-00-00 00:00:00') echo "Not Completed"; else echo $row['dTimeEnd'];?></td>
                    <td> <?php echo $row['user_staff'];?></td>
                    <td> <?php echo $row['staffname'];?></td>
                    <td><a href="javascript:viewChatLog(<?php echo $row['nChatId']?>,'<?php echo $row['chat_flg']?>');"><?php echo TEXT_VIEW_LOG;?></a></td>
                </tr>
                        <?php } ?>


                    <?php   } else { ?>
                <tr><td><?php echo   TEXT_NO_RECORDS?></td></tr>
                    <?php } ?>

            </table>
        </div>
