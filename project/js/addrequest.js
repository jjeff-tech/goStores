 $(document).ready(function()
{
$("#frmaddrequest").validate({
                rules: { tUserComments: { required: true},                    
                    nRequestedAmount: { required: true,number: true}
            },
                messages: { tUserComments: { required: "Please enter Description"},                    
                    nRequestedAmount: { required: "Please enter the Requested Amount",number: "Please enter a Number"}
               }
            });
});