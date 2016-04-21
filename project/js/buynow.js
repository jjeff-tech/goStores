$(document).ready(function()
{
$("#jqCheckSubdomainExist").click(checkdomainavailability);

$("#jqProductBuy").validate({
                rules: { txtStoreName: { required: true},
                    txtEmail: { required: true, email: true },
                    txtName: { required: true} ,
                    txtPassword: { required: true }
            },
                messages: { txtStoreName: { required: "Please enter secure url"},
                    txtEmail: { required: "Please enter email", email:"Please enter a valid email" } 	,
                    txtName: { required: "Please enter your name"},
                    txtPassword: { required: "Please enter password" } 	}
            });
});

function checkdomainavailability()
{
    var ajaxSubmitOption 	= {
                                url      : checkeAccount,
                                data     : "storeName="+$("#txtStoreName").val(),
                                type     : "post",
                                dataType : "json",
                                success  : function(data)
                                           {
                                               $('#overlay').fadeOut('slow');
                                                if(data.faild)
                                                {
                                                        jQuery('#jqShowMessage').removeClass('domainsearchsuccess');
                                                        jQuery('#jqShowMessage').addClass('domainsearchfaild');
                                                        $("#jqCretaeAccount").html('').css('display', 'none');
                                                        $("#jqShowMessage").text(unescape(data.list));

                                                }
                                                else if(data.success)
                                                {

                                                    jQuery('#jqShowMessage').removeClass('domainsearchfaild');
                                                    jQuery('#jqShowMessage').addClass('domainsearchsuccess');
                                                    $("#jqCretaeAccount").html('').css('display', '');
                                                    $("#jqShowMessage").text(unescape(data.list));
                                                }
                                           },
                             beforeSend : function()
                                          {
                                              $('#overlay').fadeIn('slow');
                                          },
                               complete : function()
                                          {
                                              $('#overlay').fadeOut('slow');
                                          }
                          };
	$.ajax(ajaxSubmitOption);
	return false;
}
function cretaedomain()
{
	alert('buynow');
return false;
     var ajaxSubmitOption 	= {
                                url      : createAccount,
                                data     : "storeName="+$("#txtStoreName").val()+"&userEmail="+$("#txtEmail").val()+"&userName="+$("#txtName").val()+"&userPassword="+$("#txtPUserPassword").val(),
                                type     : "post",
                                dataType : "json",
                                success  : function(data)
                                           {
                                               $('#overlay').fadeOut('slow');
                                                if(data.error)
                                                {
                                                }
                                                else if(data.success)
                                                {
//                                                    var contents=
                                                           $("#jqMessage").html(unescape(data.list));
                                                            $("#jqProductBuy").css('display', 'none');
//                                                            $("#jqScoreMagazineCarCare").html(unescape(data.carcarelist));
                                                }
                                           },
                             beforeSend : function()
                                          {
                                              $('#overlay').fadeIn('slow');
                                          },
                               complete : function()
                                          {
                                              $('#overlay').fadeOut('slow');
                                          }
                          };
	$.ajax(ajaxSubmitOption);
	return false;
}
