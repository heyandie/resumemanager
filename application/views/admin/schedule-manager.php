<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/admin.css">

<style type="text/css">
body{
	overflow-y:auto;
}
.admin-login{
	text-align: center;
}
</style>
<div class="grid-container admin-login">
	<div id="companies-list" class="grid-100 grid-parent">
		<div class="grid-50 prefix-25 suffix-25 tablet-grid-80 tablet-prefix-10 tablet-suffix-10 admin-container">
			
			<div class="login-header">
				<div class="capes-logo"></div>
			</div>
			<h2>Resume Box Admin</h2>
			<div class="admin-links cl-effect-5">
				<a class="active" href="<?php echo site_url('admin/company_list'); ?>"><span data-hover="Company List">Company List</span></a>
				<a href="<?php echo site_url('admin/manage_companies'); ?>"><span data-hover="Manage Companies">Manage Companies</span></a>
				<a href="<?php echo site_url('admin/logout'); ?>"><span data-hover="Logout">Logout</span></a>
			</div>

			<div class="grid-100 tablet-grid-80 tablet-prefix-10 mobile-grid-80 mobile-prefix-10">
				
				<select class="grid-100 tablet-grid-100 mobile-grid-100 yellow-select" data-bind="options: availableSchedule,optionsText:'date',
				optionsCaption:'Choose a day', value:dateToShow">
				</select>
				
		
				<ol class="companylist" data-bind="foreach: companiesToShow">
				    <li class="company-item">
				    	<span data-bind='text: company_name'>
				    </li>
				</ol>
			</div>

		</div>

		
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>js/companies.js"></script>
<script>
	var schedule = <?php echo $schedule; ?>;
	var companies = <?php echo $companies; ?>;


	$(function(){
		var companyListModel = new CompanyListModel();
		ko.applyBindings(companyListModel, document.getElementById('companies-list'));

	});

</script>