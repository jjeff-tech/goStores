var isSetUpComplete = false;
var barWidth        = 0;
var messageArray    = new Array("<p><span>Phase 1: Originate</span><br />Analyzing input, preparing installation files and scripts</p>",
    "<p><span>Phase 2: Focus</span><br />Setting proper foundation, folders, permissions</p>",
    "<p><span>Phase 3: Design</span><br />Creating platform , databases, images, styles</p>",
    "<p><span>Phase 4: Build</span><br />Adding features, functionalities, themes and data</p>",
    "<p><span>Phase 5: Occupy </span><br />Almost done. We are doing final touch ups</p>");
$(document).ready(function(){
    
    $('#jqProceedToPay').click(proceedtopay);
    $('#jqBillPayTab').click(proceedtopay);
    
    $("#jqCheckSubdomainExist").click(checksubdomainavailability);
    $("#jqCheckDomainExist").live("click",{
        param: 0
    },checkdomainavailability);
    $(".jqOptionStyle").click(showhideinstalloption);
    $(".jqSerciceCatVal").click(priceUpdate);
    $(".jqSerciceCatValRd").click(priceUpdate);
    $("#jqProductPayNow").live("submit",{
        param: 0
    },registorDomain);

    $("#jqSubDomainCreate").click(paynow);

    $("#frmUsers").live("submit",{
        param: 0
    },paynow);
    $("#frmUsers").validate({
        rules: {
            fname: {
                required: true
            },
            lname: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            /*  ccno1: {
                required: true
            } ,
            cvv1: {
                required: true
            },*/
            add1: {
                required: true
            },
            state: {
                required: true
            },
            city: {
                required: true
            },
            zip: {
                required: true
            }
        },
        messages: {
            fname: {
                required: "Please enter first name"
            },
            lname: {
                required: "Please enter last name"
            },
            email: {
                required: "Please enter email",
                email:"Please enter a valid email"
            }	,
            /* ccno: {
                required: "Please enter your card number"
            },
            cvv: {
                required: "Please enter cw"
            },*/
            
            add1: {
                required: "Please enter your address"
            },
            state: {
                required: "Please enter your state"
            },
            city: {
                required: "Please enter your city"
            },
            zip: {
                required: "Please enter your zip code"
            }

        }
    });

    $("#jqNumYears").click(checkdomainavailability);
    /*
     * Form Validation
     */
    //alert('1');
    $('#jqShowMessage').text('');
    $("#jqProductPayNow").validate({
        rules: {
            RegistrantFirstName: {
                required: true
            },
            RegistrantEmailAddress: {
                required: true, 
                email: true
            },
            RegistrantLastName: {
                required: true
            } ,
            RegistrantJobTitle: {
                required: true
            },
            RegistrantOrganizationName: {
                required: true
            },
            RegistrantAddress1: {
                required: true
            },
            RegistrantCity: {
                required: true
            },
            RegistrantPostalCode:{
                required: true
            },
            RegistrantFax: {
                required: true
            },
            RegistrantPhone: {
                required: true
            },
            ccno2:{
                required: true
            },
            cvv2:{
                required: true
            }



        },
        messages: {
            RegistrantFirstName: {
                required: "Please enter first name"
            },
            RegistrantEmailAddress: {
                required: "Please enter email", 
                email:"Please enter a valid email"
            }	,
            RegistrantLastName: {
                required: "Please enter last name"
            },
            RegistrantJobTitle: {
                required: "Please enter job title"
            },
            RegistrantOrganizationName: {
                required: "Please enter organization name"
            },
            RegistrantAddress1: {
                required: "Please enter address"
            },
            RegistrantCity: {
                required: "Please enter city"
            },
            RegistrantPostalCode: {
                required: "Please enter postal/zip code"
            },
            RegistrantFax: {
                required: "Please enter fax"
            },
            RegistrantPhone: {
                required: "Please enter phone number"
            },
            ccno2: {
                required: "Please enter card number"
            },
            cvv2: {
                required: "Please enter cvv number"
            }
        }
    });
    
    $("#jqBackToPlan").click(function(){
        showPlansList('tab4')
        });
    $(".jqPlans").click(function(){
        updateSelectedPlan(this)
        });
    $(".jqBackToDomain").click(function(){
        showPlansList('tab1')
        });
    
    $("#li_tab3").find('a').hover(function(){
        $("#li_tab3").find('a').css('background' , 'none');
    });
    
    
    if($('.proceed_content_box3')){
        $("#jqShowAccountMessage").hide();
        $("#jqShowEmailAccountMessage").hide();
        $('#email').focusout(checkemail);
    }
    if($('.proceed_content_box2')){
        $("#jqShowAccountMessage").hide();
        $("#jqShowEmailAccountMessage").hide();
        $('#RegistrantEmailAddress').focusout(checkemail);
    }
    
});

function showhideinstalloption()
{ 
    $("#jqShowMessage").text('');
    $('#jqCpricemode').val('');
    $('#jqCvalue').val('0.00');

    $("input[name='couponNumber']").val('');

    if($("input:radio[name='jqOptionStyle']:checked").val()==1)
    {
        $("#jqProceedFlag").val(0);
        $("#jqDomainEntryBox").hide('slow');//.css('display', 'none');
        $("#jqSubDomainEntryBox").show('slow');//.css('display', '');
        $(".proceed_content_box2").hide('slow');//css('display', 'none');
        $("#jqUserDomainEntryBox").hide('slow');//css('display', '');
        $("#idsld1").val('');
        $("#idsld2").val('');
        $("#jqTldPrice").val(0);
        $("#domainFlag").val(0);
        $('#spanjqTldPrice').text('0.00');
    }
    else if($("input:radio[name='jqOptionStyle']:checked").val()==2)
    {
        $("#jqProceedFlag").val(0);
        $("#jqDomainEntryBox").show('slow');//css('display', '');
        $("#jqSubDomainEntryBox").hide('slow');//css('display', 'none');
        $(".proceed_content_box3").hide('slow');//css('display', '');
        $(".proceed_content_box2").hide('slow');//css('display', 'none');
        $("#jqUserDomainEntryBox").hide('slow');//css('display', '');
        $("#domainFlag").val(1);
        $("#idsld2").val('');
        $("#txtStoreName").val('');
    }
    else if($("input:radio[name='jqOptionStyle']:checked").val()==3)
    {

        $("#jqUserDomainEntryBox").show('slow');//css('display', '');
        $("#jqDomainEntryBox").hide('slow');//css('display', '');
        $("#jqSubDomainEntryBox").hide('slow');//css('display', 'none');
        $(".proceed_content_box3").hide('slow');//css('display', '');
        $(".proceed_content_box2").hide('slow');//css('display', 'none');
        $("#jqTldPrice").val(0);
        $("#domainFlag").val(0);
        $("#txtStoreName").val('');
        $("#idsld1").val('');
        $("#jqProceedFlag").val(1);
        $('#spanjqTldPrice').text('0.00');
         $("#domainRegistration").hide();
    }
}

function paynow(flag)
{
    // Validate Coupon Code
    if($("#jqUserExistFlag").val() ==1 && couponCodeValidation('couponNumber2')){
        // End Valideta Coupon Code
        var paymantFlage = 1;
        var queryData = "";
        var urlData ="";
        var currentPaymant = "";
        var currentID = "";
        if($('#paymentmethod_paypalpro')){
            queryData = queryData + "&paymentmethod=" + $('#paymentmethod_paypalpro').val();
        }
        if($('#currentpaymant')){
            currentPaymant = $("#currentpaymant").val();
            currentID = "_" + currentPaymant;
            queryData = queryData + "&currentpaymant=" + $("#currentpaymant").val();
        }

        if(currentPaymant == 'paypalpro' || currentPaymant == 'paypalflow' || currentPaymant == 'authorize'){
            paymantFlage = 1;
        }else{
            paymantFlage = 2;
        }
        if(currentPaymant=='authorize'){
             var ecardNumber =  $("#ccno1_authorize").val();
             var eyear      =  $("#expY1_authorize").val();
             var emonth     =  $("#expM1_authorize").val();
             var ecvv     =  $("#cvv1_authorize").val();
             
             var currentTime = new Date()

          
            var currentMonth = currentTime.getMonth() + 1;
            var currentYear  =  currentTime.getFullYear().toString().substr(2,2);
            var  emonthSub   = emonth.toString()[0];;
           
            if(emonthSub==0){
               emonth = emonth.toString()[1];
               
            }
           
            if(ecardNumber==''){
                 alert('Please Enter Card Number');
                 $("#ccno1_authorize").focus();
                  return false;
             }
             //alert(eyear);
             // alert(currentYear);
             if(eyear<currentYear){
                 alert('Please Select Valid Year');
                  return false;
             }
              if(eyear<=currentYear){
                  if(emonth<currentMonth){
                    
                    alert('Please Select Valid month');
                     return false;
                  }
             }
             if(ecvv==''){
                 alert('Please Enter CVV Number');
                 $("#cvv1_authorize").focus();
                  return false;
             }
           
        } 

        urlData = "expM="+$("#expM1"+currentID).val()+"&expY="+$("#expY1"+currentID).val()+"&cvv="+$("#cvv1"+currentID).val()+"&ccno="+$("#ccno1"+currentID).val();
        urlData = urlData + "&fname="+$("#fname").val()+"&lname="+$("#lname").val()+"&add1="+$("#add1").val()+"&city="+$("#city").val()+"&state="+$("#state").val()+"&country="+$("#country").val()+"&zip="+$("#zip").val()+"&txtStoreName="+$("#txtStoreName").val()+"&email="+$("#email").val()+"&productId="+$("#productId").val()+"&ServiceAmount="+$("#jqFinalPrice").val()+"&serCat="+serCat+queryData;
        urlData = urlData + "&couponNo="+$("#couponNumber2").val();
 
        //alert(urlData);return false;

        var serCat          = $("#jqProductService").val();

        if(paymantFlage == 1){

            var ajaxSubmitOption 	= {
                url      : payNow,
                async    : false,
                // data     : "expM="+$("#expM1").val()+"&expY="+$("#expY1").val()+"&cvv="+$("#cvv1").val()+"&ccno="+$("#ccno1").val()+"&fname="+$("#fname").val()+"&lname="+$("#lname").val()+"&add1="+$("#add1").val()+"&city="+$("#city").val()+"&state="+$("#state").val()+"&country="+$("#country").val()+"&zip="+$("#zip").val()+"&txtStoreName="+$("#txtStoreName").val()+"&email="+$("#email").val()+"&productId="+$("#productId").val()+"&ServiceAmount="+$("#jqFinalPrice").val()+"&serCat="+serCat+queryData,
                data     :urlData,
                type     : "post",
                dataType : "json",
                success  : function(data)
                {
                    //$('#overlay').fadeOut('slow');
                    if(data){
                        if(data.failed)
                        {
                            //                $("#jqMessage").html(unescape(data.list));
                            //                 isSetUpComplete = true;
                            //                closeProductInstallationProgress('fail');
                            $(".jqShowPaymentprocess1").hide();
                            showerrormessage(unescape(data.list),1);
                            isSetUpComplete = false;
                        }
                        else if(data.success)
                        {   
                            $(".proceed_content_box3").hide(); 
                            //hide result tab
                            $(".proceed_content_box2").hide(); //hide result tab
                            $(".payment_option1").hide(); //hide result tab
                            $(".content_area_inner").css('min-height', 50);
                            $("#jqMessage").hide(); //hide result tab
                            $("#jqProgress").show();//show progress tab
                            showProductInstallationProgress();
                            //                                                    var contents=
                            $("#jqMessage").html(unescape(data.list));
                            isSetUpComplete = true;
                        //                                                            $("#jqScoreMagazineCarCare").html(unescape(data.carcarelist));
                        }
                        else{
                            $("#jqMessage").html('Account setup failed.');
                            isSetUpComplete = true;
                            closeProductInstallationProgress('fail');
                        }
                    }else{
                        $(".jqShowPaymentprocess1").hide();
                        showerrormessage('Payment Failed',1);
                        isSetUpComplete = false;
                 
                 
                    }
                },
                beforeSend : function()
                {
                    //                $(".proceed_content_box3").hide(); //hide result tab
                    //                $(".proceed_content_box2").hide(); //hide result tab
                    //                $(".payment_option1").hide(); //hide result tab
                    //
                    //               $("#jqMessage").hide(); //hide result tab
                    //               $("#jqProgress").show();//show progress tab
                    //               showProductInstallationProgress();

                    $(".jqShowPaymentprocess1").show();
   
          
                },
                complete : function()
                {
                    isSetUpComplete = true;
                },
         error : function()
            {
                $(".jqShowPaymentprocess1").hide();
                showerrormessage('Some errors encountered. Please try after some time.',1);
                isSetUpComplete = false;
            }
            };
    
            $(".jqShowPaymentprocess1").show();
    
            //  clearTimeout() for displaying loader in chrome by making a delay
            clearTimeout(window.timer);
            window.timer=setTimeout(function(){ // setting the delay for each keypress
                $.ajax(ajaxSubmitOption); //runs the ajax request

            }, 3000);
   
            return false;

        }//paymant curl ends
        else if(paymantFlage == 2){
            // other paymant
            $('#product_price').val($('#jqFinalPrice').val()); 

            $("#frmUsers").attr('action',otherpaymanturl);
            return true;
        }


    //coupencode ends
    } else {
        return false;
    }
}

function proceedtopay()
{
    //$('#jqTldPrice').val(0);
    //$('.jqDiscount').html('0.00');
    
    if($("input:radio[name='jqOptionStyle']:checked").val()==1)
    {
       
        if($("#txtStoreName").val()=="" || $("#jqProceedFlag").val()==0)
        {
            jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
            jQuery('#jqShowMessage').addClass('domainsearchfaild');
            $("#jqShowMessage").text("Please enter a subdomain and check the availability before proceeding to the next step");
            return false;
        }
        $(".proceed_content_box3").show();//css('display', '');
        $(".proceed_content_box2").hide();//css('display', 'none');
        
        $("#jqShowAccountMessage").hide();
        loadServices();
        showPlansList('tab3');
        
    }
    else if($("input:radio[name='jqOptionStyle']:checked").val()==2 || $("input:radio[name='jqOptionStyle']:checked").val()==3)
    {
        if($("input:radio[name='jqOptionStyle']:checked").val()==2)
        {
            if($("#idsld1").val()=="" || $("#jqProceedFlag").val()==0)
            {
                jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                jQuery('#jqShowMessage').addClass('domainsearchfaild');
                $("#jqShowMessage").text("Please enter a domain and check the availability before proceeding to the next step");
                return false;
            }
            $("#domainFlag").val(1);
        }
        else
        {
            if($("#idsld2").val()=="")
            {
                jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                jQuery('#jqShowMessage').addClass('domainsearchfaild');
                $("#jqShowMessage").text("Please enter your domain before proceeding to the next step");
                return false;
            }
            $("#jqRegisterYears").hide();
        }
        $("#jqShowEmailAccountMessage").hide();
        $(".proceed_content_box3").hide();//css('display', '');
        $(".proceed_content_box2").show();//css('display', 'none');
        
        loadServices();
        showPlansList('tab3');
        
    }
    return false;
}

function checksubdomainavailability()
{
    $('#jqShowMessage').text('');
    $("#jqProceedFlag").val(0);
    
    if(!hasWhiteSpace($("#txtStoreName").val()) && $("#txtStoreName").val().length>0)
    {
        jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
        jQuery('#jqShowMessage').addClass('domainsearchfaild');
        $("#jqCretaeAccount").html('').css('display', 'none');
        
        if($("#txtStoreName").val()==""){
            $("#jqShowMessage").text("Site name can't be empty!. Use letters and numbers only.");
        }else{
            $("#jqShowMessage").text("Please enter a valid Site name. Use letters and numbers only.");
        }
                
        return false;
    }
    else{
        if($("#txtStoreName").val()==""){
            jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
            jQuery('#jqShowMessage').addClass('domainsearchfaild');
            $("#jqCretaeAccount").html('').css('display', 'none');
            $("#jqShowMessage").text("Site name can't be empty!. Use letters and numbers only.");
            return false;
        }
    }
    
    
    var ajaxSubmitOption 	= {
        url      : checkeAccount,
        data     : "storeName="+$("#txtStoreName").val(),
        type     : "post",
        dataType : "json",
        success  : function(data)
        {
            //$('#overlay').fadeOut('slow');
            if(data.faild)
            {
                jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                jQuery('#jqShowMessage').addClass('domainsearchfaild');
                $("#jqCretaeAccount").html('').css('display', 'none');
                $('#jqChkAvailable').hide();
                $("#jqShowMessage").text(unescape(data.list));
                $("#jqProceedFlag").val(0);

            }
            else if(data.success)
            {

                jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                $("#jqCretaeAccount").html('').css('display', '');
                $('#jqChkAvailable').hide();
                $("#jqShowMessage").text(unescape(data.list));
                $("#jqProceedFlag").val(1);
            }
        },
        beforeSend : function()
        {
            //$('#overlay').fadeIn('slow');
            $('#jqChkAvailable').show();
        },
        complete : function()
        {
            //$('#overlay').fadeOut('slow');
            $('#jqChkAvailable').hide();
        }
    };
    $.ajax(ajaxSubmitOption);
    return false;
}
function cretaedomain()
{
    var ajaxSubmitOption 	= {
        url      : createAccount,
        data     : "storeName="+$("#txtStoreName").val()+"&userEmail="+$("#txtEmail").val()+"&userName="+$("#txtName").val()+"&userPassword="+$("#txtPUserPassword").val(),
        type     : "post",
        dataType : "json",
        success  : function(data)
        {
            $('#overlay').fadeOut('slow');
            if(data.error)
            {
            }
            else if(data.success)
            {
                //                                                    var contents=
                $("#jqMessage").html(unescape(data.list));
            //                                                            $("#jqScoreMagazineCarCare").html(unescape(data.carcarelist));
            }
        },
        beforeSend : function()
        {
            $('#overlay').fadeIn('slow');
        },
        complete : function()
        {
            $('#overlay').fadeOut('slow');
        }
    };
    $.ajax(ajaxSubmitOption);
    return false;
}

function checkdomainavailability(flag)
{
    $("#jqShowMessage").text('');
    $("#jqProceedFlag").val(0);
    if($("input:radio[name='jqOptionStyle']:checked").val()==2)
    {
        if(!hasWhiteSpace($("#idsld1").val()) && $("#idsld1").val().length>0)
        {
            jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
            jQuery('#jqShowMessage').addClass('domainsearchfaild');
            $("#jqCretaeAccount").html('').css('display', 'none');
            
            if($("#txtStoreName").val()==""){
                $("#jqShowMessage").text("Domain name can't be empty!. Use letters and numbers only.");
            }else{
                $("#jqShowMessage").text("Please enter a valid Domain name. Use letters and numbers only.");
            }

            return false;
        }
        else
        {
            if($("#idsld1").val()=="")
            {
                jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                jQuery('#jqShowMessage').addClass('domainsearchfaild');
                $("#jqShowMessage").text("Please enter a domain name");
                return false;
            }
        }
        
        
        
    }
    else
    {
        if($("#idsld2").val()=="")
        {
            jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
            jQuery('#jqShowMessage').addClass('domainsearchfaild');
            $("#jqShowMessage").text("Please enter a domain name");
            return false;
        }
    }
    var ajaxSubmitOption 	= {
        url      : checkeDomainAvailability,
        data     : "idsld="+$("#idsld1").val()+"&tld="+$("#tld1").val()+"&action=check",
        type     : "post",
        dataType : "json",
        success  : function(data)
        {
            //$('#overlay').fadeOut('slow');            
            if(data.faild)
            {
                if(flag==1)
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                    jQuery('#jqShowMessage').addClass('domainsearchfaild');
                    //                                                                $("#jqCretaeAccount").html('').css('display', 'none');
                    $('#jqChkAvailable').hide();
                    $("#jqShowMessage").text(unescape(data.list));
                    $('#jqDomainStatusVal').val(0);
                    $('#jqTldPrice').val(0);
                    return 0;
                }
                else
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                    jQuery('#jqShowMessage').addClass('domainsearchfaild');
                    //                                                                $("#jqCretaeAccount").html('').css('display', 'none');
                    $('#jqChkAvailable').hide();
                    $("#jqShowMessage").text(unescape(data.list));
                    $('#jqTldPrice').val(0);

                }
                $("#jqProceedFlag").val(0);
            }
            else if(data.success)
            {
                if(flag==1)
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                    //                                                                $("#jqCretaeAccount").html('').css('display', '');
                    $('#jqChkAvailable').hide();
                    $("#jqShowMessage").text(unescape(data.list));
                    $('#jqDomainStatusVal').val(1);
                    if($("#jqNumYears")){
                        var years = parseInt($("#jqNumYears").val());
                    }else
                        years = 1;
                    var tldprice = data.tldprice * years;
                    $('#jqTldPrice').val(tldprice);
                    $('#spanjqTldPrice').text(tldprice);

                    priceUpdate();
                    return(1);
                }
                else
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                    //                                                                $("#jqCretaeAccount").html('').css('display', '');
                    $('#jqChkAvailable').hide();
                    if($("#jqNumYears")){
                        years = parseInt($("#jqNumYears").val());
                    }else
                        years = 1;
                    tldprice = data.tldprice * years;
                    $('#jqTldPrice').val(tldprice);
                    $('#spanjqTldPrice').text(tldprice);
                    priceUpdate();
                    $("#jqShowMessage").text(unescape(data.list));
                }
                $("#jqProceedFlag").val(1);
            }
        },
        beforeSend : function()
        {
            //$('#overlay').fadeIn('slow');
            $('#jqChkAvailable').show();
        },
        complete : function()
        {
            //$('#overlay').fadeOut('slow');
            $('#jqChkAvailable').hide();
        }
    };
    $.ajax(ajaxSubmitOption);
    return false;
}

/* Function from autohoster*/
//Registrant fns
function fnRegistrantStateSelected()
{
    //		document.frmRegistInfo.RegistrantProvince.value = "";
    $('#RegistrantProvince').val('');

}

function fnRegistrantProvinceSelected()
{
    $("#RegistrantState").val("undefined");
//		document.frmRegistInfo.RegistrantState.selectedIndex=0;
}

function fnRegistrantNoneSelected()
{
    $('#RegistrantProvince').val('');
    //		document.frmRegistInfo.RegistrantProvince.value = "";
    $("#RegistrantProvince").val("");

//		document.frmRegistInfo.RegistrantState.selectedIndex=0;
}

function registorDomain(flag)
{ 
    // Validate Coupon Code
    if($("#jqUserExistFlag").val() ==1 && couponCodeValidation('couponNumber2')) {
        // End Valideta Coupon Code
        var tld             =   "";
        var sld             =   "";
        var domainFlag      = 1;
        var tldPrice        = 0;
        var serCat          = $("#jqProductService").val();



        if($("input:radio[name='jqOptionStyle']:checked").val()==2)
        {
            tld     =   $("#tld1").val();
            sld     =   $("#idsld1").val();
            tldPrice=   $("#jqTldPrice").val();

        }
        else
        {
            tld     =   $("#tld2").val();
            sld     =   $("#idsld2").val();
            domainFlag =   0;
        }


        //
        var paymantFlage = 1;
        var queryData = "";
        var urlData ="";
        var currentPaymant = "";
        var currentID = "";
        if($('#paymentmethod_paypalprodomain')){
            queryData = queryData + "&paymentmethod=" + $('#paymentmethod_paypalprodomain').val();
        }
        if($('#currentpaymantdomain')){
            currentPaymant = $("#currentpaymantdomain").val();
            currentID = "_" + currentPaymant;
            queryData = queryData + "&currentpaymant=" + $("#currentpaymantdomain").val();
        }

        if(currentPaymant == 'paypalprodomain' || currentPaymant == 'paypalflowdomain' || currentPaymant == 'authorizedomain'){
            paymantFlage = 1;
        }else{
            paymantFlage = 2;
        }

        urlData = queryData + "&expM="+$("#expM1"+currentID).val()+"&expY="+$("#expY1"+currentID).val()+"&cvv="+$("#cvv1"+currentID).val()+"&ccno="+$("#ccno1"+currentID).val();
        urlData = urlData + "&RegistrantFirstName="+$("#RegistrantFirstName").val()+"&RegistrantLastName="+$("#RegistrantLastName").val()+"&RegistrantJobTitle="+$("#RegistrantJobTitle").val()+"&RegistrantOrganizationName="+$("#RegistrantOrganizationName").val()+"&RegistrantAddress1="+$("#RegistrantAddress1").val()+"&RegistrantAddress2="+$("#RegistrantAddress2").val()+"&RegistrantCity="+$("#RegistrantCity").val()+"&RegistrantState="+$("#RegistrantState").val()+"&RegistrantProvince="+$("#RegistrantProvince").val()+"&RegistrantPostalCode="+$("#RegistrantPostalCode").val()+"&idRegistrantCountry="+$("#idRegistrantCountry").val()+"&RegistrantFax="+$("#RegistrantFax").val()+"&RegistrantPhone="+$("#RegistrantPhone").val()+"&RegistrantEmailAddress="+$("#RegistrantEmailAddress").val()+"&idsld="+sld+"&tld="+tld+"&NumYears="+$("#jqNumYears").val()+"&UnLockRegistrar="+$("#UnLockRegistrar").val()+"&ServiceAmount="+$("#jqFinalPrice").val()+"&productId="+$("#productId").val()+"&domainFlag="+domainFlag+"&tldPrice="+tldPrice+"&serCat="+serCat;
        urlData = urlData + "&couponNo="+$("#couponNumber1").val();
        //alert(urlData);return false;

        //
        if(paymantFlage == 1){
            var ajaxSubmitOption 	= {
                url      : registerDomain,
                async    :  false,
                //data     : "expM="+$("#expM2").val()+"&expY="+$("#expY2").val()+"&cvv="+$("#cvv2").val()+"&ccno="+$("#ccno2").val()+"&RegistrantFirstName="+$("#RegistrantFirstName").val()+"&RegistrantLastName="+$("#RegistrantLastName").val()+"&RegistrantJobTitle="+$("#RegistrantJobTitle").val()+"&RegistrantOrganizationName="+$("#RegistrantOrganizationName").val()+"&RegistrantAddress1="+$("#RegistrantAddress1").val()+"&RegistrantAddress2="+$("#RegistrantAddress2").val()+"&RegistrantCity="+$("#RegistrantCity").val()+"&RegistrantState="+$("#RegistrantState").val()+"&RegistrantProvince="+$("#RegistrantProvince").val()+"&RegistrantPostalCode="+$("#RegistrantPostalCode").val()+"&idRegistrantCountry="+$("#idRegistrantCountry").val()+"&RegistrantFax="+$("#RegistrantFax").val()+"&RegistrantPhone="+$("#RegistrantPhone").val()+"&RegistrantEmailAddress="+$("#RegistrantEmailAddress").val()+"&idsld="+sld+"&tld="+tld+"&NumYears="+$("#jqNumYears").val()+"&UnLockRegistrar="+$("#UnLockRegistrar").val()+"&ServiceAmount="+$("#jqFinalPrice").val()+"&productId="+$("#productId").val()+"&domainFlag="+domainFlag+"&tldPrice="+tldPrice+"&serCat="+serCat,
                data     : urlData,
                type     : "post",
                dataType : "json",
                success  : function(data)
                {
                    //$('#overlay').fadeOut('slow');
                    if(data.failed)
                    {
                        //                $("#jqMessage").html(unescape(data.list));
                        //                 isSetUpComplete = true;
                        //                closeProductInstallationProgress('fail');
                        $(".jqShowPaymentprocess1").hide();
                        showerrormessage(unescape(data.list),1);
                        isSetUpComplete = false;
                    }
                    else if(data.success)
                    {   
               
                        $(".proceed_content_box3").hide(); //hide result tab
                        $(".proceed_content_box2").hide(); //hide result tab
                        $(".payment_option1").hide(); //hide result tab
                        $(".content_area_inner").css('min-height', 50);
                        $("#jqMessage").hide(); //hide result tab
                        $("#jqProgress").show();//show progress tab
                        showProductInstallationProgress();
                        //                                                    var contents=
                        $("#jqMessage").html(unescape(data.list));
                        isSetUpComplete = true;
                    //                                                            $("#jqScoreMagazineCarCare").html(unescape(data.carcarelist));
                    }
                    else{
                        $("#jqMessage").html('Account setup failed.');
                        isSetUpComplete = true;
                        closeProductInstallationProgress('fail');
                    }
                },
                beforeSend : function()
                {
                    //                $(".proceed_content_box3").hide(); //hide result tab
                    //                $(".proceed_content_box2").hide(); //hide result tab
                    //                $(".payment_option1").hide(); //hide result tab
                    //
                    //               $("#jqMessage").hide(); //hide result tab
                    //               $("#jqProgress").show();//show progress tab
                    //               showProductInstallationProgress();
                    $(".jqShowPaymentprocess1").show();
                },
                complete : function()
                {
                    isSetUpComplete = true;
                },
                error : function()
                    {
                        $(".jqShowPaymentprocess1").hide();
                        showerrormessage('Some errors encountered. Please try after some time.',1);
                        isSetUpComplete = false;
                    }
            };
            $(".jqShowPaymentprocess1").show();
    
            //  clearTimeout() for displaying loader in chrome by making a delay
            clearTimeout(window.timer);
            window.timer=setTimeout(function(){ // setting the delay for each keypress
                $.ajax(ajaxSubmitOption); //runs the ajax request

            }, 3000);
            return false;
        } else {

            // domain register

            if(paymantFlage == 2){
                // other paymant
                $("#slddomain").val(sld);
                $("#tlddomain").val(tld);
                $('#product_pricedomain').val($('#jqFinalPrice').val()); 
                $("#jqProductPayNow").attr('action',otherpaymanturldomain);
                return true;
            }

        }
    }
    else{
        return false;
    }

}


function doCalculatediscount(){
    var pricemode = $('#jqCpricemode').val();
    var value = $('#jqCvalue').val();
    var total = parseFloat($('.jqSubTotal').html());
    var discount = parseFloat(0).toFixed(2);
    if(pricemode=='rate'){
        discount = value;
   
    } else if(pricemode=='percentage'){
        discount = parseFloat(total) * (parseFloat(value)/100);
    }
    total = parseFloat(total).toFixed(2)-parseFloat(discount).toFixed(2);
   
    $('.jqDiscount').html(parseFloat(discount).toFixed(2));
    $('#jqFinalPrice').val(total.toFixed(2));
    $('.jqTotalPurchaseVal').html(total.toFixed(2));
                                        
}

function priceUpdate()
{
    //    alert($("input[name='jqSerciceCatVal[]']:checked").val());
    var totVal  = 0;
    var idStr   ="";
   
    //    $("#jqPriceDisplayarea").html((totVal+parseInt($("#jqTotalPrice").val())+parseInt($("#jqTldPrice").val())).toFixed(2));
    var priceTag = totVal+parseFloat(parseFloat($('#product_price').val()).toFixed(2))+parseFloat($("#jqTldPrice").val());
    
    //    $("#jqPriceTagArea").html(priceTag); // Price Tag Area
    $(".jqSubTotal").html(priceTag);
   
    $(".jqTotalPurchaseVal").html(priceTag);
    doCalculatediscount();

    //    $("#jqProductService").val(priceTag);

    return true;
}

function  showProductInstallationProgress(){
    
    isSetUpComplete = false;
    barWidth        = 5;
    updatePageTitleBar('inprogress');
    
        $('.progress').asProgress({
            'namespace': 'progress'
        });

        var stopsindex = 0;
        var stopsvalue;
        var stops = [3, 21, 41, 61, 81, 100];
        var timer = $.timer(function(){
            stopsvalue = stops[stopsindex];
            if(isSetUpComplete && stopsindex >= 5){
               timer.stop();
               //hide this and show successpage
               closeProductInstallationProgress('success')
            }
            else{
                if(stopsindex <= 5){
                    $('.progress').asProgress('go',stopsvalue +'%');
                    $("#jqProgressMessage").html(messageArray[stopsindex]);
                }
            }
            stopsindex = stopsindex+1;

        },
        5000,
        true);
    
        /*
    var timer = $.timer(
        function() {
            if(barWidth <= 490){
                var  progressPercent = Math.ceil( barWidth/500 * 100);
                var pointerMark    = barWidth-120;
                $("#jqProgress .progress_count").html(progressPercent+" %");
                $("#jqProgress .pointer").css("left",0 + "px");
                $("#jqProgress .bar").css("width",barWidth+"px");
                var i= Math.floor(barWidth/100);

                $("#jqProgress .pointer").html(messageArray[i]);
                barWidth = barWidth+100;
            }
            if(isSetUpComplete && barWidth >=490){
                timer.stop();
                //hide this and show successpage

                closeProductInstallationProgress('success')
            }
        },
        5000,
        true
        );
            */

}

function closeProductInstallationProgress(status){
    
    $("#jqProgress").hide();
    $("#jqMessage").show();
    updatePageTitleBar(status)
}

function updatePageTitleBar(shortMsg) {
    if(shortMsg == 'success') {
        $('#pageTitleText').html('<h4>Installation Complete</h4>');
    } else if(shortMsg == 'fail') {
        $('#pageTitleText').html('<h4>Installation Failed</h4>');
    } else if(shortMsg == 'inprogress') {
        $('#pageTitleText').html('<h4>Installation Inprogress...</h4>');
    }

}
function couponCodeValidation(field){
    var couponNumber = $('#'+field).val();
   
    var rootUrl = MAIN_URL+'index/checkcoupon';
    var total = parseFloat($('.jqSubTotal').html());
    var discount;
    $('#'+field+'_err').html('<label class="error"></label>');
   
   
    if(couponNumber!=''){
        $.ajax({
            type: "POST",
            url: rootUrl,
            data: {
                coupon: couponNumber
            }
        }).success(function( msg ) {
            //alert(msg);                       
            var obj = $.parseJSON(msg);
            // obj.valid;
            // obj.pricemode;
            // obj.value;
            // obj.msg;
            
            if(obj.valid!=1){
                $('#'+field+'_err').html('<label class="error">'+obj.msg+'</label>');
                $('.jqDiscount').html(parseFloat(0).toFixed(2));
                $('#jqFinalPrice').val(total.toFixed(2));
                $('.jqTotalPurchaseVal').html(total.toFixed(2));
                $('#jqCpricemode').val('');
                $('#jqCvalue').val('0.00');

                return false;
            } else {                                                       
                discount =(obj.pricemode=='rate') ? parseFloat(obj.value) : parseFloat(total)* (parseFloat(obj.value)/100);                                       
                total = parseFloat(total).toFixed(2)-parseFloat(discount).toFixed(2);
                if(total>0){
                    $('#jqCpricemode').val(obj.pricemode);
                    $('#jqCvalue').val(obj.value);

                    $('.jqDiscount').html(parseFloat(discount).toFixed(2));
                    $('#jqFinalPrice').val(total.toFixed(2));
                    $('.jqTotalPurchaseVal').html(total.toFixed(2));

                    $('#'+field+'_err').html('<label class="sucess">'+obj.msg+'</label>');
                }else {
                    $('#'+field+'_err').html('<label class="error">Sorry! this coupon is not valid for this plan </label>');
                }

                                       
            }

        });

    } // End If
      
    return true;
} // End Function


function loadServices(){

    var amount= new Array();
    var serviceName = new Array();
    var serviceDur = new Array();

    amount = $("#product_price").val() ;

    serviceName = $("#product_name").val() ;
    serviceDur = $("#product_bill").val() ;

    $("input[name='jqSerciceCatVal[]']:checked").each(function() {
        amount+= ','+parseInt($(this).val());
        var idStr = this.id;
        serviceName+= ','+ $("#desc_"+idStr).val();
        serviceDur+= ','+$("#interval_"+idStr).val();             
    });

    var radioCount;
    radioCount = $('#radioCount').val();
    for(k=1; k<=radioCount; k++){
        $("input[name='serviceSubscription["+k+"]']:checked").each(function(i,j) {
            //alert($(j).val());            
            amount+= ',' + parseInt($(j).val());
            var itemId = $(j).attr('id');
            var itemSerId = itemId.split('_')[1];
            serviceName+= ','+ $("#desc_"+itemSerId).val();
            serviceDur+= ','+$("#interval_"+itemSerId).val();
        });
    }
    priceUpdate();
     
    
    var ajaxSubmitOption 	= {
        url      : loadServicesUrl+serviceName+"/"+serviceDur+"/"+amount,
        data     : "serviceNames="+serviceName+"&billing_durations="+serviceDur+"&amounts="+amount,
        type     : "post",
        success  : function(data)
        {
            
         
            $('#services').html(data);
            
            $('#domain_services').html(data);
        }
    };
    $.ajax(ajaxSubmitOption);
    $('#spanjqTldPrice').val($('#jqTldPrice').val());
    return false;
}

function updateTotal(){
//$("#jqTldPrice").val
}

function showerrormessage(msg,flag)
{
    if(flag==2)
    {
        $(".error_msg_container").show();
        $(".jqErrorMessage").html(msg);
        $('.error_msg_container').delay(5000).fadeOut('slow');

    }
    else
    {
        $(".error_msg_container").show();
        $(".jqErrorMessage").html(msg);
        $('.error_msg_container').delay(5000).fadeOut('slow');
    }
    return false;
}

function showPlansList(selectedTabId)
{
    $("#tabs li").removeClass('active');
    $("#li_"+selectedTabId).addClass('active');
    $(".tab_content").hide();
    var selected_tab = $("#li_"+selectedTabId).find("a").attr("href");
    $(selected_tab).fadeIn();
}

function updateSelectedPlan(elem)
{
    var plan_id = $(elem).attr('id');
    $.ajax({
        url      : ajaxGetPlanDetails,
        data     : 'planId='+plan_id,
        type     : "post",
        async    : false,
        dataType : "json",
        success  : function(data)
        {
            $("#productId").val(plan_id);
            $("#product_name").val(unescape(data.planName));
            $("#product_bill").val(unescape(data.vBillingInterval));
            $("#product_price").val(unescape(data.planPrice));
            showPlansList('tab1');
            $("#jqPriceDisplayarea").html(unescape(data.planName)+" [ $ "+unescape(data.planPrice)+" ]");
            priceUpdate();
        }
    });
}


function otherpaymant(id)
{
    if(id ==1){ 
              
        $("#jqProgress").show();//show progress tab
        showProductInstallationProgress();
        // delay(1000);
        setTimeout("closeProductInstallationProgress('success')", 30000);
              
    }
    return false;
}

function otherpaymantfailed(msg)
{
    if(msg){
             
        showerrormessage(msg,1);
        isSetUpComplete = false;
              
    }
    return false;
}

function hasWhiteSpace(s) {
    /*
    if(!validSubdomain(s))
    {}
    return s.indexOf(' ') >= 0;
    
   if(s.length<6)
       {
       return false;
       }
    */   
    var reg = /^([A-Za-z0-9])+[A-Za-z0-9-]+([A-Za-z0-9])$/;
    if(reg.test(s) == false){
        return false;
    }else{
        return true;
    }
}

function checkemail(){

    $("#jqShowAccountMessage").hide();
    $("#jqUserExistFlag").val('0');
    $("#jqShowEmailAccountMessage").hide();
    $("#jqRegistrantAccountExistence").val('0');
    var email ;
    if($('.proceed_content_box3').css('display')!='none')
        email = $("#email").val();
     
    if($('.proceed_content_box2').css('display')!='none')
        email = $('#RegistrantEmailAddress').val();
   
    if(email){
        var ajaxSubmitOption 	= {
            url      : checkUserAccount,
            data     : "email="+email,
            type     : "post",
            dataType : "json",
            success  : function(data)
            {
           
                if(data.faild)
                {
                    $("#jqShowAccountMessage").show();
                    $("#jqUserExistFlag").val('0');
                    $("#jqShowEmailAccountMessage").show();
                    $("#jqRegistrantAccountExistence").val('1');
                }
                else{
                    $('#jqUserExistFlag').val(1);
                }
            
            }
        };
        $.ajax(ajaxSubmitOption);
    
    }
    return true;
}
