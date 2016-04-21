var isSetUpComplete = false;
var barWidth        = 0;
var messageArray    = new Array("<p><span>Phase 1: Originate</span><br />Analyzing input, preparing installation files and scripts</p>",
                            "<p><span>Phase 2: Focus</span><br />Setting proper foundation, folders, permissions</p>",
                            "<p><span>Phase 3: Design</span><br />Creating platform , databases, images, styles</p>",
                            "<p><span>Phase 4: Build</span><br />Adding features, functionalities, themes and data</p>",
                            "<p><span>Phase 5: Occupy </span><br />Almost done. We are doing final touch ups</p>");

$(document).ready(function()
    {
        //$("#jqCheckSubdomainExist").click(checkdomainavailability);
        //   $("#jqCheckSubdomainExist").live("click",{ param: 2 },checkdomainavailability);
        //$("#jqCheckSubdomainExist").live("click",checkdomainavailability(1));
        $("#jqCheckSubdomainExist").live("click",{
            param: 0
        },checksubdomainavailability);
        $("#jqCheckDomainExist").live("click",{
            param: 0
        },checkdomainavailability);

        $('#jqtxtStoreName').focusout(checksubdomainavailability);
        $('#jqtxtEmail').focusout(checkemail);

      //  $("#txtStoreName").live("blur",{param:0},checkdomainavailability);

        $("#jqProductBuy").validate({
            rules: {
                jqtxtStoreName: {
                    required: true
                },
                jqtxtEmail: {
                    required: true,
                    email: true
                },
                txtName: {
                    required: true
                } ,
                txtPassword: {
                    required: true
                }
            },
            messages: {
                jqtxtStoreName: {
                    required: "Please enter store name"
                },
                jqtxtEmail: {
                    required: "Please enter email",
                    email:"Please enter a valid email"
                }	,
                txtName: {
                    required: "Please enter your name"
                },
                txtPassword: {
                    required: "Please enter password"
                }
            },
            submitHandler: function(form) {
                cretaedomain();
            }
        });

        

          /*  $("#jqProductBuy").live("submit",{
        param: 0
    },cretaedomain);*/
});
function checkSubmite(){
    alert("sdfsdf");
    return false;
}
function checksubdomainavailability(flag)
{
    var data = "storeName="+$("#jqtxtStoreName").val();
    if($("#jqtxtStoreName").val().length==0)
        {
                $('#jqDomainStatusVal').val(0);
                return false;
        }
 if(!hasWhiteSpace1($("#jqtxtStoreName").val()) && $("#jqtxtStoreName").val().length>0)
        {
            $('#jqDomainStatusVal').val(0);
            jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
            jQuery('#jqShowMessage').addClass('domainsearchfaild');
            $("#jqShowMessage").html("<span class=\"error\">Please enter a valid site name.Use letters and numbers only.</span>");
            return false;
   }else if(!hasValidateNumber($("#jqtxtStoreName").val())){
       $('#jqDomainStatusVal').val(0);
        jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
        jQuery('#jqShowMessage').addClass('domainsearchfaild');
        $("#jqShowMessage").html("<span class=\"error\">Site name minimum length should be six.</span>");
        return false;
   }
    if($("#jqtxtEmail"))
        data+="&email="+$("#jqtxtEmail").val();
    var ajaxSubmitOption 	= {
        url      : checkeAccount,
        async:false,
        data     : data,
        type     : "post",
        dataType : "json",
        success  : function(data)
        {
            $('#overlay').fadeOut('slow');
            if(data.faild)
            {
                if(flag==1)
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                    jQuery('#jqShowMessage').addClass('domainsearchfaild');
                    //                                                                $("#jqCretaeAccount").html('').css('display', 'none');
                    $("#jqShowMessage").html('<span class="error">'+unescape(data.list)+'</span>');
                    $('#jqDomainStatusVal').val(0);
                    if(data.account_message){
                        jQuery('#jqShowAccountMessage').removeClass('domainsearchsuccess');
                        jQuery('#jqShowAccountMessage').addClass('domainsearchfaild');
                        if(data.account_message=='')
                        $("#jqShowAccountMessage").html('<span class="error">'+unescape(data.account_message)+'</span>');
                    }
                    return 0;
                }
                else
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                    jQuery('#jqShowMessage').addClass('domainsearchfaild');
                    //                                                                $("#jqCretaeAccount").html('').css('display', 'none');
                    $("#jqShowMessage").html('<span class="error">'+unescape(data.list)+'</span>');                 
                    return false;
                }

            }
            else if(data.success)
            {
                if(flag==1)
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                    //                                                                $("#jqCretaeAccount").html('').css('display', '');
                    $("#jqShowMessage").html('<span class="success">'+unescape(data.list)+'</span>'); 
                    $('#jqDomainStatusVal').val(1);
                    return true;
                }
                else
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                    //                                                                $("#jqCretaeAccount").html('').css('display', '');
                    $("#jqShowMessage").text(unescape(data.list));
                }
            }
        },
        beforeSend : function()
        {          
           $(".domain_ajax_loader").show();

        },
        complete : function()
        {
             $(".domain_ajax_loader").hide();
        }
    };
    $.ajax(ajaxSubmitOption);
    return false;
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
        $('.progress').asProgress('start');
        $.each(stops, function(index, value){
            setTimeout(function(){
                $('.progress').asProgress('go',value +'%');
                $("#jqProgressMessage").html(messageArray[index]);
                if(isSetUpComplete && value == 100){
                   //hide this and show successpage
                   closeProductInstallationProgress('success')
                    return true;
                }
            }, index * 5000);
        });
         */
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



function cretaedomain()
{
    checksubdomainavailability(1);
    var flag=parseInt($('#jqDomainStatusVal').val());
    
    if(flag==0 || $("#jqUserExistFlag").val()==0)
    {
        return false;
    }
    //    if($('#jqDomainStatusVal').val()==0)
    //        {
    //            return false;
    //
    //        }
    //     else
    //         {
    ////             alert("got success");
    ////            return false;
    //         }
    var ajaxSubmitOption 	= {

        url      : createAccount,
        data     : "txtStoreName="+$("#jqtxtStoreName").val()+"&txtEmail="+$("#jqtxtEmail").val()+"&txtPassword="+$("#jqtxtPUserPassword").val()+"&txtUserName="+$("#txtName").val(),
        type     : "post",
        dataType : "json",
        success  : function(data)
        {
            
           // $('#overlay').fadeOut('slow');
            if(data.failed)
            {
                // $("#jqMessage").html(unescape(data.list));
                $('#jqProductTryDiv').hide('slow');
                $('.error_msg_container1').show('slow');
                $("#jqErrorMessage").html(unescape(data.list));
                 isSetUpComplete = true;
                 closeProductInstallationProgress('fail');
            }
            else if(data.success)
            {
                //                                                    var contents=
                $('#jqProductTryDiv').hide('slow');              
                $("#jqMessage").html(unescape(data.list));
                isSetUpComplete = true;
            //                                                            $("#jqScoreMagazineCarCare").html(unescape(data.carcarelist));
            }
            else
                {
                    $('#jqProductTryDiv').hide('slow');
                    $("#jqMessage").html(unescape(data.list));
                    isSetUpComplete = true;
                    closeProductInstallationProgress('fail');
                }
        },
        beforeSend : function()
        {
               $("#jqProductTryDiv").hide(); //hide main tab
               $("#jqMessage").hide(); //hide result tab
               $("#jqProgress").show();//show progress tab
               showProductInstallationProgress();
           
        },
        complete : function()
        {
           // $('#overlay').fadeOut('slow');
          // $( "#product_configuration_status_loader" ).close();
          isSetUpComplete = true;
        },
         error : function()
        {
            // $("#jqMessage").html(unescape(data.list));
                $('#jqProductTryDiv').hide('slow');
                $('.error_msg_container1').show('slow');
                $("#jqErrorMessage").html('Some errors encountered. Please try after some time.');
                 isSetUpComplete = true;
                 closeProductInstallationProgress('fail');
        }
    };
    $.ajax(ajaxSubmitOption);
    return false;
}
function checkdomainavailability(flag)
{
//   / alert('asdads');
    var ajaxSubmitOption 	= {
        url      : "checkeDomainAvailability",
        data     : "idsld="+$("#idsld").val()+"&tld="+$("#tld").val()+"&action=check",
        type     : "post",
        dataType : "json",
        success  : function(data)
        {
            
            $('#overlay').fadeOut('slow');
            if(data.faild)
            {
                if(flag==1)
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                    jQuery('#jqShowMessage').addClass('domainsearchfaild');
                    //                                                                $("#jqCretaeAccount").html('').css('display', 'none');
                    $("#jqShowMessage").text(unescape(data.list));
                    $('#jqDomainStatusVal').val(0);
                    return 0;
                }
                else
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                    jQuery('#jqShowMessage').addClass('domainsearchfaild');
                    //                                                                $("#jqCretaeAccount").html('').css('display', 'none');
                    $("#jqShowMessage").text(unescape(data.list));
                }

            }
            else if(data.success)
            {
                if(flag==1)
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                    //                                                                $("#jqCretaeAccount").html('').css('display', '');
                    $("#jqShowMessage").text(unescape(data.list));
                    $('#jqDomainStatusVal').val(1);
                    return(1);
                }
                else
                {
                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                    //                                                                $("#jqCretaeAccount").html('').css('display', '');
                    $("#jqShowMessage").text(unescape(data.list));
                }
            }
        },
        beforeSend : function()
        {
           // $('#overlay').fadeIn('slow');
             $(".domain_ajax_loader").show();
        },
        complete : function()
        {
           // $('#overlay').fadeOut('slow');
             $(".domain_ajax_loader").hide();
        }
    };
    $.ajax(ajaxSubmitOption);
    return false;
}

function checkemail(){
    $("#jqShowEmailAccountMessage").text('');

    var email = $("#jqtxtEmail").val();
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

                 jQuery('#jqShowEmailAccountMessage').removeClass('domainsearchsuccess');
                 jQuery('#jqShowEmailAccountMessage').addClass('domainsearchfaild');
                 $("#jqShowEmailAccountMessage").html(unescape(data.account_message));
                 $("#jqShowEmailAccountMessage").show();
                 $('#jqDomainStatusVal').val(0);
                 $('#jqUserExistFlag').val(0);
            }else{
                $('#jqDomainStatusVal').val(1);
                $('#jqUserExistFlag').val(1);
            }

        }
    };
    $.ajax(ajaxSubmitOption);

    }
    return true;
}

function chekcProceedStatus()
{
    checksubdomainavailability(1);
    checkemail();
    
    var flag=parseInt($('#jqDomainStatusVal').val());

//     alert(flag+'--'+$("#jqUserExistFlag").val());

    if(flag==0 || $("#jqUserExistFlag").val()==0)
    {
//        alert('cancel');
        return false;
    }
    else
        {
//            alert('proceed');
            return true;
        }

    return false;
}

function hasWhiteSpace(s) {
    /*
    if(!validSubdomain(s))
    {}
    return s.indexOf(' ') >= 0;
    */
   if(s.length<6)
       {
        return false;
       }

    var reg = /^([A-Za-z0-9])+[A-Za-z0-9-]+([A-Za-z0-9])$/;
    if(reg.test(s) == false){
        return false;
    }else{
        return true;
    }
}
function hasValidateNumber(s) {
    /*
    if(!validSubdomain(s))
    {}
    return s.indexOf(' ') >= 0;
    */
   if(s.length<6)
    {
     return false;
    }else {
        return true;
    }

    
}
function hasWhiteSpace1(s) {
    /*
    if(!validSubdomain(s))
    {}
    return s.indexOf(' ') >= 0;
    */
    var reg = /^([A-Za-z0-9])+[A-Za-z0-9-]+([A-Za-z0-9])$/;
    if(reg.test(s) == false){
        return false;
    }else{
        return true;
    }
}
