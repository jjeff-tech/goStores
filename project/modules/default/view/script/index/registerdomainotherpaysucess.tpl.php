<div class="inner_page_title">
<h4><?php echo $this->staticContentTitle;?><span></span></h4>
</div>

<div class="page_center_content_area">

<div style="position: relative;">
<div class="col-md-8 col-md-offset-2">
    <!-- Display area for " PROCEED TO PAY "-->
                                                                    <div class="storecration_instalation_wrapper" style="display:none"  id="jqProgress">
                                                                        <h3>Please wait your installation is in progress</h3>
                                                                        <div class="storecration_instalation_wrapper_inner">

                                                                            <div class="store_installationimg">	</div>

                                                                            <div style="display:block;">
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
                                                                                    <div class="clear"></div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="overlay" id="overlay" style="display:none;">

                                                                                <div class="clear"></div>
                                                                            </div>

                                                                            <div class="clear"></div>
                                                                        </div> </div>
                                                                            <div id="jqMessage" style="display:none;">
                                                                                <div class="storecration_instalation_wrapper" style="">
                                                                        <h3>Your Installation Successfully Completed</h3>
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>
                                                                            
                                                                             <div class="pymnt_sucessmsgs" style="text-align:center;">
                                                                                 <?php
                                                                                    if(PageContext::$response->registerDomain['success'] == 1)
                                                                                    echo PageContext::$response->registerDomain['list'];
                                                                                    ?>
                                                                                 <div class="clear"></div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                    </div>
                                                                            </div>

                                                                        <?php
                                                                                    if(PageContext::$response->registerDomain['success'] != 1){
                                                                            ?>
                                                                        <div class="error_msg_container">
                                                                                <h2>Sorry !</h2>
                                                                                <p class="jqErrorMessage"><?php echo PageContext::$response->registerDomain['list'];?></p>
                                                                            </div>
                                                                    <?php
                                                                      }
                                                                    ?>
                                                                    <!-- installation completed----------------------------------------------------------------------- -->

                                                                    <div class="storecration_instalation_wrapper" style="display:none">
                                                                        <h3>Your Installation Successfully Completed</h3>
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>
                                                                            <h4>Congratulations!!!</h4>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                    </div>
                                                                    <!-- installation completed----------------------------------------------------------------------- -->



<div class="clear"></div>

</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
<script language="javascript">
     <?php
        if(PageContext::$response->registerDomain['success'] == 1){
    ?>
     otherpaymant('1');
    <?php
    }/*else{?>
        otherpaymantfailed('<?php echo PageContext::$response->registerDomain['list'];?>')
        
        <?php
    }*/
    ?>
</script>
