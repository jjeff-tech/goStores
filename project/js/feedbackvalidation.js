/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
     $("#frmFeedback").validate({
        rules: {
            name: {
                required: true
            },
           email: {
                required: true,
                email: true
            },

            feedback: {required: true}

        },
        messages: {
            fname: {
                required: "Please enter name"
            },
            name: {
                required: "Please enter name"
            },
            email: {
                required: "Please enter email",
                email:"Please enter a valid email"
            }	,

            feedback: {
                required: "Please enter your feedback"
            }

        }
    });
});
