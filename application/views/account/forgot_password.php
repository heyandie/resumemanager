<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/signup-1-20-15-3-25.min.css">
<style type="text/css">
.btn-small{
	padding: 5px;
}
.btn-default{
	background: #ccc;
}
html{
	height: 100%;
}
body{
	height: 100%;
	position: relative;

}
label,p{
	color: #111;
}
.login-container{
	background: #fff;
	padding:20px 15px;
	margin: 0 auto;
	color: #111;
	position: relative;
	width: 280px;
	height: auto;
}
input{
	background: transparent;
	outline: 0px;
	border: 1px solid #ccc;
}
h1,h2{
	color: #111;
	text-align: left !important;
}
footer{
	position: absolute;
	bottom: 0px;
	width: 100%;
	color: #fff;
}
</style>
<div class="grid-container menu-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5 menu-bar">
		<div class="brand">
			<a style="display:inline-block;" href="<?php echo site_url();?>"><div class="capes-logo"></div></a>
		</div>
		
		<div class="brand"><a href="<?php echo site_url();?>">Resume Manager</a></div>
		<div class="link-pull-right cl-effect-5">
				
				<a href="<?php echo site_url('register');?>"><span data-hover="Sign Up">Sign Up</span></a>
			</div>
	</div>

</div>
<div id="signup-wrapper" class="grid-container default-container">
	<div class="grid-90 prefix-5 tablet-grid-70 tablet-prefix-15 mobile-grid-80 mobile-prefix-10 grid-parent" style="position:relative">
		
		<div class="login-container" style="margin-top:120px">
				
				<h2 style="text-align:center">Find your account</h2>
		
				
						
						<div id="inner">
							
							<div class="form-inputs">
							<div>
							<input maxlength="9" class="grid-100 tablet-grid-100 mobile-grid-100 credentials" name="student_number" 
							placeholder="Student Number (Ex. 2014XXXXX)" data-bind="event:{focus: resetInput,blur: validateOnBlur}"></input>
							</div>
							<div>
							</div>
							</div>
							<button id="search-acct"class="btn btn-block">Search</button>
						</div>
					
		
		</div>
		
		

	</div>
</div>
<footer>
	<div>UP CAPES Resume Box © 2015</div>
</footer>
<script type="text/javascript" src="<?php echo base_url();?>js/signup.js"></script>

<script type="text/javascript">
$(function(){
	var sn="";
	function searchAccount(){
		var environ = window.location.host;
		function getAjaxUrl(){
			if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/login/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"login/";
			}
			return baseurl;
		}
		var student_number=$("input[name='student_number']").val().trim();
		$.ajax({
			url:"check_account",
			type:"POST",
			data:{student_number:student_number},
			success:function(data){
				if(data==0){
					$("#inner").html("");
					$("#inner").append("<p>Sorry, no account is registered with student number <b>"+student_number+"</b></p>");
					$("#inner").append('<button class="btn btn-dismiss btn-block">Ok</button>');
				}else{
					data=JSON.parse(data);
					sn=student_number;
					$("#inner").html("");
					$("#inner").append("<p>Are you sure you are <b>"+data['name']+"</b>?<br>");
					$("#inner").append("<p>If yes, we will email a link to reset your password to<br></p>");
					$("#inner").append("<p>"+data.email_address+"</p>");
					$("#inner").append('<div class="grid-100 grid-parent">'+
							'<a id="confirm_account" class="btn btn-small">Confirm</a>&nbsp;'+
							'<button id="btn"class="btn btn-dismiss btn-small btn-default">Cancel</button>'+
							'</div>');
							
				}
				
			}

		});
	}
	$("#search-acct").click(function(){
		searchAccount();
	});
	$("body").on('click','.btn-dismiss',function(){
		location.replace("/login");
	});
	$("body").on('click','#confirm_account',function(){
	
		$.ajax({
			url:"send_password_reset_link",
			type:"POST",
			data:{student_number:sn},
			beforeSend:function(){
				$('body').append('<div class="div-loading"></div>');
			},
			success:function(data){
				if(data==1){
					$("#inner").html("");
					$("#inner").append("<h3>Email Sent!</h3>");
					$("#inner").append("<p>Please check your email. If you are not seeing any mail from us in your inbox, kindly check if it is in your spam and please mark it as not spam. Thank you.</p>");
					$("#inner").append('<a href="/" class="btn btn-dismiss btn-block">Close</a>');
				}else if(data==2){
					$("#inner").html("");
					$("#inner").append("<h3>Oops. Something went wrong.</h3>");
					$("#inner").append("<p>You already made a previous request. Please check your email.</p>");
					$("#inner").append('<a href="/" class="btn btn-dismiss btn-block">Close</a>');
				}else{
					$("#inner").html("");
					$("#inner").append("<h3>Oops. Something went wrong. Please try again.</h3>");
					$("#inner").append('<button class="btn btn-dismiss btn-block">Ok</button>');
				}
				
			},complete:function(data){

				$('.div-loading').remove();
			},error:function(){
				$("#inner").html("");
				$("#inner").append("<h3>Error! Please refresh this page.</h3>");
			}

		});
	});
	$("input[name='student_number']").keypress(function (e) {
		  if (e.which == 13) {
		    searchAccount();
		    return false;    //<---- Add this line
		  }
		});
	});

</script>

<script type="text/javascript">
	$(function(){
		var signUpModel = new SignUpModel();
		ko.applyBindings(signUpModel, document.getElementById('signup-wrapper'));

	});

</script>