<div class="form_container">
<div class="form_top">DASHBOARD</div> 		
<div class="form_bgr">
   
<div class="column_containers" >
<div class="general_content_boxes">
    <div class="dboard_block1">
<h3>Free Trials By Month</h3>
<div class="contents_container">
<?php $this->graph2->renderchart(); ?>
</div>
</div>
<div class="dboard_block2">
<h3>Subscriptions By Month</h3>
<div class="contents_container">
<?php $this->graph4->renderchart(); ?>
</div>
</div>
</div>
</div>

    
<div class="column_containers" >
<div class="general_content_boxes">
    <div class="dboard_block1">
<h3>Upgrades By Month</h3>
<div class="contents_container">
<?php $this->graph3->renderchart(); ?>
</div>
</div>
<div class="dboard_block2">
<h3>Installations By Product</h3>
<div class="contents_container">
   <!-- <table id="id-form" class="formstyle">
         <tr>
               <td>Start Date :<span class="mandred">*</span></td>
               <td><input id="graphStartDate" name="graphStartDate" validate="required:true " class="report_date_field"  value="08/01/2012"  title="Please provide Start Date" tabindex="5" type="text" style="width: 90px;" /></td>
        
               <td>End Date :<span class="mandred">*</span></td>
               <td><input id="graphEndDate" name="graphEndDate" class="report_date_field { messages{required:Please enter Start Date}}"  value="10/31/2012"  title="Please provide End Date" tabindex="5" type="text" style="width: 90px;" validate="required:true"/></td>
               <td colspan="2" align="left"><input id="plot_graph" type="submit" name="btnAdd"  value="Go"  /></td>
         </tr>
    </table>-->
    <div>
<div ><?php $this->graph5->renderchart(); ?></div>
<div  ><?php $this->graph6->renderchart(); ?></div>
    </div>
</div>
</div>
</div>
</div>
<div class="column_containers" >
<div class="general_content_boxes">
    <div class="dboard_block3">
<h3>Trends</h3>
<div class="contents_container">
<?php $this->graph->renderchart(); ?>
</div>
    </div>
</div>
</div>
   
<div class="column_containers" >
<div class="general_content_boxes">
  <div class="dboard_block1">
    <h3>Pending Invoices</h3>
    <div class="contents_container">
      <div class="content_items">
        <table width="100%" cellspacing="1" cellpadding="0" border="0" class="table_listing">
          <tbody>
            <tr>
              <th valign="top" width="10%" align="center">#</th>
              <th valign="top" width="60%" align="left">User</th>
              <th valign="top" width="30%" align="center">Amount</th>
            </tr>
            <?php
            if(!empty($this->latestInvArr)) {               
                foreach($this->latestInvArr as $invItem) {                    
            ?>
            <tr class="row_color1">
                <td valign="top" align="center"><?php echo $invItem->nInvId ?></td>
              <td valign="top" align="left"><?php echo $invItem->vUsername ?></td>
              <td valign="top" align="center"><?php $amount =(!empty($invItem->nTotal)) ? $invItem->nTotal : 0; echo CURRENCY_SYMBOL.Utils::formatPrice($amount); ?></td>
            </tr>
            <?php

                }
            }
            
         //   if(count($this->latestInvArr)<= 4) {
              // for($i=4; $i >= count($this->latestInvArr); $i--){
            ?>
          <!--  <tr class="row_color1">
                <td valign="top" align="center">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
              <td valign="top" align="center">&nbsp;</td>
            </tr>-->
            <?php
           //    }
           // }
            ?>           
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="dboard_block2">
    <h3>Latest Payments</h3>
    <div class="contents_container">
      <div class="content_items">
        <table width="100%" cellspacing="1" cellpadding="0" border="0" class="table_listing">
          <tbody>
            <tr>
              <th valign="top" width="10%" align="center">#</th>
              <th valign="top" width="60%" align="left">User</th>
              <th valign="top" width="30%" align="center">Amount</th>
            </tr>
            <?php               
                if(!empty($this->latestPymtArr)) {
                    foreach($this->latestPymtArr as $paymentItem) {
            ?>
            <tr class="row_color1">
                <td valign="top" align="center"><?php echo $paymentItem->nPaymentId ?></td>
              <td valign="top" align="left"><?php echo $paymentItem->vUsername ?></td>
              <td valign="top" align="center"><?php $amount =(!empty($paymentItem->nAmount)) ? $paymentItem->nAmount : 0; echo CURRENCY_SYMBOL.Utils::formatPrice($paymentItem->nAmount); ?></td>
            </tr>
            <?php
                    }
                } // End If
            
        //    if(count($this->latestPymtArr)<= 4) {
             //  for($i=4; $i >= count($this->latestPymtArr); $i--){
            ?>
        <!--    <tr class="row_color1">
                <td valign="top" align="center">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
              <td valign="top" align="center">&nbsp;</td>
            </tr>-->
            <?php
               //}
         //   }
            ?>

            <!--<tr class="row_color1">
              <td valign="top" align="center">1</td>
              <td valign="top" align="left">product 1</td>
              <td valign="top" align="center">0</td>
            </tr>
            <tr class="row_color1">
              <td valign="top" align="center">2</td>
              <td valign="top" align="left">product 2</td>
              <td valign="top" align="center">0</td>
            </tr>
            <tr class="row_color1">
              <td valign="top" align="center">3</td>
              <td valign="top" align="left">product 3</td>
              <td valign="top" align="center">0</td>
            </tr>
            <tr class="row_color1">
              <td valign="top" align="center">4</td>
              <td valign="top" align="left">product 4</td>
              <td valign="top" align="center">0</td>
            </tr>
            <tr class="row_color1">
              <td valign="top" align="center">5</td>
              <td valign="top" align="left">product 5</td>
              <td valign="top" align="center">0</td>
            </tr>-->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
</div>
  



</div>
<div class="form_bottom"></div>
</div>
