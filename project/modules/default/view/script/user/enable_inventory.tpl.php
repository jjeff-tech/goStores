  <div class="right_column">
    <div class="form_container">
  <div class="row">
       <div class="col-sm-12">
           
           <?php PageContext::renderPostAction($this->messageFunction);?>
           
                    <div>
                         <h4>
                        You will be Charged $<?php echo PageContext::$response->inventory_source_amount ?> in an interval period of <?php echo PageContext::$response->inventory_source_plan_duration ?> days from below credit card for enabling this feature,you can cancel this anytime.
                        </h4>
                         <div class="clear"></div>
                    </div>
              </div>
      <br/><br/><br/>
      <div class="col-sm-12">
            <div class="col-md-4  cont">
            <div class="full-width ma-bm">
            <div class="col-md-4 col-sm-4 ryt-text">
               <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/creditcard.png">
            </div>
             <div class="col-md-8 col-sm-8 sd-brd">
            <h4>Card Number</h4>
            <h4><a href="#"><?php echo PageContext::$response->card_number; ?></a></h4>
            <h4>Expiry Date</h4>
            <h4><a href="#"><?php echo PageContext::$response->expiration_date; ?></a></h4>
          </div>
        </div>

            
          </div>
          <div class="col-md-8">
          <div class="form-block">

              <form name="frmSignUp" id="frmSignUp" method="POST" action="" class="form-horizontal ">
                
               
                
               
          
                <div class="form-group">
                  <div class="full-width-new main-mrg">
                    <div class="col-md-4 col-sm-4">
                    <input type="button" value="BACK" name="back_btn" onClick="window.history.go(-1);" class="ash-btn">
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <input type="submit"  name="btnConfirm" value="Subscribe"  class="send-btn">
                  </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
            
            </div>
      
    </div>
        
        
        
        
    <div class="clear"></div>
</div>
</div>

