<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/resume-editor-1.0.1.css">
<style type="text/css">
body{
  overflow:hidden;

}
html{
	height: auto;
}

</style>

<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,400italic' rel='stylesheet' type='text/css'>

<div id="resume-manager" class="grid-container">
	
		
	<div id="grid-editor" class="grid-40 tablet-grid-40 grid-parent">
		
		<div id="edit-grid-header" class="form-title grid-100 tablet-grid-100 mobile-grid-100" style="margin-bottom:0px;">
			<div class="grid-100 grid-parent menu-container">
				<div class="menu-bar grid-100 grid-parent">
					<div class="brand">
						<a style="display:inline-block;" href="<?php echo site_url();?>"><div class="capes-logo"></div></a>
					</div>
					
					<div class="brand"><a href="<?php echo site_url('');?>">Resume Manager</a></div>
					<div class="link-pull-right cl-effect-5 menu-links">
					<ul>

						<li>
							<a>
								<span data-hover="Menu">Menu</span>
								&nbsp;<i class="fa fa-caret-down"></i>
							</a>
							<ul class="dropdown">
								<li><a href="<?php echo site_url('companies_checklist') ?>">Companies Checklist</a></li>
								<li><a href="<?php echo site_url('home/logout') ?>">Logout</a></li>
							</ul>
						</li>
					</ul>
					</div>
					
				</div>
			</div>
			<h3 style="color:#444;font-size:28px;padding-top:5px;">Resume Editor</h3>

			<div class="grid-100 grid-parent rm-nav-wrapper">
	
				<div class="rm-nav-icon-wrapper" data-bind="click: showPersonalInfo, css: {active: isPersonalInfo()}">
					<div class="rm-nav-icon">
						<span class="tiny-num">1</span> Personal
					</div>
				</div>
				<div class="rm-nav-icon-wrapper" data-bind="click: showEducation, css: {active: isEducation()}">
					<div class="rm-nav-icon">
						<span class="tiny-num">2</span> Education
					</div>
				</div>
				<div class="rm-nav-icon-wrapper" data-bind="click: showWorkExp, css: {active: isWorkExp()}">
					<div class="rm-nav-icon">
						<span class="tiny-num">3</span> Work
					</div>
				</div>
				<div class="rm-nav-icon-wrapper" data-bind="click: showExtraCurr, css: {active: isExtraCurr()}">
					<div class="rm-nav-icon">
						<span class="tiny-num">4</span> Affiliation
					</div>
				</div>
				<div class="rm-nav-icon-wrapper" data-bind="click: showSkills, css: {active: isSkills()}">
					<div class="rm-nav-icon">
						<span class="tiny-num">5</span> Skills
					</div>
				</div>
				<div class="rm-nav-icon-wrapper" data-bind="click: showSubmit, css: {active: isSubmit()}">
					<div class="rm-nav-icon">
						<span class="tiny-num">6</span> Submit
					</div>
				</div>
			</div>
		</div>
		<?php $this->load->view('resume_editor/personal_info'); ?>
		<?php $this->load->view('resume_editor/education'); ?>
		<?php $this->load->view('resume_editor/work'); ?>
		<?php $this->load->view('resume_editor/affiliation'); ?>
		<?php $this->load->view('resume_editor/skills'); ?>
		<?php $this->load->view('resume_editor/submit'); ?>
		<footer class="grid-100">
			<div class="footer">UP CAPES Resume Box © 2015</div>
		</footer>
		<div id="submit-success" style="display:none" class="div-mask">
			<div class="div-mask-popout">
				<div style="display:table;margin:10px 0px">
				<span style="display:table-cell;vertical-align:middle">
					<i class="success-check-default fa fa-check"></i></span>
					<h3 style="display:table-cell;vertical-align:middle">Success!</h3>
				</div>
				<p>Thank you for submitting your resume.<br><br>You will receive a 
					confirmation mail at <b data-bind="text:personal_info.email_address"></b> including an attached copy of your resume. If there is no any mail from us, please check it in the spam and kindly mark it as not spam.</p>
				<p style="text-align:center"><button style="width:50px"class="btn-dismiss btn">Ok</button></p>
				<a class="popup-dismiss btn-dismiss fa fa-remove"></a>
			</div>

		</div>
		<div id="submit-error" style="display:none" class="div-mask">
			<div class="div-mask-popout">
				<div style="display:table;margin:10px 0px">
					<h3 style="display:table-cell;vertical-align:middle">Error!</h3>
				</div>
				<p>There was an error while submitting your resume. Please try again.</p>
				<p style="text-align:center"><button style="width:50px"class="btn-dismiss btn">Ok</button></p>
				<a class="popup-dismiss btn-dismiss fa fa-remove"></a>
			</div>

		</div>
	</div>

	<div id="grid-preview" class="grid-60 tablet-grid-60 grid-parent">
		<div class="color-scheme-palette" data-bind="foreach:color_palette">
			<li><input type="radio" name="color" data-bind="attr:{id:'scheme-'+color(),checked:selected}"></input>
				<label data-bind="click:setColorScheme,attr:{class:color(),for:'scheme-'+color()}"></label></li>
		</div>
		<?php $this->load->view('resume_editor/preview'); ?>
	</div>
</div>


<!--end of loading stylesheets-->



<script>
	var student_number="<?php echo $student_number; ?>";
	var personal_info=<?php echo $personal_info; ?>;
	var student_type=<?php echo $student_type; ?>;
	var education_graduate=<?php echo $education_graduate; ?>;
	var education_undergraduate=<?php echo $education_undergraduate; ?>;
	var education_highschool=<?php echo $education_highschool; ?>;
	var graduate_recognitions=<?php echo $graduate_recognitions; ?>;
	var undergraduate_recognitions=<?php echo $undergraduate_recognitions; ?>;
	var highschool_recognitions=<?php echo $highschool_recognitions; ?>;
	var work_experience=<?php echo $work_experience; ?>;
	var affiliations=<?php echo $affiliations; ?>;
	var skills="<?php echo $skills; ?>";
</script>
