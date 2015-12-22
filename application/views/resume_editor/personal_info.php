<div data-bind="fadeVisible: isPersonalInfo()" id="personal-information" 
class="form-container grid-100 grid-parent tablet-grid-100 mobile-grid-100">
	
	<div class="grid-100">
		<h3 class="form-header">1 Personal Information</h3>
	</div>
	<!-- ko with: personal_info -->
	<div class="data-group">
		<div class="grid-100  grid-parent" data-bind="visible:!isEditing()" style="position:relative">
			<b>Name:</b> <span data-bind="text:$root.fullname"></span><br>
			<b>Email:</b> <span data-bind="text:email_address"></span><br>
			<b>Mobile:</b> <span data-bind="text:mobile_number"></span><br>
			<b>Adress:</b> <span data-bind="text:address"></span><br>
			<div class="work-actions-inactive" style="padding-top:0px">
				<button class="work-edit-button" data-bind="enable:!$root.isEditing(),click:edit">Edit</button>
			</div>
		</div>
		<div class="grid-100 grid-parent" data-bind="visible:isEditing()">
			<div class="grid-100 grid-parent" >
			
				
				<div class="form-group grid-50 tablet-grid-50  mobile-grid-100">
					<label class="required">First Name</label>
					<input class="grid-100 tablet-grid-100 mobile-grid-100" name="first_name" type="text" 
					data-bind="value: first_name, valueUpdate: 'input',
					event:{focus: $root.resetInput,blur: $root.validateOnBlur}" maxlength="35"></input>
				</div>

				<div class="form-group grid-50 tablet-grid-50 mobile-grid-100">
					<label class="required">Last Name</label>
					<input class="grid-100 tablet-grid-100 mobile-grid-100" name="last_name" type="text"
					data-bind="value: last_name, valueUpdate: 'input',
					event:{focus: $root.resetInput,blur: $root.validateOnBlur}" maxlength="35"></input>
				</div>
			</div>
				<div class="grid-100 grid-parent">
				<!--div class="form-group mobile-grid-100" >
					<label class="required">Student Number</label>
					<input class="max-width" name="student_number" type="text" 
					data-bind="value: personal_info.student_number,valueUpdate: 'input', 
					event:{focus: resetInput,blur: validateOnBlur}"></input>
				</div-->

				<div class="form-group grid-50 tablet-grid-50  mobile-grid-100">
					<label class="required">Email Address</label>
					<input class="grid-100 tablet-grid-100 mobile-grid-100" name="email_address" type="text" 
					data-bind="value: email_address, valueUpdate: 'input',
					event:{focus: $root.resetInput,blur: $root.validateOnBlur}" maxlength="256"></input>
				</div>

				<div class="form-group  grid-50 tablet-grid-50 mobile-grid-100">
					<label class="required">Mobile Number</label>
					<input class="grid-100 tablet-grid-100 mobile-grid-100" name="mobile_number" type="text" 
					data-bind="value: mobile_number, valueUpdate: 'input',
					event:{focus: $root.resetInput,blur: $root.validateOnBlur}" maxlength="11"></input>
				</div>

				
			</div>
			<div class="grid-100 grid-parent">

				<div class="form-group grid-100">
					<label class="required">Address</label>
					<textarea rows="2" class="grid-100 tablet-grid-100 mobile-grid-100" name="address" 
					data-bind="value: address, valueUpdate: 'input', 
					event:{focus: $root.resetInput,blur: $root.validateOnBlur}" maxlength="512"></textarea>
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

				<div class="grid-100">

					<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-green" data-bind="click:save">Save</button>
					<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-normal" data-bind="click:cancel">Cancel</button>
				</div>
			</div>
			
		</div>

	</div>
	<!--/ko-->
	

	<div class="form-buttons grid-100">	
		<a data-bind="click: showEducation" class="rm-next"></a>
	</div>
	
</div>