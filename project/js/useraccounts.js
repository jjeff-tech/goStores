
$(function() {

$("#firstName").focus();



    $("#frmSignUp").validate({
        rules: {
            firstName: {
                required: true
            },
            lastName: {
                required: true
            },
            emailAddress: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                minlength: 10
            },
            password: {
                required: true,
                minlength: 8,
            } ,
            confirmPassword: {
                required: true,
                minlength: 8,
                equalTo: '#password'
            }

        },
        messages: {
            firstName: {
                required: "Please enter first name"
            },
            lastName: {
                required: "Please enter last name"
            },
            emailAddress: {
                required: "Please enter email",
                email:"Please enter a valid email"
            }	,
            phone: {
                required: "Phone number is required",
                minlength: "Please enter at least 10 digits for phone number."
            },
            password: {
                required: "Please enter password",
                minlength: "Your password must be at least 8 characters long"
            },
            confirmPassword: {
                required: "Please re-type password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above"
            }

        },
        submitHandler: function(form) {
        //$('#div-loading').show();
        //$('#div-submit').hide();
        //alert('Here comes the control');
        // do : ajax call for user duplication check with email
         var rootUrl = BASE_URL+'index/checkduplicateuser';
         var emailId = $('#emailAddress').val();
         $('#emailAddress_err').html('');
         $.ajax({
                        type: "POST",
                        url: rootUrl,
                        data: {email: emailId}
                    }).success(function( msg ) {
                        //alert(msg);
                        if(msg==1){
                            $('#emailAddress_err').html('Email address already exists');
                        } else {
                            form.submit();
                        } // End If
                    });

        }
    });

});
