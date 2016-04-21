<div class="right_column">
    <div class="form_container">
      <div class="form_top">Billing History / Receipts</div>
      <div>
          <div class="search_container_dashboard">
		 <!-- Search Form -->
		<div class="search_container_inner">
		<form class="cmxform" id="frmPayments" action="<?php echo ConfigUrl::base(); ?>user/payments" method="post" >
			<input type="hidden" name="action" value="search">
                        <input type="hidden" name="page" id="page" value="<?php echo $this->pageCount;?>">
                        <div class="search_container" >
                          <input name="search" id="search" type="text" class="search_box" value="<?php echo $this->searchParam ; ?>" placeholder="Search By  Invoice Number" title="Search By Invoice Number">
                           <a href="<?php echo ConfigUrl::base(); ?>user/payments"><input name="btnSearch" type="button" class="button_orange" value="Reset"></a>
			<input name="btnSearch" type="submit" class="button_orange" value="Search">
                       
                        </div>
		</form>
		<!-- End Search Form -->
		</div>
		
		<div class="clear"></div>
		</div>
<div class="table-responsive">    
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="formstyle">
  <tbody>
    <tr class="heading1">
      <td width="8%" align="left" class="sl_no_padding">#</td>
      <td width="22%" align="left">Invoice Number</td>
      <td width="22%" align="left">Plan</td>
      <td width="10%" align="left">Amount</td>
      <td width="10%" align="left">Discount</td>
      <td width="10%" align="left">Total</td>
      <td width="15%" align="left">Generated</td>
      <td width="15%" align="left">Due Date</td>
      <td width="10%" align="left">Status</td>
      <td width="15%" align="left">Payment Date</td>
    </tr>
    <?php
        $i=0;
       $currentDate =date("Y-m-d H:i:s");
        if(!empty($this->pageContents)) {
            $i=$this->pageInfo['base'];
            foreach($this->pageContents as $row) { 
                //print '<pre />';print_r($row);
                $i++;
                $className=($i%2) ? 'column2' : 'column1';
                ?>
    <tr class="<?php echo $className;?>">
      <td align="left"><?php echo $i;?></td>
      <td align="left"><a href="<?php echo BASE_URL . 'user/invoicedetails?id='.$row->vInvNo.'&vtype='.stripslashes($row->billgenType); ?>"><?php echo $row->vInvNo; ?></a></td>
      <td align="left"><?php echo stripslashes($row->vServiceName); ?><br>
          <?php echo Admincomponents::getStoreHost($row->nPLId); ?>
      </td>
      <td align="left"><?php echo CURRENCY_SYMBOL.' '.Utils::formatPrice($row->nAmount);?></td>
      <td align="left"><?php echo CURRENCY_SYMBOL.' '.Utils::formatPrice($row->nDiscount);?></td>
      <td align="left"><?php echo CURRENCY_SYMBOL.' '.Utils::formatPrice($row->nTotal);?></td>
      <td align="left"><?php echo Utils::formatDateUS($row->dGeneratedDate);?></td>
      <td align="left"><?php echo Utils::formatDateUS($row->dDueDate);?></td>
      <td align="left"><?php echo User::getInvoicePaymentStatus($currentDate, $row->dDueDate, $row->dPayment);?></td>
      <td align="left"><?php echo Utils::formatDateUS($row->dPayment);?></td>
    </tr>
    <?php
                    }
                } else {
                    ?>
            <tr class="column1">
                <td align="center" colspan="10">No Results Found</td>
            </tr>
                    <?php
                }
                ?>
  </tbody>
</table>
</div>
<div class="more_entries">
        <div class="wp-pagenavi">
            <?php if(!empty($this->pageContents) && $this->pageInfo['maxPages']>1) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'user/payments/'); } ?>
            </div>
        </div>
</div>
     
    </div>
  </div>






