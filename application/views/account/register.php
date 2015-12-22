<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/signup.latest.css">

<div class="grid-container menu-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5 menu-bar grid-parent">
		<div class="brand">
			<a style="display:inline-block;" href="<?php echo site_url();?>"><div class="capes-logo"></div></a>
		</div>
		
		<div class="brand"><a href="<?php echo site_url();?>">Resume Manager</a></div>
		<div class="link-pull-right cl-effect-5">
				
				<a href="<?php echo site_url('login');?>"><span data-hover="Log In">Log In</span></a>
			</div>
	</div>

</div>
<div id="signup-wrapper" class="grid-container default-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5 grid-parent">
		<div class="grid-45 suffix-5 tablet-grid-40 tablet-suffix-10 grid-parent benefits" style="margin-bottom:60px">

			<h1 style="">What are the benefits of having your resume with <span style="color:#ffcc1d">UP CAPES</span>?</h1>
			<div class="grid-100 grid-parent benefit">
				<i class="fa fa-check"></i>Hassle-free way of submitting your resume to different companies
			</div>
			<div class="grid-100 grid-parent benefit">
				<i class="fa fa-check"></i>Makes your resume instantly accessible to your dream companies
			</div>
			<div class="grid-100 grid-parent benefit">
				<i class="fa fa-check"></i>An avenue to help you achieve your career choice
			</div>
			
			<table style="position:relative;margin-top:25px;border-top:1px dotted #fff;border-bottom:1px dotted #fff;padding:10px 0px;vertical-align:top">
			<tr>
			<td>
				<img src="<?php echo base_url();?>images/chb.jpg" width="65px"></img>
			</td>
			<td ><h2 style="display:table-row;font-size:18px;color:#ffcc1d;width:auto;margin-top:0px">Know the first steps on finding a job.</h2>
				<h2 style="font-size:22px;width:auto;margin-bottom:5px;">
				Get your Career Handbook 2015</h2>
			
				<i class="fa fa-check"></i>Learn about job search ethics, bureaucratic procedures, evaluating job offers and more!
				<a style="font-weight:bold;color:#ffcc1d" target="_blank" href="<?php echo base_url();?>chb.pdf">Get it here.</a>
			</td>
			<td>
			</td>
			</tr>
			</table>
			<table style="width:100%;position:relative;margin-top:15px;padding:10px 0px;vertical-align:top">
			<tr>
			<td ><h2 style="display:table-row;font-size:18px;color:#ffcc1d;width:auto;margin-top:0px">Bug Fixes</h2>
			
				<div class="new-feature"><i class="fa fa-check"></i> Affiliation name appearing more than once for affiliations having more than one committee/position.<br/></div>
			</td>
			</tr>
			</table>
			<table style="width:100%;position:relative;margin-top:15px;padding:10px 0px;vertical-align:top">
			<tr>
			<td ><h2 style="display:table-row;font-size:18px;color:#ffcc1d;width:auto;margin-top:0px">Newest Features</h2>
				<div class="new-feature"><i class="fa fa-check"></i> Different color schemes available<br/></div>
				<div class="new-feature"><i class="fa fa-check"></i> Sortable affiliations by drag-and-drop<br/></div>
				<div class="new-feature"><i class="fa fa-check"></i> To present as an option for end of a duration<br/></div>
			</td>
			</tr>
			</table>
			
			

		</div>
		<div class="grid-30 prefix-20 tablet-grid-50 grid-parent">
			<div class="form-group grid-100 grid-parent">
			
				<div class="grid-100 grid-parent">
					<h2>Sign Up</h2>
				</div>
				<form id="signupform"method="POST" action="<?php echo site_url('register/submit') ?>">
				<div class="grid-100 grid-parent form-group">
				

					<input name="first_name" placeholder="First Name" class="grid-100 tablet-grid-100 mobile-grid-100
					<?php if ( $this->session->flashdata('first_name')!=null ) { echo ' danger'; };?>"
					data-bind="value:personal_info.first_name,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"
					data-value="<?php if ( $this->session->flashdata('first_name_val')!=null ) 
					{ echo $this->session->flashdata('first_name_val'); };?>" maxlength="35"></input>
					<?php

					    if ( $this->session->flashdata('first_name')!=null ) {
					        echo $this->session->flashdata('first_name');
					    };
					?>
					
					<input name="last_name" placeholder="Last Name"class="grid-100 tablet-grid-100 mobile-grid-100
					<?php if ( $this->session->flashdata('last_name')!=null ) { echo ' danger'; };?>"
					data-bind="value:personal_info.last_name,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"
					data-value="<?php if ( $this->session->flashdata('last_name_val')!=null ) 
					{ echo $this->session->flashdata('last_name_val'); };?>" maxlength="35"></input>
					<?php 
						if ( $this->session->flashdata('last_name')!=null ) {
					        echo $this->session->flashdata('last_name');
					    };
					?>

					<input name="student_number" placeholder="Student Number (Ex. 20XX12345)"class="grid-100 tablet-grid-100 mobile-grid-100
					<?php if ( $this->session->flashdata('student_number')!=null ) { echo ' danger'; };?>"
					data-bind="value:personal_info.student_number,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"
					data-value="<?php if ( $this->session->flashdata('student_number_val')!=null ) 
					{ echo $this->session->flashdata('student_number_val'); };?>" maxlength="9"></input>
					<?php 
						if ( $this->session->flashdata('student_number')!=null ) {
					        echo $this->session->flashdata('student_number');
					    };
					?>


					<input name="email_address" placeholder="Email"class="grid-100 tablet-grid-100 mobile-grid-100
					<?php if ( $this->session->flashdata('email_address')!=null ) { echo ' danger'; };?>"
					data-bind="value:personal_info.email_address,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"
					data-value="<?php if ( $this->session->flashdata('email_address_val')!=null ) 
					{ echo $this->session->flashdata('email_address_val'); };?>" maxlength="256"></input>
					<?php 
						if ( $this->session->flashdata('email_address')!=null ) {
					        echo $this->session->flashdata('email_address');
					    };
					?>

					<input name="password" placeholder="Password (Must be at least 6 characters)" class="grid-100 tablet-grid-100 mobile-grid-100" type="password"
					data-bind="value:personal_info.password,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"></input>

					<input name="confirm_password" placeholder="Re-type Password" class="grid-100 tablet-grid-100 mobile-grid-100" type="password"
					data-bind="value:personal_info.confirm_password,valueUpdate:'input',event:{focus: resetInput,blur: validateOnBlur}"></input>

					<br><br><span class="checkbox"style="font-size:12px"><input type="checkbox" data-bind="checked: isTermsAgreed"></input> I allow UP CAPES to include my resume in the Resume Book 2015*, which is a compilation of resumes offered to companies that are affiliated with the organization.</span>
					</br>
					<span style="font-size:11px">* The Resume Book is a project of the UP CAPES to collect and compile the resume of all undergraduate 
						and graduate students of the College who agree to have their resume published and offered to companies that are affiliated 
						with the organization.
				
					</span>
				</div>
				
				<div class="grid-100 grid-parent form-group" style="padding-top:0px;padding-bottom:10px">
					<button type="submit" class="btn btn-block" data-bind="enable:isPersonalInfoValid(),click:submitSignUp">Sign Up</button>
				</div>
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