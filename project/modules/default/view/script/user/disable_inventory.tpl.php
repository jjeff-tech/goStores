  <div class="right_column">
    <div class="form_container">
  <div class="row">
       <div class="col-sm-12">
           
           <?php PageContext::renderPostAction($this->messageFunction);?>
           
                    <div class="mgr-t">
                         <h4>
                             You can you can use the service till <?php echo date('m/d/Y',  strtotime(PageContext::$response->InvPlanDetails->dateEnd))?> even after un subscribing the service,you activate the service anytime you want.
                        </h4>
                         <div class="clear"></div>
                    </div>
              </div>
      <br/><br/><br/>
      <div class="col-sm-12">
           
       
        

              <form name="frmSignUp" id="frmSignUp" method="POST" action="" class="form-horizontal ">
                
               
                
               
          
                <div class="form-group">
                  <div class="col-sm-12">
                    <div class="full-width-new">
                       <div class="col-md-3 col-sm-3">
                       </div>
                      <div class="col-md-3 col-sm-3 disa btn-right">
                    <input type="button" value="BACK" name="back_btn" onClick="window.history.go(-1);" class="ash-btn">
                  </div>

                  <div class="col-md-3 col-sm-3 disa">
                    <input type="submit"  name="btnConfirm" value="Unsubscribe"  class="send-btn">
                  </div>
                  <div class="col-md-3 col-sm-3">
                       </div>

                  </div>
                </div>
                </div>
              </form>
           
      
            
            </div>
      
    </div>
        
        
        
        
    <div class="clear"></div>
</div>
</div>

