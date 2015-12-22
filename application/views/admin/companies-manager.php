<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/admin.css">
<style type="text/css">
body{
	overflow-y:auto;
}
.admin-login{
	text-align: center;
}
</style>
<div id="mask"></div>
<div class="grid-container">
	<div id="companies-manager" class="grid-100 grid-parent">
		<div class="grid-80 prefix-10 suffix-10 tablet-grid-100 admin-container">
			
			<div class="login-header">
				<div class="capes-logo"></div>
			</div>
			<h2>Resume Box Admin</h2>
			<div class="admin-links cl-effect-5">
				<a href="<?php echo site_url('admin/company_list'); ?>"><span data-hover="Company List">Company List</span></a>
				<a class="active" href="<?php echo site_url('admin/manage_companies'); ?>"><span data-hover="Manage Companies">Manage Companies</span></a>
				<a href="<?php echo site_url('admin/download_all_resumes'); ?>"><span data-hover="Download All Resumes">Download All Resumes</span></a>
				<a href="<?php echo site_url('admin/logout'); ?>"><span data-hover="Logout">Logout</span></a>
			</div>

			<div class="grid-100 tablet-grid-90 tablet-prefix-5 mobile-grid-100">
				<div style="text-align:left;padding:10px 0px;">
					<b>Total No. of Resumes:</b><?php $total=$stat['employment']+$stat['internship'];echo $total;?><br>
					<b>Resumes for Employment:</b><?php $total=$stat['employment'];echo $total;?><br>
					<b>Resumes for Internship:</b><?php $total=$stat['internship'];echo $total;?>
				</div>
				<b data-bind="visible:availableCompanies().length==0">No companies available.</b>
				<table data-bind="visible:availableCompanies().length!=0">
					<thead>
						<tr>
							<td>Company Name</td>
							<!-- ko foreach: availableSchedule -->
							<td data-bind="text:date"></td>
							<!-- /ko -->
							<td>Actions</td>
						</tr>
					</thead>
					<tbody>
						<!-- ko foreach: availableCompanies -->
						<tr data-bind="attr:{name:'row:'+company()}">
							<td><div style="position:relative">
								<b class="number"style="color:#ffcc1d"data-bind="text:$index()+1+'.'"></b> <input data-bind="value: company,valueUpdate:'input',
								event:{click:editName,blur:onBlur},css:{'disabled':!enableEdit()}"></input>
								</div>
							</td>
							<!-- ko foreach: schedule -->
							<td><span class="checkbox"style="font-size:12px"><input type="checkbox" data-bind="checked:selected,click:$parent.checkChanges"></input>

							</td>

							<!-- /ko -->
							<td class="action-buttons">
								<button class="save"data-bind="click:save,enable:enableSave()">Save</button>
								<a data-bind="attr:{href:'download_resumes/'+company_id()}"class="save" style="background:#ffcc1d;color:#111" data-toggle="tooltip" data-placement="top" title="Download Resumes"><i class="fa fa-download"></i></a>
								<a href="#company-stat-popup" data-bind="click:showCompanyStat"class="save" style="background:#ffcc1d;color:#111" data-toggle="tooltip" data-placement="top" title="Count Resumes"><i class="fa fa-search"></i></a>
								<!--<button class="delete" data-bind="click: remove">Delete</button>--></td>
						</tr>
						<!-- /ko -->
					</tbody>
				</table>
				
				<div class="grid-100 grid-parent" style="text-align:center">
					<a href="#new-company-popup" data-bind="click:showAddCompany"id="new-company"class="btn btn-transparent"
					>New Company</a>
				</div>
			</div>
		</div>
		<div id="new-company-popup" class="popup-container">
			<a class="fa fa-remove popup-dismiss data-dismiss" data-bind="click:closeAddCompany"></a>
			<div class="grid-container">
				<div class="grid-100">
					<h2>Add Company</h2>
					<div style="text-align:left">

						<input name="company_to_add" placeholder="Name of Company" data-bind="value:companyToAdd,valueUpdate:'input'
						,click:$root.resetInput"></input>
						<div class="dates-selection" data-bind="foreach: availableSchedule" style="margin:10px 0;">
							<div>
								<span class="checkbox"style="font-size:14px">
									<input type="checkbox" data-bind="checkedValue: $data.sqldate(), checked: $root.chosenDates"></input>
														<span data-bind="text:date"></span></span>
							</div>
						</div>
						<div style="text-align:center;padding:10px 0px">
							<a data-bind="click:addCompany" class="btn">Add</a>
						</div>
					</div>
				</div>
			</div>
			
		</div>

		<div id="company-stat-popup" class="popup-container">
			<a class="fa fa-remove popup-dismiss data-dismiss" data-bind="click:closeCompanyStat"></a>
			<div class="grid-container">
				<div class="grid-100">
					<h2>Dropbox Stats</h2>
					<b>Company: </b><span data-bind="text:viewed_company"></span><br/>
					<b>Employment: </b><span data-bind="text:employment_count"></span><br/>
					<b>Internship: </b><span data-bind="text:internship_count"></span><br/>
				</div>
			</div>
			

		</div>

	

		
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>js/companies.js"></script>
<script>
	var schedule = <?php echo $schedule; ?>;
	var companies = <?php echo $companies; ?>;
	console.dir(companies);
	$(function(){
		var companiesManagerModel = new CompaniesManagerModel();
		ko.applyBindings(companiesManagerModel, document.getElementById('companies-manager'));
		$('a').tooltip();
	});

</script>