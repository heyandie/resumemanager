<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/signup.css">
<style type="text/css">

html{
	height: 100%;
}
body{
	height: 100%;
	position: relative;
	overflow-x:hidden;
}

.login-container{
	background: #fff;
	padding:20px 15px;
	margin: 0 auto;
	position: relative;
	width: 300px;
	height: auto;
}
input{
	background: #fff;
	border: 1px solid #ccc;
	outline: 0px;
}
h1{
	color: #111;
}
footer{
	position: fixed;
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
		
		<div class="login-container" style="margin-top:80px">
		
				<h1 style="text-align:center">Log In</h1>
				<form id="login-form">
					<div class="form-inputs">
						<div>
							<?php if($this->session->flashdata('success')):?>
						<div class="dismissable-alert-success">
							<?php echo $this->session->flashdata('success');?></div>
							<?php endif?>
						<input class="grid-100 tablet-grid-100 mobile-grid-100 credentials" name="student_number_login" 
						placeholder="Student Number (Ex. 2014XXXXX)" data-bind="value: login_details.student_number, 
						valueUpdate: 'input', event:{focus: resetInput,blur: validateOnBlur}"
						data-value="<?php if ( $this->session->flashdata('student_number')!=null ) 
					{ echo $this->session->flashdata('student_number'); };?>" maxlength="9"></input>
						
						<div>
						<input class="grid-100 tablet-grid-100 mobile-grid-100 credentials" name="password_login" 
						placeholder="Password" type="password" data-bind="value: login_details.password, valueUpdate: 'input'
						,event:{focus: resetInput,blur: validateOnBlur}"></input>
						</div>
					</div>
					<button data-bind="click:login, enable:isReadyForLogIn()" class="btn btn-block">Log In</button>
				</form>
				<a href="login/forgot_password"style="color:#00729b;cursor:pointer;font-size:12px;text-decoration:none"><span>Forgot your password?</span></a>
		</div>
		
		

	</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>js/signup.js"></script>


<script type="text/javascript">
	$(function(){
		var signUpModel = new SignUpModel();
		ko.applyBindings(signUpModel, document.getElementById('signup-wrapper'));

	});

</script>