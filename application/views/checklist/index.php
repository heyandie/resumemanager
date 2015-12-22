<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/checklist.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/signup.latest.css">

<div class="grid-container menu-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5 menu-bar">
		<div class="brand">
			<a style="display:inline-block;" href="<?php echo site_url();?>"><div class="capes-logo"></div></a>
		</div>
		
		<div class="brand"><a href="<?php echo site_url();?>">Resume Box</a></div>

		<div class="link-pull-right cl-effect-5 menu-links">
			<ul>
			
			<li>
				<a>
					<span data-hover="Menu">Menu</span>
					&nbsp;<i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown">
					<li><a href="<?php echo site_url('resume_editor') ?>">Resume Editor</a></li>
					<li><a href="<?php echo site_url('home/logout') ?>">Logout</a></li>
				</ul>
			</li>
			</ul>
		</div>
	</div>
</div>

<div id="companies-checklist" class="grid-container default-container">
	<div class="grid-80 prefix-10 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">
		<div class="grid-100 tablet-grid-100 grid-parent">
			
		</div>

		<div class="grid-50 tablet-grid-60 grid-parent">
			
			<div class="grid-100 grid-parent">
			
				
				<div class="grid-100 tablet-grid-100 grid-parent">
					<!--ko if:companies().length==0-->
						<p style="padding:0px">Sorry there are no companies scheduled for this day.</p>
					<!--/ko-->
				<div class="grid-100 mobile-grid-100 tablet-grid-100 grid-parent checklist" data-bind="if:companies().length!=0">

					

					<ul class="date">
							
						<li><h2>Companies Checklist <a class="fa fa-info guide" data-toggle="tooltip" data-placement="right" title="Show Guideline"></a></h2></li>
						
						
						<li><b>Date:</b> <span style="font-weight:normal"data-bind="text:dateToShow"></span>
						<br><b>Time Remaining:</b> <span id="countdown"></span>
						<table class="filter">
						
							<tr>
							<td><b>Show:</b></td>
							<td>
							<li>
							<input id="AllCompanies" value="1" type="radio" checked name="filter" data-bind="checked:companiesFilter"></input>
							<label for="AllCompanies">All Companies</label>
							</li>
							<li>
							<input id="ChosenCompanies" value="2" type="radio" name="filter" data-bind="checked:companiesFilter"></input>
							<label for="ChosenCompanies">Selected Companies</label>
							</li><br>
							<li>
							<input id="ByCourse" type="checkbox" name="courseFilter" data-bind="checked:filterByCourse"></input>
							<label for="ByCourse">By Course</label>
							
								<div class="select" data-bind="style:{opacity: filterByCourse()? 1 : 0}">
									<div class="select">
										
										<select data-bind="options:courses,optionsText:'abbr',value:courseFilter"></select>
									</div>
									<div class="select">
										<select data-bind="options:hiring,optionsText:'name',optionsValue:'abbr',value:hiringFilter"></select>
									</div>
								</div>
							</li>
							<div class="input">
								<input data-bind="value:searchCompany,valueUpdate:'input'"placeholder="Search Company"></input>
								</div>
							
							</td>
							</tr>	
						</table>

					</li>

						</ul>
					<div class="criteria">
						<i style="font-size:12px">Showing <span data-bind="html:matchDescription"></span></i>
					</div>
					<ul class="companychecklist" data-bind="template: { foreach: companiesToShow }">
					    <li class="checklist-item">
					    		<input type="checkbox" data-bind="checked: selected, attr: {id: company_name()}"/>
					    	<label data-bind='highlightedText: { text: company_name, highlight: $root.searchCompany, css: "highlight" },attr:{for:company_name()}'></label>
					    	
					    </li>

					</ul>

					<ul class="date" style="border-top:1px dotted #aaa">

						<li><div class="form-buttons grid-100 grid-parent" style="padding-top:5px 0px;text-align:center">	
							<button data-bind="click: save,visible:companies().length!=0" class="btn">Save Checklist</button>

						</div></li>
					</ul>
				</div>
			</div>
			</div>
			
		</div>

		<div class="grid-45 tablet-grid-35 prefix-5 tablet-prefix-5 grid-parent" >
	
				<h2>Frequently Asked Questions:</h2>
				<p><b>Q: Will my resume be immediately sent to the companies I selected at the end of the day?</b></p>
				<p>A: No. Your resume will be sent to all the companies you selected after the Jobfair.</p>
				<div class="divider"></div>
				<p><b>Q: What happens if I selected a company in more than one date?</b></p>
				<p>A: Selected companies having more than one date will still be counted as a single request.</p>
				<div class="divider"></div>
				<p><b>Q: How will the company know if I am applying for an employment or an internship?</b></p>
				<p>A: You will need to fill up first your resume at the <a  data-toggle="tooltip" data-placement="top" title="Go to Resume Editor" href="//resume.upcapes.org/resume_editor">Resume Editor</a>. At the end of the form, you will be asked which type 
					of application are you
				 interested to.</p>
				<div class="divider"></div>
				For more info please click <a class="guide">here</a>
				<p>
					Go to <a href="//jobfair2015.upcapes.org">jobfair2015.upcapes.org</a> to see the full list of companies for Jobfair 2015.
				</p>
		</div>
		</div>
		<footer class="grid-100 tablet-grid-100 mobile-grid-100">
		<div>UP CAPES Resume Box © 2015</div>
		</footer>
	</div>
	
	<div id="success" style="display:none" class="div-mask">
		<div class="div-mask-popout">
			<div style="display:table;margin:10px 0px">
			<span style="display:table-cell;vertical-align:middle">
				<i class="success-check-default fa fa-check"></i></span>
				<h3 style="display:table-cell;vertical-align:middle">Success!</h3>
			</div>
			<p>Your checklist has been saved.</p>
			<p style="text-align:center"><button style="width:50px"class="btn-dismiss btn">Ok</button></p>
			<a class="popup-dismiss btn-dismiss fa fa-remove"></a>
		</div>

	</div>
	
</div>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.countdown.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/coursematrix.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/companieschecklist.js"></script>
<script>
	$(function() {
		var m = new Array("Jan", "Feb", "Mar", 
		"Apr", "May", "Jun", "Jul", "Aug", "Sep", 
		"Oct", "Nov", "Dec");
		var tomorrow= new Date();
		tomorrow.setDate(tomorrow.getDate() + 1);
		var tom_date = tomorrow.getDate();
		var tom_month = tomorrow.getMonth();
		var tom_year = tomorrow.getFullYear();
		tomorrow=m[tom_month]+' '+tom_date+', '+tom_year+" 00:00:00";
	    $('#countdown').countdown({
	        date: tomorrow,
	        render: function(data) {
	        	var hrs=this.leadingZeros(data.hours, 2)==="01"||this.leadingZeros(data.hours, 2)==="00" ?  this.leadingZeros(data.hours, 2) + " <span>hr</span> ":this.leadingZeros(data.hours, 2) + " <span>hrs</span> ";
	            $(this.el).html(hrs + this.leadingZeros(data.min, 2) + " <span>min</span> " + this.leadingZeros(data.sec, 2) + " <span>sec</span>");

	          },
	        onEnd:function(){
	        	window.location.reload(true);
	        }
	    });
	});
	var student_number = "<?php echo $student_number; ?>";
	var schedule = <?php echo $schedule; ?>;
	var companies = <?php echo $companies; ?>;
	var degree_program = "<?php echo $degree_program; ?>";
	var today="<?php echo $today;?>";
	$(function(){
		var companiesChecklistModel = new CompaniesChecklistModel();
		ko.applyBindings(companiesChecklistModel, document.getElementById('companies-checklist'));
		$(window).on('beforeunload', function(){
                  $('body').append('<div class="div-white"/>');
           });
		$("body").on('click','.btn-dismiss',function(){
						$(".div-mask").fadeOut('fast');
					});
	});

</script>

<div id="before-unload" style="display:none" class="div-mask">
	<div class="div-mask-popout">
		<div style="display:table;margin:10px 0px">
		<span style="display:table-cell;vertical-align:middle">
			<i class="success-check-default fa fa-check"></i></span>
			<h3 style="display:table-cell;vertical-align:middle">Confirm Navigation</h3>
		</div>
		<p>Are you sure you want to leave?<br></p>
		<p style="text-align:center"><button style="width:50px"class="btn-confirm btn">Ok</button> <button class="btn-normal btn-dismiss btn">Cancel</button></p>
		<a class="popup-dismiss btn-dismiss fa fa-remove"></a>
	</div>

</div>
<div id="guide" style="display:none" class="div-mask">
	<div class="div-mask-popout">
		<p>Hi, <b><?php echo $this->session->userdata('name'); ?></b>.</p>
			<!-- ko if:companies().length>0-->
			<p>On the checklist are the participating companies in UP CAPES' Jobfair 2015.
				There will be different sets of companies each day for the whole duration the event.
			</p>
			<p>
				If you wish to send your resume to a company, please click on the box containing the name of the company. And please save your changes when you are done.
			</p>
			
			<!--/ko-->
			<?php if(!$has_resume):?>
			<p class="dismissable-alert grid-100">You have not yet submitted your resume. Please use the <a href="<?php echo site_url('resume_editor') ?>">Resume Editor</a> to submit your resume.</p>
			<?php endif?>
		<p style="text-align:center"><button style="width:50px"class="btn-dismiss btn">Ok</button>
		<a class="popup-dismiss btn-dismiss fa fa-remove"></a>
	</div>

</div>
<script type="text/javascript">

$(function(){
	$('.guide').click(function(){
		$('#guide').fadeIn(100);
	});
	$('[data-toggle="tooltip"]').tooltip();
	$('input').bind('keypress', function (event) {
	    var regex = new RegExp("^[a-zA-Z0-9-/_]+$");
	    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	    if (!regex.test(key)) {
	       event.preventDefault();
	       return false;
	    }
	});
});

</script>