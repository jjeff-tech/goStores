<div class="form_container">
    <div class="form_top"><?php echo $this->pageTitle;?></div>
    <div class="form_bgr">
        <!-- Invoice Details -->
<div id="inv_wrapper">

    <p style="text-align:center; font-weight:bold; padding-top:5mm;">INVOICE</p>
    <br />
    <table class="heading" style="width:100%;">
    	<tr>
    		<td style="width:80mm;">
    			<h1 class="heading"><?php echo COMPANY_NAME; ?></h1>
				<h2 class="heading">
					<?php echo stripslashes(COMPANY_ADDRESS); ?>

					Website : <?php echo COMPANY_WEBSITE; ?><br />
					E-mail : <?php echo COMPANY_EMAIL; ?><br />
					Phone : <?php echo COMPANY_PHONE; ?>
				</h2>
			</td>
			<td rowspan="2" valign="top" align="right" style="padding:3mm;">
				<table>
					<tr><td>Invoice No : </td><td><?php echo stripslashes($this->dataArr[0]->vInvNo); ?></td></tr>
					<tr><td>Dated : </td><td><?php echo Utils::formatDate($this->dataArr[0]->dGeneratedDate); ?></td></tr>
					<tr><td>Currency : </td><td><?php echo stripslashes(CURRENCY); ?></td></tr>
				</table>
			</td>
		</tr>
    	<tr>
    		<td>
    			<b>Invoiced To :</b><br />
    			<?php echo stripslashes($this->dataArr[0]->vFirstName); ?><?php echo (!empty($this->dataArr[0]->vLastName)) ? '&nbsp;'.stripslashes($this->dataArr[0]->vLastName) : NULL; ?><br />
			<?php echo nl2br(stripslashes($this->dataArr[0]->vAddress)); ?>
    			<br />
    			<?php echo (!empty($this->dataArr[0]->vCity)) ? stripslashes($this->dataArr[0]->vCity) : NULL; ?><?php echo (!empty($this->dataArr[0]->vState)) ? ' '.stripslashes($this->dataArr[0]->vState) : NULL; ?><?php echo (!empty($this->dataArr[0]->vZipcode)) ? '&nbsp;-&nbsp;'.stripslashes($this->dataArr[0]->vZipcode) : NULL; ?><?php echo (!empty($this->dataArr[0]->vCountry)) ? '&nbsp;,&nbsp;'.stripslashes($this->dataArr[0]->vCountry) : NULL; ?><br />    		</td>
    	</tr>
    </table>


	<div id="content">

		<div id="invoice_body">
			<table>
			<tr style="background:#eee;">
				<td style="width:8%;"><b>Sl. No.</b></td>
				<td><b>Product</b></td>
				<td style="width:15%;"><b>Billing Type</b></td>
				<td style="width:15%;"><b>Rate</b></td>
				<td style="width:15%;"><b>Total</b></td>
			</tr>
			</table>

			<table>
                        <?php
                        $discount = 0;
                        $total = 0;
                        $totalAmount = 0;
                        $i=0;

                        if(!empty($this->dataDomArr)){
                            foreach($this->dataDomArr as $itemD){
                                $total = 0;
                                ++$i;
                            ?>
                            <tr>
                                <td style="width:8%;"><?php echo $i; ?></td>
                                <td style="text-align:left; padding-left:10px;"><?php echo stripslashes($this->dataArr[0]->vPName); ?><br />Description : <?php echo stripslashes($itemD->vDescription); ?></td>
                                <td class="mono" style="width:15%;"><?php echo Admincomponents::billingType($itemD->vType); ?></td>
                                <td style="width:15%;" class="mono"><?php echo Utils::formatPrice($item->nAmount); ?></td>
                                <td style="width:15%;" class="mono"><?php $discount +=$itemD->nDiscount; $total =($item->nAmount - $itemD->nDiscount); $totalAmount += $total;   echo Utils::formatPrice($total); ?></td>
                            </tr>
                        <?php
                            }// End Foreach
                        } //
                        if(!empty($this->dataArr)){
                            foreach($this->dataArr as $item) {
                                //echo '<pre>'; print_r($item); echo '</pre>';
                                $total = 0;
                                ++$i;
                            ?>
                            <tr>
                                <td style="width:8%;"><?php echo $i; ?></td>
                                <td style="text-align:left; padding-left:10px;"><?php echo stripslashes($item->vPName); ?><br />Description : <?php echo stripslashes($item->vServiceName); ?><?php echo (!empty($item->vServiceDescription)) ? '&nbsp;-&nbsp;'.nl2br(stripslashes($item->vServiceDescription)) : NULL; ?></td>
                                <td class="mono" style="width:15%;"><?php echo Admincomponents::billingType($item->vType); ?></td>
                                <td style="width:15%;" class="mono"><?php echo Utils::formatPrice($item->ipAmount); ?></td>
                                <td style="width:15%;" class="mono"><?php $discount +=$item->ipDiscount; $total =($item->ipAmount - $item->ipDiscount); $totalAmount += $total;   echo Utils::formatPrice($total); ?></td>
                            </tr>
                            <?php
                            }
                        }
                        ?>
			<tr>
				<td colspan="3"></td>
				<td></td>
				<td></td>
			</tr>
                        <tr>
				<td colspan="3"></td>
				<td>Discount :</td>
				<td class="mono"><?php echo Utils::formatPrice($discount); ?></td>
			</tr>
			<tr>
                            <td colspan="3" style="padding-left: 10px; text-align:left;"><?php echo (!empty($item->vTerms)) ? 'Terms : '.ucfirst($item->vTerms).'<br/>' : NULL; ?><?php echo (!empty($item->vNotes)) ? 'Notes : '.ucfirst($item->vNotes).'<br/>' : NULL; ?></td>
				<td>Total :</td>
				<td class="mono"><?php echo Utils::formatPrice($totalAmount); ?></td>
			</tr>
		</table>
		</div>
		<div id="invoice_total">
			Total Amount :
			<table>
				<tr>
                                    <td style="text-align:left; padding-left:10px;"><?php echo ucfirst(Utils::number_to_words($totalAmount)); ?> only</td>
					<td style="width:15%;"><?php echo stripslashes(CURRENCY); ?></td>
					<td style="width:15%;" class="mono"><?php echo Utils::formatPrice($totalAmount); ?></td>
				</tr>
			</table>
		</div>
	</div>
	</div>
        <!-- Invoice Details End -->
    </div>
</div>
<div class="form_bottom"></div>