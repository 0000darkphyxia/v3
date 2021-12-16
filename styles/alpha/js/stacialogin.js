function login() { 
	var Username = document.getElementById('Username').value;
	var Password = document.getElementById('Password').value;
    var details = "Login&Username=" + Username +"&Password=" + Password;
    $.ajax({
        type: "POST",
        url: "?login",
        data: details,
        success: function(html){    
			if(html=='true'){
				window.location = "?dashboard";
			} else {
				$("#add_err").css('display', 'inline', 'important');
				$("#add_err").html("<span style='color:red'>Invalid Username and Password!</span>");
				$("#add_err").effect("shake");
			}
		},
		beforeSend:function()
		{
			$("#add_err").css('display', 'inline', 'important');
			$("#add_err").html("<img width='25px'src='/styles/Miracle/img/loading.gif' /> Loading...")
		}
    });
    return false;
};

function signup() { 
	var Username = document.getElementById('sUsername').value;
	var Email = document.getElementById('sEmail').value;
	var Password = document.getElementById('sPassword').value;
	var ConfirmPassword = document.getElementById('sConfirmPassword').value;
	if(Username != "" && Email != "" && Password != "" && ConfirmPassword != ""){
		if(Password == ConfirmPassword){
			var details = "Signup&Username=" + Username +"&Email=" + Email +"&Password=" + Password;
			$.ajax({
				type: "POST",
				url: "?signup",
				data: details,
				success: function(html){    
					if(html=='true'){
						$("#add_err2").css('display', 'inline', 'important');
						$(".login_form").css('display', 'block');
						$(".display_none").css('display', 'none');
						$("#add_err").html("<span style='color:black'>Your account is now ready.</span>");
					} else if(html=='taken') {
						$("#add_err2").css('display', 'inline', 'important');
						$("#add_err2").html("<span style='color:red'>Username is already taken!</span>");
						$("#add_err2").effect("shake");
					}
				},
				beforeSend:function()
				{
					$("#add_err2").css('display', 'inline', 'important');
					$("#add_err2").html("<img width='25px'src='/styles/Miracle/img/loading.gif' /> Loading...")
				}
			});
		} else {
			$("#add_err2").css('display', 'inline', 'important');
			$("#add_err2").html("<span style='color:red'>Password not matched!</span>");
			$("#add_err2").effect("shake");
		};
	} else {
		$("#add_err2").css('display', 'inline', 'important');
		$("#add_err2").html("<span style='color:red'>Fill up the missing fields!</span>");
		$("#add_err2").effect("shake");
	}
    return false;
};


$(document).ready(function(){
    $("#log_in").click(function(){
        $(".login_form").css('display', 'none');
        $(".display_none").css('display', 'block');
    });
	
	$("#sign_up").click(function(){
        $(".login_form").css('display', 'block');
        $(".display_none").css('display', 'none');
    });
});

