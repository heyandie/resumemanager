var offsetToScroll = "";
var editor=$("div#grid-editor");
var rules = {
	"default": /^[ñA-Za-z0-9 \-\.']+$/i,
	"student_number": /^[0-9]{9}$/,
	"first_name": /^[ñA-Za-z0-9 \-\.']+$/i,
	"last_name": /^[ñA-Za-z0-9 \-\.']+$/i,
	"mobile_number": /^[0-9]{11}$/,
	"email_address": /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/,
	"address": /^(?!\s*$).+/,
	"year": /^[12][0-9]{3}$/

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
function checkoverflow(page){

	var currentpage = page;	
	var previouspage = currentpage;
	var $rows = $('#resume-page-'+currentpage+' .resume-row');
	console.dir(currentpage);
	var pageBottomOffset = $('#resume-page-'+currentpage).innerHeight() + $('#resume-page-'+currentpage).offset().top;
	for(var i=0; i<$rows.length; i++){
			
		var rowoffset = $rows.eq(i).offset().top + $rows.eq(i).outerHeight(true);
		if( rowoffset > pageBottomOffset){
			
			
			currentpage += 1;
			$('#resume-page-'+previouspage).after('<div id="resume-page-'+currentpage+'" class="a4-wrapper"></div>');
			$('#resume-page-'+currentpage).append('<div class="a4 grid-container"></div>');
			var $next = $rows.eq(i).nextAll();
			$('#resume-page-'+currentpage+' .grid-container').append($rows.eq(i));				
			$('#resume-page-'+currentpage+' .grid-container').append($next);
			break;
		}

	}
	if(currentpage>previouspage){
		console.dir('continue');
		checkoverflow(currentpage);
	}
		

}


var RecognitionsModel = function(data){
	var self=this;
	self.recognitions = ko.observableArray([]);
	self.type=ko.observable("");
	self.allowAdd = function(){
		return self.recognitions().length<=10 ? true : false;
	}

	self.add = function(){

		if(self.allowAdd()){
			self.recognitions.push(new RecognitionModel({award:ko.observable(""),award_id:0},self));
			$('[data-toggle="tooltip"]').tooltip();
		}
			
	}
	self.remove = function(item){
		
				$.ajax({
					url:"resume_editor/delete_recognition",
					method:"POST",
					data:{student_number:student_number,id:item.award_id(),type:self.type()},
					success:function(response){
					
						self.recognitions.remove(item);
					}
				});
				
	}
	ko.mapping.fromJS(data,mapping,self);
}
var RecognitionModel = function(data,parent){
	var self=this;
	self.parent=parent;
	self.remove = function(){
		self.parent.remove(self);
	}
	self.saveOnBlur=function(item,event){
		var $target=$(event.target);
		var type = item.parent['type']();
		var val=$target.val();
		var award_id=item.award_id();
		var data = {
			student_number:student_number,
			section:"recognitions",
			type:type,
			id:award_id,
			value:val.trim()
		}
		if(val.trim()==""){
			self.parent.remove(this);
			return;
		}
		if(award_id<0)
			return;
		$.ajax({
			url:"resume_editor/save_item",
			method:"POST",
			data:data,
			success:function(response){
				
				if(!response)
					return;
				response=JSON.parse(response);
				if(award_id==0){
					item.award_id(response['award_id']);
					
				}
				var $success_icon=$('<span class="fa fa-check success-check" style="display:none"></span>');
				$target.parent('div').append($success_icon)
				 .find('.success-check').fadeIn();
				setTimeout(function(){
					$target.parent().find('.success-check').fadeOut();
				},1500);
			}
		});
	}
	ko.mapping.fromJS(data,mapping,self);
}

var SubsetModel = function(data,parent){
	var self=this;
	self.parent=parent;
	self.schema;
	self.addSubset = function(){
		var subset=self.schema.subset;

		if(self[subset.name]().length<subset.max)
		{
			var data={};
			data[subset.id]=0;
			var item=subset.model(data,self);
			self[subset.name].push(item);
			if(item.setSchema!=undefined)
				item.setSchema();
			$('[data-toggle="tooltip"]').tooltip();
		}
		
		
	}	
	self.setSchema=function(){
		self.schema=self.parent.schema.subset;
	}
	return self;
	
}
var MainSetModel = function(data,parent){
	var self = this;
	self.parent=parent;
	self.alias={
		id:"",
		classifier:"",
		section:"",
		parent:"",
		parent_div:""
	}
	self.schema={
		id:"",
		subset:{
			name:"",
			id:"",
			data:{
			}
		}
	}
	self.getFields = function(){
		return self.getChanges();

	}
	self.getChanges = function(){
		var schema=self.schema.subset;
		var subset=self[schema.name]();
		var changed=0;
		var data={};
		data.data={}
		for(var prop in self.old_data){
			var a=self.old_data[prop];
			var b=self[prop]();
			var match= a===b;
			if(!match){
				changed+=1;
				data.data[prop]=b;
			}
		}
	
		function check(schema,subset,data){
			if(data['to_delete_'+schema.name]==undefined)
				data['to_delete_'+schema.name]=[];
			data[schema.name]={};
			ko.utils.arrayForEach(subset,function(x,index){
				var to_delete=x.parent['to_delete_'+schema.name];
				var to_delete_length=to_delete.length;
				if(to_delete_length>0){
					data['to_delete_'+schema.name]=$.merge(data['to_delete_'+schema.name],to_delete);
				}
				var item={};
				var a={};
				var length1=x.parent['old_'+schema.name]().length;
				var length2=subset.length;
				if(length1==length2){
					item[schema.id]=x[schema.id]();
					for (var prop in schema.data){
						var value=x[schema.data[prop]]();

						if(x[schema.id]()==x.parent['old_'+schema.name]()[index][schema.id]()){
							var oldvalue=x.parent['old_'+schema.name]()[index][schema.data[prop]]();
				
							var match=oldvalue===value;
							if(!match){
								a[schema.data[prop]]=value;
								changed+=1;
							}
						}else{
							a[schema.data[prop]]=value;
							changed+=1;
						}
						
					}
				}else{
					item[schema.id]=x[schema.id]();
					for (var prop in schema.data){
						var value=x[schema.data[prop]]();
						a[schema.data[prop]]=value;
						changed+=1;
					}
				}
				
				item.data=a;
				data[schema.name][index]=item;
				
				if(schema.subset!=undefined){
					var i=data[schema.name][index];
					check(schema.subset,subset[index][schema.subset.name](),i);
				}
				
				
			});
		}
		check(schema,subset,data);
		if(changed==0)
			return 0;
		else
			return data;
	}
	self.getMainFields = function(){
		var required ={};
		var maindata=self.schema.data;
		for(var prop in maindata){
			required[maindata[prop]]=self[maindata[prop]]();
		}
		return required;
	}
	self.getMutatedMainFields = function(){
		if(!self[self.schema.id]()){
			return self.getMainFields();
		}else{
			var data={};
			for(var prop in self.old_data){
				var a=self.old_data[prop];
				var b=self[prop]();
				var match= a===b;
				if(!match){
					data[prop]=b;
				}
			}
			return data;
		}
	}
	self.validateFields = function($target){

		var required=self.getMainFields();
		var $parent_el=$target.parents('div'+self.alias.classifier);
		var failed=0;
		for (var prop in required) {
			$field=$parent_el.find("input[name*='"+prop+"']");
			if($field.length==0)
				$field=$parent_el.find("select[name*='"+prop+"']");
			if($field.length==0)
				$field=$parent_el.find("textarea[name*='"+prop+"']");
			if($field.length==0){
				window.location.reload();
			}else{
				if(!self.parent.validate($field))
					failed+=1;
			}
		};
		var schema=self.schema.subset;
		var subset=self[schema.name]();
		function validate(schema,subset){

			ko.utils.arrayForEach(subset,function(x,index){
				if(schema.required){
					$target=$parent_el.find(schema.classifier+':eq('+index+')');
					for (var prop in schema.data){
					
						$field=$target.find("input[name*='"+schema.data[prop]+"']");
			
						if($field.length==0)
							$field=$target.find("select[name*='"+schema.data[prop]+"']");
						if($field.length==0)
							$field=$target.find("textarea[name*='"+schema.data[prop]+"']");
						if($field.length==0)
							window.location.reload();
						else{

							if(!self.parent.validate($field))
								failed+=1;
						}
					}
				}
				if(schema.subset!=undefined){
					validate(schema.subset,subset[index][schema.subset.name]());
				}
				
				
			});
		}
		validate(schema,subset);
		return failed;
	}
	self.setOldData=function(){
		self.old_data=self.getMainFields();
		var schema=self.schema.subset;
		var subset=self[schema.name]();
		function set(schema,subset){

			ko.utils.arrayForEach(subset,function(x,index){

				x.parent['old_'+schema.name].push(ko.mapping.fromJS(ko.toJS(x)));
				
				if(schema.subset!=undefined){
					set(schema.subset,subset[index][schema.subset.name]());
				}
				
				
			});
		}
		set(schema,subset);
	}
	self.save=function(item,event){
		self.closeInfo();
		if(!self.isEditing())
			return;

		
		self.removeEmptyNewSubsets();
		var get_fields=self.getFields();
		if(!get_fields&&self[self.alias.id]()){
			self.isEditing(false);
			self.parent.isEditing(false);
			return;
		}
		
		$target=$(event.target);
		var $parent_el=$target.parents('div'+self.alias.classifier);
		var failed=self.validateFields($target);
		var id_key=self.alias.id;
		if(!failed){
			var data={};
			data[id_key]=self[id_key]();
			data['section']=self.alias.section;
			data['student_number']=self.parent.student_number();
			
			jQuery.extend(data,get_fields);
			data['data']['student_number']=self.parent.student_number();
			console.dir(data);
	
			$.ajax({
				url:"resume_editor/save_"+self.alias.section,
				method:"POST",
				data:data,
				beforeSend:function(){
					$parent_el.append('<div class="div-loading"></div>');
				},
				success:function(response){
					if(response){
						console.dir(response);
						response=JSON.parse(response);
						if(response['code']==404){
							window.location.replace(response['url']);
						}
						if(!self.work_id()&&self.work_id!=undefined){
							self.work_id(response['work_id']);
						}
						if(response['from_date']!=undefined){
							self.from_date(response['from_date']);
							self.parent[self.alias.parent].sort(function (l, r) { return l.from_date() > r.from_date() ? 1 : -1 });
							self.parent[self.alias.parent].valueHasMutated();
						}
						if(response['work_descriptions'].length>0){
							ko.utils.arrayForEach(self.work_descriptions(),function(item,index){
								if(!item.work_description_id()){
									item.work_description_id(response['work_descriptions'][index]);
								}
							});
						}
						
					}
					
				},
				complete:function(){
					setTimeout(function(){
						$parent_el.find('div.div-loading').remove();
						self.isEditing(false);
						self.parent.isEditing(false);
					},700);
					
				}
			});
			
		}
		
	}

	self.remove=function(item,event){
		$target=$(event.target);
		self.closeInfo();
		var $parent_el=$target.parents('div'+self.alias.classifier);
		if(self[self.alias.id]()){
			var data={
					section:self.alias.section,
					student_number:self.parent.student_number()
				};
			data[self.alias.id]=self[self.alias.id]();
			$.ajax({
				url:"resume_editor/delete_"+self.alias.section,
				method:"POST",
				data:data,
				beforeSend:function(){
					$parent_el.append('<div class="div-loading"></div>');
				},
				success:function(response){
					if(response){
						response=JSON.parse(response);
						if(response['code']==1||response['code']==0){
							self.parent[self.alias.parent].remove(self);
							self.parent.isEditing(false);
						}else{
							window.location.replace(response['url']);
						}
					}
					
				},
				complete:function(){
					setTimeout(function(){
						$parent_el.find('div.div-loading').remove();
						self.isEditing(false);
						self.parent.isEditing(false);
					},800);
					
				}

			});
		}else{
			self.parent[self.alias.parent].remove(self);
			self.parent.isEditing(false);
		}
		
	}

	self.closeInfo=function(){
		var index=self.parent[self.alias.parent].indexOf(self);
		var $mask=$(self.alias.parent_div+' .alert-mask:eq('+index+')');
		var $alert=$(self.alias.parent_div+' .alert-info:eq('+index+')');
		$('.alert-mask:visible').removeClass('alert-notify');
		$('.alert-info:visible').first().removeClass('alert-notify');
	}
	self.edit=function(item,event){

		item.isEditing(true);
		item.parent.isEditing(true);
		var offsetToScroll = $(event.target).parents('div'+self.alias.classifier).offset().top;
		editor.scroll();
		editor.animate({scrollTop: offsetToScroll-200},"");

	}

	self.addSubset = function(){
		var subset=self.schema.subset;

		if(self[subset.name]().length<subset.max)
		{
			var data={};
			data[subset.id]=0;
			self[subset.name].push(subset.model(data,self));
			$('[data-toggle="tooltip"]').tooltip();
		}
		
		
	}	
	
	self.getSubsets=function(){
		var data=[];
		var subset=self.schema.subset;

		ko.utils.arrayForEach(self[subset.name](),function(x){
			var a="";
			var filled=0;
			var item={};
			for (var prop in subset.data){

				var b=x[subset.data[prop]]();
				var match= a===b;
				if(!match){
					filled+=1;
				}
				item[subset.data[prop]]=b;

			}
			if(filled){
				
				item[subset.id]=x[subset.id]();
				data.push(item);

			}else{
				x.remove();
			}
				
				

		});
		return data;
	}
	


}


var WorkExpModel = function(data,parent){

	var self = new MainSetModel(data,parent);
	self.parent=parent;
	self.alias={
		id:"work_id",
		classifier:".work-experience-group",
		section:"work_experience",
		parent:"workExp",
		parent_div:"#workexp"
	}

	self.schema={
		id:"work_id",
		data:{
			0:"company_name",
			1:"position",
			2:"company_location",
			3:"from_month",
			4:"from_year",
			5:"to_month",
			6:"to_year"

		},
		subset:{
			name:"work_descriptions",
			model:function(data,parent){
				return new WorkDescriptionModel(data,parent);
			},
			id:"work_description_id",
			data:{
				0:"work_description"
			},
			required:0,
			max:5,
			min:0,
			classifier:""
		}
	}

	self.work_id = ko.observable("");
	self.from_date=ko.observable("");
	self.from_month= ko.observable("");
	self.from_year= ko.observable("");
	self.to_month = ko.observable("");
	self.to_year= ko.observable("");
	self.position = ko.observable("");
	self.company_name = ko.observable("");
	self.company_location = ko.observable("");
	self.work_descriptions = ko.observableArray([]);
	self.old_work_descriptions = ko.observableArray([]);
	self.to_delete_work_descriptions =[];

	self.isEditing=ko.observable(false);
	self.old_data=0;


	
	self.removeEmptyNewSubsets=function(){
		ko.utils.arrayForEach(self.work_descriptions(),function(item){
			var str="";
			if(item.work_description_id()==0){
				if(str.match(item.work_description()))
					item.remove();
			}
				
		});
	}

	self.hasNoChanges=function(){
		for(var prop in self.old_data){
			var a=self.old_data[prop];
			var b=self[prop]();
			var match= a===b;
			if(!match){
				return false;
			}
		}
		if(!self.descriptionsHasNoChanges())
			return false;
		return true;
	}

	self.descriptionsHasNoChanges = function(){
		if(self.old_work_descriptions().length!=self.work_descriptions().length)
			return false;
		else{
			var bool=true;
			ko.utils.arrayForEach(self.work_descriptions(),function(item,index){
				var a=self.old_work_descriptions()[index].work_description();
				var b=item.work_description();
				var match = a===b;
				if(!match){
					bool=false;
					return false;
				}
					
			});
			return bool;
		}
	}
	self.allowAddWorkDescription = function(){
		if(self.work_description().length<7){
			return true;
		}
		return false;
	}
	
	self.cancel=function(item,event){
		item.closeInfo();
		if(item.work_id()==0){
			item.parent.workExp.remove(item);
			item.parent.isEditing(false);
			item.old_data=0;
			
		}else{
			
			for(var prop in item.old_data){

				item[prop](item.old_data[prop]);
			}
		
			item.work_descriptions(item.old_work_descriptions());
			item.isEditing(false);
			item.parent.isEditing(false);
		}
	}

	
	self.initialize = function(){
		self.setOldData();
	}
	
	ko.mapping.fromJS(data,mapping,self);
	return self;
}



var AffiliationModel = function(data,parent){
	var self = new MainSetModel();
	self.parent=parent;
	self.alias={
		id:"affiliation_id",
		classifier:".affiliation-group",
		section:"affiliation",
		parent:"affiliations",
		parent_div:"#extra-curricular"
	}

	self.schema={
		id:"affiliation_id",
		data:{
			0:"affiliation_name"

		},
		subset:{
			name:"committees",
			model:function(data,parent){
				return new CommitteeModel(data,parent);
			},
			id:"committee_id",
			data:{
				0:"committee_name",
				1:"from_month",
				2:"from_year",
				3:"to_month",
				4:"to_year"
			},
			subset:{
				name:"task_descriptions",
				model:function(data,parent){
					return new TaskDescriptionModel(data,parent);
				},
				id:"task_description_id",
				data:{
					0:"task_description"
				},
				required:0,
				max:8,
				classifier:""
			},
			required:1,
			max:5,
			classifier:".committee-group"
		},
		required:1,
		max:5,
		classifier:".affiliation-group"
	}

	self.affiliation_id = ko.observable("");
	self.affiliation_name=ko.observable("");
	self.from_month= ko.observable("");
	self.from_year= ko.observable("");
	self.to_month = ko.observable("");
	self.to_year= ko.observable("");
	self.position = ko.observable("");
	self.company_name = ko.observable("");
	self.company_location = ko.observable("");
	self.committees = ko.observableArray([]);
	self.old_committees = ko.observableArray([]);
	self.to_delete_committees = [];

	self.isEditing=ko.observable(false);
	self.old_data=0;

	self.initialize = function(){
		var item =new CommitteeModel({committee_id:0},self);
		item.setSchema();
		self.committees.push(item);
		self.setOldData();
	}
	
	
	self.removeEmptyNewSubsets=function(){
	
	}

	self.hasNoChanges=function(){

	}

	

	
	self.cancel=function(item,event){
		item.closeInfo();
		if(item[self.schema.id]()==0){
			item.parent[self.alias.parent].remove(item);
			item.parent.isEditing(false);
			item.old_data=0;
			
		}else{
			item.isEditing(false);
			item.parent.isEditing(false);
			for(var prop in item.old_data){

				item[prop](item.old_data[prop]);
			}
		
			var schema=self.schema.subset;
			var subset=self[schema.name]();
			function set(schema,subset){

				ko.utils.arrayForEach(subset,function(x,index){
					subset(x.parent['old_'+schema.name]());
					
					if(schema.subset!=undefined){
						set(schema.subset,subset[index][schema.subset.name]());
					}
					
					
				});
			}
			set(schema,subset);
		}
	}

	


	ko.mapping.fromJS(data,mapping,self);
	return self;
}

var CommitteeModel = function(data,parent){
	var self = new SubsetModel(data,parent);
	self.parent=parent;
	self.schema="";
	self.committee_id = ko.observable("");
	self.committee_name = ko.observable("Publicity Committee");
	self.from_date=ko.observable("");
	self.from_month = ko.observable("Apr");
	self.from_year= ko.observable("2014");
	self.to_month= ko.observable("May");
	self.to_year= ko.observable("2015");
	self.task_descriptions = ko.observableArray([]);
	self.to_delete_task_descriptions=[];
	self.old_task_descriptions=ko.observableArray([]);
	self.remove=function(){
		if(self.parent.committees().length>1){
			if(self.committee_id())
				self.parent.to_delete_committees.push(self.committee_id());
			self.parent.committees.remove(self);
		}
	}
	
	ko.mapping.fromJS(data,mapping,self);
	return self;

}

var WorkDescriptionModel = function(data,parent){
	var self = this;
	self.work_description_id=ko.observable("");
	self.parent=parent;
	self.work_description=ko.observable("");
	self.remove=function(){
		if(self.work_description_id())
			self.parent.to_delete_work_descriptions.push(self.work_description_id());
		self.parent.work_descriptions.remove(self);
	}
	
	ko.mapping.fromJS(data,mapping,self);
}

var TaskDescriptionModel = function(data,parent){
	var self = this;
	self.task_description_id=ko.observable("");
	self.parent=parent;
	self.task_description=ko.observable("");
	self.remove=function(){
		if(self.task_description_id())
			self.parent.to_delete_task_descriptions.push(self.task_description_id());
		self.parent.task_descriptions.remove(self);
	}
	ko.mapping.fromJS(data,mapping,self);
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
	},
	'task_descriptions':{
		create:function(options){
			return new TaskDescriptionModel(options.data,options.parent);
		}
	}

}



var ResumeManagerModel = function(){

	var self = this;

	self.months = [
		"Jan","Feb","Mar","Apr",
		"May","Jun","Jul","Aug","Sep",
		"Oct","Nov","Dec"];

	self.student_number=ko.observable(student_number);
	self.personal_info = ko.mapping.fromJS(personal_info);

	self.fullname = ko.computed(function(){
		return self.personal_info.first_name() + " " + self.personal_info.last_name();
	});

	self.availableStudentType = ko.observableArray(
		["Undergraduate","Graduate"]

		);

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

	self.student_type = ko.observable(student_type);
	self.education_graduate = ko.mapping.fromJS(education_graduate);
	self.education_undergraduate = ko.mapping.fromJS(education_undergraduate);
	self.education_highschool = ko.mapping.fromJS(education_highschool);

	self.graduate_recognitions = new RecognitionsModel(graduate_recognitions);
	self.undergraduate_recognitions = new RecognitionsModel(undergraduate_recognitions);
	self.highschool_recognitions = new RecognitionsModel(highschool_recognitions);
	
    ko.mapping.fromJS(self.graduate_recognitions,mapping);
    ko.mapping.fromJS(self.undergraduate_recognitions,mapping);
    ko.mapping.fromJS(self.highschool_recognitions,mapping);
	
    var mapped_work_experience=ko.utils.arrayMap(work_experience,function(data){
		return new WorkExpModel(data,self);});
	self.workExp =ko.observableArray(mapped_work_experience);
	self.affiliations = ko.observableArray([]);

	
	self.hasPersonalInfo = function(){
		
		for(var prop in self.personal_info){

			if(rules[prop]!=undefined){
				if(!self.personal_info[prop]().match(rules[prop])){
					return false;
				}
					
			}
		}
		return true;

	}

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

	self.isEditing=ko.observable(false);
	self.addWorkExp = function(){
		var count = self.workExp().length;
		if(count<10&&!self.isEditing()){
			var item=new WorkExpModel({work_id:0,isEditing:true},self);
			item.initialize();
			self.workExp.push(item);
			self.isEditing(true);
			$('[data-toggle="tooltip"]').tooltip();
			var offsetToScroll = $('div.work-experience-group').last().offset().top;
			editor.scroll();
			editor.animate({scrollTop: offsetToScroll-200},"");
		}
			
	}

	self.addAffiliation = function(){
		var count = self.affiliations().length;
		if(count<10&&!self.isEditing()){
			var aff=new AffiliationModel({affiliation_id:0,isEditing:true},self);
			aff.initialize();
			self.affiliations.push(aff);
			self.isEditing(true);
			var offsetToScroll = $('div.affiliation-group').last().offset().top;
			editor.scroll();
			editor.animate({scrollTop: offsetToScroll-200},"");

		}
			
	}
	self.addAffiliationTitle = ko.pureComputed(function() {
        return self.isEditing() ? "Disabled on editing mode." : "Create new affiliation";
    }, self);

	self.isUndergraduate = function(){
		return (self.student_type()=="Undergraduate") ? true : false;
	}

	self.isPersonalInfo = ko.observable(false);
	self.isEducation = ko.observable(false);
	self.isWorkExp = ko.observable(false);
	self.isExtraCurr = ko.observable(true);
	self.isSubmit = ko.observable(false);
	self.isPreview = ko.observable(true);
	self.isConfirmed = ko.observable(false);

	self.showPersonalInfo = function(){
		if(self.isPersonalInfo())
			return;

		if(self.validateAll()){
			self.isPersonalInfo(true);
			self.isEducation(false);
			self.isWorkExp(false);
			self.isExtraCurr(false);
			self.isSubmit(false);
			$(window).scrollTop(0);
		}
	}

	self.showEducation = function(){
		if(self.isEducation())
			return;
		if(self.validateAll()){
			self.isPersonalInfo(false);
			self.isEducation(true);
			self.isWorkExp(false);
			self.isExtraCurr(false);
			self.isSubmit(false);
			$(window).scrollTop(0);
		}
	}

	self.showWorkExp = function(){
		if(self.isWorkExp())
			return;
		if(self.validateAll()){
			self.isPersonalInfo(false);
			self.isEducation(false);
			self.isWorkExp(true);
			self.isExtraCurr(false);
			self.isSubmit(false);
			$(window).scrollTop(0);
		}
	}

	self.showExtraCurr = function(){
		if(self.isExtraCurr())
			return;
		if(self.validateAll()){
			self.isPersonalInfo(false);
			self.isEducation(false);
			self.isWorkExp(false);
			self.isExtraCurr(true);
			self.isSubmit(false);
			$(window).scrollTop(0);
		}
	}

	self.submitResume = function(){

		if(self.validateAll()){
			self.isPersonalInfo(false);
			self.isEducation(false);
			self.isWorkExp(false);
			self.isExtraCurr(false);
			self.isSubmit(true);
			$(window).scrollTop(0);
		}
		
	}

	self.isPersonalInfoValid = function(){

		for(var prop in self.personal_info){

			if(rules[prop]!=undefined){
				if(!self.personal_info[prop]().match(rules[prop])){
					return false;
				}
					
			}
		}
		return true;
	}

	self.isEducBackgroundValid = function(){

		for(var prop in self.education.college){

			if(prop=="is_graduating"||prop=="recognitions")
				continue;
			
			if(prop=="graduation_date"){
				if(self.education.college[prop].month()=="")
					return false;
				if(self.education.college[prop].year()=="")
					return false;
				continue;
			}

			if(self.education.college[prop]()==""||self.education.college[prop]()==undefined){
				return false;
			}
		}

		for(var prop in self.education.highschool){
			if(prop=="recognitions")
				continue;

			if(self.education.highschool[prop]()==""||self.education.highschool[prop]()==undefined){
				return false;
			}
		}

		return true;
	}

	

	

	self.validateOnBlur = function(item,event){
		var $target = $(event.target);
		
		if(self.validate($target)){
			var field_name = $target.attr("name");
			var value = $target.val().trim();
			var data="";
			var valid=0;
			if(self.isPersonalInfo()){
				
				data={
							student_number:self.student_number(),
							section:'personal_info',
							field_name: field_name,
							value: value.trim()
						}
			
				if(value.match(self.personal_info[field_name]())){
					self.personal_info[field_name](value);
					valid=1;
				}
					

					
			}else if(self.isEducation()){
				if(field_name.match("student_type")){
					value=self.student_type();
					valid=1;

					if(value.match("Undergraduate")){
						value=1;
					}
					else
						value=2;
					data={
						student_number:self.student_number(),
						section:'personal_info',
						field_name:field_name,
						value:value
					}
				}else if(field_name.indexOf("undergraduate:")>-1){
					field_name=field_name.replace('undergraduate:','');
					if(value.match(self.education_undergraduate[field_name]()))
					valid=1;
					
					data={
							student_number:self.student_number(),
							section:'undergraduate_education',
							field_name: field_name,
							value: value
						}
				}else if(field_name.indexOf("highschool:")>-1){
					field_name=field_name.replace('highschool:','');
					if(value.match(self.education_highschool[field_name]()))
					valid=1;
					
					data={
							student_number:self.student_number(),
							section:'highschool_education',
							field_name: field_name,
							value: value
						}
				}
			}

			if(valid){
				$.ajax({
						type: "POST",
						url: "resume_editor/save_item",
						data: data,
					
						success:function(response){

							if(!response)
								return;
							response=JSON.parse(response);
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
		
		$('div#personal-information').find('input,textarea').each(
			function(){

				if(!self.validate($(this)))
					failed += 1;
			});
		
		if(failed){

			self.showPersonalInfo();
			setTimeout(function(){
				self.validateAll();
			},100);
			return false;
		}

		failed=0;
		$('div#education').find('input,textarea,select').each(
			function(){
				if(!self.validate($(this)))
					failed += 1;
			});
	
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
		if(self.isPersonalInfo()){
			$('div#personal-information').find('input,textarea').each(
				function(){

					if(!self.validate($(this)))
						failed += 1;
				});
		}

		else if(self.isEducation()){
			$('div#education').find('input,textarea,select').each(
				function(){
					if(!self.validate($(this)))
						failed += 1;
				});
		}

		

		if(failed){
			var offsetToScroll = $('.danger').first().offset().top;
			editor.scroll();
			editor.animate({scrollTop: offsetToScroll-300},"");
		}else{
			if(self.isWorkExp()){
			
				ko.utils.arrayForEach(self.workExp(),function(item,index){
				
					if(item.isEditing()){
						item.removeEmptyNewSubsets();
						if(!item.getChanges())
						{
							item.cancel(item);
							return true;
						}
						var $mask=$('#workexp .alert-mask:eq('+index+')');
						var $alert=$('#workexp .alert-info:eq('+index+')');
						$mask.on('click',function(){
							$mask.removeClass('alert-notify');
							$alert.removeClass('alert-notify');
						});
						var offsetToScroll = $alert.offset().top;
						editor.scroll();
						editor.animate({scrollTop: offsetToScroll-200},"");
						failed+=1;
						setTimeout(function(){
							$mask.addClass('alert-notify');
							$alert.addClass('alert-notify');
						},100);
						setTimeout(function(){
							$mask.removeClass('alert-notify');
							$alert.removeClass('alert-notify');
						},4200);
					}
				});
				

			}else if(self.isExtraCurr()){
				ko.utils.arrayForEach(self.affiliations(),function(item,index){
				
					if(item.isEditing()){
						
						var $mask=$('#extra-curricular .alert-mask:eq('+index+')');
						var $alert=$('#extra-curricular .alert-info:eq('+index+')');
						$mask.on('click',function(){
							$mask.removeClass('alert-notify');
							$alert.removeClass('alert-notify');
						});
						var offsetToScroll = $alert.offset().top;
						editor.scroll();
						editor.animate({scrollTop: offsetToScroll-200},"");
						failed+=1;
						setTimeout(function(){
							$mask.addClass('alert-notify');
							$alert.addClass('alert-notify');
						},100);
						setTimeout(function(){
							$mask.removeClass('alert-notify');
							$alert.removeClass('alert-notify');
						},4200);
					}
				});
			}
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

	
	self.confirmSubmit = function(){
		checkoverflow(1);
		function hiddenClone(element){
		  // Create clone of element
		  var clone = element.cloneNode(true);

		  // Position element relatively within the 
		  // body but still out of the viewport
		  var style = clone.style;
		  style.position = 'relative';
		  style.top = window.innerHeight + 'px';
		  style.left = 0;

		  // Append clone to body and return the clone
		  document.body.appendChild(clone);
		  return clone;
		}

		var offScreen = document.querySelector('#resume-page-1 .a4');

		// Clone off-screen element
		var clone = hiddenClone(offScreen);


		// Use clone with htm2canvas and delete clone
		html2canvas(clone, {
		    onrendered: function(canvas) {
		    	var ctx = canvas.getContext("2d");
		    	ctx.webkitImageSmoothingEnabled = false;
				ctx.mozImageSmoothingEnabled = false;
				ctx.imageSmoothingEnabled = false;
		      	var myImage = canvas.toDataURL();
				window.open(myImage);
		      	document.body.removeChild(clone);
		    }
		});


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
	        ko.unwrap(value) ? $(element).delay(100).fadeIn(500) : $(element).fadeOut(100);
	    }
	};


}





var RecognitionsModel = function(data){
	var self=this;
	self.recognitions = ko.observableArray([]);
	self.type=ko.observable("");
	self.allowAdd = function(){
		return self.recognitions().length<=10 ? true : false;
	}

	self.add = function(){

		if(self.allowAdd()){
			self.recognitions.push(new RecognitionModel({award:ko.observable(""),award_id:0},self));
			$('[data-toggle="tooltip"]').tooltip();
		}
			
	}
	self.remove = function(item){
		
				$.ajax({
					url:"resume_editor/delete_recognition",
					method:"POST",
					data:{student_number:student_number,id:item.award_id(),type:self.type()},
					success:function(response){
					
						self.recognitions.remove(item);
					}
				});
				
	}
	ko.mapping.fromJS(data,mapping,self);
}
var RecognitionModel = function(data,parent){
	var self=this;
	self.parent=parent;
	self.remove = function(){
		self.parent.remove(self);
	}
	self.saveOnBlur=function(item,event){
		var $target=$(event.target);
		var type = item.parent['type']();
		var val=$target.val();
		var award_id=item.award_id();
		var data = {
			student_number:student_number,
			section:"recognitions",
			type:type,
			id:award_id,
			value:val.trim()
		}
		if(val.trim()==""){
			self.parent.remove(this);
			return;
		}
		if(award_id<0)
			return;
		$.ajax({
			url:"resume_editor/save_item",
			method:"POST",
			data:data,
			success:function(response){
				
				if(!response)
					return;
				response=JSON.parse(response);
				if(award_id==0){
					item.award_id(response['award_id']);
					
				}
				var $success_icon=$('<span class="fa fa-check success-check" style="display:none"></span>');
				$target.parent('div').append($success_icon)
				 .find('.success-check').fadeIn(400);
				setTimeout(function(){
					$target.parent().find('.success-check').fadeOut();
				},1000);
			}
		});
	}
	ko.mapping.fromJS(data,mapping,self);
}

var prop={
					0:{
						prefix:'undergraduate:',
						section:"undergraduate_education",
						alias:"education_undergraduate"
					},
					1:{
						prefix:'highschool:',
						section:"highschool_education",
						alias:"education_highschool"
					},
					2:{
						prefix:'graduate:',
						section:"graduate_education",
						alias:"education_graduate"
					}
				};
				for(var i in prop){
					if(field_name.indexOf(prop[i].prefix)>-1){
						field_name=field_name.replace(prop[i].prefix,'');

						if(value.match(self[prop[i].alias][field_name]()))
						valid=1;
						
						data={
								student_number:self.student_number(),
								section:prop[i].section,
								field_name: field_name,
								value: value
							}
					}
				}

				function checkoverflow(page){

	var currentpage = page;	
	var previouspage = currentpage;
	var $rows = $('#resume-page-'+currentpage+' .resume-row');
	console.dir(currentpage);
	var pageBottomOffset = $('#resume-page-'+currentpage).innerHeight() + $('#resume-page-'+currentpage).offset().top;
	for(var i=0; i<$rows.length; i++){
			
		var rowoffset = $rows.eq(i).offset().top + $rows.eq(i).outerHeight(true);
		if( rowoffset > pageBottomOffset){
			
			
			currentpage += 1;
			$('#resume-page-'+previouspage).after('<div id="resume-page-'+currentpage+'" class="a4-wrapper"></div>');
			$('#resume-page-'+currentpage).append('<div class="a4 grid-container"></div>');
			var $next = $rows.eq(i).nextAll();
			$('#resume-page-'+currentpage+' .grid-container').append($rows.eq(i));				
			$('#resume-page-'+currentpage+' .grid-container').append($next);
			break;
		}

	}
	if(currentpage>previouspage){
		console.dir('continue');
		checkoverflow(currentpage);
	}
		

}<script type="text/javascript" src="<?php echo base_url();?>js/personal-infomodel.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/educationmodel.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/affiliationmodel.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/workmodel.js"></script>-->