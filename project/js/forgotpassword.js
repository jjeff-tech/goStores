    $().ready(function() {
            $("#frmuserPassword").validate({
                rules: { txtuseremail: { required: true, email: true } },
                messages: { txtuseremail: { required: "Please enter Email-ID", email: "Please enter valid Email-ID"} 	}
            });
    });    