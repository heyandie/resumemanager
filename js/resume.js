
var offsetToScroll = "";
var editor=$("div#grid-editor");
var rules = {
	"default": /^[ñA-Za-z0-9 \-\.']+$/i,
	"student_number": /^[0-9]{9}$/,
	"first_name": /^[ñA-Za-z0-9 \-\.']+$/i,
	"last_name": /^[ñA-Za-z0-9 \-\.']+$/i,
	"mobile_number": /^[0-9]{11}$/,
	"email_address": /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i,
	"address": /^(?!\s*$).+/,
	"year": /^[12][0-9]{3}$/

}
var environ = window.location.host;
function getAjaxUrl(){
	if (environ === "localhost") {
	    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/";
	} else {
	    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/";
	}
	return baseurl;
}

var errormessages = {
	"student_number": "Student number must be 9 digits without dash.",
	"first_name": "First name can contain alphabet characters, spaces or dashes only.",
	"last_name": "Last name can contain alphabet characters, spaces or dashes only.",
	"mobile_number": "Mobile number must be 11 digits.",
	"email_address": "Email address must be valid.",
	"default": "must be valid"
}

var required = {
	"default": "Required.",
	"student_number": "Please enter your student number.",
	"first_name": "Please enter your first name.",
	"last_name": "Please enter your last name.",
	"email_address": "Please enter your email address.",
	"mobile_number": "Please enter your mobile number.",
	"address": "Please enter your address.",
	"degree_program": "Please choose a degree program."
}



var mapping = {
	'recognitions':{
		create:function(options){
			return new RecognitionModel(options.data,options.parent);
		}
	},
	'work_descriptions':{
		create:function(options){
			return new WorkDescriptionModel(options.data,options.parent);
		}
	},
	'committees':{
		create:function(options){
			return new CommitteeModel(options.data,options.parent);
		}
	}

}

var SkillsModel = function(data,parent){
	var self=this;
	self.parent=parent;
	self.skills=ko.observable("");
	self.old_skills="";
	self.isEditing=ko.observable(false);
	self.edit_skills=ko.computed(function(){
		return self.skills().replace(/<br\s*[\/]?>/gi, "\n");
	},self);
	self.text_skills=ko.computed(function(){
		return self.skills().replace(/\r\n|\r|\n/g, "<br/>");
	},self);
	
	
	self.edit=function(item,event){
		$(event.target).parents('div.skills-group').find('textarea').autosize({resizeDelay:100,append:false});
		self.isEditing(true);
		self.parent.isEditing(true);
		self.old_skills=self.skills();
		self.skills(self.edit_skills());

	}
	self.cancel=function(){
		self.isEditing(false);
		self.parent.isEditing(false);
		self.skills(self.old_skills);
		self.old_skills="";
	}
	self.save=function(){
		var $skills=$('div#skills .skills-group').first();
		
		var data={
				student_number:self.parent.student_number(),
				section:"skills",
				skills:self.skills()
			};
		if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/save_skills/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/save_skills/";
			}
		$.ajax({
			url:baseurl,
			type:"POST",
			data:data,
			beforeSend:function(){
				$('body').append('<div class="div-loading"></div>');
			},
			success:function(response){
			
				$(".div-loading").remove();
				self.isEditing(false);
				self.parent.isEditing(false);
			}
		})
	}
	ko.mapping.fromJS(data,{},self);
}
var ResumeManagerModel = function(){

	var self = this;

	jQuery.fn.scrollTo = function(elem, speed) { 
	    $(this).animate({
	        scrollTop:  $(this).scrollTop() - $(this).offset().top + elem.offset().top - 150
	    }, speed == undefined ? 400 : speed); 
	    return this; 
	};


	self.months = [
		"Jan","Feb","Mar","Apr",
		"May","Jun","Jul","Aug","Sep",
		"Oct","Nov","Dec"];

	self.student_number=ko.observable(student_number);
	self.personal_info = new PersonalInfoModel(personal_info,self);
	self.skills=new SkillsModel({},self);
	self.skills.skills(skills);
	self.fullname = ko.computed(function(){
		return self.personal_info.first_name() + " " + self.personal_info.last_name();
	});

	self.availableStudentType = ko.observableArray(
		["Undergraduate","Graduate"]

		);
	self.isGraduating=ko.observable(false);

	self.availableUndergraduateCourses = ko.observable([
		"Bachelor of Science in Chemical Engineering",
		"Bachelor of Science in Civil Engineering",
		"Bachelor of Science in Computer Science",
		"Bachelor of Science in Computer Engineering",
		"Bachelor of Science in Electrical Engineering",
		"Bachelor of Science in Electronics & Communications Engineering",
		"Bachelor of Science in Geodetic Engineering",
		"Bachelor of Science in Industrial Engineering",
		"Bachelor of Science in Mechanical Engineering",
		"Bachelor of Science in Materials Engineering",
		"Bachelor of Science in Metallurgical Engineering",
		"Bachelor of Science in Mining Engineering"
		])

	self.isEditing=ko.observable(false);
	self.isEditing.subscribe(function(){
		ko.bindingHandlers.sortable.isEnabled(!self.isEditing());
	},self);
	self.student_type = ko.observable(student_type);
	self.education_graduate = new EducationModel(education_graduate,self);
	self.education_undergraduate = new EducationModel(education_undergraduate,self);
	self.education_highschool = new EducationModel(education_highschool,self);
	self.education=ko.observableArray([]);
	if(self.student_type()==="Graduate"){
		self.education.push(self.education_graduate);
	}


	self.education.push(self.education_undergraduate,self.education_highschool);
    var mapped_work_experience=ko.utils.arrayMap(work_experience,function(data){
		return new WorkExpModel(data,self);});
	self.workExp =ko.observableArray(mapped_work_experience);
	var mapped_affiliations=ko.utils.arrayMap(affiliations,function(data){
		return new AffiliationModel(data,self);});
	self.affiliations = ko.observableArray(mapped_affiliations);
	self.application_type=ko.observable("");

	

	self.hasWorkExp = function(){
		/*var notempty = 0;
		ko.utils.arrayForEach(self.workExp(),function(work){
			if(work.company_name()!="")
				notempty += 1;
		});*/
		if(self.workExp().length>0){
			return true;
		}else
			return false;

	}

	self.hasAffiliation = function(){
		if(self.affiliations().length>0){
			return true;
		}else
			return false;
	}

	self.addWorkExp = function(){
		var count = self.workExp().length;
		if(count<10&&!self.isEditing()){
			var work=new WorkExpModel({work_id:0},self);
			self.workExp.push(work);
			work.edit(work);
			self.isEditing(true);
			$('[data-toggle="tooltip"]').tooltip();
			editor.scroll();
			editor.scrollTo($('div.work-experience-group').last());
		}
			
	}

	self.addAffiliation = function(){
		var count = self.affiliations().length;
		if(count<10&&!self.isEditing()){
			self.affiliations.push(new AffiliationModel({affiliation_id:0,isEditing:true,order:count},self));
			self.isEditing(true);
			editor.scroll();
			editor.scrollTo($('div.affiliation-group').last());

		}
			
	}
	self.addAffiliationTitle = ko.pureComputed(function() {
        return self.isEditing() ? "Disabled on editing mode." : "Create new affiliation";
    }, self);

	self.isUndergraduate = function(){
		return (self.student_type()=="Undergraduate") ? true : false;
	}

	self.isPersonalInfo = ko.observable(true);
	self.isEducation = ko.observable(false);
	self.isWorkExp = ko.observable(false);
	self.isExtraCurr = ko.observable(false);
	self.isSubmit = ko.observable(false);
	self.isPreview = ko.observable(true);
	self.isSkills = ko.observable(false);
	self.isConfirmed = ko.observable(false);

	self.showPersonalInfo = function(){
		if(self.isPersonalInfo())
			return;

		if(self.isWorkExp()||self.isExtraCurr()){
			if(!self.cancelEmptyItems()){
				return false;
			}
		}
			
		self.isPersonalInfo(true);
		self.isEducation(false);
		self.isWorkExp(false);
		self.isExtraCurr(false);
		self.isSkills(false);
		self.isSubmit(false);

		$(window).scrollTop(0);
	}

	self.showEducation = function(){
		if(self.isEducation())
			return;
		
		if(!self.cancelEmptyItems()){
			return false;
		}
		self.isPersonalInfo(false);
		self.isEducation(true);
		self.isWorkExp(false);
		self.isExtraCurr(false);
		self.isSkills(false);
		self.isSubmit(false);
		$(window).scrollTop(0);
	}

	self.showWorkExp = function(){
		if(self.isWorkExp())
			return;
		if(!self.cancelEmptyItems()){
			return false;
		}
		self.isPersonalInfo(false);
		self.isEducation(false);
		self.isWorkExp(true);
		self.isExtraCurr(false);
		self.isSkills(false);
		self.isSubmit(false);
		$(window).scrollTop(0);
	}

	self.showExtraCurr = function(){
		if(self.isExtraCurr())
			return;
		if(!self.cancelEmptyItems()){
			return false;
		}
		self.isPersonalInfo(false);
		self.isEducation(false);
		self.isWorkExp(false);
		self.isExtraCurr(true);
		self.isSkills(false);
		self.isSubmit(false);
		$(window).scrollTop(0);
	}
	self.showSkills = function(){
		if(self.isSkills())
			return;
		if(!self.cancelEmptyItems()){
			return false;
		}
		self.isPersonalInfo(false);
		self.isEducation(false);
		self.isWorkExp(false);
		self.isExtraCurr(false);
		self.isSkills(true);
		self.isSubmit(false);
		$(window).scrollTop(0);
	}


	self.showSubmit = function(){

		if(self.isSubmit())
			return;
		if(!self.cancelEmptyItems()){
			return false;
		}
		self.isPersonalInfo(false);
		self.isEducation(false);
		self.isWorkExp(false);
		self.isExtraCurr(false);
		self.isSkills(false);
		self.isSubmit(true);
		$(window).scrollTop(0);
		
	}
	self.isCertified=ko.observable(false);
	self.submitResume=function(item,event){

		if(self.validateSubmit()){
			if(!self.isUndergraduate()){
				self.application_type(1);
			}
			if(!self.isCertified()||!self.application_type()){

				if(!self.isCertified()){
					$(".terms").addClass("terms-danger");
					setTimeout(function(){
						$(".terms").removeClass("terms-danger");
					},1200);
				}
				if(!self.application_type()){
					$(".application-type").addClass("terms-danger");
					setTimeout(function(){
						$(".application-type").removeClass("terms-danger");
					},1200);
				}
				var file = $('input#userfile')[0].files[0];
				
				if(file!=undefined){
					if(file.name.length > 0)
						if(file.size >5000000){
			    			alert("File is too big");
			    		}else if(file.type != 'application/pdf') {
			    			 alert("File is not a pdf file");
			    			}
				}
					
			}else{
				var form = new FormData(document.getElementById('resume-submit'));
				var baseurl=getAjaxUrl()+"submit_resume";
				form.append('student_number',self.student_number());
				if(!(currentscheme.color()==="yellow"))
					form.append('colorscheme',currentscheme.color());
				 $.ajax({type: 'POST',
			        url: baseurl,
			        data: form,
			        beforeSend:function(){
						$('body').append('<div class="div-loading"></div>');
					},
			        processData: false,
			        contentType: false,
			        success: function(response) {
			            if(response){
							$(".div-loading").remove();
							$("#submit-success").fadeIn("fast");

						}else{
							$(".div-loading").remove();
							$("#submit-success").fadeIn("fast");
						}
			           
			        },
			        error:function(x,t,h){
			
							$(".div-loading").remove();
							$("#submit-error").fadeIn("fast");
						
					},
					complete:function(){
						$('form#resume-submit').find('input').removeAttr('checked');
						$('form#resume-submit').find('userfile').val('');
					}
			    });
				return false;
				/*
				
				$.ajax({
					url:"resume_editor/submit_resume",
					method:"POST",
					dataType: 'html',
					data:{student_number:self.student_number(),
						application_type:self.application_type(),
						student_type:self.student_type()},
					beforeSend:function(){
						$('body').append('<div class="div-loading"></div>');
					},
					success:function(response){
						

						if(response){
							$(".div-loading").remove();
							$("#submit-success").fadeIn("fast");

						}
					},
					error:function(x,t,h){
			
							$(".div-loading").remove();
							$("#submit-error").fadeIn("fast");
						
					}
				});*/
				
			}
		}
	}

	

	self.validateOnBlur = function(item,event){
		var $target = $(event.target);
		
		if(self.validate($target)){

			var $success_icon=$('<span class="fa fa-check success-check" style="display:none"></span>');
				$target.parent('div').append($success_icon)
				 .find('.success-check').fadeIn();
				setTimeout(function(){
					$target.parent().find('.success-check').fadeOut();
				},1500);
								
			var field_name = $target.attr("name");
			var value = $target.val().trim();
			var data="";
			var valid=0;
			if(self.isEducation()){
				if(field_name.match("student_type")){
					value=self.student_type();
					valid=1;

					if(value.match("Undergraduate")){
						self.education.remove(function(item){return item['type']()==="graduate"});
						value=1;
					}
					else{
						self.education.unshift(self.education_graduate);
						value=2;
					}
					
					data={
						student_number:self.student_number(),
						section:'personal_info',
						field_name:field_name,
						value:value
					}
				}
				
			}

			if(valid){
				if (environ === "localhost") {
				    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/save_item/";
				} else {
				    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/save_item/";
				}
				$.ajax({
						type: "POST",
						url: baseurl,
						data: data,
						beforeSend:function(){
							$('body').append('<div class="div-loading"></div>');
						},
						success:function(response){

							if(!response)
								return;
							response=JSON.parse(response);
							if(field_name=="student_type"&&value==1){
								ko.utils.arrayForEach(self.education(),function(item){
									if(item['type']()==="undergraduate"){
										item.school_name("University of the Philippines Diliman");
									}
								});
							}
							if(field_name=="student_type"&&value==2){
								ko.utils.arrayForEach(self.education(),function(item){
									if(item['type']()==="undergraduate"){
										item.degree_program("");
									}
								});
							}
							if(response['code']==404)
								window.location.replace(response['url']);
							else{
								var $success_icon=$('<span class="fa fa-check success-check" style="display:none"></span>');
								$target.parent('div').append($success_icon)
								 .find('.success-check').fadeIn();
								setTimeout(function(){
									$target.parent().find('.success-check').fadeOut();
								},1500);
							}

							
							
						},
						complete:function(){
							$('div.div-loading').remove();
						}
						
					});
			}
		}

		$('.work-experience-group, .affiliation-group').removeClass('group-highlight');

		
	}

	self.validate = function($target,allrequired){

		var allrequired = (allrequired === undefined) ? 0 : allrequired;
		var name = $target.attr("name");
		var input = $target.val();
		var $p = $target.next('p');
		if(input==""){

			if(!allrequired){
				if($target.attr("data-notrequired")!=undefined)
					return true;				
			}
			

			$target.addClass('danger');
		
				if(required[name]!=undefined){

					if($p.length==0)
						$target.after('<p class="error">'+ required[name]+'</p>');

				}else{
					if($p.length==0)
						$target.after('<p class="error">'+required['default'] + '</p>');
			
				}
						
			return false;
		}
		else{
			if(name!=""&&name!=undefined){
				if(rules[name]!=undefined)
					if(!input.match(rules[name])){
						$target.addClass("danger");
						

						if(errormessages[name]!=undefined){
							if($p.length==0)				
								$target.after('<p class="error">'+ errormessages[name] +'</p>');
			

						}else{
							var errormessage = (name.charAt(0).toUpperCase() + name.slice(1)).replace(/_/g, " ") + " " + errormessages['default'];
							if($p.length==0)				
								$target.after('<p class="error">'+ errormessage +'</p>');
						}

						return false;
					}

				if(name.indexOf("year") > -1){

					name = 'year';
					if(!input.match(rules[name])){
						$target.addClass("danger");

						if(errormessages[name]!=undefined){
							if($p.length==0)				
								$target.after('<p style="width:'+ $target.width() + 'px" class="error">'+ errormessages[name] +'</p>');
			

						}else{
							var errormessage = (name.charAt(0).toUpperCase() + name.slice(1)).replace(/_/g, " ") + " " + errormessages['default'];
							if($p.length==0)				
								$target.after('<p style="width:'+ $target.width() + 'px" class="error">'+ errormessage +'.</p>');
						}

						return false;
					}
				}
			}
		}

		return true;
		

	}
	self.validateSubmit = function(){
		var failed = 0;

		$('input,textarea,select').removeClass('danger');
		$('p.error').remove();
		if(!self.personal_info.validateFields()){
			return false;
		}
	
		failed=0;

		prop={
			'undergraduate:':{
				0:'graduation_month',
				1:'graduation_year',
				2:'degree_program'
			},
			'highschool:':{
				0:'graduation_month',
				1:'graduation_year',
				2:'school_name'
			}
		}
		if(self.student_type()==="Graduate"){
			prop['graduate:']={
				0:'graduation_month',
				1:'graduation_year',
				2:'degree_program'
			};
			prop['undergraduate:'][3]="school_name";
		}
		var count=0;
		for(var i in prop){
			
			for(var j in prop[i]){
				$field=$("#education").find('input[name~="'+i+prop[i][j]+'"], textarea[name~="'+i+prop[i][j]+'"], select[name~="'+i+prop[i][j]+'"]');
				if($field.length==0)
					window.location.reload();
				else{
					if(!self.validate($field,1)){
						failed+=1;
						
						self.education()[count].edit(self.education()[count]);
					}
				}
			}
			count+=1;
		}

		if(failed){
			self.showEducation();
			setTimeout(function(){
				self.validateAll();
			},100);
			return false;
		}

		return failed==0;
	}
	self.validateAll = function(){
		var failed = 0;

		$('input,textarea,select').removeClass('danger');
		$('p.error').remove();
		if(!self.personal_info.validateFields()){
			self.showPersonalInfo();
		}

		else if(self.isEducation()){
			$('div#education').find('input,textarea,select').each(
				function(){
					if(!self.validate($(this)))
						failed += 1;
				});
		}

		

		if(failed){
			editor.scroll();
			editor.scrollTo($('.danger').first());
		}else{
			self.cancelEmptyItems();
		}

		return failed==0;
	}
	self.cancelEmptyItems=function(){
		var failed=0;
		if(self.isPersonalInfo()){
			if(self.personal_info.isEditing()){
				if(self.personal_info.hasNoChanges()){
					self.personal_info.cancel();
				}else{
					self.personal_info.setAlerts(".alert-info.alert-changes");
					return false;
				}


			}
		}else if(self.isEducation()){
			ko.utils.arrayForEach(self.education(),function(item,index){
				
					if(item.isEditing()){
						if(item.hasNoChanges())
						{
							item.cancel(item);
						}else{
							var $mask=$('#education .alert-mask:eq('+index+')');
							var $alert=$('#education .alert-changes:eq('+index+')');
							$mask.on('click',function(){
								$mask.removeClass('alert-notify');
								$alert.removeClass('alert-notify');
							});
							editor.scroll();
							editor.scrollTo($('#education .work-actions:eq('+index+')'));
							failed+=1;
							setTimeout(function(){
								$mask.addClass('alert-notify');
								$alert.addClass('alert-notify');
							},100);
							setTimeout(function(){
								$mask.removeClass('alert-notify');
								$alert.removeClass('alert-notify');
							},2500);
							return false;
						}
						
					}
				});
				
		}
		else if(self.isWorkExp()){
			
				ko.utils.arrayForEach(self.workExp(),function(item,index){
				
					if(item.isEditing()){
						if(item.hasNoChanges())
						{
							item.cancel(item);
						}else{
							var $mask=$('#workexp .alert-mask:eq('+index+')');
							var $alert=$('#workexp .alert-changes:eq('+index+')');
							$mask.on('click',function(){
								$mask.removeClass('alert-notify');
								$alert.removeClass('alert-notify');
							});
							editor.scroll();
							editor.scrollTo($('#workexp .work-actions:eq('+index+')'));
							failed+=1;
							setTimeout(function(){
								$mask.addClass('alert-notify');
								$alert.addClass('alert-notify');
							},100);
							setTimeout(function(){
								$mask.removeClass('alert-notify');
								$alert.removeClass('alert-notify');
							},2500);
							return false;
						}
						
					}
				});
				

			}else if(self.isExtraCurr()){
				ko.utils.arrayForEach(self.affiliations(),function(item,index){
				
					if(item.isEditing()){
						if(item.hasNoChanges())
						{
							item.cancel(item);
						}else{
							var $mask=$('#extra-curricular .alert-mask:eq('+index+')');
							var $alert=$('#extra-curricular .alert-changes:eq('+index+')');
							$mask.on('click',function(){
								$mask.removeClass('alert-notify');
								$alert.removeClass('alert-notify');
							});
							editor.scroll();
							editor.scrollTo($('#extra-curricular .work-actions:eq('+index+')'));
							failed+=1;
							setTimeout(function(){
								$mask.addClass('alert-notify');
								$alert.addClass('alert-notify');
							},100);
							setTimeout(function(){
								$mask.removeClass('alert-notify');
								$alert.removeClass('alert-notify');
							},2500);
							return false;
						}
						
					}
				});
				if(self.isOrderChanged())
					self.cancelOrder();
			}else if(self.isSkills()){
				if(self.skills.isEditing())
					self.skills.cancel();
				
			}
			return failed==0;
	}

	self.resetInput = function(item,event){
		var $target = $(event.target);
		$target.removeClass("danger");
		$target.next('p').fadeOut(100, function(){
			$(this).remove();});

		if(self.isWorkExp()){
			$target.parents('.work-experience-group').addClass('group-highlight');
		}

		if(self.isExtraCurr()){
			$target.parents('.affiliation-group').addClass('group-highlight');
		}
	}

	
	
	// Here's a custom Knockout binding that makes elements shown/hidden via jQuery's fadeIn()/fadeOut() methods
	// Could be stored in a separate utility library
	ko.bindingHandlers.fadeVisible = {
	    init: function(element, valueAccessor) {
	        // Initially set the element to be instantly visible/hidden depending on the value
	        var value = valueAccessor();
	        $(element).toggle(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
	    },
	    update: function(element, valueAccessor) {
	        // Whenever the value subsequently changes, slowly fade the element in or out
	        var value = valueAccessor();
	        ko.unwrap(value) ? $(element).delay(50).fadeIn(400) : $(element).fadeOut(50);
	    }
	};

	var old_affiliations=[];
	self.isOrderChanged=ko.observable(false);
	self.saveOrder=function(){
		var affiliations=[];

		if(self.isExtraCurr()){
			ko.utils.arrayForEach(self.affiliations(),function(x){
				affiliations.push({affiliation_id:x.affiliation_id(),order:x.order()});
			});
	
			$.ajax({
				url:getAjaxUrl()+"save_affiliations_order",
				type:"POST",
				data:{section:"affiliations",student_number:self.student_number(),affiliations:affiliations},
				beforeSend:function(){
					$('body').append('<div class="div-loading"></div>');
				},
				success:function(response){
				
					$(".div-loading").remove();
					self.isOrderChanged(false);
					old_affiliations=[];
				}
			});
		}
	}
	self.cancelOrder=function(){
		if(self.isExtraCurr()){
			ko.utils.arrayForEach(self.affiliations(),function(x){
				x.order(old_affiliations[x.affiliation_id()]);
			});
			self.isOrderChanged(false);
			self.affiliations().sort(function (l, r) { 
				return l.order() > r.order() ? 1 : -1 }

				);
			self.affiliations.valueHasMutated();
			old_affiliations=[];
		}
	}

	ko.bindingHandlers.sortable.beforeMove=function(arg){
		var i=0;
		if(self.isExtraCurr()){
			if(old_affiliations.length==0)
			ko.utils.arrayForEach(arg.sourceParent(),function(x){
				old_affiliations[x.affiliation_id()]=i;
				i++;
			});
		}
		
	}
	ko.bindingHandlers.sortable.options={
		placeholder:"sortable-state-highlight",
		start: function(e, ui){
	        ui.placeholder.height(ui.helper.outerHeight());
	    },
	    containment: "#grid-editor" 
	}
	ko.bindingHandlers.sortable.isEnabled=ko.observable(true);
	ko.bindingHandlers.sortable.afterMove=function(arg){
		var i=0;
		if(self.isExtraCurr()){
			ko.utils.arrayForEach(arg.targetParent(),function(x){
				x.order(i);
				i++;
			});
			self.isOrderChanged(true);
		}
	}
	
	var colorSchemeModel=function(data,parent){
		var self=this;
		self.color=ko.observable("");
		self.selected=ko.observable(false);
		self.setColorScheme=function(){
			$('.label-col,.subname').removeClass(currentscheme.color()+"-scheme");
			$('.label-col,.subname').addClass(self.color()+"-scheme");
			currentscheme=self;
			return true;
		}
		ko.mapping.fromJS(data,{},self);
	}
	var currentscheme=new colorSchemeModel({color:"yellow",selected:true});
	self.color_palette=ko.observableArray([
		currentscheme,
		new colorSchemeModel({color:"orange",selected:false}),
		new colorSchemeModel({color:"plum",selected:false}),
		new colorSchemeModel({color:"blue",selected:false})
		
		]);
}



/*!
	Autosize v1.18.9 - 2014-05-27
	Automatically adjust textarea height based on user input.
	(c) 2014 Jack Moore - http://www.jacklmoore.com/autosize
	license: http://www.opensource.org/licenses/mit-license.php
*/
