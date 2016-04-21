<?php
                    $user_id             =   $_GET['uid'];
                    $ticket_id           =   $_GET['ticket_id'];
                    $sqlTicketDetails    =  "SELECT * FROM sptbl_tickets AS tickets WHERE nTicketId='".$ticket_id."'";
                    $resTicketDetails    =   executeSelect($sqlTicketDetails,$conn);
                    $row_ticket          =   mysql_fetch_object($resTicketDetails);?>
                    <div align="left">
		<div class="content_section_title" style="padding-top: 20px;margin-left: 14px">
                        <table cellpadding="0" cellspacing="0" border="0" class="comm_tbl2" width="100%">
                                <tr align="left">
                                    <td  width="16%" style="word-break:break-all; " align="left"><b><?php echo TEXT_REFERENCE_NO; ?></b></td>
                                    <td align="left"><?php echo ":". $row_ticket->vRefNo; ?> </td>
                                </tr>
                                 <tr>
                                 <td  width="15%" align="left"><b><?php echo TEXT_TICKET_TITLE; ?></b></td>
                            <td align="left">: <?php echo htmlentities(stripslashes($row_ticket->vTitle)); ?></td>
                                 </tr>
                                  <tr>
                            <td align="left" valign="top"><b><?php echo TEXT_DESC; ?></b></td>
                            <td align="left"><?php echo nl2br(stripslashes($row_ticket->tQuestion)); ?></td>
                        </tr>
                    </table></div>
					</div>
                    <?php
                    $sqlGetTicketStaffs  =    "SELECT distinct(replies.nStaffId),staffs.vStaffname  FROM  sptbl_replies AS replies
                                              INNER JOIN  sptbl_staffs AS staffs ON  staffs.nStaffId=replies.nStaffId
                                              WHERE nTicketId ='".$ticket_id."' AND (replies.nStaffId!='NULL' OR replies.nStaffId!='0' OR replies.nStaffId!='')";
                    $resGetTicketStaffs  = executeSelect($sqlGetTicketStaffs,$conn);
                    $count=1;
                    if (mysql_num_rows($resGetTicketStaffs) > 0) {
                        while($row_staff=mysql_fetch_array($resGetTicketStaffs)) {?>
                    <div style="padding:10px; border:1px solid #cfcfcf; margin:10px;" id="jqRateProduct_<?php echo $row_staff['nStaffId']?>"><?php echo TEXT_STAFF_NAME .": <strong>".$row_staff['vStaffname']." </strong> - "; ?>
                        <?php $sqlratingexist  =  "SELECT nSRId FROM sptbl_staffratings
                                                 WHERE nUserId ='".$user_id."'
                                                 AND nStaffId ='".$row_staff['nStaffId']."'
                                                 AND nTicketId ='".$ticket_id."'";
                              $resratingexist  =   executeSelect($sqlratingexist,$conn);
                            if(mysql_num_rows($resratingexist)==0) {?>
                                <a href="#" class="prdetails_link1" onclick="return rateProduct('<?php echo $row_staff['nStaffId']?>')"><?php echo TEXT_KNOWLEDGEBASE_RATENOW?></a></div>
                           <?php
                            }
                            else {
                                echo "Already rated!!";
                            }?>

                    <div id="jqRatingPop_<?php echo $row_staff['nStaffId']?>" class="jqRatingPop" style="display:none;">
                        <div style="width:280px; padding:10px;">

						<h1 style="font-size:14px; ">Rate this Staff  (<?php echo stripslashes($row_staff['vStaffname']); ?>)</h1></div>
                        <div id="jqLoader" style="display:none;"><img src="images/loading.gif" border="0" class="loader" alt="" /></div>
                        <div id="ratingArea">
						<div class="content_section" style="border:1px solid #cfcfcf; padding:10px; ">
                            <form name="frmRate" method="post" action="#" >
                                <table cellpadding="0" cellspacing="0" width="100%" border="0" class="ratingBox">
                                    <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr>
                                        <td class="emailStoryStyle" style="font-size:12px; color:#363636; "><strong>Rate:</strong></td>
                                        <td>
                                            <input name="star<?php echo $row_staff['nStaffId']?>" rel="<?php echo $row_staff['nStaffId']?>" type="radio" class="star" onclick="return ratingStarValue('<?php echo $row_staff['nStaffId']?>')" value="1" />
                                            <input name="star<?php echo $row_staff['nStaffId']?>" rel="<?php echo $row_staff['nStaffId']?>" type="radio" class="star" onclick="return ratingStarValue('<?php echo $row_staff['nStaffId']?>')" value="2" />
                                            <input name="star<?php echo $row_staff['nStaffId']?>" rel="<?php echo $row_staff['nStaffId']?>" type="radio" class="star" onclick="return ratingStarValue('<?php echo $row_staff['nStaffId']?>')" value="3" />
                                            <input name="star<?php echo $row_staff['nStaffId']?>" rel="<?php echo $row_staff['nStaffId']?>" type="radio" class="star" onclick="return ratingStarValue('<?php echo $row_staff['nStaffId']?>')" value="4" />
                                            <input name="star<?php echo $row_staff['nStaffId']?>" rel="<?php echo $row_staff['nStaffId']?>" type="radio" class="star" onclick="return ratingStarValue('<?php echo $row_staff['nStaffId']?>')" value="5" />
                                        </td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr>
                                        <td style="font-size:12px; color:#363636; ">
                                            <strong>Your Comment:</strong>
                                        </td>
                                        <td>
                                            <textarea name="txtComments" cols="30"id="txtComment_<?php echo $row_staff['nStaffId']?>"></textarea>
                                        </td>
                                    </tr>

                                    <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr><td colspan="2">&nbsp; </td></tr>
                                    <tr>
                                        <td align="center" colspan="2">
                                            <input type="hidden" name="hid_user_id" id="hid_user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="hid_ticket_id" id="hid_ticket_id" value="<?php echo $ticket_id; ?>" />
                                            <input type="button" value="Rate This Staff" style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqPostProductRating" onclick="submitProductRating('<?php echo $row_staff['nStaffId']?>')" id="comn_button_blue1"/>
                                            <input type="button" value="Cancel"  style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqProductRatingCancel" id="comn_button_blue2" onclick="closeProductRating('<?php echo $row_staff['nStaffId']?>')"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
							</div>
                        </div>
                    </div>
                        <?php
                        $count++;
                    }
                }?>
<br>
