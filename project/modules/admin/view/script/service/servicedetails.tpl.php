
<div class="modal" id="serviceFullDetails" style="width: 800px;left: 44%;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  id="jqCloseLinkUserDetails">X</button>
        <h4 id="myModalLabel" style="text-align: left;">Service Details : <?php echo PageContext::$response->dataArr[0]->vServiceName;  ?>  </h4>
    </div>
    <div class="modal-body" style="overflow: scroll!important;">
        <!-- Invoice Details -->
        <div id="inv_wrapper" style="margin-bottom: 20px;">

            <table class="heading" style="width:100%;">

                <tr>
                    <td valign="top" align="left">
                        <b>User Details :</b><br />
                        <?php echo stripslashes(PageContext::$response->dataArr[0]->vFullName); ?><br />
                        <?php echo nl2br(stripslashes(PageContext::$response->dataArr[0]->vAddress)); ?>
                        <br />
                        <?php echo (!empty(PageContext::$response->dataArr[0]->vCity)) ? stripslashes(PageContext::$response->dataArr[0]->vCity) : NULL; ?><?php echo (!empty(PageContext::$response->dataArr[0]->vState)) ? ' '.stripslashes(PageContext::$response->dataArr[0]->vState) : NULL; ?><?php echo (!empty(PageContext::$response->dataArr[0]->vZipcode)) ? '&nbsp;-&nbsp;'.stripslashes(PageContext::$response->dataArr[0]->vZipcode) : NULL; ?><?php echo (!empty(PageContext::$response->dataArr[0]->vCountry)) ? '&nbsp;,&nbsp;'.stripslashes(PageContext::$response->dataArr[0]->vCountry) : NULL; ?><br />
                        <b>Email : </b><?php echo (!empty(PageContext::$response->dataArr[0]->vEmail)) ? stripslashes(PageContext::$response->dataArr[0]->vEmail) : '--'; ?><br />
                        <b>Invoice Email : </b><?php echo (!empty(PageContext::$response->dataArr[0]->vInvoiceEmail)) ? stripslashes(PageContext::$response->dataArr[0]->vInvoiceEmail) : '--'; ?><br />
                    </td>
                </tr>
            </table>


            <div id="content">

                <div id="invoice_body">
                    <!-- Domain Details -->
                    <table>
                        <tr style="background:#eee;">				
                            <td colspan="4"><b>Domain Details</b></td>
                        </tr>
                        <tr style="background:#eee;">
                            <td style="width:8%;"><b>#</b></td>
                            <td><b>Domain</b></td>
                            <td style="width:15%;"><b>Registration Date</b></td>
                            <td style="width:25%;"><b>Location</b></td>
                        </tr>
                    
                        <?php

                        $i=0;
                        if(!empty(PageContext::$response->dataDomainArr)) {
                            foreach(PageContext::$response->dataDomainArr as $itemD) {
                                ++$i;
                                ?>
                        <tr>
                            <td style="width:8%;"><?php echo $i; ?></td>
                            <td style="text-align:left; padding-left:10px;"><?php echo stripslashes($itemD['domain']); ?></td>
                            <td class="mono" style="width:15%;"><?php echo Utils::formatDateUS($itemD['registeredOn']); ?></td>
                            <td style="width:25%;" class="mono"><?php echo $itemD['location']; ?></td>

                        </tr>
                                <?php
                            }// End Foreach
                        } //

                        ?>



                    </table>
                    <!-- Domain Details End -->
                    <!-- Invoice Details -->
                    <table>
                        <tr style="background:#eee;">
                            <td colspan="8"><b>Invoice Details</b></td>
                        </tr>
                        <tr style="background:#eee;">
                            <td style="width:6%;"><b>#</b></td>
                            <td style="width:14%;"><b>Plan</b></td>
                            <td style="width:15%;"><b>Domain</b></td>
                            <td style="width:10%;"><b>Amount (<?php echo CURRENCY_SYMBOL ?>)</b></td>
                            <td style="width:15%;"><b>Generated</b></td>
                            <td style="width:15%;"><b>Due Date</b></td>
                            <td style="width:10%;"><b>Status</b></td>
                            <td style="width:15%;"><b>Payment Date</b></td>
                        </tr>
                    
                        <?php
                        $discount = 0;
                        $total = 0;
                        $totalAmount = 0;
                        $i=0;

                        if(!empty(PageContext::$response->dataArr)) {
                            foreach(PageContext::$response->dataArr as $item) {
                                //echopre($item);
                                ++$i;
                                ?>
                        <tr>
                            <td style="width:6%;">
                                <!--a href="<?php //echo BASE_URL?>cms?section=invoice_details&parent_id=<?php //echo $item->nInvId;?>" class="cms_list_operation"><?php //echo $item->vInvNo; ?></a-->
                                <?php echo $item->vInvNo; ?>
                            </td>
                            <td style="width:14%;" class="mono"><?php echo $item->vServiceName; ?></td>
                            <td style="width:15%;" class="mono"><?php echo Admincomponents::getStoreHost($item->nPLId); ?></td>
                            <td style="width:10%;" class="mono"><?php echo Utils::formatPrice($item->nTotal); ?></td>
                            <td style="width:15%;" class="mono" ><?php echo Utils::formatDateUS($item->dGeneratedDate); ?></td>
                            <td style="width:15%;" class="mono"><?php echo Utils::formatDateUS($item->dDueDate); ?></td>
                            <td style="width:15%;" class="mono"><?php if($item->vSubscriptionType=='FREE') {
                                            echo ucfirst(strtolower($item->vSubscriptionType));
                                        } else {
                                            echo Admincomponents::getInvoicePaymentStatus($item->currentDate, $item->dDueDate, $item->dPayment);
                                        } ?></td>
                            <td style="width:10%;" class="mono"><?php echo Utils::formatDateUS($item->dPayment); ?></td>
                        </tr>
                        <?php
                            }
                        } else {
                            ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding-left:10px;">No Records found</td>
                        </tr>
                        <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
            <!--div class="jhistoryBack" style="margin-top: 20px;text-align: left;">
                <a class="addrecord btn btn-info" href="<?php //echo BASE_URL?>cms?section=orders">Back</a>
            </div-->
        </div>

        <!-- Invoice Details End -->
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"  id="jqCloseUserDetails">Close</button>
        </div>

    </div>
</div>