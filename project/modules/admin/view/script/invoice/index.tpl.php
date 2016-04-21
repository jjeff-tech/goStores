<div class="form_container">
    <div class="form_top"><?php echo $this->pageTitle;?></div>
    <div class="form_bgr">
        <div>
        <!-- Search Form -->
        <form class="cmxform" id="reportSearch" action="<?php echo BASE_URL; ?>admin/invoice" method="post" >
            <?php   PageContext::renderPostAction('errormessage', 'index'); ?>
            <table width="95%" border="0" align="center" cellpadding="15" cellspacing="0" bgcolor="f0f0f0" class="formstyle report_search_table_style" id="id-form">
                <tr>
                    <td>Start Date :<span class="mandred">*</span></td>
                    <td><input id="reportStartDate" name="reportStartDate" validate="required:true " class="report_date_field"  value="<?php echo $this->dataArr['reportStartDate']; ?>"  title="Please provide Start Date" tabindex="5" type="text" style="width: 90px;" /></td>
                    <td>End Date :<span class="mandred">*</span></td>
                    <td colspan="3"><input id="reportEndDate" name="reportEndDate" class="report_date_field { messages{required:Please enter Start Date}}"  value="<?php echo $this->dataArr['reportEndDate']; ?>"  title="Please provide End Date" tabindex="5" type="text" style="width: 90px;" validate="required:true"/></td>
                </tr>
                <tr>
                    <td>Products:</td>
                    <td>
                        <select class="report_date_field" id="product" name="product" >
                            <option value="all" <?php echo $selected = ($this->dataArr['product']=='all') ? 'selected="selected"' : ''; ?>>All</option>
                            <?php foreach($this->productsList as $product) {
                                $selected = ($this->dataArr['product']==$product->nPId) ? 'selected' : '';
                                ?>
                            <option value="<?php echo $product->nPId;?>" <?php echo $selected;?>><?php echo $product->vPName ;?></option>
                                <?php } ?>
                        </select>
                    </td>
                    <td>Subscription Type:</td>
                    <td>
                        <input type="checkbox" value="PAID" name="paid_subscription"<?php echo ($this->dataArr['paid_subscription']=='PAID') ? ' checked':'';?>>Paid Only
                        <input type="checkbox" value="FREE" name="free_subscription"<?php echo ($this->dataArr['free_subscription']=='FREE') ? ' checked':'';?>>Free Only
                        <input type="checkbox" value="DUE" name="invDue"<?php echo ($this->dataArr['invDue']=='DUE') ? ' checked' : ''; ?>>Due
                    </td>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td>User Email:</td>
                    <td>
                        <select class="report_date_field" id="userEmail" name="userEmail" >
                            <option value="all" <?php echo $selected = ($this->dataArr['userEmail']=='all') ? 'selected="selected"' : ''; ?>>All</option>
                            <?php
                            if(!empty($this->userEmailList)) {
                                foreach($this->userEmailList as $userId => $userEmail ) {
                                    $selected = ($this->dataArr['userEmail']==$userId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $userId;?>" <?php echo $selected;?>><?php echo $userEmail ;?></option>
                                    <?php

                                }
                            }
                            ?>

                        </select>
                    </td>                    
                    <td colspan="3" align="left"><input type="submit" name="btnAdd"  value="search"  /></td>
                    <td align="right" valign="bottom">                         <div align="right"><?php if(!empty($this->pageContents)) { ?><a href="<?php echo BASE_URL.'admin/invoice/exportReport/'.Utils::serializeNencodeArr($this->dataArr);?>" title="Export to excel"><img src="<?php echo IMAGE_URL; ?>icon_excel.png" alt="Export to excel" border="0" /></a><?php } ?></div>
</td>

                </tr>
          </table>

        </form>
        <div class="clear"></div>
        <!-- Listing Form -->
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="6%" align="center" valign="top">Sl No.</td>
                <td width="8%" align="left" valign="top">Invoice No.</td>
                <td align="left" valign="top">Product</td>
                <td width="11%" align="left" valign="top">Subscription Type</td>
                <td align="left" valign="top">User</td>
                <td align="left" valign="top">Generated On</td>
                <td align="left" valign="top">Due On</td>
                <td align="left" valign="top">Paid On</td>
                <td align="left" valign="top">Payment Status</td>
                <td width="12%" align="left" valign="top">Total Amount</td>
            </tr>
            <?php
            if(!empty($this->pageContents)) {

                $i=0;
                $i=$this->pageInfo['base'];
                foreach($this->pageContents as $pageItem) {
                    //echo '<pre>'; print_r($pageItem); echo '</pre>';
                    $i++;
                    $className=($i%2) ? 'column2' : 'column1';
                    ?>
            <tr class="<?php echo $className ?>">
                <td align="left"><?php echo $i; ?></td>
                <td align="left"><span class="edit"><a href="<?php echo BASE_URL.'admin/invoice/invoicedetails/'.$pageItem->nInvId; ?>" title="View Invoice Details"><?php echo $pageItem->vInvNo; ?></a></span></td>
                <td align="left"><?php echo $pageItem->vPName; ?></td>
                <td align="left"><?php echo $pageItem->vSubscriptionType; ?></td>
                <td align="left"><a title="<?php echo $pageItem->vEmail; ?>"><?php echo strlen($pageItem->vEmail)>10?substr($pageItem->vEmail,0,10).'..':$pageItem->vEmail; ?></a></td>
                <td align="left"><?php echo Utils::formatDate($pageItem->dGeneratedDate); ?></td>
                <td align="left"><?php echo Utils::formatDate($pageItem->dDueDate); ?></td>
                <td align="left"><?php echo Utils::formatDate($pageItem->dPayment); ?></td>
                <td align="left"><?php echo ($pageItem->vSubscriptionType!='FREE') ? Admincomponents::getInvoicePaymentStatus($pageItem->currentDate, $pageItem->dDueDate, $pageItem->dPayment) : '--'; ?></td>
                <td align="right"><?php echo CURRENCY_SYMBOL.Utils::formatPrice($pageItem->nTotal); ?></td>
            </tr>
            <?php             
                }
            }else{
                ?>
            <tr class="column1">
                <td align="center" colspan="10">No Results Found</td>
            </tr>
                <?php
            }
            ?>            
        </table>
        </div>
        <div class="more_entries">
            <div class="wp-pagenavi">
                <?php
                if(!empty($this->pageContents) && $this->pageInfo['maxPages']>1) {
                    echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/invoice/index/'.($this->dataArr['reportStartDate']?str_replace('/','-',$this->dataArr['reportStartDate']):'x').'/'.($this->dataArr['reportEndDate']?str_replace('/','-',$this->dataArr['reportEndDate']):'x').'/'.$this->dataArr['product'].'/'.(($this->dataArr['paid_subscription']?$this->dataArr['paid_subscription']:'').($this->dataArr['free_subscription']?'-'.$this->dataArr['free_subscription']:'').($this->dataArr['invDue']?'-'.$this->dataArr['invDue']:'')).'/'.$this->dataArr['userEmail'].'/');
                } ?>
            </div>
        </div>

    </div>
</div>
<div class="form_bottom"></div>