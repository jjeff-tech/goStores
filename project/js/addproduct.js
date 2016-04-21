    $(function() {
     // Handler for .ready() called.
     //
     
         $("#chkAll").click(function() {
            if ($('#chkAll').is(':checked')) {
                $("input[name='chkService[]']").each(function(i, j){
                  
                    ($(j).attr('checked')) ? '' : $(j).attr('checked', true);
                });
            } else {
                $("input[name='chkService[]']").each(function(i, j){
                    ($(j).attr('checked')) ? $(j).attr('checked', false) : '';
                });
            }
         });
         // Delete Checked items

         $("#deleteChk").click(function() {
            var delFlg = false;
            $("input[name='chkService[]']").each(function(i, j){

                if($(j).attr('checked')) {
                    $(j).closest('li').remove();
                    delFlg=true;
                } 
            });
            if(delFlg==false){
                alert("Select the service item to delete");
            } else {
                ($('#chkAll').is(':checked')) ? $('#chkAll').attr('checked', false) : '';
            }
         });

          $("#deleteChkEd").click(function() {
            var delFlg = false;
            var rootUrl = BASE_URL+'admin/products/dropservice';
            $("input[name='chkService[]']").each(function(i, j){
                if($(j).attr('checked')) {
                    var serId = $(j).val();
                    if($(j).val()!=''){
                        $.ajax({
                        type: "POST",
                        url: rootUrl,
                        data: {id: serId}
                    }).success(function( msg ) {
                        //alert(msg);                                                
                    });
                    }
                    $(j).closest('li').remove();
                    delFlg=true;
                } 
            });
            if(delFlg==false){
                alert("Select the service item to delete");
            } else {
                ($('#chkAll').is(':checked')) ? $('#chkAll').attr('checked', false) : '';
            }
         });

     
$('input:radio').click(function() {

var radioName = $(this).attr('name');

if(radioName.search("billingType")!=-1 && $(this).attr('value')=='L'){
    
    var intervalId = radioName.replace("billingType","billingInterval");
    intervalId = intervalId.replace("[","_");
    intervalId = intervalId.replace("]","");
    var index = parseInt(intervalId.slice(-1));
    intervalId = intervalId.replace(index,index+1);
     $('#'+intervalId).val('1');
     $('#'+intervalId).attr('readonly', true);


}else if(radioName.search("billingType")!=-1 && $(this).attr('value')!='L'){
     var intervalId = radioName.replace("billingType","billingInterval");
    intervalId = intervalId.replace("[","_");
    intervalId = intervalId.replace("]","");
    var index = parseInt(intervalId.slice(-1));
    intervalId = intervalId.replace(index,index+1);
     
     $('#'+intervalId).attr('readonly', false);
}
}); 
}); 

   function newServiceItem(){
        var rootUrl = BASE_URL+'admin/products/addServiceItem';
        // Add New
        var newItemCnt = $('#addNew').val();
        var countLast = $('#countL').val();
        var newCnt;
        newCnt = parseInt(newItemCnt)+parseInt(countLast);
        if(newItemCnt==''){
            alert("Enter no of services you wish to add");
             $('#addNew').focus();
        } else {
             // New Item
            $.ajax({
                type: "POST",
                url: rootUrl,
                data: {cntN: newItemCnt, cntL: countLast}
            }).success(function( msg ) {
                //alert(msg);
                //$('#serItem_'+countLast).append(msg);
                $('#productSer li:last').prev().append(msg);
                $('#countL').val(newCnt);
                $('#addNew').val('');
                $('input:radio').click(function() {

var radioName = $(this).attr('name');

if(radioName.search("billingType")!=-1 && $(this).attr('value')=='L'){

    var intervalId = radioName.replace("billingType","billingInterval");
    intervalId = intervalId.replace("[","_");
    intervalId = intervalId.replace("]","");
    var index = parseInt(intervalId.slice(-1));
    intervalId = intervalId.replace(index,index+1);
     $('#'+intervalId).val('1');
     $('#'+intervalId).attr('readonly', true);


}else if(radioName.search("billingType")!=-1 && $(this).attr('value')!='L'){
     var intervalId = radioName.replace("billingType","billingInterval");
    intervalId = intervalId.replace("[","_");
    intervalId = intervalId.replace("]","");
    var index = parseInt(intervalId.slice(-1));
    intervalId = intervalId.replace(index,index+1);

     $('#'+intervalId).attr('readonly', false);
}
}); 
            });
        }
   } // End Function

   function validateProduct() {
    /*
     */
    clearProductErrorFields();
     var status = true;
     if($('#productName').val()=='') {
        $('#product_name_error').html('This field is required');
        status = false;
     }

     // Product Release
     if($('#productRelease').val()=='') {
         $('#product_release_error').html('This field is required');
         status = false;
     }

     // Product Caption
     if($('#productCaption').val()=='') {
         $('#product_caption_error').html('This field is required');
          status = false;
     }

     // Product Logo
     if($('#productLogo').val()=='') {
         $('#product_logo_error').html('This field is required');
         status = false;
     }

     // Product Logo Extension check
      if($('#productLogo').val()!='') {
        var extLogo = $('#productLogo').val().split('.').pop().toLowerCase();
        if($.inArray(extLogo, ['gif','png','jpg','jpeg']) == -1) {
            $('#product_logo_error').html('Invalid file type for Product Logo');
            status = false;
        }
      }

     // Product Logo Small
     if($('#productLogoSmall').val()=='') {
         $('#product_logo_small_error').html('This field is required');
         status = false;
     }

     // Product Logo Small Extension check
     if($('#productLogoSmall').val()!='') {
        var extLogoSmall = $('#productLogoSmall').val().split('.').pop().toLowerCase();
        if($.inArray(extLogoSmall, ['gif','png','jpg','jpeg']) == -1) {
            $('#product_logo_small_error').html('Invalid file type for Product Logo Small');
            status = false;
        }
     }

     //Product Screens
      if($('#productScreens').val()=='') {
          $('#product_screen_error').html('This field is required');
          status = false;
     }

     // Product Logo Screens Extension check
     if($('#productScreens').val()!='') {
        var extLogoSmall = $('#productScreens').val().split('.').pop().toLowerCase();
        if($.inArray(extLogoSmall, ['gif','png','jpg','jpeg']) == -1) {
            $('#product_screen_error').html('Invalid file type for Product Screens');
            status = false;
        }
     }

     //productPack
      if($('#productPack').val()=='') {
          $('#product_pack_error').html('This field is required');
          status = false;
     }

     // Product Pack Extension check
     if($('#productPack').val()!='') {
        var extProductPack = $('#productPack').val().split('.').pop().toLowerCase();
        if($.inArray(extProductPack, ['zip']) == -1) {
            $('#product_pack_error').html('Invalid type for Product Pack');
            status = false;
        }
     }

     //Product Service Name
     $("input[name='serviceName[]']").each(function(i, j){
       if($(j).val()=='') {
            status = false;
            $('#service_name_error_'+(i+1)).html('This field is required');
       }
    });

    //Product Service Description
    $("textarea[name='serviceDescription[]']").each(function(i, j){
        if($(j).val()=='') {
            $('#service_description_error_'+(i+1)).html('This field is required');
             status = false;
        }
    });

    //Product Service Price
    $("input[name='servicePrice[]']").each(function(i, j){
       if($(j).val()=='') {
            status = false;
            $('#service_price_error_'+(i+1)).html('This field is required');
       } else if(isNaN($(j).val())){
            status = false;
            $('#service_price_error_'+(i+1)).html('Enter valid Service Price');
       }
    });

    // Billing Duration
     $("input[name='billingInterval[]']").each(function(i, j){
        
        if($(j).val()=='' && $('input[name="billingType['+i+']"]:checked').val()!='L'){
            status = false;
            $('#service_billing_duration_error_'+(i+1)).html('This field is required');
        }else if(isNaN($(j).val())){
             status = false;
            $('#service_billing_duration_error_'+(i+1)).html('Enter valid Billing Duration');
       }
     });

     return status;

   } // End Function

    function validateProductEd() {
    /*
     */
     clearProductErrorFields();
     var status = true;

     if($('#productName').val()=='') {
          $('#product_name_error').html('This field is required');
            status = false;
     }

     // Product Release
     if($('#productRelease').val()=='') {
         $('#product_release_error').html('This field is required');
         status = false;
     }

     // Product Caption
     if($('#productCaption').val()=='') {
          $('#product_caption_error').html('This field is required');
          status = false;
     }

     // Product Logo Extension check
     if($('#productLogo').val()!='') {
         var extLogo = $('#productLogo').val().split('.').pop().toLowerCase();
         if($.inArray(extLogo, ['gif','png','jpg','jpeg']) == -1) {
             $('#product_logo_error').html('Invalid file type for Product Logo');
             status = false;
         }
     }

     // Product Logo Small Extension check
     if($('#productLogoSmall').val()!='') {
         var extLogoSmall = $('#productLogoSmall').val().split('.').pop().toLowerCase();
         if($.inArray(extLogoSmall, ['gif','png','jpg','jpeg']) == -1) {
              $('#product_logo_small_error').html('Invalid file type for Product Logo Small');
              status = false;
         }
     }

     // Product Logo Screens Extension check
     if($('#productScreens').val()!='') {
         var extLogoSmall = $('#productScreens').val().split('.').pop().toLowerCase();
         if($.inArray(extLogoSmall, ['gif','png','jpg','jpeg']) == -1) {
             $('#product_screen_error').html('Invalid file type for Product Screens');
            status = false;
         }
     }

     // Product Pack Extension check
     if($('#productPack').val()!='') {
         var extProductPack = $('#productPack').val().split('.').pop().toLowerCase();
         if($.inArray(extProductPack, ['zip']) == -1) {
              $('#product_pack_error').html('Invalid type for Product Pack');
              status = false;
         }
     }

     //Product Service Name
     $("input[name='serviceName[]']").each(function(i, j){
       if($(j).val()=='') {
           status = false;
         $('#service_name_error_'+(i+1)).html('This field is required');
       }
    });
    
    $("textarea[name='serviceDescription[]']").each(function(i, j){
       if($(j).val()=='') {
           $('#service_description_error_'+(i+1)).html('This field is required');
             status = false;
       }
    });
   
    $("input[name='servicePrice[]']").each(function(i, j){
       if($(j).val()=='') {
             status = false;
            $('#service_price_error_'+(i+1)).html('This field is required');
       } else if(isNaN($(j).val())){
             status = false;
            $('#service_price_error_'+(i+1)).html('Enter valid Service Price');
       }
    });

     $("input[name='billingInterval[]']").each(function(i, j){
        if($(j).val()==''  && $('input[name="billingType['+i+']"]:checked').val()!='L'){
            status = false;
            $('#service_billing_duration_error_'+(i+1)).html('This field is required');
        }else if(isNaN($(j).val())){
             status = false;
            $('#service_billing_duration_error_'+(i+1)).html('Enter valid Billing Duration');
        }
     });
  
    return status;

   } // End Function

   function closeerrormessage(){
       $('#message-red').slideUp('slow');
   }
   function closesuccessmessage(){
       $('#message-green').slideUp('slow');
   }


function clearProductErrorFields(){
    $('#product_name_error').html('');
    $('#product_release_error').html('');
    $('#product_caption_error').html('');
    $('#product_logo_error').html('');
    $('#product_logo_small_error').html('');
    $('#product_screen_error').html('');
    $('#product_pack_error').html('');

    $("input[name='serviceName[]']").each(function(i, j){
         $('#service_name_error_'+(i+1)).html('');
    });
    $("textarea[name='serviceDescription[]']").each(function(i, j){
         $('#service_description_error_'+(i+1)).html('');
    });

    $("input[name='servicePrice[]']").each(function(i, j){
        $('#service_price_error_'+(i+1)).html('');
    });

    $("input[name='billingInterval[]']").each(function(i, j){
        $('#service_billing_duration_error_'+(i+1)).html('');
     });
}



