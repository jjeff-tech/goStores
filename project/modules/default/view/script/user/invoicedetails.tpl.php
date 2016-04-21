<link href="<?php echo BASE_URL ?>project/styles/invoice.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var BASE_URL = '<?php echo BASE_URL; ?>';
</script>
<script type="text/javascript" src="<?php echo BASE_URL ?>project/js/invoice.js"></script>
<div class="right_column">
    <div class="form_container">
      <div class="form_top">Invoice Details : <?php echo $this->dataArr[0]->vInvNo;  ?></div>
      <div>
          
          <!------------------- Template ------------------------->
          <div class="" id="invoiceFullDetails" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
     
       
    </div>
    <div class="">
        <!-- Invoice Details -->
        <div id="inv_wrapper" style="margin-bottom: 20px;">

            <p style="text-align:center; font-weight:bold; padding-top:5mm;">INVOICE</p>
            <br />
            <div class="table-responsive">
            <table class="heading" style="width:100%;text-align: left;">
                <tr>
                    <td style="width:80mm;">
                        <h1 class="heading"><?php echo COMPANY_NAME; ?></h1>
                        <h2 class="heading">
                            <?php echo COMPANY_ADDRESS ?>
                            <?php $w =COMPANY_WEBSITE;
                            if(!empty($w)) { ?>Website : <?php echo COMPANY_WEBSITE; ?><br /><?php } ?>
                            <?php $e =COMPANY_EMAIL;
                            if(!empty($e)) { ?>E-mail : <?php echo COMPANY_EMAIL; ?><br /><?php } ?>
                            <?php $p =COMPANY_PHONE;
                            if(!empty($p)) { ?>Phone : <?php echo COMPANY_PHONE; ?><?php } ?>
                        </h2>
                    </td>
                    <td rowspan="2" valign="top" align="right" style="padding:3mm;">
                        <table>
                            <tr><td>Invoice No : </td><td><?php echo stripslashes($this->dataArr[0]->vInvNo); ?></td></tr>
                            <tr><td>Dated : </td><td><?php echo Utils::formatDateUS($this->dataArr[0]->dGeneratedDate); ?></td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Invoiced To :</b><br />
                        <?php echo stripslashes($this->dataArr[0]->vFullName); ?><br />
                        <?php echo nl2br(stripslashes($this->dataArr[0]->vAddress)); ?>
                        <br />
                        <?php echo (!empty($this->dataArr[0]->vCity)) ? stripslashes($this->dataArr[0]->vCity) : NULL; ?><?php echo (!empty($this->dataArr[0]->vState)) ? ' '.stripslashes($this->dataArr[0]->vState) : NULL; ?><?php echo (!empty($this->dataArr[0]->vZipcode)) ? '&nbsp;-&nbsp;'.stripslashes($this->dataArr[0]->vZipcode) : NULL; ?><?php echo (!empty($this->dataArr[0]->vCountry)) ? '&nbsp;,&nbsp;'.stripslashes($this->dataArr[0]->vCountry) : NULL; ?><br />    		</td>
                </tr>
            </table>
            </div>

            <div id="content">

                <div id="invoice_body">
                    
                    <div class="table-responsive">
                    <table>
                        <tr style="background:#eee;">
                            <td style="width:8%;"><b>#</b></td>
                            <?php if($_GET['vtype']== 'template') {?>
                           
                            <td style="width:15%;"><b>Template Name</b></td>
                             <td style="width:15%;"><b>Domain Name</b></td>
                            <td style="width:15%;"><b>Purchaged Date</b></td>
                           
                           
                            <?php }else { ?>
                            <td><b>Plan</b></td>
                            <td style="width:15%;"><b>Billing Type</b></td>
                            <td style="width:15%;"><b>Billing Period</b></td>
                          
                            <?php } ?>
                            <td style="width:15%;"><b>Rate</b></td>
                            <td style="width:15%;"><b>Total</b></td>
                        </tr>
                    </table>
                    </div>
                    <div class="table-responsive">
                    <table>
                        <?php
                        $discount = 0;
                        $total = 0;
                        $totalAmount = 0;
                        $i=0;
                        if(!empty($this->dataDomArr)) {
                            foreach($this->dataDomArr as $itemD) { 
                                $total = 0;
                                ++$i;
                                ?>
                        <tr>
                            <td style="width:8%;"><?php echo $i; ?></td>
                            <td style="text-align:left; padding-left:10px;"><?php echo stripslashes($itemD->vDescription); ?></td>
                            <td class="mono" style="width:15%;"><?php echo Admincomponents::billingInterval($itemD->vBillingInterval); ?></td>
                            <td class="mono" style="width:15%;">--</td>
                            <td style="width:15%;" class="mono"><?php echo CURRENCY_SYMBOL.Utils::formatPrice($itemD->nRate); ?></td>
                            <td style="width:15%;" class="mono"><?php $discount +=$itemD->nDiscount;
                                        $total =($itemD->nAmount - $itemD->nDiscount);
                                        $totalAmount += $total;
                                        echo CURRENCY_SYMBOL.Utils::formatPrice($total); ?></td>
                        </tr>
                        <?php
                            }// End Foreach
                        } //

                        $vSubscriptionType = NULL;

                        if(!empty($this->dataArr)) {
                           
                            foreach($this->dataArr as $item) {
                                //echopre($item);
                                $total = 0;
                                ++$i;
                                $vSubscriptionType = $item->vSubscriptionType;
                                ?>
                        <tr>
                            <td style="width:8%;"><?php echo $i; ?></td>
                            <?php if($_GET['vtype']== 'invoice') {?>
                            <td style="text-align:left; padding-left:10px;"><?php echo stripslashes($item->vServiceName); ?><br><?php echo User::getStoreHost($item->nPLId); ?><br><br><?php echo (!empty($item->vServiceDescription)) ? nl2br(stripslashes($item->vServiceDescription)) : NULL; ?></td>
                            <td class="mono" style="width:15%;"><?php echo User::billingInterval($item->ipBillingInterval); ?></td>
                            <td class="mono" style="width:15%;"><?php echo Utils::formatDateUS($item->dDateStart,FALSE,'date'); ?> to <?php echo Utils::formatDateUS($item->dDateStop,FALSE,'date'); ?></td>
                            <td style="width:15%;" class="mono"><?php echo CURRENCY_SYMBOL.Utils::formatPrice($item->ipAmount); ?></td>
                            <?php 
                            
                             $total =($item->ipAmount);
                            }else { ?>
                             <td style="text-align:left; padding-left:10px;"><?php  echo stripslashes($item->vServiceName); ?></td>
                            <td class="mono" style="width:15%;"><?php echo User::getStoreHost($item->nPLId); ?></td>
                            <td class="mono" style="width:15%;"><?php echo Utils::formatDateUS($item->dPayment);?></td>
                            <td style="width:15%;" class="mono"><?php echo CURRENCY_SYMBOL.Utils::formatPrice($item->nAmount); ?></td>
                            <?php 
                            
                             $total =($item->nAmount);
                            } ?>
                            
                            <td style="width:15%;" class="mono"><?php $discount +=$item->nDiscount;
                                        //$total =($item->ipAmount - $item->nDiscount);
                                       
                                        $totalAmount += $total;
                                        echo CURRENCY_SYMBOL.Utils::formatPrice($total); ?></td>
                        </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="4"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
//Specials
                        if(!empty($this->dataArr)) {
                            foreach($this->dataArr as $item) {
                                $specialArr = array();
                                if(!empty($item->vSpecials)) {
                                    $specialArr = json_decode($item->vSpecials);
                                    if(!empty($specialArr)) {
                                        foreach($specialArr as $spItem) {
                                            ?>
                        <tr>
                            <td colspan="4" style="padding-left: 10px; text-align:left;"><?php echo $spItem->note; ?><?php echo Admincomponents::getSpecialsCaptureType($spItem->capture); ?></td>
                            <td>--</td>
                            <td class="mono"><?php $totalAmount += $spItem->cost;
                                                    echo CURRENCY_SYMBOL.Utils::formatPrice($spItem->cost); ?></td>
                        </tr>
                                            <?php
                                        }
                                    }
                                }


                            }
                        } // End Specials
                        ?>

                        <tr>
                            <td colspan="4" style="padding-left: 10px; text-align:left;"><?php echo (!empty($item->vTerms)) ? 'Terms : '.ucfirst($item->vTerms).'<br/>' : NULL; ?><?php echo (!empty($item->vNotes)) ? 'Notes : '.ucfirst($item->vNotes).'<br/>' : NULL; ?></td>
                            <td>Total :</td>
                            <td class="mono"><?php echo CURRENCY_SYMBOL.Utils::formatPrice($totalAmount); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Discount :</td>
                            <td class="mono"><?php echo CURRENCY_SYMBOL.Utils::formatPrice($discount); ?></td>
                        </tr>
                    </table>
                    </div>
                </div>
                <div id="invoice_total">
			Total Amount :
                    <table>
                        <tr>
                            <?php $amountAfterDiscount = ($totalAmount-$discount); ?>
                            <td style="text-align:left; padding-left:10px;"><?php echo ucfirst(Utils::number_to_words($amountAfterDiscount)); ?> only</td>
                            <td style="width:15%;" class="mono"><?php echo CURRENCY_SYMBOL; ?><?php echo Utils::formatPrice($amountAfterDiscount); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-top: 10px;">
                <input type="button" onclick="window.location.href='<?php echo BASE_URL . 'user/payments'; ?>'" name="GoBack" value="Go Back" class="button_orange">
            </div>
            <!--div class="jhistoryBack" style="margin-top: 20px;text-align: left;">-->
                <!--a class="addrecord btn btn-info" href="javascript:history.go(-1);">Back to Service Details</a>&nbsp;&nbsp;-->
                <!--a class="addrecord btn btn-info" href="<?php //echo BASE_URL?>cms?section=invoice">Back</a-->
            <!--</div-->
        </div>

        <!-- Invoice Details End -->
        

    </div>
</div>
          <!------------------- Template ------------------------->
      </div>
     
    </div>
  </div>