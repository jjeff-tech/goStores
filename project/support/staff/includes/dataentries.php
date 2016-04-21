<?php //print_r($_SESSION);

$kbsql = "SELECT nKBID FROM sptbl_kb skb INNER JOIN sptbl_categories spc ON spc.nCatId=skb.nCatId INNER JOIN sptbl_depts spd ON
    spd.nDeptId=spc.nDeptId INNER JOIN sptbl_staffdept ssdp ON ssdp.nDeptId=spd.nDeptId WHERE ssdp.nStaffId=".$_SESSION['sess_staffid'];
$kbcount=mysql_query($kbsql);
$kbcount=mysql_num_rows($kbcount);

$sql_ticket_details ="SELECT count(nTicketId) AS  todaysticketcount,
                     (SELECT count(nTicketId) FROM sptbl_tickets
                     WHERE date_format(dPostDate,'%Y-%m-%d')>=(CURDATE() - INTERVAL 3 DAY)  AND nDeptId
                     IN(SELECT nDeptId FROM sptbl_staffdept WHERE nStaffId='".$_SESSION['sess_staffid']."')
                     AND vStatus!='closed')
                     AS last3daysticketcount,
                     (SELECT count(nTicketId) FROM sptbl_tickets
                     WHERE date_format(dPostDate,'%Y-%m-%d')>=(CURDATE() - INTERVAL 10 DAY)  AND nDeptId
                     IN(SELECT nDeptId FROM sptbl_staffdept WHERE nStaffId='".$_SESSION['sess_staffid']."')
                     AND vStatus!='closed')
                     AS last10daysticketcount,
                     (SELECT count(Distinct(nTicketId))
                     FROM  sptbl_replies
                     WHERE nStaffId='".$_SESSION['sess_staffid']."'
                     AND date_format(dDate,'%Y-%m-%d')=CURDATE() )
                     AS repliedCount 
                     FROM sptbl_tickets
                     WHERE date_format(dPostDate,'%Y-%m-%d')=CURDATE()
                      AND nDeptId
                     IN(SELECT nDeptId FROM sptbl_staffdept WHERE nStaffId='".$_SESSION['sess_staffid']."')
                     AND vStatus!='closed'";
$res_ticket_details = executeSelect($sql_ticket_details,$conn);
$row_ticket_details=mysql_fetch_array($res_ticket_details);
//echo"<pre>";print_r($row_ticket_details);echo"</pre>";

?>          
<div class="left_item_block">
    <div class="left_item_title"><?php echo TEXT_FIELDS_DATA_ENTRIES ?></div>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
        <tr>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_TICKETS;?></td><td width="26%" bgcolor="#FFFFFF"><?php echo "<b>".$tot_tickets."</b>"; ?></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_KNOWLEDGE_BASE; ?></td><td bgcolor="#FFFFFF"><?php echo "<b>".$kbcount."</b>"; ?></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_CORRESPONDENCE; ?></td><td bgcolor="#FFFFFF"><?php echo "<b>".$tot_corespondence."</b>"; ?></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_TODAYS_TICKET; ?></td><td bgcolor="#FFFFFF"><?php echo "<b>".$row_ticket_details['todaysticketcount']."</b>"; ?></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_THREE_DAYS_TICKET; ?></td><td bgcolor="#FFFFFF"><?php echo "<b>".$row_ticket_details['last3daysticketcount']."</b>"; ?></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_TEN_DAYS_TICKET; ?></td><td bgcolor="#FFFFFF"><?php echo "<b>".$row_ticket_details['last10daysticketcount']."</b>"; ?></td>
        </tr>
        <tr>
           <?php $row_ticket_details['repliedCount']=($row_ticket_details['repliedCount']>0)?$row_ticket_details['repliedCount']:'0';?>
            <td bgcolor="#FFFFFF" width="74%"><?php echo TEXT_REPLIED_TICKET; ?></td><td bgcolor="#FFFFFF"><?php echo "<b>".$row_ticket_details['repliedCount']."</b>"; ?></td>
        </tr>

    </table>

    <?php
    if($tot_tickets > $_SESSION["sess_totaltickets"] ) {

        echo"
                 <script language=JavaScript>
                         playSound()
                 </script>
                 ";
        $_SESSION["sess_totaltickets"]=$tot_tickets;

        echo " <object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' width='0' height='0'>
      <param name='movie' value='./../sound/SOS.swf'>
      <param name='quality' value='low'>
      <embed src='./../sound/SOS.swf' quality='low' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='0' height='0'></embed>
    </object>";
    }
    ?>


</div>