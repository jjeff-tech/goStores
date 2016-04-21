
<div class="form_container">
<div class="form_top"><?php echo $this->pageTitle;?></div>
<div class="form_bgr">
  
 <!-- Search Form -->
 <div>
<form class="cmxform" id="reportSearch" action="<?php echo BASE_URL; ?>admin/reports" method="post" >
 <?php   PageContext::renderPostAction('errormessage', 'index'); ?>
        <table width="95%" border="0" align="center" cellpadding="15" cellspacing="0" bgcolor="f0f0f0" class="formstyle">
           <tr>
               <td align="right" width="15%">Subscription Type:</td>
           <td width="22%"><input type="checkbox" value="PAID" name="paid_subscription" <?php echo $this->subscriptionType!='FREE'?'checked':'';?>>Paid Only
               <input type="checkbox" value="FREE" name="free_subscription" <?php echo $this->subscriptionType!='PAID'?'checked':'';?>>Free Only
           </td>
               <td width="16%" align="right" valign="top">Start Date :<span class="mandred">*</span></td>
               <td width="17%" colspan="2"><input id="reportStartDate" name="reportStartDate" validate="required:true " class="report_date_field"  value="<?php echo $this->startDate;?>"  title="Please provide Start Date" tabindex="5" type="text" style="width: 90px;" /></td>
              
           </tr>
           <tr>
               <td align="right" width="15%">Products:</td>
             <td width="22%">
                   <select style="width: 110px;" id="product" name="product" >
                       <option value="all">All</option>
                       <?php foreach($this->productsList as $product){
                            if( $this->product ==$product->nPId)
                                $selected ='selected';
                            else
                                $selected ='';
                           ?>
                       }
                       <option value="<?php echo $product->nPId;?>" <?php echo $selected;?>><?php echo $product->vPName ;?></option>
                       <?php } ?>
                   </select>
           </td>
            <td width="26%" align="right" valign="top">End Date :<span class="mandred">*</span></td>
               <td width="29%" ><input id="reportEndDate" name="reportEndDate" class="report_date_field { messages{required:Please enter Start Date}}"  value="<?php echo $this->endDate;?>"  title="Please provide End Date" tabindex="5" type="text" style="width: 90px;" validate="required:true"/></td>
           
           <td width="12%" colspan="1" align="left"><input type="submit" name="btnAdd"  value="search"  /></td>
           <td align="right" valign="bottom"><div align="right">  <?php if(!empty($this->reports)) { ?>
        <a href="<?php echo BASE_URL.'admin/reports/exportReport/'.str_replace('/','-',$this->startDate).'/'.str_replace('/','-',$this->endDate).'/'.$this->product.'/'.$this->subscriptionType;?>"><img src="<?php echo IMAGE_URL; ?>icon_excel.png" alt="Export to excel" border="0" /></a>
            <?php    } ?>
</div></td>
           </tr>
          
</table>
    </br>
  
</form>
<div class="clear"></div>
<!-- Listing Form -->
<table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="heading1">
      <td width="6%" align="center">Sl No.</td>
      <td width="26%" align="left">Product<a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/index/users/<?php echo $this->usernameSortAction;?>"  class="<?php echo $this->usernameSortStyle;?>"></a></td>
      <td width="26%" align="left">Subscription Type</td>
      <td width="20%" align="left">Subscriptions</td>
      <td width="20%" align="left">Total Amount</td>
    </tr>
    <?php
    if(!empty($this->reports)) {
         
        $i=0;
        $i=$this->pageInfo['base'];
        foreach($this->reports as $report) {
          $i++;
          $className=($i%2) ? 'column2' : 'column1';
    ?>
    <tr class="<?php echo $className ?>">
     <td align="left"><?php echo $i; ?></td>
     <td align="left"><?php echo $report->vPName; ?></td>
     <td align="left"><?php echo $report->vSubscriptionType; ?></td>
     <td align="left"><?php echo $report->invoice_count; ?></td>
     <td align="left"><?php echo CURRENCY_SYMBOL.''.Utils::formatPrice($report->toal_amount); ?></td>
    </tr>
   
   <?php } } else {
                    ?>
            <tr class="column1">
                <td align="center" colspan="6">No Results Found</td>
            </tr>
                    <?php
                }
                ?>
            
</table>
 </div>
<div class="more_entries">
            <div class="wp-pagenavi">
                    <?php if(!empty($this->reports) && $this->pageInfo['maxPages']>1) {
        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/reports/index/'.str_replace('/','-',$this->startDate).'/'.str_replace('/','-',$this->endDate).'/'.$this->product.'/'.$this->subscriptionType.'/');
    } ?>
            </div>
        </div>

</div>
</div>
<div class="form_bottom"></div> 