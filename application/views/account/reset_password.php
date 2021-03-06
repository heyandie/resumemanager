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
		
		<div class="login-container" style="margin-top:100px">
				
				<h2 style="text-align:center">Reset your password</h2>
		
				
						
						<div id="inner">
							<form id="form-reset"method="POST" data-bind="enable:isPasswordMatch()" action="confirm_reset_password">
							<div class="form-inputs">
							<div>
							<input type="hidden" name="request_id" value="<?php echo $key;?>"></input>
							<input class="grid-100 tablet-grid-100 mobile-grid-100 credentials" name="student_number" 
							placeholder="Student Number (Ex. 2014XXXXX)" data-bind="event:{focus: resetInput,blur: validateOnBlur}"></input>
							<input name="password" placeholder="Password (Must be at least 6 characters)" class="grid-100 tablet-grid-100 mobile-grid-100" type="password"
					data-bind="value:personal_info.password,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"></input>

					<input name="confirm_password" placeholder="Re-type Password" class="grid-100 tablet-grid-100 mobile-grid-100" type="password"
					data-bind="value:personal_info.confirm_password,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"></input>
							
							</div>
							<button type="submit"data-bind="enable:isPasswordMatch()" class="btn btn-block">Reset Password</button>
							</form>
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
		var signUpModel = new SignUpModel();
		ko.applyBindings(signUpModel, document.getElementById('signup-wrapper'));

	});

</script>