function validatePaypalpro(thisform){ 
    var i=0;
    var fcs_fld=new Array();
    var errorstr = '';
    var msgstr = "Sorry, we cannot complete your request.\nKindly provide us the missing or incorrect information enclosed below.\n\n";
    if ($.trim($('#ccno1').val()) == '') { errorstr += "- Please enter Credit Card Number.\n";  fcs_fld[i]='ccno1'; i++;}
    if ($.trim($('#cvv1').val()) == '') { errorstr += "- Please enter Credit Card Validation Code.\n";  fcs_fld[i]='cvv1'; i++;}
    if (errorstr != '')
	{
		msgstr = msgstr + errorstr;
		alert(msgstr);
		$('#'+fcs_fld[0]+'').focus();$('#'+fcs_fld[0]+'').select();
		return false;
	}
	else
	{
		thisform.submit();
		return true;
	}
}

function showPaymentdiv(divid){ 


  $(".allpayment").hide();
  $('.allpayment').removeClass('active');
  $('#'+divid).show();
  $('#'+divid).addClass('allpayment active');
  setcurrentPaymnet(divid);
  setsubDomain();
}



function showpaymentMethod(){
    
} //End Function


function setcurrentPaymnet(payment){
    if($("#currentpaymant"))
     $("#currentpaymant").val(payment);
    if($("#currentpaymantdomain"))
     $("#currentpaymantdomain").val(payment);

     setsubDomain();
     return true;
}

function setStripe()
{



  // var sessionid=$("#stripe_sessionid").val();
  
     if($("#currentpaymant"))
     $("#currentpaymant").val('stripe');
    if($("#currentpaymantdomain"))
     $("#currentpaymantdomain").val('stripe');
   // if($("#sessionid"))
   //   $("#stripe_sessionid").val(sessionid);

     setsubDomain();
     return true;

}

function setsubDomain(){
    $("#txtStoreNameuserfm").val($("#txtStoreName").val());
     return true;
}

function showpaymentOption(paymentMethod){   
    $('#insetPayment').show();
    if(paymentMethod=='paypalpro'){
      $('#paymentMethod').show();
      var options ='<option value="US">United States</option>';
      $('#country').html(options);
    }
     $("#paymentOption").val(paymentMethod);     
} //End Function

function setcurrentPayment(paymentMethod){    
    $("#paymentOption").val(paymentMethod);     
}





