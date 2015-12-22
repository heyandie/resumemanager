var PersonalInfoModel = function(data,parent){
	var self=this;
	var environ = window.location.host;
	self.parent=parent;
	self.first_name=ko.observable("");
	self.last_name=ko.observable("");
	self.address=ko.observable("");
	self.email_address=ko.observable("");
	self.mobile_number=ko.observable("");
	self.student_number=ko.observable(parent.student_number());
	self.isEditing=ko.observable(false);
	self.old_data={};
	function getRequiredFields(){
		var required_fields={
			first_name:self.first_name(),
			last_name:self.last_name(),
			address:self.address(),
			email_address:self.email_address(),
			mobile_number:self.mobile_number()
		}
		return required_fields;
	}

	self.validateFields=function(){
		var prop=getRequiredFields();
		var failed=0;
		for(var i in prop){
			$field=$("#personal-information").find('input[name~="'+i+'"], textarea[name~="'+i+'"]');
			
			if($field.length==0)
				window.location.reload();
			else{
				if(!self.parent.validate($field))
					failed += 1;
			}
		}
		if(failed==0){
			
			return prop;
		}else{
			self.parent.showPersonalInfo();
			self.edit();
			return false;
		}
	
	}

	self.edit=function(){
		self.setOldData();
		self.isEditing(true);
		self.parent.isEditing(true);
	}
	self.hasNoChanges=function(){
		for(var i in self.old_data){
			if(!(self.old_data[i]===self[i]())){
				return false;
			}
		}
		return true;
	}
	self.setOldData=function(){
		self.old_data=getRequiredFields();
	}

	self.cancel=function(){
		self.isEditing(false);
		self.parent.isEditing(false);
		self.closeInfo();
	}
	self.closeInfo=function(){
		$personalinfo=$('#personal-information .data-group');
		$personalinfo.find('div.div-loading').remove();
		$mask=$personalinfo.find('.alert-mask');
		$alert=$personalinfo.find(".alert-notify");
		$mask.removeClass("alert-notify");
		$alert.removeClass("alert-notify");
	}
	self.save=function(item,event){
		var required_fields = self.validateFields();

		if(!required_fields){
			self.isEditing(false);
			self.parent.isEditing(false);
			self.closeInfo();
			return false;
		}
		if(self.hasNoChanges()){
			self.isEditing(false);
			self.parent.isEditing(false);
			self.closeInfo();
		}else{
			var $target = $(event.target);
			var $personalinfo=$target.parents('div.data-group');
			data={
				data:required_fields,
				student_number:self.student_number(),
				section:"personal_information"
			}
			if (environ === "localhost") {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/resume_editor/save_personal_information/";
			} else {
			    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"resume_editor/save_personal_information/";
			}
			$.ajax({
				url: baseurl,
				type:"POST",
				data:data,
				beforeSend:function(){
					$('body').append('<div class="div-loading"></div>');
				},
				success:function(response){
					if(response){
						var response=JSON.parse(response);
						if(response['code']==1){
							$(".div-loading").remove();
						}else{
							window.location.replace(response['url']);
						}
						self.cancel();
					}
				},
				error:function(x,t,h){
					if(t==="timeout"){
						self.setAlerts(".alert-error.alert-info");
					}
				},
				timeout:60000
			});
		}
	}
	self.setAlerts=function(_class){
		$personalinfo=$('#personal-information .data-group');
		$personalinfo.find('div.div-loading').remove();
		$mask=$personalinfo.find('.alert-mask');
		$alert=$personalinfo.find(_class);
		$mask.addClass("alert-notify");
			$alert.addClass("alert-notify");
		setTimeout(function(){
			$mask.removeClass("alert-notify");
			$alert.removeClass("alert-notify");
		},2500);
		$mask.on('click',function(){
				$mask.removeClass('alert-notify');
				$alert.removeClass('alert-notify');
			});
	}
	ko.mapping.fromJS(data, {}, self);
}