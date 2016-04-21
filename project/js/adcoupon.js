    $().ready(function() {
            $("#frmgenerateCoupons").validate({
                rules: {
                    txtCouponName:{ required: true},
                    txtNoOfCoupons: { required: true,number: true },
                    txtExpiryDate: { required: true, date: true },
                    txtDiscountRate: { required: true ,number: true }
                    
            },
                messages: {
                    txtCouponName: { required: "Please enter coupon name"},
                    txtNoOfCoupons: { required: "Please enter coupon count", number: "Please enter a valid number" },
                    txtExpiryDate: { required: "Please enter expiry date" } 	,
                    txtDiscountRate: { required: "Please enter discount value" , number: "Please enter a valid number" }
                }
            });

           /* $('input:radio').click(function() {
           if($(this).attr('value')=='percentage'){
               $("#jqCurrency").html('&nbsp;&nbsp;&nbsp;');
           }else{
               $("#jqCurrency").html(CURRENCY+'&nbsp;');
           }
       });*/
    });