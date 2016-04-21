<div class="container">
  <div class="row">
        <div class="content_area_inner">
          <div class="col-md-12">
         <!--  <div class="main_headings ">
           <h2><?php echo PageContext::$response->staticContentTitle;?></h2>
         </div> -->
           <div class="main-titile">  
         <h2>Let’s Get Started</h2>
       </div>
       <p class="text-center bm-mg">
        Tell us about your company and we’ll show you how the Make ready arms platform can instantly
modernize your firearms business to compete in the digital age.
       </p>
       <div class="contact-outer">
                  <div class="col-md-6 ">
          <div class="form-block  light-gray-bg border-clear text-left">
              <div class="main_headings">
               
              </div>
              <div class="col-sm-12">
                    <div>
                        <?php echo PageContext::$response->staticContent;?>
                        <div class="clear"></div>
                    </div>
              </div>
              <div class="col-md-12">
                    <?php
                    if(isset ( PageContext::$response->message)){
                    ?>
                    <div class="sucess"><?php echo  PageContext::$response->message;?></div>
                    <?php
                    }
                    ?>
                    <?php 
                    if(isset ( PageContext::$response->error)){
                    ?>
                    <div class="error"><?php echo  PageContext::$response->error;?></div>
                    <?php
                    }
                    ?>
              </div>
              <form name="frmSignUp" id="frmSignUp" method="POST" action="" class="form-horizontal ">
                
                <div class="form-group has-feedback">
                 
                  <div class="col-sm-12">
                    <input class="form-control"  type="text" id="name" name="name" value="<?php echo stripslashes(PageContext::$request['name']);?>" placeholder="Name*">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  
                  <div class="col-sm-12">
                    <input type="text" id="email" name="email" value="<?php echo stripslashes(PageContext::$request['email']);?>" placeholder="Email*" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  
                  <div class="col-sm-12">
                    <textarea id="feedback" placeholder="Feedback*" name="feedback" value="" class="form-control"><?php echo stripslashes(PageContext::$request['feedback']);?></textarea>
                  </div>
                </div>
                <?php if(PageContext::$response->recaptcha_enable=='Y'){ ?>
                <div class="form-group has-feedback">
                  <label class="col-sm-12 control-label" for="inputPassword">Security Code <span class="text-danger small">*</span></label>
                  <div class="col-sm-12">
                    <div class="captche"><div class="table-responsive"><?php echo PageContext::$response->recaptchaHTML; ?></div></div>
                  </div>
                </div>
                <?php } ?>
                <div class="form-group">
                  <div class="col-sm-12">
                    <!-- <input type="button" value="BACK" name="back_btn" onClick="window.history.go(-1);" class="button_orange2 jqBackToDomain">&nbsp;&nbsp; -->
                    <input type="submit"  name="btnFeedback" value="SUBMIT"  class="send-btn">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-md-6  cont">
            <div class="full-width ma-bm">
            
           <div class="round-icn"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
             </div>    
             
            <h4>For Sales Queries</h4>
            <h4><a href="#">admin@iscriptsdemo.com
            </a></h4>
            <h4>For Support</h4>
            <h4><a href="#">admin@iscriptsdemo.com</a></h4>
         
        </div>

            <div class="full-width">
            <div class="round-icn"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>

             </div> 
            <h4>Phone</h4>
            <h4><a href="#">310-565-7813</a></h4>
            
          
        </div>
             <div class="full-width">
            <div class="round-icn"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>

             </div> 
            <h4>Location</h4>
            <h4><a href="#">3179 Raccoon Run Seattle, WA 98109</a></h4>
            
          
        </div>
          </div>
</div>
            <div class="clear"></div>
        </div>
    </div>
  </div>
    <div class="clear"></div>
</div>


