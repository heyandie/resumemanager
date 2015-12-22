<div style="display:none" data-bind="fadeVisible: isExtraCurr()" id="extra-curricular" 
class="form-container grid-100 grid-parent tablet-grid-100 mobile-grid-100">
	
	<div class="grid-100">
		<h3 class="form-header">4 Extra Curricular Activities</h3>
	</div>
	<div class="grid-100 grid-parent" data-bind="sortable:affiliations">

		<div class="affiliation-group">
			<div data-bind="visible:isEditing()" class="alert-mask">
				
			</div>
			<div data-bind="visible:!isEditing()" class="grid-100 grid-parent" style="position:relative;">
				<div style="padding-right:10%">
					<b>Organization:</b> <span data-bind="text:affiliation_name"></span><br>
				</div>
				 
				<div class="work-actions-inactive" style="margin-top:-5px">
					<button class="work-edit-button" data-bind="enable:!parent.isEditing(),click:edit">Edit</button>
				</div>
			</div>
			<div data-bind="visible:isEditing()" class="grid-100 grid-parent">
			<div class="form-group grid-100 grid-parent">
				<label>Name of Organization</label>
				<input  type="text" class="grid-100 tablet-grid-100 mobile-grid-100"
				data-bind="value:affiliation_name, valueUpdate: 'input',event: {blur: $root.validateOnBlur,focus: $root.resetInput},
				attr: {name: 'affiliation:'+affiliation_id()+'affiliation_name'}"></input>
			</div>

			

			<div class="grid-100 grid-parent" data-bind="foreach: committees">

				<div class="committee-group" style="position:relative">
					
					<div class="grid-100">
						<a data-bind="visible: $parent.committees().length > 1,click: remove" 
							class="work-delete-button committee-delete-button">Delete Committee</a>
					</div>
					<div class="form-group grid-100">

						<label>Committee Name/Position Held</label>
						<input  class="grid-100 tablet-grid-100 mobile-grid-100"
						data-bind="value:committee_name, valueUpdate: 'input',event: {blur: $root.validateOnBlur,focus: $root.resetInput},
						attr: {name: 'committee_'+committee_id()+':committee_name'}"></input>	
					</div>
					<div class="grid-100 grid-parent">
						<div class="form-group grid-50 mobile-grid-100 tablet-grid-50">
							<div style="display:inline-block; float:left;padding-right: 5px;width:auto;position:relative">
								<div class="grid-100 grid-parent">
									<label>From</label>
								</div>

								<div style="display:inline-block; float:left;padding-right: 5px;">
									<select  data-bind="options: $root.months, optionsCaption: 'Month', 
									event: {blur: $root.validateOnBlur,focus: $root.resetInput},
									attr: {name: 'committee_'+committee_id()+':from_month'},
									value: from_month, valueUpdate: 'input'"></select>
								</div>

								<div style="display:inline-block; float:left;width:auto;">
									<input type="text" placeholder="Year"  style="width:60px"
									data-bind="event: {blur: $root.validateOnBlur,focus: $root.resetInput},
									attr: {name: 'committee_'+committee_id()+':from_year'},
									value: from_year, valueUpdate: 'input'" maxlength="4"></input>
								</div>
							</div>
						</div>

						<div class="form-group grid-50 mobile-grid-100 tablet-grid-50">
							<div style="display:inline-block; float:left;padding-right: 5px;position:relative;width:auto">
								<div class="grid-100 grid-parent">
								<label style="display:inline-block">To</label><input type="checkbox" class="topresent" 
									data-bind="attr:{id:'committee:'+committee_id()+'_topresent'},
									checked:to_present"></input>
								<label class="topresent" data-bind="attr:{for:'committee:'+committee_id()+'_topresent'}">Present</label>
								</div>
								<div style="display:inline-block; float:left;padding-right: 5px;">
									<select  data-bind="options: $root.months, optionsCaption: 'Month', 
									event: {focus: $root.resetInput},
									attr: {name: 'committee_'+committee_id()+':to_month'},
									value: to_month, valueUpdate: 'input',enable:!to_present()"></select>
								</div>

								<div style="display:inline-block; float:left">
									<input   type="text" placeholder="Year" style="width:60px"
									data-bind="event: {focus: $root.resetInput},
									attr: {name: 'committee_'+committee_id()+':to_year'},
									value: to_year, valueUpdate: 'input',enable:!to_present()" maxlength="4"></input>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group grid-100">
							<label>Task Description</label>
							<textarea type="text" class="grid-100 tablet-grid-100 mobile-grid-100"
							  data-bind="value:task_description, valueUpdate: 'input',
							 attr: {name: 'committee_'+committee_id()+':task_description'},
							 event: {blur: $root.validateOnBlur,focus: $root.resetInput}" rows="3"></textarea>	
							 
					</div>
				</div>
			</div>
			<div class="grid-100 grid-parent" style="text-align:right">
				<button data-bind="click: addCommittee" class="btn btn-default">Add Committee</button>
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
				<div data-bind="if:affiliation_id()">
					<button class="grid-33 tablet-grid-33 mobile-grid-33 btn btn-green" data-bind="click:save">Save</button>
					<button class="grid-33 tablet-grid-33 mobile-grid-33 btn btn-warning" data-bind="click:remove">Delete</button>
					<button class="grid-33 tablet-grid-33 mobile-grid-33 btn btn-normal" data-bind="click:cancel">Cancel</button>
				</div>
				<div data-bind="if:!affiliation_id()">
					<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-green" data-bind="click:save">Save</button>
					<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-normal" data-bind="click:cancel">Cancel</button>
				</div>
			</div>
			</div>
			
		</div>
	</div>
	<div class="grid-100 grid-parent">

		<div class="grid-100 grid-parent">
			<button id="add-affiliation-btn" data-bind="click: addAffiliation,enable:!isEditing(),attr:{title:addAffiliationTitle}" class="btn btn-default"
			 data-toggle="tooltip" data-placement="right">Add Affiliation</button>
			 <button id="add-affiliation-btn" data-bind="click: saveOrder,enable:isOrderChanged()" class="btn btn-green"
			 data-toggle="tooltip" data-placement="right">Save Order</button>
			  <button id="add-affiliation-btn" data-bind="click: cancelOrder,visible:isOrderChanged()" class="btn btn-normal"
			 data-toggle="tooltip" data-placement="right">Cancel</button>
		</div>
		<div class="form-buttons grid-100">	
			<a data-bind="click: showWorkExp" class="rm-prev"></a>
			<a data-bind="click: showSkills" class="rm-next"></a>
		</div>
		
	</div>

</div>