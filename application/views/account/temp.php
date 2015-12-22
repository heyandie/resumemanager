<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/signup-1-20-15-3-25.min.css">
<style type="text/css">

html{
	height: 100%;
}
body{
	height: 100%;
	position: relative;
}

.login-container{
	background: #fff;
	padding:20px 15px;
	margin: 0 auto;
	position: relative;
	width: 280px;
	height: auto;
}
input{
	background: transparent;
	border: 1px solid #ccc;
}
h1{
	padding-top: 100px;
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
		
	</div>

</div>
<div id="signup-wrapper" class="grid-container default-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">
	<h1>Sorry but we are temporarily down. Please check again later.</h1>
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