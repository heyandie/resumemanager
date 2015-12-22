<div data-bind="fadeVisible: isSubmit()" id="submit"
class="form-container grid-100 grid-parent tablet-grid-100 mobile-grid-100">
	<div class="grid-100">
		<h3 class="form-header">6 Submit Resume</h3>
	</div>
	<form id="resume-submit" data-bind="submit:submitResume"enctype="multipart/form-data" method="POST">
	<div class="application-type grid-100 grid-parent">
		<div class="important" style="line-height:18px;">
			<b><span>I. Choose the type of job you are applying for:</span><br><br></b>
			<span class="checkbox"style="text-align:justify"><input value="1" data-bind="checked: application_type" name="application_type"type="radio"/>
			 I am a graduating student and seeking for a career level position.</span>
			<span class="checkbox"style="text-align:justify"><input value="2" data-bind="checked: application_type" name="application_type" type="radio"/> 
			I am not a graduating student and seeking for an internship program.</span>
		</div>
	</div>
	<br>
	<div class="divider"></div>
	<br>
	<div class="optional-upload">
		<b><span>II. You can optionally upload your own resume. This will be considered as your official resume.</span><br><br></b>

        <input style="border:0px;background:transparent" type="file" name="userfile"accept=".pdf" id="userfile"></input>
	</div>
	<br>
	<div class="divider"></div>
	<br>
	<div class="terms" >
		<b><span>III. Please verify all of the information in your resume.</span><br><br></b>
		<span class="checkbox"style="text-align:justify"><input name="terms" type="checkbox" data-bind="checked:isCertified"></input>
			 I hereby certify that all of the information in this resume is true and correct to the best of my knowledge and belief.</span>
		</br>
	</div>
	<input type="hidden" name="student_number" val="<?php echo $student_number;?>"></input>
	<div class="form-buttons grid-100" style="text-align:right;">	
		<button type="submit" class="btn">Submit Resume</button>
		
	</div>
	</form>
	
</div>