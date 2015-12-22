var offsetToScroll = "";
var SignUpModel = function(){
	var environ = window.location.host;
	function getAjaxUrl(){
			if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/";
			}
			return baseurl;
		}
	var self = this;
	var rules = {
		"default": /^[ñA-Za-z -]+$/i,
		"student_number": /^[0-9]{9}$/,
		"student_number_login": /^[0-9]{9}$/,
		"first_name": /^[ñA-Za-z0-9 \-\.']+$/i,
		"last_name": /^[ñA-Za-z0-9 \-\.']+$/i,
		"mobile_number": /^[0-9]{11}$/,
		"email_address": /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i,
		"password": /^[A-Z0-9\-\_']{6,}$/i,
		"password_login": /^[A-Z0-9\-\_']{6,}$/i,
		"confirm_password": /^.{6,}$/

	}

	var errormessages = {
		"student_number": "Student number must be 9 digits without dash.",
		"student_number_login": "Student number must be 9 digits without dash.",
		"first_name": "First name can contain alphabet characters, spaces or dashes only.",
		"last_name": "Last name can contain alphabet characters, spaces or dashes only.",
		"mobile_number": "Mobile number must be 11 digits.",
		"email_address": "Email address must be valid.",
		"default": "must be valid",
		"password": "Password must contain at least 6 character combination of letters, numbers, _ or -.",
		"password_login": "Password must contain at least 6 character combination of letters, numbers, _ or -.",
		"confirm_password": "Passwords must match."


	}

	var required = {
		"default": "Required.",
		"student_number": "Please enter your student number.",
		"first_name": "Please enter your first name.",
		"last_name": "Please enter your last name.",
		"email_address": "Please enter your email address.",
		"mobile_number": "Please enter your mobile number.",
		"password": "Please type your password.",
		"password_login": "Please type your password.",
		"confirm_password": "Please confirm your password."	
	}

	self.availableStudentType = ko.observableArray(
		["Undergraduate","Graduate"]

		);	

	self.personal_info = {
		
		first_name: ko.observable($("input[name='first_name']").attr('data-value')),
		last_name: ko.observable($("input[name='last_name']").attr('data-value')),
		student_number: ko.observable($("input[name='student_number']").attr('data-value')),
		email_address: ko.observable($("input[name='email_address']").attr('data-value')),
		password: ko.observable(""),
		confirm_password: ko.observable("")

	}
	self.login_details = {
		student_number: ko.observable($("input[name='student_number_login']").attr('data-value')),
		password: ko.observable("")
	}
	self.isTermsAgreed = ko.observable(false);
	self.isReadyForLogIn = function(){
		if(self.login_details.student_number()==""||self.login_details.password()=="")
			return false;
		else{
			for(var prop in self.login_details){

				if(rules[prop]!=undefined){
					if(!self.login_details[prop]().match(rules[prop])){
						return false;
					}
						
				}
			}
		}
		return true;
	}

	self.login = function(){
		$.ajax({
			type: "post",
			url: getAjaxUrl()+"login/auth",
			data: ko.toJS(self.login_details),
			beforeSend:function(){
				$('.login-container').append('<div class="div-loading"></div>');
			},
			success: function(result){
				if(result){
					result=JSON.parse(result);
					window.location.replace(result['url']);
				}else{
					$("#alert-error").remove();
					$('.div-loading').remove();
					$("input[name='student_number_login']").before('<div id="alert-error">Invalid student number and password combination.</div>')
					setTimeout(function(){
						$("#alert-error").fadeOut("normal");
					},2500);
				}
			},
			error:function(x,t,h){
				if(t==="timeout"){
					$('.div-loading').remove();
					$("#alert-error").remove();
					$("input[name='student_number_login']").before('<div id="alert-error">Error connecting to Resume Box. Please try again.</div>')
					setTimeout(function(){
						$("#alert-error").fadeOut("normal");
					},1000);
				}else{
					window.location.reload();
				}
			}
		});
	}

	self.validateOnBlur = function(item,event){
		var $target = $(event.target);
		
		self.validate($target);

		
	}

	self.validate = function($target,allrequired){

		var allrequired = (allrequired === undefined) ? 0 : allrequired;
		var name = $target.attr("name");
		var input = $target.val();
		var $p = $target.next('p');
		if(input==""){

			if(!allrequired){
				if($target.attr("data-notrequired")!=undefined)
					return true;				
			}
			

			$target.addClass('danger');
		
				if(required[name]!=undefined){

					if($p.length==0)
						$target.after('<p class="error">'+ required[name]+'</p>');

				}else{
					if($p.length==0)
						$target.after('<p class="error">'+required['default'] + '</p>');
			
				}
						
			return false;
		}
		else{
			if(name.match("confirm_password")||name.match("password")){
				var pass=self.personal_info.password();
				var confirm=self.personal_info.confirm_password();
	
				if(!pass.match(confirm)){
			
					$target.addClass("danger");
					if($p.length==0)				
						$target.after('<p class="error matchpassword">'+ errormessages["confirm_password"] +'</p>');
					return false;
				}else{
					$('input[name="confirm_password"],input[name="password"]').removeClass('danger');
					$('p.matchpassword').remove();
				}
			}

			if(name!=""&&name!=undefined){

				if(rules[name]!=undefined)
					if(!input.match(rules[name])){
						$target.addClass("danger");
				
						
						if(errormessages[name]!=undefined){
							if($p.length==0)				
								$target.after('<p class="error">'+ errormessages[name] +'</p>');
						
						}else{
							var errormessage = (name.charAt(0).toUpperCase() + name.slice(1)).replace(/_/g, " ") + " " + errormessages['default'];
							if($p.length==0)				
								$target.after('<p class="error">'+ errormessage +'</p>');
						}

						return false;
					}

				
			}
		}



		return true;
		

	}
	self.resetInput = function(item,event){
		var $target = $(event.target);
		$target.removeClass("danger");
		$target.next('p').fadeOut(100, function(){
			$(this).remove();});
	}

	self.isPasswordMatch = function(){
		var passwords={
			password:self.personal_info.password(),
			confirm_password:self.personal_info.confirm_password()
		}
		for(var prop in passwords){

			if(rules[prop]!=undefined){
				if(!self.personal_info[prop]().match(rules[prop])){
					return false;
				}
					
			}
		}
		var pass=self.personal_info.password();
		var confirm=self.personal_info.confirm_password();

		if(!pass.match(confirm)){
	
			return false;
		}else{
			$('input[name="confirm_password"],input[name="password"]').removeClass('danger');
			$('p.matchpassword').remove();
		}

		return true;
	}


	self.isPersonalInfoValid = function(){
		for(var prop in self.personal_info){

			if(rules[prop]!=undefined){
				if(!self.personal_info[prop]().match(rules[prop])){
					return false;
				}
					
			}
		}

		if(!self.isTermsAgreed())
			return false;
		if(!self.isPasswordMatch())
			return false;
		return true;
	}
	self.submitSignUp=function(){
		$('body').append('<div class="div-loading"></div>');
		$('#signupform').submit();
	}
	self.submitReset=function(){
		$('body').append('<div class="div-loading"></div>');
		$('#form-reset').submit();
	}
}