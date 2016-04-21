$(document).ready(function(){

   if($.metadata){
        $.metadata.setType("attr", "validate");
     //  alert($('form').attr('id'));
   if($('form').attr('id')=="frmAdminUsers")
        $("#frmAdminUsers").validate();
    if($('form').attr('id')=="frmModule")
        $("#frmModule").validate();
    if($('form').attr('id')== "frmSiteUsers")
        $("#frmSiteUsers").validate();
    if($('form').attr('id')== "frmWallet")
        $("#frmWallet").validate();
    if($('form').attr('id')== "reportSearch")
        $("#reportSearch").validate();
    if($('form').attr('id')== "frmSettings")
        $("#frmSettings").validate();
    if($('form').attr('id')=="frmPlanPurchaseCategory")
        $("#frmPlanPurchaseCategory").validate();
 //   if($("#frmAddProduct"))
        //$("#frmAddProduct").validate();
    
   }
   if($('#search').length != 0){
       $("#search").addPlaceholder();
   }
    //Sorting Operation in various pages
    $(".sort_column_down,.sort_column_up").click(function() {
        var searchText = document.getElementById('search').value;
        var page = document.getElementById('page').value;
        var currentUrl = $(this).attr("id");
        if(searchText)
            window.location = currentUrl+'/'+searchText+'/'+page;
        else
            window.location = currentUrl+'/x/'+page;
    });
    
    // Datepicker
    $('#reportStartDate,#reportEndDate').datepicker({
        inline: true

    });

$('#graphStartDate,#graphEndDate').datepicker({
        inline: true

    });

$("#generateCouponName").click(function() {
   
        $.ajax({
            url: MAIN_URL+"admin/coupon/generateCouponCode",
            type: "POST",
            cache: false,
            success: function(response) {
                $('#txtCouponName').val(response);
            }
        });
   
});

   
    
});
function closeerrormessage(){
    $("#message-red").fadeOut("slow");
}

function closesuccessmessage(){
    if($("#settings-message-green"))
        $("#settings-message-green").fadeOut("slow");
    if($("#message-green"))
        $("#message-green").fadeOut("slow");
}

