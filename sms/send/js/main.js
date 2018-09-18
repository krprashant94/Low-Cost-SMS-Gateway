(function ($) {
    "use strict";
    
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;
		event.preventDefault();
        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }else if(i == 0){
				if(input[i].value.length < 10 || input[i].value.trim().match(/^[0-9]*$/) == null){
					showValidate(input[i]);
					check=false;
				}
			}else if(i == 2){
				if(input[i].value.length != 64){
					showValidate(input[i]);
					check=false;
				}
			}
        }
		if(check){
			$(".login100-form-btn").prop('disabled', true);
			$(".login100-form-btn").html('Sending...');
			$.ajax({
				type: 'POST',
				url: "../API/SMSGateway.php",
				data: "phone="+input[0].value+"&message="+input[1].value+"&key="+input[2].value+"&sms&otp",
				dataType: "JSON",
				success: function(resultData) {
                    $(".login100-form-btn").html('Send');
                    $(".login100-form-btn").prop('disabled', false);
                    document.getElementById("smsForm").last.value = resultData[2];
					console.log(resultData);
				}
			});
		}
        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    

})(jQuery);