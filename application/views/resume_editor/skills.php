<div style="display:none" data-bind="fadeVisible: isSkills" id="skills" 
class="form-container grid-100 grid-parent tablet-grid-100 mobile-grid-100">
	<div class="grid-100">
		<h3 class="form-header">5 Skills</h3>
	</div>
	<!-- ko with:skills-->
	<div class="data-group skills-group">
		<div data-bind="visible:!isEditing()" class="grid-100 grid-parent" style="position:relative">
			<b>Skills</b><br/><br/>
			<span data-bind="html: text_skills()!=''?text_skills : 'None'"></span>
			<div class="work-actions-inactive" style="margin-top:-3px">
				<button class="work-edit-button" data-bind="enable:!parent.isEditing(),click:edit">Edit</button>
			</div>
		</div>
		
		<div data-bind="visible:isEditing()" class="grid-100 grid-parent">
			<label>Skills<span style="color:#777"> (Tip: You can use enter to create mutliple lines.)</span></label>

			<textarea data-bind="value:skills,valueUpdate:'input',click:$root.resetInput" rows="10" class="grid-100 tablet-grid-100 mobile-grid-100" placeholder="Type your skills here"></textarea>
			<div>
				<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-green" data-bind="click:save">Save</button>
				<button class="grid-50 tablet-grid-50 mobile-grid-50 btn btn-normal" data-bind="click:cancel">Cancel</button>
			</div>
		</div>
		
	</div>
	<!-- /ko-->
	<div class="form-buttons grid-100">	
		<a data-bind="click: showExtraCurr" class="rm-prev"></a>
		<a data-bind="click: showSubmit" class="rm-next"></a>
	</div>
	
</div>
