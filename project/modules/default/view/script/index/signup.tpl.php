<script type="text/javascript">
function validateNumber(evt){
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;

    if(key == 8 || key == 127){
        theEvent.returnValue = true;
    }else{
        key = String.fromCharCode( key );
        var regex = /[0-9]|\.|\-/;
        if(!regex.test(key)){
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }
}
</script>

  <script type="text/javascript" src="/MakeReadyArms/project/styles/themes/theme2/js/sliding.form.js"></script>

<div class="register-outer">
    <div class="register-left">
        <div class="full-width">
                <h2 class="no-mrg"><?php echo $this->pageTitle;?> Here</h2>
                <p>Sign up with GOSTORES</p>
<!--
                <form class="demo-form">
  <div class="form-section">
    <label for="firstname">First Name:</label>
    <input type="text" class="form-control" name="firstname" required="">

    <label for="lastname">Last Name:</label>
    <input type="text" class="form-control" name="lastname" required="">
  </div>

  <div class="form-section">
    <label for="email">Email:</label>
    <input type="email" class="form-control" name="email" required="">
  </div>

  <div class="form-section">
    <label for="color">Favorite color:</label>
    <input type="text" class="form-control" name="color" required="">
  </div>

  <div class="form-navigation">
    <button type="button" class="previous btn btn-info pull-left">&lt; Previous</button>
    <button type="button" class="next btn btn-info pull-right">Next &gt;</button>
    <input type="submit" class="btn btn-default pull-right">
    <span class="clearfix"></span>
  </div>

</form> -->

              </div>
    </div>
    <div class="register-right">
        <div class="content_area_inner">
          <div class="form-block center-block p-30 light-gray-bg border-clear text-left ">
            <div class="no-disply">
              <form name="frmSignUp" id="frmSignUp" method="POST" action="" class="form-horizontal">
                <div class="col-sm-12">
                  <span id="emailAddress_err" class="error"><?php echo $this->errMsg; ?></span>
                </div>
                <div class="form-group has-feedback">
                 <!--  <label class="col-sm-3 control-label" for="inputName">First Name <span class="text-danger small">*</span></label> -->
                  <div class="col-sm-12">
                    <input name="firstName" id="firstName" placeholder="First Name*" type="text" class="form-control"  value="<?php echo stripslashes($this->dataArr['firstName']); ?>">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <!-- <label class="col-sm-3 control-label" for="inputName">Last Name <span class="text-danger small">*</span></label> -->
                  <div class="col-sm-12">
                    <input name="lastName" id="lastName" placeholder="Last Name" type="text" value="<?php echo stripslashes($this->dataArr['lastName']); ?>" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <!-- <label class="col-sm-3 control-label" for="inputEmail">Email <span class="text-danger small">*</span></label> -->
                  <div class="col-sm-12">
                    <input name="emailAddress" id="emailAddress" placeholder="Email*"  type="text" value="<?php echo stripslashes($this->dataArr['emailAddress']); ?>" class="form-control">
                  </div>
                </div>

                <div class="form-group has-feedback">
                  <!-- <label class="col-sm-3 control-label" for="inputEmail">Email <span class="text-danger small">*</span></label> -->
                  <div class="col-sm-12">

                    <!-- country codes (ISO 3166) and Dial codes. -->



                    <input name="phone" id="phone" placeholder="Phone number*" maxlength="10" type="text" value="<?php echo stripslashes($this->dataArr['phone']); ?>" class="form-control" onkeypress="validateNumber(event);" autocomplete="off">
                  </div>
                </div>




                <div class="form-group has-feedback">
                  <!-- <label class="col-sm-3 control-label" for="inputPassword">Password <span class="text-danger small">*</span></label> -->
                  <div class="col-sm-12">
                    <input name="password" placeholder="Password*" id="password" type="password" value="" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <!-- <label class="col-sm-3 control-label" for="inputPassword">Re-type Password <span class="text-danger small">*</span></label> -->
                  <div class="col-sm-12">
                    <input name="confirmPassword" placeholder="Re-type Password*" id="confirmPassword" type="password" value="" class="form-control">
                  </div>
                </div>
                 <?php if(PageContext::$response->recaptcha_enable=='Y'){ ?>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label" for="inputPassword">Security Code <span class="text-danger small">*</span></label>
                  <div class="col-sm-12">
                    <div class="table-responsive">
                    <?php echo PageContext::$response->recaptchaHTML; ?>
                    </div>
                  </div>
                </div>
                <?php } ?>
                <div class="form-group">
                  <div class="col-sm-12 text-right">
                    <input type="submit" class= "next-btn"name="Submit" value="Next">
                  </div>
                </div>
              </form>
            </div>
            </div>

            <div class="clear"></div>
        </div>
      </div>

    <div class="clear"></div>


</div>
