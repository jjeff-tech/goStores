<!--
My Store Name:

(Don't worry, this can be changed at anytime.) <br><input type="text" name="" id="">.mydomain.com<input type="submit" name="Check Availability" value="Check Availability" id="jqDomainCheck">
-->
<div class="container">
  <div class="row">

      <div class="content_area_inner" style="min-height: 500px;">

            <div class="main-titile marg20_top">
               
                    <h2>Start your free <?php echo PageContext::$response->freePlanPeriod; ?> trial</h2>
            <?php
              if( $this->setFlag==0) {
                    echo $this->errMsg;
            } ?>
               
                <div class="clearfix"></div>
            <div class="col-md-8 col-md-offset-2">
                
                <div class="storecration_instalation_wrapper"  id="jqProgress" style="display:none;">
                    <h3>Please wait your installation is in progress</h3>
                    <div class="storecration_instalation_wrapper_inner">

                        <div class="store_installationimg">	</div>

                        <div>
                            <div class="progress_outer">
                                <div class="progress_bar">
                                    <!--div class="pointer">
                                        <p><span>Phase 1: Originate</span><br /> Analyzing input, preparing installation files and scripts</p>
                                    </div>
                                    <div class="bar" style="width:0%;"></div-->
                                    
                                    <div class="pointer" id="jqProgressMessage">
                                    </div>
                                    <div class="progress" role="progressbar" data-goal="-50" aria-valuemin="-100" aria-valuemax="0">
                                        <div class="progress__bar"><span class="progress__label"></span></div>
                                    </div>
                                    
                                    
                                </div>
                                <!--div class="progress_count">0&nbsp;%</div-->
                                
                                
                                
                                
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div> </div>

              

                <div id="jqMessage">


                </div>
                <div class="overlay" id="overlay" style="display:none;" align="center">
                    <table width="100%" height="50%"><tr><td align="center"><img src="<?php echo IMAGE_URL;?>loader.gif"></td></tr></table>
                    <input type="hidden" id="jqUserExistFlag" value="1">
                    <input type="hidden" id="jqDomainStatusVal" value="1">
                </div>

                <div class="box" id="box">

                </div>
            </div>
            </div>
            <div id="product_configuration_status_loader" title="" style="display:none;">
                <p>Please wait while we setup the application for you...</p>
                <span class="modal-ajax-loader"><img src="<?php echo BASE_URL; ?>project/styles/images/ajax-loader1.gif"></span>
            </div>

            <div class="error_msg_container1" style="display: none;">
                <h2>Sorry !</h2>
                <p id="jqErrorMessage"></p>
            </div>
        </div>

        
    </div>
    <div class="clear"></div>
</div>
<?php if( $this->setFlag==1) {
    ?>
<input type="hidden" id="jqtxtStoreName" value="<?php echo $this->txtStoreName;?>">
<input type="hidden" id="jqtxtEmail" value="<?php echo $this->txtEmail;?>">
<input type="hidden" id="txtName" value="<?php echo $this->txtName;?>">
<input type="hidden" id="jqtxtPUserPassword" value="<?php echo $this->txtPassword;?>">
<script language="javascript">
    cretaedomain();
</script>
    <?php } ?>
    </div>