<div class="grid-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5 menu-bar">
		<div class="brand">
			<a style="display:inline-block;" href="<?php echo base_url('assets/css/uikit.min.css');?>" <div class="capes-logo"></div></a>
		</div>
		
		<div class="brand"><a href="<?php echo site_url();?>">Resume Box</a></div>

		<div class="link-pull-right cl-effect-5 menu-links">
			<ul>
			<li><a href="<?php echo site_url('resume_editor') ?>"><span data-hover="Resume Editor">Resume Editor</span></a></li>
			<li>
				<a>
					<span data-hover="<?php echo $this->session->userdata('first_name'); ?>"><?php echo $this->session->userdata('first_name'); ?></span>
					&nbsp;<i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown">
					<li><a>Settings</a></li>
					<li><a href="<?php echo site_url('home/logout') ?>">Logout</a></li>
				</ul>
			</li>
			</ul>
		</div>
	</div>

</div>
<div id="company-checklist" class="grid-container default-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">

		<div class="grid-50 grid-parent">
			
			<h2>Company Checklist</h2>
			<div class="grid-100 grid-parent">
				<p class="notebox">
					Hi, <b><?php echo $this->session->userdata('name'); ?></b>. Listed below are the participating companies in UP CAPES' Job Fair 2015.
					There will be different sets of companies each day for the whole duration the event.
					If you wish to send your resume to a participating company, please use the checkbox and mark it checked.
				</p>

				<select class="grid-100 mobile-grid-100 tablet-grid-100 yellow-select" data-bind="options: availableSchedule,optionsText:'date',
				optionsCaption:'Choose a day', value:dateToShow">
				</select>

				<ul class="companychecklist" data-bind="foreach: companiesToShow">
				    <li class="company-item">
				    	<span class="checkbox" style="font-size:12px"><input type="checkbox" data-bind="checked: selected, attr: {id: company_name}"/>
				    	<span data-bind='text: company_name'>
				    	</span>
				    </li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>js/companychecklist.js"></script>
<script>
	var schedule = <?php echo $schedule; ?>;
	var companies = <?php echo $companies; ?>;
	$(function(){
		var companyChecklistModel = new CompanyChecklistModel();
		ko.applyBindings(companyChecklistModel, document.getElementById('company-checklist'));

	});

</script>