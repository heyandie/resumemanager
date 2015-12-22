var AffiliationModel = function(data,parent){
	
	var environ = window.location.host;
	var self = this;
	self.parent=parent;
	self.affiliation_id = ko.observable("");
	self.affiliation_name = ko.observable("");
	self.committees = ko.observableArray([new CommitteeModel({committee_id:0},self)]);
	self.isEditing=ko.observable("");
	self.old_data=[];
	self.old_committees=[];
	self.to_delete_committees=[];
	self.row_span=ko.computed(function(){
		return self.committees().length+1;
	},self);
	
	self.removeCommittee = function(item){
		var count = self.committees().length;
		if(count>1){
			self.committees.remove(item);
		}
	}
	self.removeEmptyNewCommittees = function(event){
		
		ko.utils.arrayForEach(self.committees(),function(x,index){
		var y=ko.toJS(x);
		var required={
				committee_name:y.committee_name,
				from_month:y.from_month,
				from_year:y.from_year,
				to_month:y.to_month,
				to_year:y.to_year,
				task_description:y.task_description
			}
			var filled=0;
			for(var prop in required){
				var a="";
				var b=required[prop];
				var match = a===b || b==undefined;
				if(!match){
			
					filled+=1;
				}
			}
			if(!filled&&self.committees().length>1){
				if(!x.committee_id()){
					self.committees.remove(x);
				}
			}
		});
	}
	
	self.addCommittee = function(item,event){
		var count = self.committees().length;
		if(count<10){
			self.committees.push(new CommitteeModel({committee_id:0},self));
			$target=$(event.target);
			$aff=$target.parents('.affiliation-group');
			$committee=$aff.find('.committee-group').last();
			var offsetToScroll=editor[0].scrollHeight-$committee.offset().top+$committee.height();
			editor.scroll();
			editor.scrollTo($committee);
		}
			
	}
	self.save = function(item,event){
		if(!self.isEditing())
			return;
		if(self.hasNoChanges()){
			self.isEditing(false);
			self.parent.isEditing(false);
			return;
		}
		$target=$(event.target);
		var $affiliation=$target.parents('div.affiliation-group');
		var failed=0;
		var required={affiliation_name:item.affiliation_name()}
		for (var prop in required) {
			$field=$affiliation.find("input[name*='"+prop+"']");
			if($field.length==0)
				$field=$affiliation.find("select[name*='"+prop+"']");
			if($field.length==0)
				$field=$affiliation.find("textarea[name*='"+prop+"']");
			if($field.length==0){
				window.location.reload();
			}else{
				if(!self.parent.validate($field,1))
					failed+=1;
			}
		};
		
		ko.utils.arrayForEach(self.committees(),function(committee,index){
			failed += committee.validate($affiliation,index);
		});

		if(!failed){
			var data;
			if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/save_affiliation/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/save_affiliation/";
			}
			data={
				section:"affiliation",
				affiliation_id:self.affiliation_id(),
				student_number:self.parent.student_number(),
				data:{
					affiliation_name:self.affiliation_name(),
					order:self.order(),
					student_number:self.parent.student_number()
				},
				committees: self.getCommittees(),
				delete_committees:self.to_delete_committees
			};
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
						if(!self.affiliation_id()){
							self.affiliation_id(response['affiliation_id']);
						}
						if(response['from_date']!=undefined){
							self.from_date(response['from_date']);
						}

						if(response['committees']!=undefined)
							if(response['committees'].length>0){
								ko.utils.arrayForEach(self.committees(),function(item,index){
									if(!item.committee_id()){
										item.committee_id(response['committees'][index]['committee_id']);
										item.from_date(response['committees'][index]['from_date'])
									}
									var from_date=response['committees'][index]['from_date'];
									if(from_date!=undefined&&from_date!=""){
										item.from_date(from_date);
									}
								});
							}
						self.committees().sort(function (l, r) { 
							if(l.from_date() !=r.from_date() )
								return l.from_date() < r.from_date() ? 1 : -1;
							else
								return l.committee_id() > r.committee_id() ? 1 : -1;
							 }

							);
						self.committees.valueHasMutated();
						$('div.div-loading').remove();
						$affiliation.find('.alert-mask').removeClass(".alert-notify");
						$affiliation.find('.alert-info').removeClass(".alert-notify");
						self.isEditing(false);
						self.parent.isEditing(false);
					}
					
				},
				error:function(x,t,h){
		
					$('div.div-loading').remove();
					$mask=$affiliation.find('.alert-mask');
					$alert=$affiliation.find('.alert-error');
					editor.scroll();
					editor.scrollTo($affiliation.find('.work-actions'));
					setTimeout(function(){
						$affiliation.find('.alert-mask').addClass("alert-notify");
						$affiliation.find('.alert-error').addClass("alert-notify");
					},300);
					$affiliation.find('.alert-mask').on('click',function(){
							$affiliation.find('.alert-mask').removeClass('alert-notify');
							$affiliation.find('.alert-error').removeClass('alert-notify');
						});
					
				},
				timeout:120000
			});
			
		}
	}
	self.remove = function(item,event){
		$target=$(event.target);
		var $affiliation=$target.parents('div.affiliation-group');
		var data;
		if (environ === "localhost") {
		    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/delete_affiliation/";
		} else {
		    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/delete_affiliation/";
		}
		if(self.affiliation_id()){
			$.ajax({
				url:baseurl,
				type:"POST",
				data:{
					section:"affiliation",
					student_number:self.parent.student_number(),
					affiliation_id:self.affiliation_id()
				},
				beforeSend:function(){
					$('body').append('<div class="div-loading"></div>');
				},
				success:function(response){
					if(response){
						response=JSON.parse(response);
						if(response['code']==1||response['code']==0){
							self.parent.affiliations.remove(self);
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
			self.parent.affiliations.remove(self);
			self.parent.isEditing(false);
		}
	}
	self.edit=function(item,event){
		self.isEditing(true);
		self.parent.isEditing(true);
		self.old_data=[];
		self.to_delete_committees=[];
		self.old_data['affiliation_name']=self.affiliation_name();
		self.old_committees=[];
		ko.utils.arrayForEach(self.committees(),function(a){
			var b=a.getMainFields();
			b.committee_id=a.committee_id();
			self.old_committees.push(b);
			
		});

		if(event!=undefined){
			var offsetToScroll = $(event.target).parents('div.affiliation-group').offset().top;
			editor.scroll();
			editor.animate({scrollTop: offsetToScroll-200},"");
		}

	}
	self.cancel=function(item,event){

		if(item.affiliation_id()==0){
			item.parent.affiliations.remove(item);
			item.parent.isEditing(false);
			item.old_data=0;
			
		}else{
			item.isEditing(false);
			item.parent.isEditing(false);
			for(var prop in item.old_data){

				item[prop](item.old_data[prop]);
			}
			self.to_delete_committees=[];
			self.committees([]);
			for(var i=0;i<self.old_committees.length;i+=1){
				var b=self.old_committees[i];
				self.committees.push(new CommitteeModel(b,self));
			}
		}
	}

	self.getCommittees=function(){
		var committees=[];
		ko.utils.arrayForEach(self.committees(),function(item){
			var data={};
			var committee={};
			committee['committee_id']=item.committee_id();
			data['committee_name']=item.committee_name();
			data['from_month']=item.from_month();
			data['from_year']=item.from_year();
			data['to_month']=item.to_present() ? "" :item.to_month();
			data['to_year']=item.to_present() ? "" :item.to_year();
			data['to_present']=item.to_present() ? 1 : 0;
			data['task_description']=item.task_description();
			committee['data']=data;
			committees.push(committee);

				

		});
		return committees;
	}
	self.hasNoChanges=function(){

		if(self.affiliation_id()==0){
			var notempty=0;
			var required={affiliation_name:self.affiliation_name()}
			for (var prop in required) {
				var str="";
				if(!str.match(self[prop]()))
					notempty++;
			}
			ko.utils.arrayForEach(self.committees(),function(committee){
				var required={
					committee_name:committee.committee_name(),
					from_month:committee.from_month(),
					from_year:committee.from_year(),
					to_month:committee.to_month(),
					to_year:committee.to_year(),
					to_present:committee.to_present(),
					task_description:committee.task_description()

				}
				for (var prop in required) {
					var str="";
					var b=required[prop];
					var match=str===b || undefined===b;
					if(!match){
						console.log(prop);
						if(!(prop==="to_present")){
							notempty++;
						}else{
							if(b)
								notempty+=1;
						}
							
					}
						
				}
			});
			return notempty==0;
		}

		for(var prop in self.old_data){
			var a=self.old_data[prop];
			var b=self[prop]();
			var match=a===b;
			if(!match)
				return false;
			
		}
		
		if(self.old_committees.length!=self.committees().length)
			return false;
		else{
			var bool=true;
			ko.utils.arrayForEach(self.committees(),function(committee,index){
				var required={
					committee_name:committee.committee_name(),
					from_month:committee.from_month(),
					from_year:committee.from_year(),
					to_month:committee.to_month(),
					to_year:committee.to_year(),
					to_present:committee.to_present(),
					task_description:committee.task_description()

				}
				for(var prop in required){
					var a=required[prop];
					var b=self.old_committees[index][prop];
					var match=a===b;
					if(!match){
						bool=false;
						return bool;
					}
				}
					
			});
			return bool;
		}
		return true;
	}
	ko.mapping.fromJS(data,mapping,self);
}

var CommitteeModel = function(data,parent){
	var self = this;
	self.parent=parent;
	self.committee_id = ko.observable("");
	self.committee_name = ko.observable("");
	self.from_date=ko.observable("");
	self.from_month = ko.observable("");
	self.from_year= ko.observable("");
	self.to_month= ko.observable("");
	self.to_year= ko.observable("");
	self.task_description = ko.observable("");

	self.to_present=ko.observable(false);


	self.setToPresent=function(){
		if(self.to_present())
			self.to_present(false);
		else
			self.to_present(true);
		
		return true;
	}

	self.remove=function(){
		if(self.parent.committees().length>1)
			if(self.committee_id())
				self.parent.to_delete_committees.push(self.committee_id());
			self.parent.committees.remove(self);
	}
	self.getMainFields=function(){
		var required={
			committee_name:self.committee_name(),
			from_month:self.from_month(),
			from_year:self.from_year(),
			to_month:self.to_month(),
			to_year:self.to_year(),
			task_description:self.task_description(),
			to_present:self.to_present()
		}
		return required;
	}
	self.validate=function($affiliation,index){
		var $committeegroup=$affiliation.find('.committee-group:eq('+index+')');
		var failed=0;
		var required={
			committee_name:self.committee_name(),
			from_month:self.from_month(),
			from_year:self.from_year(),
			task_description:self.task_description()
		}
		for (var prop in required) {
			$field=$committeegroup.find("input[name*='"+prop+"']");
			if($field.length==0)
				$field=$committeegroup.find("select[name*='"+prop+"']");
			if($field.length==0)
				$field=$committeegroup.find("textarea[name*='"+prop+"']");
			if($field.length==0){
				console.dir("missing: "+prop);
			}else{
				if(!self.parent.parent.validate($field,1))
					failed+=1;
			}
		};
		return failed;
	}

	ko.mapping.fromJS(data,mapping,self);

}

