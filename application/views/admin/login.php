<style type="text/css">
body{
	overflow-y:auto;
}
</style>
<div class="grid-container admin-login">
	<div class="grid-100">
		<div class="grid-40 prefix-30 suffix-30 tablet-grid-60 tablet-prefix-20 tablet-suffix-20  mobile-grid-70 mobile-prefix-15 tablet-suffix-15 login-form-container">
			
			<div class="login-header">
				<div class="capes-logo"></div>
			</div>
			<h2>Resume Box Admin</h2>
			<div class="grid-70 prefix-15 suffix-15 tablet-grid-70 tablet-prefix-15 tablet-suffix-15">
				<form method="post" action="<?php echo site_url('admin/login');?>">
					<div class="form-group">
						<label>Username</label>
						<input name="username" placeholder="Enter your username" class="grid-100 tablet-grid-100 mobile-grid-100"></input>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input name="password" type="password" placeholder="Enter your password" class="grid-100 tablet-grid-100 mobile-grid-100"></input>
					</div>
					<button class="login">Log In</button>
				</form>
			</div>
		</div>
		
	</div>
</div>