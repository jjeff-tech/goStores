<script type="text/javascript">
    $(function() {
        //$('#paymentblock').hide('slow');
        //On selection of the store show payment
        $('#proceed').click(function() {
            // var store = $('#store').val();
            var flag = true;

            $('#err_template').html('');
            $('#err_store').html('');

            if($('#template').val()==''){
                flag = false;
                $('#err_template').html('Select the template');
            }

            if($('#store').val()==''){
                flag = false;
                $('#err_store').html('Select the store');

            }

            if(flag==true){
                $('#paymentblock').show('slow');
            }

        });



        $('#template').change(function() {

            var template = $('#template').val();
            var image = '';
            image = image_file_url+template.split('||')[1];
            var flag = '';
            flag = ImageExist(image);

            if(flag==true){
                $("#templateImage").attr("src", image);
            } else {
                image=image_url+'no-image.jpg';
                $("#templateImage").attr("src", image);

            }
        });



        // Click of payment

        $("#frmBuyTemplates").validate({
            rules: {
                template: {required: true},
                store: {required: true},
                fname: {required: function(){
                        return methodCheck();
                }},
                lname: {required: function(){
                        return methodCheck();
                }},
                email: {
                    required: function(){
                        return methodCheck();
                },
                    email: function(){
                        return methodCheck();
                }
                },
                add1: {required: function(){
                        return methodCheck();
                }},
                country: {required: function(){
                        return methodCheck();
                }},
                state: {required: function(){
                        return methodCheck();
                }},
                city: {required: function(){
                        return methodCheck();
                }},
                zip: {required: function(){
                        return methodCheck();
                }},
                ccno: {required: function(){
                        return methodCheck();
                }},
                expM: {required: function(){
                        return methodCheck();
                }},
                expY: {required: function(){
                        return methodCheck();
                }},
                cvv: {required: function(){
                        return methodCheck();
                }}
            },
            messages: {
                template: {required: "Please select template"},
                store: {required: "Please select store"},
                fname: {required: "Please enter first name"},
                lname: {required: "Please enter last name"},
                email: {required: "Please enter email",
                    email:"Please enter a valid email"
                },
                add1: {required: "Please enter your address"},
                country: {required: "Please enter your country"},
                state: {required: "Please enter your state"},
                city: {required: "Please enter your city"},
                zip: {required: "Please enter your zipcode"},
                ccno: {required: "Please enter credit card no"},
                expM: {required: "Please enter expiration month"},
                expY: {required: "Please enter expiration year"},
                cvv: {required: "Please enter cvv/cvv2 no"}
            },
            submitHandler: function(form) {

                if($('#paymentOption').val()=='authorize' || $('#paymentOption').val()=='paypalpro' || $('#paymentOption').val()=='paypalflow' || $('#paymentOption').val()=='yourpay' || $('#paymentOption').val()=='quickbook') {



                $('#paymentblock').hide('slow');
                $('#paymentStatus').removeClass('loader_off');
                $('#paymentStatus').addClass('loader_on');
                // do : ajax call for user duplication check with email
                //$('#frmBuyTemplates').show();
                var rootUrl = MAIN_URL+'index/paymentmiddleware';
                var strFrom = $("form").serialize();
                // alert(strFrom);
                $.post(rootUrl, $("#frmBuyTemplates").serialize(), function(data) {
                    $('#paymentStatus').removeClass('loader_on');
                    $('#paymentStatus').addClass('loader_off flashmsg');
                    $('#paymentblock').hide('slow');
                   //data['payment']['Amount']
                    if(data['payment']['success']==1 && data['installation']['ftp']==1){
                      $('#paymentStatus').html('Payment Completed and template installed into your store!.')
                      $('#paymentStatus').css('border-color','#72b55f');
                      $('#paymentStatus').css('color','#72b55f');
                    } else if(data['payment']['success']==1 && data['installation']['ftp']==0){
                      $('#paymentStatus').html('Payment Completed and template installation failed!.')
                      $('#paymentStatus').css('border-color','#72b55f');
                      $('#paymentStatus').css('color','#72b55f');
                    }else if(data['payment']['success']==0 && data['installation']['ftp']==0){
                      $('#paymentStatus').html('Payment and template installation failed!.')
                      $('#paymentStatus').css('border-color','#d82b2b');
                      $('#paymentStatus').css('color','#d82b2b');
                    }else if(data['payment']['success']==0){
                  	 var msg = data['payment']['Message'];
                  	 //.alert(msg);
                            $('#paymentStatus').html(msg)
                            $('#paymentStatus').css('border-color','#d82b2b');
                            $('#paymentStatus').css('color','#d82b2b');
                    }



                },"json");
             } else {
                var actionurl = MAIN_URL+"index/paymentmiddlewareformpost/";

                $("#frmBuyTemplates").attr("action", actionurl);
                $("#frmBuyTemplates").submit();

            }
            }
        });

    });

    function ImageExist(url)
    {
        var img = new Image();
        img.src = url;
        return img.height != 0;
    }

    function methodCheck() {

        if($('#paymentOption').val()=='authorize') {
            return true;
        } else if($('#paymentOption').val()=='paypalpro') {
            return true;
        } else if($('#paymentOption').val()=='paypalflow') {
            return true;
        } else if($('#paymentOption').val()=='yourpay') {
            return true;
        } else if($('#paymentOption').val()=='quickbook') {
            return true;
        } else{
            return false;
        }
    }
</script>
<div class="container">
  <div class="row">
        <div class="content_area_inner">
                <div class="main-titile marg20_top">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <h2 class="left"><?php echo $this->pageTitle;?></h2>
                    </div>
                    <!--<h4>Caption</h4>-->
                    
                    <div class="clear"></div>
                </div>
            <!-- Sign Up Form -->
            <div class="payment_form">
                <!-- Display Area for Registrant information -->
                <form name="frmBuyTemplates" id="frmBuyTemplates" class="form-horizontal" method="POST" action="">

               
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label" for="inputName">Template <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                    <select name="template" id="template" class="form-control">
                        <option value="">- Select -</option>
                        <?php
                        $selectedTemplateImage= NULL;

                        if(!empty(PageContext::$response->pageContents)) {
                            foreach(PageContext::$response->pageContents as $item) {
                                if(is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)) {
                                $selectedTemplate = ($item->nTemplateId==PageContext::$response->templateID) ? ' selected="selected"' : '';
                                if($item->nTemplateId==PageContext::$response->templateID) {
                                    $selectedTemplateImage = $item->homeScreenshot;
                                }

                                ?>
                        <option value="<?php echo $item->nTemplateId ?>||<?php echo $item->homeScreenshot ?>||<?php echo $item->vTemplateName; ?>||<?php echo $item->nCost; ?>"<?php echo $selectedTemplate; ?>><?php echo stripslashes($item->vTemplateName); ?>&nbsp;(<?php echo CURRENCY_SYMBOL.' '.$item->nCost;?>)</option>
                                <?php
                                }
                            }
                        }
                        ?>
                    </select>
                    <div class="clearfix"></div>
                    <span id="err_template" class="errortext"></span>
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label" for="inputName"></label>
                  <div class="col-sm-8">
                    <div class="sceenshot screenshot_template">
                         <?php
                            if(!empty(PageContext::$response->pageContents)) {
                                foreach(PageContext::$response->pageContents as $item) {
                                    if(is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)) {
                                        $display = $item->nTemplateId==PageContext::$response->templateID ? "" : "style='display:none'";
                                        $itemId = $item->nTemplateId==PageContext::$response->templateID ? ' id="templateImage"' : "";
                                        ?>
                            <img<?php echo $itemId ?> src="<?php echo IMAGE_FILE_URL.$item->homeScreenshot; ?>" alt="Template Home Page" border="0" width="380" height="231" <?php echo $display; ?> />
                                        <?php
                                    }
                                }
                            }

                            ?>
                    </div>
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label" for="inputName">Choose the store you wish to buy the template for <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                        <select name="store" id="store">
                            <option value="">- Select -</option>
                            <?php

                            if(!empty(PageContext::$response->userStores)) {
foreach(PageContext::$response->userStores as $item) {
                                    ?>
                            <option value="<?php echo $item['nPLId']?>||<?php echo $item['host']?>"><?php echo $item['host']?></option>
                                    <?php
                                }
}
?>
                        </select>
                        <div class="clearfix"></div>
                        <span style="float:left;" id="err_store" class="errortext"></span>
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label" for="inputName"></label>
                  <div class="col-sm-8">
                        <input type="button" name="proceed" id="proceed" class="orng_btnfreetrail" value="Proceed" />
                  </div>
                </div>
                <div class="form-group has-feedback" id="paymentblock" style="display:none;">
                  <label class="col-sm-3 control-label" for="inputName"></label>
                  <div class="col-sm-8">
                        <?php PageContext::renderPostAction('renderallpayment','payments');?>
                  </div>
                </div>

                <?php /*
                    <table width="100%"  border="0" cellspacing="0" cellpadding="2" align="center" class="buytemplate_tbl">
                        <tr>
                            <td colspan="4" align="center">
                                <span id="paymentStatus" class="loader_off" style="float:left;width:59%;padding:15px!important;">

                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%"  align="left"  valign="center" >Template<span class="mandred">*</span></td>
                            <td width="27%"  align="left">
                                <select name="template" id="template">
                                    <option value="">- Select -</option>
                                    <?php
                                    $selectedTemplateImage= NULL;

                                    if(!empty(PageContext::$response->pageContents)) {
                                        foreach(PageContext::$response->pageContents as $item) {
                                            if(is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)) {
                                            $selectedTemplate = ($item->nTemplateId==PageContext::$response->templateID) ? ' selected="selected"' : '';
                                            if($item->nTemplateId==PageContext::$response->templateID) {
                                                $selectedTemplateImage = $item->homeScreenshot;
                                            }

                                            ?>
                                    <option value="<?php echo $item->nTemplateId ?>||<?php echo $item->homeScreenshot ?>||<?php echo $item->vTemplateName; ?>||<?php echo $item->nCost; ?>"<?php echo $selectedTemplate; ?>><?php echo stripslashes($item->vTemplateName); ?>&nbsp;(<?php echo CURRENCY_SYMBOL.' '.$item->nCost;?>)</option>
                                            <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <span id="err_template" class="errortext"></span>

                            </td>
                            <td width="3%"  align="left">&nbsp;</td>
                            <td width="35%" rowspan="3"  align="center" valign="top">
							<div class="temp_imgpreview">
                                <?php
                                if(!empty(PageContext::$response->pageContents)) {
                                    foreach(PageContext::$response->pageContents as $item) {
                                        if(is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)) {
                                            $display = $item->nTemplateId==PageContext::$response->templateID ? "" : "style='display:none'";
                                            $itemId = $item->nTemplateId==PageContext::$response->templateID ? ' id="templateImage"' : "";
                                            ?>
                                <img<?php echo $itemId ?> src="<?php echo IMAGE_FILE_URL.$item->homeScreenshot; ?>" alt="Template Home Page" border="0" width="380" height="231" <?php echo $display; ?> />
                                            <?php
                                        }
                                    }
                                }

								?>
								</div>
                            </td>
                        </tr>
                        <tr>
                            <td  align="left"  valign="center" >Choose the store you wish to buy the template for<span class="mandred">*</span></td>
                            <td  align="left">
                                <select name="store" id="store">
                                    <option value="">- Select -</option>
                                    <?php

                                    if(!empty(PageContext::$response->userStores)) {
    foreach(PageContext::$response->userStores as $item) {
                                            ?>
                                    <option value="<?php echo $item['nPLId']?>||<?php echo $item['host']?>"><?php echo $item['host']?></option>
                                            <?php
                                        }
}
?>
                                </select>
                                <span style="float:left;" id="err_store" class="errortext"></span>
                            </td>
                            <td  align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" align="right" class="bordertop"><input type="button" name="proceed" id="proceed" class="orng_btnfreetrail" value="Proceed" /></td>
                        </tr>
                        <tr id="paymentblock" style="display:none;">
                            <td colspan="4"><?php PageContext::renderPostAction('renderallpayment','payments');?></td>
                        </tr>
                        
                        <!--<tr>
                            <td align="left" valign="center">&nbsp;</td>
                            <td align="left"><input type="submit" class= "button_orange_big"name="Submit" value="Proceed"></td>
                        </tr>-->
                    </table>
                    */ ?>
                </form>
            </div>
            <!-- Sign Up Form End -->
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>