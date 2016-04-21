$().ready(function() {

    $("#loginbuttonhome").click(function(){
       $('#txtUsername').focus();
    });
 $("#txtUsername").val('');
  $("#txtPassword").val('');
            $("#loginForm").validate({
                rules: { txtUsername: { required: true },
                    txtPassword: { required: true } },
                messages: { txtUsername: { required: "Please enter Email Address" },
                    txtPassword: { required: "Please enter Password" } 	}
            });
            $("#frmSearch").validate({
                rules: { txtsearch: { required: true } },
                messages: { txtsearch: { required: "Please enter search text" }}
            });

$("#jqProductTryForm").validate({
            rules: {
                txtStoreName: {
                    required: true
                },
                txtEmail: {
                    required: true,
                    email: true
                },
                txtPassword: {
                    required: true
                }
            },
            messages: {
                txtStoreName: {
                    required: "Please enter store name"
                },
                txtEmail: {
                    required: "Please enter email address",
                    email:"Please enter a valid email"
                }	,
                txtPassword: {
                    required: "Please enter password"
                }
            }
        });

        $("#jqProductTryForm1").validate({
            rules: {
                txtStoreName: {
                    required: true
                }
            },
            messages: {
                txtStoreName: {
                    required: "Please enter store name"
                }

            }
        });


            $(".loginContainer").hide();

              $(".signin").removeClass("menu-open");
              $("div#signin_menu").hide();

            $(".jqLoginInnerDiv").live("click",{
        param: 0
    },showLoginDiv);
    $(".jqInnerLoginClose").live("click",{
        param: 0
    },hideLoginDiv);


        });
     function loginuseraction()
 {

         var ajaxSubmitOption 	= {
                                url      : userLogin,
                                data     : "username="+$("#txtUsername").val()+"&password="+$("#txtPassword").val(),
                                type     : "post",
                                dataType : "json",
                                success  : function(data)
                                           {

                                                if(data.faild)
                                                {
                                                        $("#jqLoginError").html(unescape(data.message));


//                                                        $(".signin").removeClass("menu-open");
//                                                        $("div#signin_menu").hide();

                                                }
                                                else if(data.success)
                                                {
                                                    //$("#jqLoginError").text(unescape(data.message));
                                                    $(".signin").removeClass("menu-open");
                                                    $("div#signin_menu").hide();
                                                    window.location.href = loginSuccess;
                                                }
                                           },
                             beforeSend : function()
                                          {
                                          },
                               complete : function()
                                          {
                                          }
                          };
	$.ajax(ajaxSubmitOption);
	return false;
 }



function insertNewsLetter()
{
     var ajaxSubmitOption 	= {
                                url      : createnewsletter,
                                data     : "Name="+$("#txtName").val()+"&Email="+$("#txtEmail").val(),
                                type     : "post",
                                dataType : "json",
                                success  : function(data)
                                           {
                                                if(data.failed)
                                                {
                                                    $("#jqNewsletterMessage").html(unescape(data.list));
                                                }
                                                else if(data.success)
                                                {
                                                  $("#txtName").val('');
                                                  $("#txtEmail").val('');
                                                  $("#jqNewsletterMessage").html(unescape(data.list));
                                                }
                                           },
                             beforeSend : function()
                                          {
                                          },
                               complete : function()
                                          {
                                          }
                          };
	$.ajax(ajaxSubmitOption);
	return false;
}

function loginuseractionfrominner()
 {

         var ajaxSubmitOption 	= {
                                url      : userLoginInner,
                                data     : "username="+$("#txtUsernameInner").val()+"&password="+$("#txtPasswordInner").val()+"&loginFromInner="+$(".loginFromInner").val(),
                                type     : "post",
                                dataType : "json",
                                success  : function(data)
                                           {
                                                if(data.faild)
                                                {
                                                        $("#jqInnerLoginError").text(unescape(data.message));
//                                                        $(".signin").removeClass("menu-open");
//                                                        $("div#signin_menu").hide();

                                                }
                                                else if(data.success)
                                                {

                                                    document.location.href=window.location;
                                                    $("#jqInnerLoginError").text(unescape(data.message));
                                                    $('.jqInnerLoginFormDiv').delay(1000).fadeOut('slow');
                                                    $("#jqShowAccountMessage").hide('slow');
                                                    $("#jqUserExistFlag").val(1);
                                                    $("#jqShowEmailAccountMessage").hide('slow');


                                                }
                                           },
                             beforeSend : function()
                                          {
                                          },
                               complete : function()
                                          {
                                          }
                          };
	$.ajax(ajaxSubmitOption);
	return false;
 }

 function showLoginDiv(){


    $("#jqInnerLoginError").text('');
    $("#txtPasswordInner").val('');
    $("#txtUsernameInner").val('');
    if ($(".jqLoginInnerDiv").is('[title]')) {
        // Pre fill the user email if the user exists in our system
        $("#txtUsernameInner").val($(".jqLoginInnerDiv").attr("title"));
    }
    $('.jqInnerLoginFormDiv').show();
    return false;
}

function hideLoginDiv()
{
    $('.jqInnerLoginFormDiv').hide('slow');
    return false;
}
