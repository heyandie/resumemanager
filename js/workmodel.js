var WorkExpModel = function(data,parent){

	var self = this;
	var environ = window.location.host;
	self.parent=parent;
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
	self.old_work_descriptions = [];
	self.to_delete_work_descriptions = ko.observableArray([]);

	self.isEditing=ko.observable(false);
	self.old_data=0;
	self.isEmpty=function(item){
		
		var notempty=0;
		if(self.work_id()==0){
			var str="";
			if(!str.match(self.from_month()))
				notempty+=1;
			if(!str.match(self.from_year()))
				notempty+=1;
			if(!str.match(self.to_month()))
				notempty+=1;
			if(!str.match(self.to_year()))
				notempty+=1;
			if(!str.match(self.company_name()))
				notempty+=1;
			if(!str.match(self.company_location()))
				notempty+=1;
			if(!str.match(self.position()))
				notempty+=1;
			ko.utils.arrayForEach(self.work_descriptions(),function(x){
				if(!str.match(x.work_description())){
					notempty+=1;
				}
			});
			return notempty==0;
		}
		return false;
	}

	self.hasNoChanges=function(){
		for(var prop in self.old_data){
			var a=self.old_data[prop];
			var b=self[prop]();
			var match= a===b;
			if(!match)
				return false;
			
		}
		if(self.old_work_descriptions.length!=self.work_descriptions().length)
			return false;
		else{
			var bool=true;
			ko.utils.arrayForEach(self.work_descriptions(),function(item,index){
				var str=self.old_work_descriptions[index].work_description;
				var match=str===item.work_description();
				if(!match){
					bool=false;
					return false;
				}
					
			});
			return bool;
		}
		return true;
	}

	self.allowAddWorkDescription = function(){
		if(self.work_description().length<7){
			return true;
		}
		return false;
	}
	self.addWorkDescription = function(){
		self.work_descriptions.push(new WorkDescriptionModel({work_description_id:0},self));
		$('[data-toggle="tooltip"]').tooltip();
		
	}	
	self.remove=function(item,event){
		$target=$(event.target);
		self.closeInfo();
		var $work=$target.parents('div.work-experience-group');
		if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/delete_work_experience/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/delete_work_experience/";
			}
		if(self.work_id()){
			$.ajax({
				url:baseurl,
				type:"POST",
				data:{
					section:"work_experience",
					student_number:self.parent.student_number(),
					work_id:self.work_id()
				},
				beforeSend:function(){
					$('body').append('<div class="div-loading"></div>');
				},
				success:function(response){
					if(response){
						response=JSON.parse(response);
						if(response['code']==1||response['code']==0){
							self.parent.workExp.remove(self);
							self.parent.isEditing(false);
						}else{
							window.location.replace(response['url']);
						}
					}
					
				},
				complete:function(){
					setTimeout(function(){
						$('div.div-loading').remove();
						self.isEditing(false);
						self.parent.isEditing(false);
					},800);
					
				}

			});
		}else{
			self.parent.workExp.remove(self);
			self.parent.isEditing(false);
		}
		
	}
	
	self.getMainFields = function(){
		var maindata={
			
			from_month:self.from_month(),
			from_year:self.from_year(),
			to_month:self.to_month(),
			to_year:self.to_year(),
			company_name:self.company_name(),
			position:self.position(),
			company_location:self.company_location()
		}
		
		return maindata;
	}
	self.getMutatedMainFields = function(){
		if(!self.work_id()){
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
	self.getWorkDescriptions=function(){
		var data=[];
		ko.utils.arrayForEach(self.work_descriptions(),function(item){
			var str="";
			if(item!==undefined)
			if(!(str===item.work_description()))
				data.push({work_description_id:item.work_description_id(),
					work_description:item.work_description()});
			else
				item.remove();
				

		});
		return data;
	}
	self.getToDeleteWorkDescriptions=function(){
		var data=[];
		ko.utils.arrayForEach(self.to_delete_work_descriptions(),function(item){
			
			data.push({work_description_id:item.work_description_id()});
		});
		return data;
	}
	self.save=function(item,event){
		self.closeInfo();
		if(!self.isEditing())
			return;
		var work_descriptions=[];
		var delete_work_descriptions=self.getToDeleteWorkDescriptions();
		if(item.hasNoChanges()&&item.work_id()){
			self.isEditing(false);
			self.parent.isEditing(false);
			return;
		}

		$target=$(event.target);
		var required={from_month:self.from_month(),
					from_year:self.from_year(),
					to_month:self.to_month(),
					to_year:self.to_year(),
					company_name:self.company_name(),
					position:self.position(),
					company_location:self.company_location()}
		var $work=$target.parents('div.work-experience-group');
		var failed=0;
		for (var prop in required) {
			$field=$work.find("input[name*='"+prop+"']");
			if($field.length==0)
				$field=$work.find("select[name*='"+prop+"']");
			if($field.length==0)
				$field=$work.find("textarea[name*='"+prop+"']");
			if($field.length==0){
				window.location.reload();
			}else{
				if(!self.parent.validate($field,1))
					failed+=1;
			}
		};
		$work.find("input[name*='work_description']").each(function(){
			if(!self.parent.validate($(this),1))
					failed+=1;
			
		})
		if(!failed){
			var data;
			data={
				section:"work_experience",
				work_id:self.work_id(),
				student_number:self.parent.student_number(),
				data:jQuery.extend({student_number:self.parent.student_number()},self.getMutatedMainFields()),
				work_descriptions:self.getWorkDescriptions(),
				delete_work_descriptions:delete_work_descriptions
				
			};
			if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/save_work_experience/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/save_work_experience/";
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
							window.location.replace(response['url']);
						}
						if(!self.work_id()){
							self.work_id(response['work_id']);
						}
						if(response['from_date']!=undefined){
							self.from_date(response['from_date']);
						}
						if(response['work_descriptions'].length>0){
							ko.utils.arrayForEach(self.work_descriptions(),function(item,index){
								if(!item.work_description_id()){
									item.work_description_id(response['work_descriptions'][index]);
								}
							});
						}
						self.parent.workExp().sort(function (l, r) { 

							if(l.from_date() !=r.from_date() )
								return l.from_date() < r.from_date() ? 1 : -1;
							else
								return l.work_id() > r.work_id() ? 1 : -1;
							 }

							);
						self.parent.workExp.valueHasMutated();

						
						$('div.div-loading').remove();
						$work.find('.alert-mask').removeClass(".alert-notify");
						$work.find('.alert-info').removeClass(".alert-notify");
						self.isEditing(false);
						self.parent.isEditing(false);
					}
					
				},
				error:function(x,t,h){
				
					$work.find('div.div-loading').remove();
					$mask=$work.find('.alert-mask');
					$alert=$work.find('.alert-error');
			
					editor.scroll();
					editor.scrollTo($alert);
					setTimeout(function(){
						$mask.addClass("alert-notify");
						$alert.addClass("alert-notify");
					},300);
					$work.find('.alert-mask').on('click',function(){
							$work.find('.alert-mask').removeClass('alert-notify');
							$work.find('.alert-error').removeClass('alert-notify');
						});
					
				}
			});
			
		}
		
	}
	self.closeInfo=function(){
		var index=self.parent.workExp.indexOf(self);
		var $mask=$('#workexp .alert-mask:eq('+index+')');
		var $alert=$('#workexp .alert-info:eq('+index+')');
		$('.alert-mask:visible').removeClass('alert-notify');
		$('.alert-info:visible').first().removeClass('alert-notify');
		$('.alert-error:visible').first().removeClass('alert-notify');
	}
	

	self.edit=function(item,event){
		self.isEditing(true);
		self.parent.isEditing(true);
		self.old_data=self.getMainFields();
		self.old_work_descriptions=[];
		ko.utils.arrayForEach(self.work_descriptions(),function(a){
			var b={};
			b.work_description_id=a.work_description_id();
			b.work_description=a.work_description();
			self.old_work_descriptions.push(b);
		});

		if(event!=undefined){
			editor.scroll();
			editor.scrollTo($(event.target).parents('div.work-experience-group'));
		}
		

	}
	self.cancel=function(item,event){
		item.closeInfo();
		if(item.work_id()==0){
			item.parent.workExp.remove(item);
			item.parent.isEditing(false);
			item.old_data=0;
			
		}else{
			item.isEditing(false);
			item.parent.isEditing(false);
			for(var prop in item.old_data){

				item[prop](item.old_data[prop]);
			}
			self.work_descriptions([]);
			for(var i=0;i<self.old_work_descriptions.length;i+=1){
				var b={};
				b.work_description_id=self.old_work_descriptions[i].work_description_id;
				b.work_description=self.old_work_descriptions[i].work_description;
				self.work_descriptions.push(new WorkDescriptionModel(b,self));
			}
		
			self.to_delete_work_descriptions([]);
			self.old_work_descriptions=[];
			self.old_data=0;
			}
	}
	ko.mapping.fromJS(data,mapping,self);
}

var WorkDescriptionModel = function(data,parent){
	var self = this;
	self.work_description_id=ko.observable("");
	self.parent=parent;
	self.work_description=ko.observable("");
	self.remove=function(){
		if(self.work_description_id())
			self.parent.to_delete_work_descriptions.push(self);
		self.parent.work_descriptions.remove(self);
	}
	
	ko.mapping.fromJS(data,mapping,self);
}
