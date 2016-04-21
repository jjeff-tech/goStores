$(function(){

   // Validate
   //$("#jqGeneralForm").validate();
   

   //General Form Validations
   $("#jqGeneralForm").validate({
        rules: {
            siteName: { required: true },
            siteTitle: { required: true },
            adminEmail: { required: true, email:true },
            googleAdsense: {
                required: function(){
                    return validateFieldsWithCheckbox('jqAdsense');
                }
            },
            streamsend_loginid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqStream');
                }
            },
            streamsend_key: {
                required: function(){
                    return validateFieldsWithCheckbox('jqStream');
                }
            },
            recaptcha_private_key: {
                required: function(){
                    return validateFieldsWithCheckbox('jqRecaptcha');
                }
            },
            recaptcha_public_key: {
                required: function(){
                    return validateFieldsWithCheckbox('jqRecaptcha');
                }
            },
            streamsend_listid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqStream');
                }
            },
            licenseKey: {
                required: true
            },
            currency_symbol:{
                required:true
            }
        }
    });
   
    //Payment Form Validations
    $("#jqPaypalForm").validate({
        rules: {
            paypalemail: {
                required: function(){
                    return validateFieldsWithCheckbox('jqEnablePaypal');
                },
                email:function(){
                    return validateFieldsWithCheckbox('jqEnablePaypal');
                }
            },
            authorize_loginid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqAuthorize');
                }
            },
            authorize_transkey: {
                required: function(){
                    return validateFieldsWithCheckbox('jqAuthorize');
                }
            },
            authorize_email: {
                required: function(){
                    return validateFieldsWithCheckbox('jqAuthorize');
                },
                email:function(){
                    return validateFieldsWithCheckbox('jqAuthorize');
                }
            },
            twoco_vendorId: {
                required: function(){
                    return validateFieldsWithCheckbox('jqTwoCheckout');
                }
            },
            paypalpro_username: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalPro');
                }
            },
            paypalpro_password: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalPro');
                }
            },
            paypalpro_signature: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalPro');
                }
            },
            paypalexpress_username: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalExpress');
                }
            },
            paypalexpress_password: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalExpress');
                }
            },
            paypalexpress_signature: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalExpress');
                }
            },
            paypaladvanced_username: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalAdvanced');
                }
            },
            paypaladvanced_password: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalAdvanced');
                }
            },
            paypaladvanced_partnerid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalAdvanced');
                }
            },
            paypaladvanced_vendorid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalAdvanced');
                }
            },
            paypalflow_partnerid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalFlow');
                }
            },
            paypalflow_vendorid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalFlow');
                }
            },
            payflow_password: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalFlow');
                }
            },
            paypalflowlink_partnerid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalFlowLink');
                }
            },
            paypalflowlink_vendorid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalFlowLink');
                }
            },
            payflowlink_password: {
                required: function(){
                    return validateFieldsWithCheckbox('jqPaypalFlowLink');
                }
            },
            ogone_partnerid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqOgone');
                }
            },
            ogone_vendorid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqOgone');
                }
            },
            moneybookers_emailid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqMoneybookers');
                },
                email:function(){
                    return validateFieldsWithCheckbox('jqMoneybookers');
                }
            },
            braintree_merchantId: {
                required: function(){
                    return validateFieldsWithCheckbox('jqBrainTree');
                }
            },
            braintree_publickey: {
                required: function(){
                    return validateFieldsWithCheckbox('jqBrainTree');
                }
            },
            braintree_privatekey: {
                required: function(){
                    return validateFieldsWithCheckbox('jqBrainTree');
                }
            },
            gcheck_merchant_id: {
                required: function(){
                    return validateFieldsWithCheckbox('jqGoogleCheckout');
                }
            },
            gcheck_merchant_key: {
                required: function(){
                    return validateFieldsWithCheckbox('jqGoogleCheckout');
                }
            },
            gcheck_currency: {
                required: function(){
                    return validateFieldsWithCheckbox('jqGoogleCheckout');
                }
            },
            gcheck_btn_checkout: {
                required: function(){
                    return validateFieldsWithCheckbox('jqGoogleCheckout');
                }
            },
            yourpay_storeid: {
                required: function(){
                    return validateFieldsWithCheckbox('jqYourpay');
                }
            },
            yourpay_pemfile: {
                required: function(){
                    return validateFieldsWithCheckbox('jqYourpay');
                }
            },
            quickbook_appname: {
                required: function(){
                    return validateFieldsWithCheckbox('jqQuickbook');
                }
            },
            quickbook_key: {
                required: function(){
                    return validateFieldsWithCheckbox('jqQuickbook');
                }
            }
        }
    });


   //Domain Registrar Form Validations
   $("#jqDomainRegistrarForm").validate({
        rules: {
            godaddy_id: {
                required: function(){
                    return validateFieldsWithSelectbox('jqDomainTypeId','GODADDY');
                }
            },
            godaddy_password: {
                required: function(){
                    return validateFieldsWithSelectbox('jqDomainTypeId','GODADDY');
                }
            },
            priceDomiainRegistration: {
                required: true,
                number:true
            },
            enom_user: {
                required: function(){
                    return validateFieldsWithSelectbox('jqDomainTypeId','ENOM');
                }
            },
            enom_password: {
                required: function(){
                    return validateFieldsWithSelectbox('jqDomainTypeId','ENOM');
                }
            }
        }
    });

    //Social Settings Form Validations
   $("#jqSocialSettingsForm").validate({
        rules: {
            facebookUrl: {
                required: function(){
                     return validateFieldsWithCheckbox('jqFacebook');
                }
            },
            twitterUrl: {
                required: function(){
                    return validateFieldsWithCheckbox('jqTwitter');
                }
            },
            linkedInUrl: {
                required: function(){
                    return validateFieldsWithCheckbox('jqLinkedIn');
                }
            }
        }
    });

    //Server Settings Form Validations
    $("#jqServerSettingsForm").validate({
        rules: {
            site_operation_mode: {
                required: function(){
                     return validateFieldsWithRadio('site_operation_mode');
                },
                errorPlacement: function(error, element) {
                    //error.insertBefore( $('#errorContainer1'));
                    $('#errorContainer1').html(error);
                }
                
            },
            site_operation_park_domain: {
                required: function(){
                    return validateFieldsWithRadio('site_operation_park_domain');
                },
                errorPlacement: function(error, element) {
                    //error.insertBefore( $('#errorContainer2'));
                    $('#errorContainer2').html(error);
                }
            }
            
        }
    }); 
   

   $('.jqToggle').change(function(){showHideDiv(this);});
   hideDivOnLoad();


   // Onchange Domain Registrar Type
   $('.jqDomainType').change(function(){      
       toggleDivs(this.value);
   });

   // On Load Set the Registar fields active respective of the registrar type
   if($('#jqDomainTypeId').val()!=''){
       toggleDivs($('#jqDomainTypeId').val());
   }

   // Site operation mode on click
   $("input[name='site_operation_mode']").click(function() {
        validateFieldsServerSettings();
   });

   $("input[name='site_operation_park_domain']").click(function() {
        validateFieldsServerSettings();
   });
   
});


function showHideDiv(elem)
{
    var divCls
    if($(elem).attr('checked')){
        divCls = $(elem).attr('id');
        $('.'+divCls).fadeIn('slow');
    }
    else{
        divCls = $(elem).attr('id');
        $('.'+divCls).fadeOut('slow');
    }
}

function hideDivOnLoad()
{
    var divCls
    $('.jqToggle').each(function(i, j){
       if(!$(j).attr('checked')){
            divCls = $(j).attr('id');
            $('.'+divCls).fadeOut('fast');
       } 
    });
}

function toggleDivs(elemValue){

   if(elemValue =='GODADDY'){
      $('.jqGoDaddy').fadeIn('fast');
      $('.jqEnom').fadeOut('fast');
   }else{
      $('.jqGoDaddy').fadeOut('fast');
      $('.jqEnom').fadeIn('fast');
   }
}

function validateFieldsWithCheckbox(checkBoxId){
    var checkVal = ($("#"+checkBoxId).is(':checked'))?true:false;
    return checkVal;
}

function validateFieldsWithRadio(radioName){
    /* Sample radio name :  */
    
    //var checkVal = ($("input[name='"+radioName+"']").is(':checked'))?true:false;
    var checkVal = true;
    if(radioName=='site_operation_mode'){
        validateFieldsServerSettings();
    }
    return checkVal;
}

function validateFieldsWithSelectbox(selectBoxId,selectBoxVal){
    var selectVal = ($("#"+selectBoxId).val()==selectBoxVal)?true:false;
    return selectVal;
}

function validateFieldsServerSettings(){    
    //site_operation_mode / site_operation_park_domain
    var operationMode = $("input[name='site_operation_mode']:checked").val();
    if(operationMode == 'M'){        
        $('input[name=site_operation_park_domain][value=Y]').prop('checked', 'checked');
        $('input[name=site_operation_park_domain][value=N]').prop('checked', '');
    }
    return true;
}

