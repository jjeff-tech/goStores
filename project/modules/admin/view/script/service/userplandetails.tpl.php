<link href="<?php echo BASE_URL ?>project/styles/invoice.css" rel="stylesheet" type="text/css" />
<div class="modal" id="userPlanFullDetails" style="width: 800px;left: 44%;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  id="jqCloseLinkUserDetails">X</button>
        <h4 id="myModalLabel" style="text-align: left;">User Plan Details  </h4>
    </div>
    <div class="modal-body" style="overflow: scroll!important;">
        <!-- Invoice Details -->
        <div id="inv_wrapper" style="margin-bottom: 20px;">
            <div id="content">
                <div id="invoice_body">
                    
                    <!-- Plan Details -->
                    <table>
                        <tr style="background:#eee;">
                            <!--td style="width:6%;"><b>#</b></td-->
                            <td style="width:14%;"><b>Plan</b></td>
                            <td style="width:15%;"><b>Domain</b></td>
                            <td style="width:10%;"><b>Amount (<?php echo CURRENCY_SYMBOL ?>)</b></td>
                            <td style="width:15%;"><b>Generated</b></td>
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
                            <!--td style="width:6%;"><?php //echo $item->vInvNo; ?></td-->
                            <td style="width:14%;" class="mono"><?php echo $item->vServiceName; ?></td>
                            <td style="width:15%;" class="mono"><?php echo Admincomponents::getStoreHost($item->nPLId); ?></td>
                            <td style="width:10%;" class="mono"><?php echo Utils::formatPrice($item->nTotal); ?></td>
                            <td style="width:15%;" class="mono" ><?php echo Utils::formatDateUS($item->dGeneratedDate); ?></td>
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
                <a class="addrecord btn btn-info" href="<?php //echo BASE_URL?>cms?section=user">Back</a>
            </div-->
        </div>

        <!-- Invoice Details End -->
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"  id="jqCloseUserDetails">Close</button>
        </div>

    </div>
</div>