var resultData = '';
$(document).ready(function(){

$(".userDetails").click(function() {
    var data = "userid="+$(this).attr('name');
    var url  = MAIN_URL+'admin/login/loaduserdetails/'+$(this).attr('name');
    loadAjaxforCmsPopup(url,data,'userFullDetails');
});

// To load service details
$(".serviceDetails").click(function() {
    var data = "parent_id="+$(this).attr('name');
    var url  = MAIN_URL+'admin/service/servicedetails/'+$(this).attr('name');
    loadAjaxforCmsPopup(url,data,'serviceFullDetails');
});

// To load invoice details
$(".invoiceDetails").click(function() {
    var data = "invoice_id="+$(this).attr('name');
    var url  = MAIN_URL+'admin/service/invoicedetails/'+$(this).attr('name');
    loadAjaxforCmsPopup(url,data,'invoiceFullDetails');
});

// To load plan features
$(".planFeatures").click(function() {
    var data = "";
    var url  = MAIN_URL+'admin/products/planFeatures';
    loadAjaxforCmsPopup(url,data,'planFeatures');
});

// Banner view
$(".viewBanner").click(function() { 
    var data = "banner_id="+$(this).attr('name'); 
    var url  = MAIN_URL+'admin/products/viewbannerimage/'+$(this).attr('name');
    loadAjaxforCmsPopup(url,data,'viewBannerImage');
});

// Screenshot view
$(".viewScreenshot").click(function() {
    var data = "screen_id="+$(this).attr('name');
    var url  = MAIN_URL+'admin/products/viewscreenimage/'+$(this).attr('name');
    loadAjaxforCmsPopup(url,data,'viewScreenshotImage');
});

// To load user plan details
$(".userPlanDetails").click(function() {
    var data = "user_id="+$(this).attr('name');
    var url  = MAIN_URL+'admin/service/userplandetails/'+$(this).attr('name');
    loadAjaxforCmsPopup(url,data,'userPlanFullDetails');
});

// Plan status change
$(".jqPlanStatusChange").live("click",function() {
    var status_id = $(this).attr('name');
    var plan_id   = $(this).attr('planId');
    var planType   = $(this).attr('planType');
    var url  = MAIN_URL+'admin/products/ajaxPlanStatusChange';
    var confirmStr = (status_id==1)? 'inactivate' : 'activate';
    var confirmMsg = confirm("Are you sure you want to "+confirmStr+" the plan");
    if(confirmMsg==true){         
    $.ajax({
        url: url,
        type: "POST",
        dataType:'html',
        data: {status_id:status_id,plan_id:plan_id,planType:planType},
        cache: false,
        asnyc: false,
        success: function(result) { //alert(result);
            var statusVal ;
            var statusMessage;            
            var res = result.split('**');
            var statusClass ;

            if(res[0]==1) statusClass='btn-success'; else statusClass='btn-danger';

            if((res[0]==1 || res[0]==2) && res[1]>=10 && planType!=='free' ){
                var errorMessage = 'You can activate only 10 plans at a time.<br> Please deactivate any other plan to activate the current plan.';
                $("#jqMessageConatainer").html('<div class="alert alert-error"><button class="close" data-dismiss="alert" type="button">x</button>'+errorMessage+'</div>');
                return false;
            }
            else{
                if(res[0]==1){statusVal='Active';statusMessage='Activated';}else{statusVal='Inactive';statusMessage='Deactivated';}
                $("#jqStatusContainer_"+plan_id).html('<a class="jqPlanStatusChange btn btn-mini '+statusClass+'" name="'+res[0]+'" planId="'+plan_id+'" planType="'+planType+'" href="#">'+statusVal+'</a>');
                $("#jqMessageConatainer").html('<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button">x</button>Plan '+statusMessage+' successfully</div>');
            }
        }
    });
    }
    return false;
});


 
   if($.metadata){
        $.metadata.setType("attr", "validate");
   if($("#frmUserProfile"))
        $("#frmUserProfile").validate();
    if($("#frmChangePassword"))
        $("#frmChangePassword").validate();
    if( $("#frmCreditCard")){

        $("#frmCreditCard").validate({
            submitHandler: function (form) {
                var flagVV = checkCvv();
                if(flagVV) {
                    form.submit();
                }
            }
        });

    }
        
    
    if($("#frmNewsLetter"))
        $("#frmNewsLetter").validate();
    
    $("#frmCreditCard").submit(function() {
       
       $('#number_field_error').html('');
       $('#code_field_error').html('');
       $('#date_field_error').html('');
       var status= true;
      

       if($('#vNumber').val()==''){
           status = false;
           $('#number_field_error').html('This field is required');
       }

        if($('#vNumber').val()!=''){
           if(isNaN($('#vNumber').val())){
               $('#number_field_error').html('Please enter digits');
                status = false;
           }else if($('#vNumber').val().length<16){
                $('#number_field_error').html('Please enter 16 digits');
                status = false;
           }
        }

        if($('#vCode').val()==''){
           status = false;
           $('#code_field_error').html('This field is required');
       }

        if($('#vCode').val()!=''){
           if(isNaN($('#vCode').val())){
               $('#code_field_error').html('Please enter digits');
              status = false;
           }else if($('#vCode').val().length<3){
                $('#code_field_error').html('Please enter atleast 3 digits');
                status = false;
           }
        }
        
    if($('#vMonth').val()==''){
           status = false;
           $('#date_field_error').html('This field is required');
       }

        if($('#vMonth').val()!=''){
           if(isNaN($('#vMonth').val())){
               $('#date_field_error').html('Please enter digits');
              status = false;
           }
        }
        if($('#vYear').val()==''){
           status = false;
           $('#date_field_error').html('This field is required');
       }

        if($('#vYear').val()!=''){
           if(isNaN($('#vYear').val())){
               $('#date_field_error').html('Please enter digits');
              status = false;
           }else if($('#vYear').val().length<4){
                $('#date_field_error').html('Please enter 4 digits');
                status = false;
           }
        }

    
          
                return status;
    });
   }
if($('#search').length != 0){
       //$("#search").addPlaceholder();
   }
});

// Function to load ajax for cms popups
function loadAjaxforCmsPopup(url,data,containerId){

    $.ajax({
        url: url,
        type: "GET",
        dataType:'html',
        data: data,
        cache: false,
        asnyc: false,
        success: function(result) { //alert(result);
            resultData = result;
            $.blockUI({message: resultData});

            $("#jqCloseUserDetails,#jqCloseLinkUserDetails").click(function() {
                $.unblockUI({message: $("#"+containerId)});
            });
        },
        complete:function(){

        }
    });
}


function closeerrormessage(){
    $("#message-red").fadeOut("slow");
}

function closesuccessmessage(){
    $("#message-green").fadeOut("slow");
}
