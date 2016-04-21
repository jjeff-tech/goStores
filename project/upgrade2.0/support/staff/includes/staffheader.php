<!--<table width="100%"  border="1" cellspacing="0" cellpadding="0">
	  <tr><td colspan="5"><img src="../images/spacer.gif" width="0" height="4"></td></tr>
      <tr>
        <td width="7%" align="center" valign="middle">
				<?php if($_SESSION['newticket_msg_alert']==1) {?>
                    <A href="newtickets.php"  class="listing" style="text-decoration:none; "><img src="./../images/button1.gif" border="0"></a>
				<?php }?>
		</td>
		<td width="9%" align="center" valign="middle">
				<?php if($_SESSION['pvt_msg_alert']==1) {?>
                    <A href="pvtmessages.php"  class="listing" style="text-decoration:none; "><img src="./../images/button2.gif" border="0"></a>
				<?php }?>
		</td>
        <td width="84%" valign="middle"  align="right">
                
       </td>
      </tr>
  	  <tr>
	  	<td  class="newticketimg" valign="top">&nbsp;
				<?php if($_SESSION['newticket_msg_alert']==1) { ?>
                    <A href="newtickets.php" class="newticketimg" style="text-decoration:none; "><?php echo HEADING_TICKETS_NEW;?></a>
			    <?php }?>
		</td>
		<td class="newticketimg" valign="top">&nbsp;
				<?php if($_SESSION['pvt_msg_alert']==1) {  ?>
                    <A href="pvtmessages.php" class="newticketimg" style="text-decoration:none; "><?php echo HEADING_STAFF_NEW_MESSAGE;?></a>
				<?php }?>
		</td>
	  <td>&nbsp;</td>
  	  </tr>
</table>-->