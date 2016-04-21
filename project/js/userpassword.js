$(document).ready(function()
{
$("#jqForgotPwd").validate({
                rules: {
                    txtEmail: {required: true, email: true}
            },
                messages: {
                    txtEmail: {required: "Please enter email", email:"Please enter a valid email"}
                }
            });

$("#jqResetPwd").validate({
                rules: {
                    password: {required: true},
                    confirm_password:{required: true}
            },
                messages: {
                    password: {required: "Please enter password"},
                    confirm_password: {required: "Please enter confirm password"}
                }
            });
 
});
