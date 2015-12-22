<div style="display:none" data-bind="fadeVisible: isWorkExp" id="workexp" 
class="form-container grid-100 grid-parent tablet-grid-100 mobile-grid-100">
	<div class="grid-100">
		<h3 class="form-header">3 Work Experience / Internships</h3>
	</div>

	<!-- ko foreach: workExp -->
	<div class="work-experience-group">
		<div data-bind="visible:isEditing()" class="alert-mask">

		</div>
		<div data-bind="visible:!isEditing()" class="grid-100 grid-parent" style="position:relative;">
			<b>Company:</b> <span data-bind="text:company_name"></span><br>
			<b>Position:</b> <span data-bind="text:position"></span>
			<div class="work-actions-inactive">
				<button class="work-edit-button" data-bind="enable:!parent.isEditing(),click:edit">Edit</button>
			</div>
		</div>
		<div data-bind="visible:isEditing()" class="grid-100 grid-parent">
		

		<div class="grid-100 grid-parent">
			<div class="form-group grid-50 mobile-grid-50 tablet-grid-50">
				<label>Company Name</label>
				<input data-bind="attr: {name: 'work_'+work_id()+':company_name'},
				value: company_name, valueUpdate: 'input',
				event: {focus: $root.resetInput,blur:$root.validateOnBlur}"
				class="grid-100 tablet-grid-100 mobile-grid-100" type="text"></input>
			</div>


			<div class="form-group grid-50 mobile-grid-50 tablet-grid-50">
				<label>Position Title</label>
				<input  data-bind="attr: {name: 'work_'+work_id()+':position'},
				value: position, valueUpdate: 'input',
				event: {focus: $root.resetInput,blur:$root.validateOnBlur}"
				class="grid-100 tablet-grid-100 mobile-grid-100" type="text"></input>
			</div>

		</div>
		<div class="grid-100 grid-parent">
			<div class="form-group grid-50 mobile-grid-100 tablet-grid-50">
				<div style="display:inline-block; float:left;position:relative;width:auto;padding-right: 5px;">
					<label>From</label>

					<div style="display:inline-block; float:left;padding-right: 5px;">
					<select  data-bind="options: $root.months, optionsCaption: 'Month', 
					event: {focus: $root.resetInput,blur:$root.validateOnBlur},
					attr: {name: 'work_'+work_id()+':from_month'},
					value: from_month, valueUpdate: 'input'"></select>
					</div>

					<div style="display:inline-block; float:left">
					<input type="text" placeholder="Year"  style="width:60px"
					data-bind="event: {blur: $root.validateOnBlur,focus: $root.resetInput},
					attr: {name: 'work_'+work_id()+':from_year'},
					value: from_year, valueUpdate: 'input'" maxlength="4"></input>
					</div>
				</div>
			</div>

			<div class="form-group grid-50 mobile-grid-100 tablet-grid-50">
				<div style="display:inline-block; float:left;position:relative;width:auto;padding-right: 5px;">
					<label>To</label>
					<div style="display:inline-block; float:left;padding-right: 5px;">
					<select  data-bind="options: $root.months, optionsCaption: 'Month', 
					event: {focus: $root.resetInput,blur:$root.validateOnBlur},
					attr: {name: 'work_'+work_id()+':to_month'},
					value: to_month, valueUpdate: 'input'"></select>
					</div>

					<div style="display:inline-block; float:left">
					<input  type="text" placeholder="Year" style="width:60px"
					data-bind="event: {focus: $root.resetInput,blur:$root.validateOnBlur},
					attr: {name: 'work_'+work_id()+':to_year'},
					value: to_year, valueUpdate: 'input'" maxlength="4"></input>
					</div>	
				</div>
			
			</div>

		</div>


		<div class="grid-100 grid-parent">
			<div class="form-group grid-100">
				<label>Location</label>
				<input  data-bind="attr: {name: 'work_'+work_id()+':company_location'},
				value: company_location, valueUpdate: 'input',
				event: {focus: $root.resetInput,blur:$root.validateOnBlur}"
				class="grid-100 tablet-grid-100 mobile-grid-100" type="text" placeholder="City/Province"></input>
			</div>

			<div class="form-group grid-100">
				<label>Work Description</label>
				<!-- ko foreach: work_descriptions -->
				<div style="position:relative">
					<textarea  rows="3"data-bind="attr: {name: 'work_description_'+work_description_id()+':work_description'},
					value: work_description, valueUpdate: 'input',event: {focus: $root.resetInput}"
					class="grid-100 tablet-grid-100 mobile-grid-100 work-description" type="text"></textarea>
					<a data-bind="event:{click:remove}" class="delete-beside-input fa fa-remove" data-toggle="tooltip" data-placement="left" title="Delete"></a>
				</div>
				<!-- /ko -->
				<button data-bind="click:addWorkDescription" class="btn btn-small">Add Work Description</button>
			</div>
			
		</div>
		
		<div class="grid-100 grid-parent work-actions">
			<div class="alert-info alert-changes grid-100">
				<div class="info-icon">
						<i class="fa fa-info"></i>
					</div>
				Your changes has not been saved.<br>Please choose an action.
			</div>
			<div class="alert-error alert-info grid-100">
				<div class="info-icon">
						<i class="fa fa-times-circle"></i>
					</div>
				<span>Error on saving your data. Please try again.</span>
			</div>
			<div data-bind="if:work_id()">
				<button class="grid-33 tablet-grid-33 mobile-grid-33 btn btn-green" data-bind="click:save">Save</button>
				<button class="grid-33 tablet-grid-33 mobile-grid-33 btn btn-warning" data-bind="click:remove">Delete</button>
				<button class="grid-33 tablet-grid-33 mobile-grid-33 btn btn-normal" data-bind="click:cancel">Cancel</button>
			</div>
			<div data-bind="if:!work_id()">
				<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-green" data-bind="click:save">Save</button>
				<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-normal" data-bind="click:cancel">Cancel</button>
			</div>
			
		</div>
		</div>
	</div>
	<!-- /ko -->
	<div class="grid-100">
		<button data-bind="enable:!isEditing(),click: addWorkExp" class="btn btn-default" style="text:align:left;margin-top:10px">Add Work</button>
	</div>
	<div class="form-buttons grid-100">	
		<a data-bind="click: showEducation" class="rm-prev"></a>
		<a data-bind="click: showExtraCurr" class="rm-next"></a>
	</div>
	
</div>