var EducationModel = function(data,parent){

	var self = this;
	var environ = window.location.host;
	self.parent=parent;
	self.type = ko.observable("");
	self.graduation_month=ko.observable("");
	self.gradution_year= ko.observable("");
	self.school_name= ko.observable("");
	self.degree_program = ko.observable("");
	self.recognitions = ko.observableArray([]);
	self.old_recognitions = ko.observableArray([]);
	self.to_delete_recognitions = [];

	self.isEditing=ko.observable(false);
	self.old_data=0;

	self.showSchool = ko.computed(function(){
		if(self.type()==="highschool")
			return true;
		if(self.parent.student_type()==="Undergraduate"&&self.type()==="undergraduate")
			return false;
		if(self.parent.student_type()==="Graduate"&&self.type()==="undergraduate"){
			self.school_name("");
			return true;
		}
			
		if(self.parent.student_type()==="Graduate"&&self.type()==="graduate")
			return false;
	},self);
	
	self.removeEmptyNewRecognitions=function(){
		ko.utils.arrayForEach(self.recognitions(),function(item){
			var str="";
			if(item.award_id()==0){
				if(str===item.award())
					item.remove();
			}
				
		});
	}
	self.hasNoChanges=function(){
		for(var prop in self.old_data){
			var a=self.old_data[prop];
			var b=self[prop]();
			var match= a===b;
			if(!match)
				return false;
			
		}
		if(self.old_recognitions().length!=self.recognitions().length)
			return false;
		else{
			var bool=true;
			ko.utils.arrayForEach(self.recognitions(),function(item,index){
				var str=self.old_recognitions()[index].award();
				if(!(str===item.award())){
					bool=false;
					return false;
				}
					
			});
			return bool;
		}
		return true;
	}

	self.allowAddRecognition = function(){
		if(self.recognitions().length<10){
			return true;
		}
		return false;
	}
	self.addRecognition = function(){
		self.recognitions.push(new RecognitionModel({award_id:0},self));
		$('[data-toggle="tooltip"]').tooltip();
		
	}	


	self.getMainFields = function(){
		var required={
			
			graduation_month:self.graduation_month(),
			graduation_year:self.graduation_year()
		}
		if(self.showSchool()){
			required['school_name']=self.school_name();
		}
		if(self.type()!="highschool"){
			
			required['degree_program']=self.degree_program();
		}
	
		return required;
	}
	self.getMutatedMainFields = function(){
		
		var data={};
		for(var prop in self.getMainFields()){
			var a=self.old_data[prop];
			var b=self[prop]();
			var match= a===b;
			if(!match){
				data[prop]=b;
			}
		}
		return data;
	}
	self.validateFields=function($target){
		var required={
			
			graduation_month:self.graduation_month(),
			graduation_year:self.graduation_year()
		}
		if(self.showSchool()){
			$.extend(required,{school_name:self.school_name()});
		}
		if(self.type()!="highschool"){
			$.extend(required,{degree_program:self.degree_program()});
		}
		var $education=$target.parents('div.education-group');
		var failed=0;
		for (var prop in required) {
			$field=$education.find("input[name*='"+prop+"']");
			if($field.length==0)
				$field=$education.find("select[name*='"+prop+"']");
			if($field.length==0)
				$field=$education.find("textarea[name*='"+prop+"']");
			if($field.length==0){
				//window.location.reload();
				console.dir(prop);
			}else{
				if(!self.parent.validate($field,1))
					failed+=1;
			}
		};
		return failed;
	}
	self.validate=function(index){

		$target=$("div#education");

		var required={
			
			graduation_month:self.graduation_month(),
			graduation_year:self.graduation_year()
		}
		if(self.showSchool()){
			$.extend(required,{school_name:self.school_name()});
		}
		if(self.type()!="highschool"){
			$.extend(required,{degree_program:self.degree_program()});
		}
		var $education=$target.find('div.education-group:eq('+index+')');

		var failed=0;
		for (var prop in required) {
			$field=$education.find("input[name*='"+prop+"']");
			if($field.length==0)
				$field=$education.find("select[name*='"+prop+"']");
			if($field.length==0)
				$field=$education.find("textarea[name*='"+prop+"']");
			if($field.length==0){
				//window.location.reload();
				console.dir(prop);
			}else{
				if(!self.parent.validate($field,1))
					failed+=1;
			}
		};
		if(failed){
			if(!self.isEditing()){
				self.edit(self);
			}
		}
		return failed;
	}
	self.save=function(item,event){
		self.closeInfo();
		if(!self.isEditing())
			return;
		
		self.removeEmptyNewRecognitions();

		$target=$(event.target);
		var $education=$target.parents('div.education-group');
		var failed=self.validateFields($target);
		if(failed)
			return false;
		if(item.hasNoChanges()){
			self.isEditing(false);
			self.parent.isEditing(false);
			return;
		}

		if(!failed){
			var data;
			data={
				section:"education",
				type:self.type(),
				student_number:self.parent.student_number(),
				data:self.getMutatedMainFields(),
				recognitions: self.getRecognitions(),
				delete_recognitions:self.to_delete_recognitions
			};
			if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/save_education/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/save_education/";
			}
			$.ajax({
				url:baseurl,
				type:"POST",
				data:data,
				beforeSend:function(){
					$('body').append('<div class="div-loading"></div>');
				},
				success:function(response){
					if(response){

						response=JSON.parse(response);
						if(response['code']==404){
							//window.location.replace(response['url']);
							console.dir(response);
						}
						
						if(response['recognitions'].length>0){
							ko.utils.arrayForEach(self.recognitions(),function(item,index){
								if(!item.award_id()){
									item.award_id(response['recognitions'][index]);
								}
							});
						}
						
						$('div.div-loading').remove();
						$education.find('.alert-mask').removeClass(".alert-notify");
						$education.find('.alert-info').removeClass(".alert-notify");
						self.isEditing(false);
						self.parent.isEditing(false);
					}
					
				},
				error:function(x,t,h){
					
					$('div.div-loading').remove();
					setTimeout(function(){
						$education.find('.alert-mask').addClass("alert-notify");
						$education.find('.alert-error').addClass("alert-notify");
					},300);
					$education.find('.alert-mask').on('click',function(){
							$education.find('.alert-mask').removeClass('alert-notify');
							$education.find('.alert-error').removeClass('alert-notify');
						});
			
				},
				timeout:120000
			});
			
		}
		
	}
	self.closeInfo=function(){
		$('.alert-mask:visible').removeClass('alert-notify');
		$('.alert-info:visible').first().removeClass('alert-notify');
		$('.alert-error:visible').first().removeClass('alert-notify');
	}
	self.getRecognitions=function(){
		var data=[];
		ko.utils.arrayForEach(self.recognitions(),function(item){
			var str="";

			if(!(str===item.award()))
				data.push({award_id:item.award_id(),
					award:item.award()});
			else
				item.remove();
				

		});
		return data;
	}

	self.edit=function(item,event){
		self.isEditing(true);
		self.parent.isEditing(true);
		self.old_data=self.getMainFields();
		self.old_recognitions([]);
		self.to_delete_recognitions=[];
		ko.utils.arrayForEach(self.recognitions(),function(a){
			var b={};
			b.award_id=a.award_id();
			b.award=a.award();
			self.old_recognitions.push(new RecognitionModel(b,self));
		});

		if(event!=undefined){
			editor.scroll();
			editor.scrollTo($(event.target).parents('div.education-group'));
		
		}
		

	}
	self.cancel=function(item,event){
		self.closeInfo();
		self.isEditing(false);
		self.parent.isEditing(false);
		for(var prop in self.old_data){
			self[prop](self.old_data[prop]);
		}
		self.recognitions([]);
		ko.utils.arrayForEach(self.old_recognitions(),function(a){
			var b={};
			b.award_id=a.award_id;
			b.award=a.award;
			self.recognitions.push(new RecognitionModel(b,self));
		});
		self.to_delete_recognitions=[];
		self.old_recognitions([]);
		self.old_data=0;
		
	}
	ko.mapping.fromJS(data,mapping,self);
}

var RecognitionModel = function(data,parent){
	var self = this;
	self.parent=parent;
	self.award_id=ko.observable("");
	self.award=ko.observable("");
	self.remove=function(item){
		if(self.award_id())
			self.parent.to_delete_recognitions.push(self.award_id());
		item.parent.recognitions.remove(item);
	}
	
	ko.mapping.fromJS(data,mapping,self);
}
