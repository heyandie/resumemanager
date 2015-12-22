<div data-bind="visible: isPreview()" id="resume-preview" 
	class="grid-100 grid-parent tablet-grid-100 mobile-grid-100">
	<!--div class="a4-buttons-wrapper">
		<div class="a4-buttons">
			<a class="btn btn-primary" id="savePDF">Save as PDF</a>			
			<a class="btn btn-primary" id="savePDF">Submit</a>
		</div>
	</div-->

	<div id="resume-page-1" class="a4-wrapper">
		<div class="a4 grid-container">
			<div class="personal-info-preview">

				<div><span class="fullname" data-bind="visible:personal_info.first_name()!='',text: personal_info.first_name"></span>
				<span class="fullname placeholder-color" data-bind="if:personal_info.first_name()===''">First Name</span> 
				<span class="fullname" data-bind="visible:personal_info.last_name()!='',text: personal_info.last_name"></span>
				<span class="fullname placeholder-color" data-bind="if:personal_info.last_name()===''">Last Name</span> </div>
				<div data-bind="if:personal_info.address()!=''"><span data-bind="text: personal_info.address"></span></div>
				<div data-bind="if:personal_info.address()===''"><span class="placeholder-color">Street No. Subdivision/Brgy, City, Province Postal Code</span></div>
				<div><b>Email:</b> <span data-bind="text: personal_info.email_address"></span>
				 / <b>Mobile:</b> <span data-bind="text: personal_info.mobile_number"></span></div>				
			</div>
			<div class="divider">
				
			</div>
			<!-- ko foreach: education -->
			<div class="grid-100 grid-parent resume-row" data-bind="">
				<table class="resume-table">
					<tr>
						<td class="label-col"><span data-bind="if:$index()==0">EDUCATION</span></td>
						<td>
							<b><span data-bind="text: school_name"></span></br></b>
							
							<!-- ko if:type()=="highschool"-->
							<span class="subname"data-bind="text: 'High School Diploma'"></span><br/>
							<!-- /ko -->
							<!-- ko ifnot:type()=="highschool"-->
							<span class="subname"data-bind="visible:degree_program()!='',text: degree_program"><br/></span>
							<span class="placeholder-color"data-bind="visible:degree_program()===undefined||degree_program()===''">Degree Program<br/></span>
							<!-- /ko -->
							<!--ko foreach:recognitions-->
							<div><i class="work-description-text" data-bind="text:award"></i></div>
							<!-- /ko-->
						</td>
						<td class="right-column">
							<span data-bind="text: graduation_month"></span>&nbsp;<span data-bind="text: graduation_year"></span>
						</td>
					</tr>
				</table>
			</div>
			
			<!-- /ko -->
			<div class="divider">
				
			</div>
			<!-- ko if: hasWorkExp() -->
			

			<!-- ko foreach: workExp -->
			<div class="grid-100 grid-parent resume-row" data-bind="">
				<table class="resume-table">
					<tr>
						<td class="label-col"><span data-bind="if:$index()==0">WORK EXPERIENCE</span></td>
						<td>
							<b><span data-bind="text: company_name"></span></b></br>
							<span class="subname position"data-bind="text: position"></span><br/>
					
							
							<!--ko foreach:work_descriptions-->
							<div><i class="work-description-text" data-bind="text:work_description"></i></div>
							<!-- /ko-->
						</td>
						<td class="right-column">
							<div><span data-bind="text: from_month"></span> <span data-bind="text: from_year"></span> - <span data-bind="text: to_month"></span> <span data-bind="text: to_year"></span></div>
						<i data-bind="text:company_location"></i>
						</td>
					</tr>
				</table>
			</div>
			<!-- /ko -->
			<div class="divider">
				
			</div>
			<!-- /ko -->

			<!-- ko if: hasAffiliation() -->

			
			<!-- ko foreach: affiliations -->
		
			<div class="grid-100 grid-parent resume-row">
				
				<table class="resume-table committee-table">
					<tbody data-bind="foreach:committees">
					<!-- ko if:$index()==0&&$parentContext.$index()==0 -->
					<tr>
						<td class="label-col"><span>ACTIVITIES</span></td>
						<td><div><b><span class="schoolname" data-bind="text: $parent.affiliation_name"></span></b></br></div></td>
						<td></td>
					</tr>
					<!-- /ko -->
					<!-- ko if:$index()==0&&$parentContext.$index()!=0 -->
					<tr>
						<td class="label-col"></td>
						<td><div><b><span class="schoolname" data-bind="text: $parent.affiliation_name"></span></b></br></div></td>
						<td></td>
					</tr>
					<!-- /ko -->
					<tr><td class="label-col"></td>
						<td>
							
							<span class="subname" data-bind="text: committee_name"></span></br>
							
							<i data-bind="text:task_description"></i></br>
						</td>
						<td class="right-column">
							<span data-bind="text: from_month"></span>&nbsp;<span data-bind="text: from_year"></span>
							<!-- ko if:to_month()!=undefined&&!to_present() -->
							<span>-</span>
							<!--/ko-->
							<!-- ko if:to_present() -->
							<span>- Present</span>
							<!--/ko-->
							<!-- ko if:!to_present() -->
							<span data-bind="text: to_month"></span><span data-bind="visible:to_month()!=undefined">&nbsp;<span data-bind="text: to_year"></span></span>
							<!--/ko-->
						</td>
					</tr>
					</tbody>
				</table>
				

			</div>
			<!-- /ko -->
			


			<!-- /ko -->
			<!-- ko if: skills.skills()!='' -->
			<div class="divider">
				
			</div>
			<!-- ko with:skills-->
			<div class="grid-100 grid-parent resume-row">
				
				<table class="resume-table">
					<tbody>
	
					<tr>
						<td class="label-col"><span>SKILLS</span></td>
						<td><div><span data-bind="html: text_skills"></span></br></div></td>
						<td></td>
					</tr>
					
					</tbody>
				</table>
				

			</div>

			<!--/ko-->
			<!--/ko-->

		</div>
	</div>
</div>