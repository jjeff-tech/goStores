// Login Form

$(function() {
   $("#loginButton").click(function() {
            var button = $('#loginButton');
              $(".loginButton").addClass("active");
              //$("#loginBox").show();

            $("#loginButton").addClass("active");

           if($('#loginBox').css('display') == 'block'){
               $('#loginBox').hide();
            } else {
                $("#loginButton").addClass("active");
               $('#loginBox').show();
            }

});

    	
    /*var button = $('#loginButton');
    var box = $('#loginBox');
   // var form = $('#loginForm');
    button.removeAttr('href');
    button.mouseup(function(login) {
	
        box.toggle();
        button.toggleClass('active');
    });*/
	
   
    /*$(this).mouseup(function(login) {
        if(!($(login.target).parent('#loginButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });*/
});
