    $().ready(function() {        
            $("#frmuserLogin").validate({
                rules: { txtUsername: { required: true },
                    txtPassword: { required: true } },
                messages: { txtUsername: { required: "Please enter Username" },
                    txtPassword: { required: "Please enter Password" } 	}
            });
    });