
$(function() {
    
    $("#resendInv").click(function() {
         // do : ajax call for resend invoice
         var rootUrl = BASE_URL+'admin/service/resendinvoice';
         var invoiceId = $('#invNumber').val();
        
         $('#msgSendInv').html('');
         $.ajax({
                        type: "POST",
                        url: rootUrl,
                        data: {invoice: invoiceId}
                    }).success(function( msg ) {
                        //alert(msg);
                        if(msg==1){
                            $('#msgSendInv').html('Invoice Sent Successfully');
                            $("#msgSendInv").slideDown('slow', function(){ setTimeout('hideMsg()', 2000); });
                        }
                    });
    });


});

function hideMsg() {
    $("#msgSendInv").slideUp('slow');
}

