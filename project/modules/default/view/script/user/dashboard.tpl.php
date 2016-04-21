  <div class="right_column">
    <div class="form_container">
      <div class="form_top">Dashboard</div>

      <div class="form_bgr">

          <?php PageContext::renderPostAction($this->messageFunction);?>
          <?php
          if(count(PageContext::$response->freeTrials) <= 0 && count(PageContext::$response->subscriptions) <= 0) {
          ?>
          <div class="dashboard_contentbox_wraper">
              <div class="bottomsection">You have not created any stores yet . <a href="<?php echo BASE_URL; ?>plan">Click here</a> to create one.</div>
            </div>
          <?php
          }else{
          ?>
          <?php foreach(PageContext::$response->freeTrials as $freeTrial){ ?>
            <div class="dashboard_contentbox_wraper">
            <!--<div class="topsection"><h3>My <?php //echo $freeTrial->vPName ;?> Installation</h3></div>-->
            <div class="bottomsection">
            <div class="bottomsection_left">Hosted On<br/>
                <a href="http://<?php echo Admincomponents::getStoreHost($freeTrial->nPLId); ?>" target="_blank"><?php echo 'http://'.Admincomponents::getStoreHost($freeTrial->nPLId); ?></a><br/>
               <?php
               if($freeTrial->nStatus!=0){
                ?>
                Expires on:<?php
                $dataArr = array("dGeneratedDate" => $freeTrial->dGeneratedDate,
                    "vBillingInterval" => $freeTrial->vBillingInterval,
                    "nBillingDuration" => $freeTrial->nBillingDuration);
                echo Utils::formatDateUS(Utils::formatServiceExpiry($dataArr)); ?></div>
            <div class="bottomsection_right"><a href="<?php echo BASE_URL; ?>index/upgrade/<?php echo $freeTrial->nPLId;?>">Upgrade</a>
                <!-- <a href="<?php echo BASE_URL; ?>index/unjoin/<?php echo $freeTrial->nPLId;?>">Cancel Account </a> -->
            </div>
            <?php
           }
           else{
               echo "This store has been Expired,Please contact administrator to renew the account.<br/>";
               echo '</div><div class="bottomsection_right">&nbsp;</div>';
           }
           ?>
            <div class="clear"></div>
            </div>
          </div>
          <?php }?>
           <?php foreach(PageContext::$response->subscriptions as $subscription){ ?>
             <div class="dashboard_contentbox_wraper">
            <!--<div class="topsection"><h3>My <?php //echo $subscription->vPName ;?> Installation</h3></div>-->
            <div class="bottomsection">
            <div class="bottomsection_left"><b>Hosted On </b><br/>
                <a href="http://<?php echo Admincomponents::getStoreHost($subscription->nPLId); ?>" target="_blank"><?php echo 'http://'.Admincomponents::getStoreHost($subscription->nPLId);?></a><br/>
               <?php
               if($subscription->userdomainstatus!=2){
                $dataArr = array("dGeneratedDate" => $subscription->dGeneratedDate,
                    "vBillingInterval" => $subscription->vBillingInterval,
                    "nBillingDuration" => $subscription->nBillingDuration);
                echo 'Expires on:'.date('m/d/Y',  strtotime($subscription->dDateStop));
            ?>

            </div>
            <div class="bottomsection_right">
                <a href="<?php echo BASE_URL; ?>user/unjoin/<?php echo $subscription->nPLId;?>" onclick="return confirm('Are your sure want to cancel the Store?')">Cancel Account</a>
            </div>
                 <div class="clear"></div>
             <div class="col-md-12">
                <div class="row">
                    <?php if($subscription->inventory_source_status==0 || $subscription->inventory_source_status==2){ ?>
                <!-- <div class="bottomsection_right">    
                <a href="<?php echo BASE_URL; ?>user/enable_inventory/<?php echo $subscription->nPLId;?>/1" onclick="" class="plan-btn">Enable Inventory Source</a>
                </div> -->
                    <?php } ?>
                    <?php if($subscription->inventory_source_status==1){ ?>
                <div class="bottomsection_right">    
               <!--  <a href="<?php echo BASE_URL; ?>user/disable_inventory/<?php echo $subscription->nPLId;?>/0" onclick="" class="plan-btn">Disable Inventory Source</a> -->
                </div>
                    <?php } ?>
                    
                    
                    <div class="bottomsection_right">
                        <a href="<?php echo BASE_URL; ?>user/edit_card/<?php echo $subscription->nPLId;?>" onclick="" class="plan-btn-edit" style="text-decoration: none!important;">Edit Card Details</a>
              </div>
                    </div>
             </div>    
                
                
            <?php
           }
           else{
               echo "This store has been Expired,Please contact administrator to renew the account.<br/>";
               echo '</div><div class="bottomsection_right">&nbsp;</div>';
           }
           ?>
           <!-- <div class="bottomsection_right"><input name="" value="Upgrade <?php //echo $freeTrial->vPName ;?>" class="button_orange" type="button"></div>-->
            <div class="clear"></div>
            </div>
          </div>
        <?php } ?>
        <div class="dashboard_contentbox_wraper" style="padding:0px !important;">
            <div class="bottomsection">If you want to create more stores then kindly <a href="<?php echo BASE_URL; ?>plan">click here</a> to continue.</div>
        </div>
        <?php } ?>
<!--<div class="dashboard_contentbox_wraper">
<div class="topsection"><h3>My Hosted Demo Installations</h3></div>
<div class="bottomsection">
<div class="bottomsection_left"><b>Hosted Demo </b><br/>
  <a href="http://mahesh.tryactivecollab.com">http://mahesh.tryactivecollab.com</a><br/>
  Expires on:Oct 19, 2012</div>
<div class="bottomsection_right"><input name="" value="Buy Socialware" class="button_orange" type="button"></div>
<div class="clear"></div>
</div>
</div>
<div class="dashboard_contentbox_wraper">
<div class="topsection"><h3>My Hosted Demo Installations</h3></div>
<div class="bottomsection">
<div class="bottomsection_left"><b>Hosted Demo </b><br/>
  <a href="http://mahesh.tryactivecollab.com">http://mahesh.tryactivecollab.com</a><br/>
  Expires on:Oct 19, 2012</div>
<div class="bottomsection_right"><input name="" value="Buy Multicart" class="button_orange" type="button"></div>
<div class="clear"></div>
</div>
</div>

        <div class="column_containers">
          <div class="general_content_boxes">
            <div class="dboard_block1">
              <h3>Heading</h3>
              <div class="contents_container"> <div class="content_items"><table width="100%" cellspacing="1" cellpadding="0" border="0" class="table_listing">
  <tbody>
    <tr>
      <th valign="top" width="10%" align="left">S.No. </th>
      <th valign="top" width="60%" align="left">Title</th>
      <th valign="top" width="30%" align="center">No. of Listings</th>
    </tr>
    <tr class="row_color1">
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
    </tr>
  </tbody>
</table>
</div>
</div>
</div>
            <div class="dboard_block2">
              <h3>Heading</h3>
              <div class="contents_container"> <div class="content_items"><table width="100%" cellspacing="1" cellpadding="0" border="0" class="table_listing">
  <tbody>
    <tr>
      <th valign="top" width="10%" align="left">S.No. </th>
      <th valign="top" width="60%" align="left">Title</th>
      <th valign="top" width="30%" align="center">No. of Listings</th>
    </tr>
    <tr class="row_color1">
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
    </tr>
  </tbody>
</table></div></div>
            </div>
            <div class="clear"></div>
          </div>
        </div>
-->



      </div>

    </div>
  </div>
