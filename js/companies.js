var environ = window.location.host;
function getAjaxUrl(){
	if (environ === "localhost") {
	    var baseurl = window.location.protocol + "//" + window.location.host + "/" + "resumebox/admin/";
	} else {
	    var baseurl = window.location.protocol + "//" + window.location.host + "/"+"admin/";
	}
	return baseurl;
}
jQuery.fn.scrollTo = function(elem, speed) { 
	    $(this).animate({
	        scrollTop:  $(this).scrollTop() - $(this).offset().top + elem.offset().top - 150
	    }, speed == undefined ? 400 : speed); 
	    return this; 
	};
var CompanyListModel = function(){

	var self = this;

	self.availableSchedule = ko.mapping.fromJS(schedule);
	self.companies =ko.mapping.fromJS(companies);
	self.dateToShow = ko.observable("");

	self.companiesToShow = ko.computed(function(){

		if(!self.dateToShow()){
			return null;
		}else{
			return ko.utils.arrayFilter(self.companies(),
				function(company){
					return company.date() == self.dateToShow().date();
				});
		}
	});

}	
var CompanyModel=function(data,parent){
	var self=this;
	self.parent=parent;
	self.company=ko.observable("");
	self.company_id=ko.observable("");
	self.schedule=ko.observableArray([]);
	ko.mapping.fromJS(data,{},self);
	self.old_name=self.company();
	self.editCompany=function(){

		self.parent.toEditCompany(ko.mapping.fromJS(ko.toJS(self)));
		$('#edit-company-popup').fadeIn('300');
		$('#mask').show();
	}
	self.save=function(){
		var data={
			company_id:self.company_id(),
			schedule:self.schedule()
		};
		$.ajax({
			url:getAjaxUrl()+"update_company",
			type:"POST",
			data:data,
			beforeSend:function(){
				$('body').append('<div class="div-loading"/>')
			},
			success:function(){
				self.enableSave(false);
			},
			complete:function(){
				$(".div-loading").remove();
			}
		})
		console.dir(data);
	}
	self.enableSave=ko.observable(false);
	self.checkChanges=function(){
		var filled=0;
		ko.utils.arrayForEach(self.schedule(),function(x){
			if(x.selected()==true){
				filled+=1;

			}
		});
		if(filled)
			self.enableSave(true);
		else
			self.enableSave(false);
		return true;
	}

	self.enableEdit=ko.observable(false);
	self.editName=function(){
		self.enableEdit(true);
		self.old_name=self.company();
	}
	self.onBlur=function(){
		self.enableEdit(false);
		if(self.old_name===self.company())
			return;
		$.ajax({
			url:getAjaxUrl()+"update_company_name",
			type:"POST",
			data:{company_id:self.company_id(),company_name:self.company()},
			beforeSend:function(){
				$('body').append('<div class="div-loading"/>');
			},
			success:function(){
				self.onBlur();
				self.parent.availableCompanies().sort(function (l, r) { return l.company().toLowerCase() > r.company().toLowerCase() ? 1 : -1 });
				self.parent.availableCompanies.valueHasMutated();
			},
			complete:function(){
				$(".div-loading").remove();
			}
		});
	}
	self.remove=function(){
		console.dir(self.company_id());
		$.ajax({
			url:getAjaxUrl()+"delete_company",
			type:"POST",
			data:{company_id:self.company_id()},
			beforeSend:function(){
				$('body').append('<div class="div-loading"/>')
			},
			success:function(){
				self.parent.availableCompanies.remove(self);
			},
			complete:function(){
				$(".div-loading").remove();
			}
		});
	}
	self.cancel=function(){
		self.parent.toEditCompany("");
		$('#edit-company-popup').fadeOut('150');
		$('#mask').hide();
	}
	self.showCompanyStat=function(){
		$('#company-stat-popup').fadeIn('100');
		$.ajax({
			url:getAjaxUrl()+"count_resumes",
			type:"POST",
			data:{company_id:self.company_id()},
			beforeSend:function(){
				$('body').append('<div class="div-loading"/>')
			},
			success:function(result){
				console.dir(result);
				result=JSON.parse(result);
				self.parent.viewed_company(self.company());
				self.parent.employment_count(result.employment);
				self.parent.internship_count(result.internship);
				$(".div-loading").remove();
				$('#mask').show();
			},
			complete:function(){
				$(".div-loading").remove();
			}
		})
		
	}
}

var CompaniesManagerModel = function(){
	
	var self = this;
	self.availableSchedule = ko.mapping.fromJS(schedule);
	
	var mapped_companies=ko.utils.arrayMap(companies,function(data){
		return new CompanyModel(data,self);});
	self.availableCompanies=ko.observableArray(mapped_companies);
	self.chosenDates=ko.observableArray([]);
	self.companyToAdd=ko.observable("");
	self.toEditCompany=ko.observable("");
	self.employment_count=ko.observable(0);
	self.internship_count=ko.observable(0);
	self.viewed_company=ko.observable("");

	var subscription=self.chosenDates.subscribe(function(){
        self.clearErrorStyle();
    }, self);
    self.clearErrorStyle=function(newValue){
    	$select=$('div.dates-selection').first();
    	$p1=$select.next('p');
    	$p1.remove();
		$select.removeClass('invalid');
		subscription.dispose();
		$('#new-company-popup input:checkbox').removeAttr('checked');
	
		
    }
    self.resetInput=function(){
    	$target=$('input[name="company_to_add"]').first();
		$p2=$target.next('p');
		$p2.remove();
		$target.removeClass('danger');

    }
	self.addCompany=function(){
		var failed=0;
		if(self.chosenDates().length==0){
			$select=$('div.dates-selection').first();
			$select.addClass('invalid');
			$p1=$select.next('p');
				if($p1.length==0){
					$select.after('<p class="error">Please select at least one date.</p>');
				}

			failed+=1;
		}
		if(!self.companyToAdd()){
			$target=$('input[name="company_to_add"]').first();
			$target.addClass('danger');
			$p2=$target.next('p');
				if($p2.length==0){
					$target.after('<p class="error">Please include the name of the company.</p>');
				}

			failed+=1;
		}
		if(failed)
			return;

		var data={
			company_name:self.companyToAdd(),
			dates:ko.toJS(self.chosenDates())
		}
		$.ajax({
			url:getAjaxUrl()+"add_company",
			type:"POST",
			data:data,
			beforeSend:function(){
				$('body').append('<div class="div-loading"/>')
			},
			success:function(response){
				response=JSON.parse(response);
				self.availableCompanies.push(new CompanyModel(response[0],self));
				self.availableCompanies().sort(function (l, r) { return l.company().toLowerCase() > r.company().toLowerCase() ? 1 : -1 });
				self.availableCompanies.valueHasMutated();
				self.closeAddCompany();
				$row=$('tr[name="row:'+response[0].company+'"]');
				$row.addClass('newly-added');
				$('body').scrollTo($row);
				setTimeout(function(){
					$row.removeClass('newly-added');
				},1000);
			},
			complete:function(){
				$(".div-loading").remove();
			}
		})
		
	}

	self.showAddCompany=function(){
		self.companyToAdd("");
		$('#new-company-popup').fadeIn('100');
		self.resetInput();
		subscription=self.chosenDates.subscribe(function(){
	        self.clearErrorStyle();
	    }, self);
		$('#mask').show();
	}
	self.closeAddCompany=function(){
		$('#new-company-popup').fadeOut('100');
		self.resetInput();
		self.clearErrorStyle();

		self.chosenDates([]);
		$('#mask').fadeOut(200);
	}

	self.closeCompanyStat=function(){
		$('#company-stat-popup').fadeOut('100');
		$('#mask').fadeOut(200);
	}

	
}

