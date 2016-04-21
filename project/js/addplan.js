$(document).ready(function(){

$("#frmAddPlan").validate({
        rules: { serviceName: { required: true},
            serviceDescription: { required: true },
            productCount: { required: true,number: true} ,
            servicePrice: { required: true,number: true},
            billingDuration: { required: function(){
                        return methodCheck();
                },number: function(){
                        return methodCheck();
                },min:1}
    },
        messages: { serviceName: { required: "Please enter Plan Name"},
            serviceDescription: { required: "Please enter Plan Description" } 	,
            productCount: { required: "Please enter Products Supported",number: "Please enter a Number"},
            servicePrice: { required: "Please enter Price",number: "Please enter a Number" } ,
            billingDuration: { required: "Please enter Billing Duration",number: "Please enter a Number",min:"Please enter a Number greater than zero"}
       }
    });

// For conformation for deleting an entry
$(".jqDeletePlan").click(function () {
var r=confirm("Are you sure you want to delete this record?")
if (r	==	false) return false;
});

// On selecting the billing interval as one-time
$("input[name='billingInterval']").click(function () {
     if($("input[name='billingInterval']:checked").val()=='L') {
        $("#billingDuration").val('');
        $("#billingDuration").attr('readonly', 'readonly');        
    } else {
        $("#billingDuration").removeAttr('readonly');
    }        
});

// If default value for billing interval is one-time
if($("input[name='billingInterval']:checked").val()=='L') {
   $("#billingDuration").val('');
   $("#billingDuration").attr('readonly', 'readonly');
} 

});

function methodCheck() {
    if($("input[name='billingInterval']:checked").val()=='L') {
        $("#billingDuration").val('');
        $("#billingDuration").attr('readonly', 'readonly');
        return false;
    } else {
        $("#billingDuration").removeAttr('readonly');
        return true;
    }
} // End Function