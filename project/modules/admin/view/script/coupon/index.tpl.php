<!--           ***********************   COUPON MANAGEMENT  *****************************
<script type="text/javascript">
    $(function(){
        // Datepicker
        $('#txtExpiryDate').datepicker({
            inline: true
        });
    });
</script>
<h1>Generate Coupons</h1>
<div><?php //echo $this->message; ?></div>
<form method="post" id="frmgenerateCoupons" action="<?php //echo ConfigUrl::base(); ?>coupon/index">
    <div class="errorBox"><?php //echo $this->errMsg ?></div>
    <table width="100%" cellpadding="0" cellspacing="3" border="0">
        <tr>
            <td align="left"><label for="noofcoupons">Number of Coupons</label></td>
            <td align="left"><input id="txtNoOfCoupons" name="txtNoOfCoupons" value="" title="NoofCoupons" tabindex="4" type="text"/></td>
        </tr>
        <tr>
            <td align="left"><label for="expirydate">Expiry Date</label></td>
            <td align="left"><input id="txtExpiryDate" name="txtExpiryDate" value="" title="ExpiryDate" tabindex="5" type="text" /></td>
        </tr>

        <tr>
            <td align="left"><label for="discountvalue">Discount Rate</label></td>
            <td align="left"><input id="txtDiscountRate" name="txtDiscountRate" value="" title="DiscountRate" tabindex="5" type="text" /></td>
        </tr>
        <tr>
            <td align="left"><label for="description">Description</label></td>
            <td align="left"><input id="txtDescription" name="txtDescription" value="" title="Description" tabindex="5" type="text" /></td>
        </tr>

        <tr>
            <td align="center" colspan="2"><input id="submitCoupon" name="submitCoupon" value="Generate Coupon" tabindex="6" type="submit"/></td>
        </tr>

    </table>
</form>

-->
<!-- Start modification ---------->
<div class="form_container">
   <div class="form_top">Coupons</div>
   <div class="form_bgr">        

<div class="r_float">
            <form class="cmxform" id="frmCoupon" action="<?php echo ConfigUrl::base(); ?>coupon/index/" method="post" onsubmit="return validateCouponSearch()">

<div class="l_float">
       <!-- Search Form -->
            <input type="hidden" name="action" value="search">           
            <div class="admin_search_container"><input name="search" id="search" type="text" class="search_box" value="<?php echo $this->txtSearch; ?>" placeholder="Search by Coupon Name"><input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;
                   </div>
			</div>
				
		<div class="l_float">
            <div class="addnew"><?php if($this->action=="") { ?><a href="<?php echo ConfigUrl::base(); ?>coupon/createcoupon">Add Coupon</a><?php } ?></div>
       </div>
<div class="clear"></div>
	    </form>
</div>
		
       <!-- End Search Form -->
        <br><br><br>
		
		
        <div><?php //echo $this->message; ?></div>
        <div  align="left">
<?php PageContext::renderPostAction('successmessage','index');
$this->messageFunction ='';?>
</div>
        <!-- Listing Form -->
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="6%" align="center">Sl No.</td>
                <td width="15%" align="left">Coupon Name</td>
                <td width="10%" align="left">No. of Coupons</td>
                <td width="14%" align="left">No. of Coupons Used</td>
                <td width="13%" align="left">Pricing Mode</td>
                <td width="15%" align="left">Coupon Value</td>
                <td width="14%" align="left">Expiry Date</td>
                <td width="18%" align="left" class="fixed_width">Actions</td>
            </tr>
             <?php
                if(!empty($this->showCoupons)) {
                    $i=0;
                     $i=$this->pageInfo['base'];
                    foreach($this->showCoupons as $row) {
                        $i++;
                        $className=($i%2) ? 'column2' : 'column1';
                       
             ?>
            <tr class="<?php echo $className ?>">
                 <td align="left"><?php echo $i; ?></td>
                 <td align="left"><?php echo $row->vCouponCode; ?></td>
                 <td align="left"><?php echo $row->nCouponCount; ?></td>
                 <td align="left"><?php echo $row->nCouponUsed; ?></td>
                 <td align="left"><?php echo ucfirst($row->vPricingMode); ?></td>
                 <td align="left"><?php echo $row->nCouponValue; ?></td>
                 <td align="left"><?php echo Utils::formatDate($row->dExpireOn." 00:00:00"); ?></td>
                 <td align="left">
                    <span class="edit"><a href="<?php echo ConfigUrl::base(); ?>coupon/createcoupon/<?php echo $row->nCouponId;?>" title="Edit" >Edit</a></span> |
                    <span class="delete"><a href="<?php echo ConfigUrl::base(); ?>coupon/coupondelete/<?php echo $row->nCouponId;?>" title="Delete" onClick="return confirm('Are you sure want to delete the Coupon ?');" >Delete</a></span>
                 </td>
            </tr> 
               <?php } } else {
                    ?>
            <tr class="column1">
                <td align="center" colspan="7">No Results Found</td>
            </tr>
                    <?php
                }
                ?>
        </table>
        <div class="more_entries">
            <div class="wp-pagenavi">
                    <?php if(!empty($this->showCoupons)&& $this->pageInfo['maxPages']>1) {
        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/coupon/index/');
    } ?>
            </div>
        </div>
       
       <!-- End Listing Form -->
   
   
   </div>    
</div>				

<!-- End Modification ----------------->
<!--
<div><?php // /echo $this->message; ?></div>
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="cms-table">
				<tr>
					<th width="5%" align="left" > Sl No   </th>
					<th width="20%" align="left" >Coupon Name</th>
					<th width="15%" align="left" >Number of Coupons</th>
                                        <th width="15%" align="left" >Number of Coupons Used</th>
                                        <th width="15%"align="left">Discount Rate</th>
					<th width="15%" align="left">Expiry Date</th>
					
					<th width="15%"align="left">  </th>
				</tr>

				<?php
				//$i=0;
				//foreach($this->showCoupons as $row)
				//{
				//$i++;
				?>
				<tr <?php //echo (($i%2==0)?' class="alternate-row"':'') ?>>
					<td><?php //echo $i; ?></td>
					<td><?php //echo $row->vCouponCode; ?></td>
					<td><?php //echo $row->nCouponCount; ?></td>
                                        <td><?php //echo $row->nCouponUsed; ?></td>
					<td><?php //echo $row->nCouponValue; ?></td>
                                        <td><?php //echo date('m/d/Y',strtotime($row->dExpireOn)); ?></td>
					<td>

					<a href="<?php //echo ConfigUrl::base(); ?>coupon/createcoupon/<?php //echo $row->nCouponId;?>" title="Edit" >Edit</a> &nbsp;&nbsp;&nbsp;
					
					<a href="<?php //echo ConfigUrl::base(); ?>coupon/coupondelete/<?php //echo $row->nCouponId;?>" title="Delete" onClick="return confirm('Are you sure want to delete the Coupon ?');" >Delete</a>
					
					</td>
				</tr>
                                <?php //} ?>
                                <tr>
                                    <td colspan="6"><a href="<?php //echo ConfigUrl::base(); ?>coupon/createcoupon">Add New Coupon</a></td>
  </tr>
                                </table>
-->