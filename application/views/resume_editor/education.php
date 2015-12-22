<div style="display:none" data-bind="fadeVisible: isEducation" id="education" 
class="form-container 100 grid-parent">

	<div class="grid-100">
		<h3 class="form-header">2 Educational Background</h3>
	</div>

	<div class="grid-100 grid-parent">
		<div class="form-group grid-parent grid-100">
			<label class="required">Student Type</label>
			<select name="student_type" style="width: 100%" data-bind="options: availableStudentType, value: student_type,event: {change: validateOnBlur,focus: $root.resetInput}">
			</select>
			
		</div>
	</div>



	<!--ko foreach: education -->
	<div class="data-group education-group">
		<div data-bind="visible:isEditing()" class="alert-mask">

		</div>
		<div data-bind="visible:!isEditing()" class="grid-100 grid-parent" style="position:relative;">
			<b style="text-transform:capitalize"data-bind="text:type"></b>: <span data-bind="text:school_name"></span><br>
			<div class="work-actions-inactive">
				<button class="work-edit-button" data-bind="enable:!parent.isEditing(),click:edit">Edit</button>
			</div>
		</div>
		<div data-bind="visible:isEditing()" class="grid-100 grid-parent">
			<div class="grid-100 subheader">
				
				<div><i class="fa fa-graduation-cap fa-3x"></i><br><span style="text-transform:capitalize"data-bind="text:type"></span></div>
			</div>
			<div class="form-group grid-50 suffix-50 tablet-grid-100 mobile-grid-100" style="position:relative;width:auto;padding-right: 10px;">
		
			
				<label class="required">Graduation Date</label>
		
				<div style="display:inline-block; float:left; width:auto;padding-right:5px">
				<select data-bind=" options: $root.months, optionsCaption: 'Month',
				value: graduation_month,
				attr: {name: type()+':graduation_month'},
				event: {focus: $root.resetInput,blur:$root.validateOnBlur}">
				</select></div>

				<div style="display:inline-block;width:60px;float:left;">
				<input style="width: 60px" placeholder="Year" type="text" data-bind="value: graduation_year, 
				valueUpdate:'input',event: {focus: $root.resetInput,blur:$root.validateOnBlur},
				attr: {name: type()+':graduation_year'}"></input>
				</div>
			</div>
			
			<div data-bind="if: showSchool" class="grid-100 form-group">
				<div class=" grid-100 grid-parent">
					
					<div class="grid-100 grid-parent" style="position:relative">
					<label class="required">School Name</label>
					<input class="grid-100 tablet-grid-100 mobile-grid-100"data-bind="valueUpdate: 'input',
					 event: {blur: $root.validateOnBlur, 
					focus: $root.resetInput},
					value: school_name,attr: {name: type()+':school_name'}"></input>
					</div>
				</div>
			</div>
			<!-- ko if: type()!='highschool'&&!$root.isUndergraduate() -->
			<div class="form-group grid-100 tablet-grid-100 mobile-grid-100">
				<div class="grid-100 grid-parent" style="position:relative">
					<label class="required">Degree Program</label>
					<input class="grid-100 tablet-grid-100 mobile-grid-100" data-bind="value: degree_program, 
					valueUpdate:'input',event: {blur: $root.validateOnBlur,focus: $root.resetInput},
					if: type=='undergraduate',attr: {name: type()+':degree_program'}" ></input>
				</div>
			</div>
			<!-- /ko -->
			<!-- ko if:$root.isUndergraduate()&&type()!='highschool'-->
			<div class="form-group grid-100" style="position:relative">
				<div class="grid-100 grid-parent" style="position:relative">
					<label class="required">Degree Program</label>
					<select data-bind="options: $root.availableUndergraduateCourses,
					optionsCaption: 'Please choose one...', value: degree_program,
					event: {change: $root.validateOnBlur,focus: $root.resetInput},
					attr: {name: type()+':degree_program'}"
					class="grid-100 tablet-grid-100 mobile-grid-100">
					</select>
				</div>
			</div>
			<!-- /ko -->
			<div class="form-group grid-100">
				<label>Recognitions/Awards Received</label>
				<div data-bind="foreach: recognitions">
					<div class="recognition grid-100 grid-parent">
					<input data-bind="value:award, valueUpdate: 'input'" 
					data-notrequired class="grid-100 tablet-grid-100 mobile-grid-100"></input>
					<a data-bind="event:{click:remove}" class="delete-beside-input fa fa-remove" data-toggle="tooltip" data-placement="left" title="Delete"></a>
					</div>
				</div>
				<button data-bind="click: addRecognition, enable: allowAddRecognition()" class="btn btn-default">Add</button>
			</div>
			<div class="grid-100 work-actions">
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
			
				<div>
					<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-green" data-bind="click:save">Save</button>
					<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-normal" data-bind="click:cancel">Cancel</button>
				</div>
				
			</div>
		</div>
	</div>
	<!--/ko-->


	<div class="form-buttons grid-100">	
		<a data-bind="click: showPersonalInfo" class="rm-prev"></a>
		<a data-bind="click: showWorkExp" class="rm-next"></a>
	</div>
	
</div>