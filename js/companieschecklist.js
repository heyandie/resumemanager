Date.prototype.yyyymmdd = function() {         
                                
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
                            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
   };  


var CompanyModel=function(data,parent){
	var self=this;
	ko.mapping.fromJS(data,{},self);
}

var CompaniesChecklistModel = function(){

	var self = this;
	self.hiring=ko.observableArray([
		{	
			name:"Employment and Internship",
			abbr:"ei",
		},
		{	
			name:"Employment",
			abbr:"e",
		},
		{	
			name:"Internship",
			abbr:"i",
		}
	]);
	self.courses = ko.observableArray([
		{
			course:"Bachelor_of_Science_in_Chemical_Engineering",
			abbr:"ChE"
		},
		{
			course:"Bachelor_of_Science_in_Civil_Engineering",
			abbr:"CE"
		},
		{
			course:"Bachelor_of_Science_in_Computer_Science",
			abbr:"CS"
		},
		{
			course:"Bachelor_of_Science_in_Computer_Engineering",
			abbr:"CoE"
		},
		{
			course:"Bachelor_of_Science_in_Electrical_Engineering",
			abbr:"EE"
		},
		{
			course:"Bachelor_of_Science_in_Electronics_&_Communications_Engineering",
			abbr:"ECE"
		},
		{
			course:"Bachelor_of_Science_in_Geodetic_Engineering",
			abbr:"GE"
		},
		{
			course:"Bachelor_of_Science_in_Industrial_Engineering",
			abbr:"IE"
		},
		{
			course:"Bachelor_of_Science_in_Mechanical_Engineering",
			abbr:"ME"
		},
		{
			course:"Bachelor_of_Science_in_Materials_Engineering",
			abbr:"MatE"
		},
		{
			course:"Bachelor_of_Science_in_Metallurgical_Engineering",
			abbr:"MetE"
		},
		{
			course:"Bachelor_of_Science_in_Mining_Engineering",
			abbr:"EM"
		}
	]);
	var course=degree_program?degree_program.replace(/ /g,'_'):"Bachelor_of_Science_in_Chemical_Engineering";
	self.course=ko.utils.arrayFirst(self.courses(), function(x) {
		    return x.course===course;
		});
	
	self.student_number=ko.observable(student_number);
	self.availableSchedule = ko.mapping.fromJS(schedule);
	self.companiesFilter=ko.observable("1");
	self.courseFilter=ko.observable(self.course);
	self.filterByCourse=ko.observable(false);
	self.hiringFilter=ko.observable("ei")
	var mapped_companies=ko.utils.arrayMap(companies,function(data){
		return new CompanyModel(data,self);});
	self.companies=ko.observableArray(mapped_companies);
	ko.utils.arrayForEach(self.companies(),function(x){
		if(x.selected()){
			self.companiesFilter("2");
			return;
		}
	});
	self.courseMatrixArray=ko.observableArray(courseMatrixArray);
	self.dateToShow = ko.observable(schedule.date);
	self.searchCompany=ko.observable("");
	// Animation callbacks for the planets list
    this.showCompanyElement = function(elem) { if (elem.nodeType === 1) $(elem).hide().slideDown() }
    this.hideCompanyElement = function(elem) { if (elem.nodeType === 1) $(elem).slideUp(function() { $(elem).remove(); }) }
	self.companiesToShow = ko.computed(function(){

		var c="";
		if(self.companiesFilter()=="1")
			c= self.companies();
		else if(self.companiesFilter()=="2"){
			c=ko.utils.arrayFilter(self.companies(),
			function(company){

				return company.selected();
			});
		}
		if(self.filterByCourse()){
			c= ko.utils.arrayFilter(c,
			function(company){
				var i=ko.utils.arrayFirst(self.courseMatrixArray(), function(x) {
				
				    return x.name.match(new RegExp(company.company_name(),'i'));
				});
				if(i){
					return i[self.courseFilter().abbr]&&i[self.courseFilter().abbr].match(new RegExp(self.hiringFilter(), 'i'));
				}
				else{
					return false;
				}
					
			});
		}

		if(self.searchCompany())
			return ko.utils.arrayFilter(c,
			function(company){

				return company.company_name().match(new RegExp(self.searchCompany(), 'i'));
			});
		
		return c;
		
	});
	self.today=today;
	self.save = function(){
		var data={
				student_number: self.student_number(),
				checklist:ko.toJS(self.companiesToShow())
			};
		var current_date=new Date();
		current_date=current_date.yyyymmdd();
		if(!current_date.match(self.today)){
			window.location.reload();
			return;
		}
			
		$.ajax({
			url:"companies_checklist/save_checklist",
			method:"POST",
			data:data,
			beforeSend:function(){
				$('body').append('<div class="div-loading"/>');

			},
			success:function(response){

				if(response){
					response=JSON.parse(response);
					if(response['code']!=undefined&&response['code']==404){
						location.replace(response['url']);
						return;
					}
					if(response['code']==300){
						$('#success').fadeIn(300);
					}
				}
			},
			complete:function(){
				$(".div-loading").remove();
			}
		});
	}
	self.matchDescription=ko.computed(function(){
		function span(str){
			return '<span style="color:#00729b">'+str+'</span>'
		}
		if(self.companiesToShow().length==0)
			var len="no";
		else
			var len=self.companiesToShow().length>0?self.companiesToShow().length:"";
		var str="";
		var num=self.companiesToShow().length>1?"all " +len:len;
		if(self.searchCompany())
				str=' matching '+span(self.searchCompany())+'</span>';
		if(self.filterByCourse()){
			var h=ko.utils.arrayFirst(self.hiring(),function(x){
				return x.abbr===self.hiringFilter();
			});	
			if(self.companiesFilter()==1)
				var y=self.companiesToShow().length>1?" companies":" company";
			else if(self.companiesFilter()==2)
				var y=len>1?" selected companies":" selected company";
			return num+" " + y+" for "+span(self.courseFilter().abbr)+' '+str+" hiring for "+span(h.name.toLowerCase());
		}
		if(self.companiesFilter()==1){
			var count=self.companiesToShow().length>1?" companies":" company";
			return num+" " +count+str;
		}else if(self.companiesFilter()==2){
			var count=len>1?" selected companies":" selected company";
			
			return num+" " +count+str;
		}
	},self);
	ko.bindingHandlers.fadeVisible = {
	    init: function(element, valueAccessor) {
	        // Initially set the element to be instantly visible/hidden depending on the value
	        var value = valueAccessor();
	        $(element).toggle(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
	    },
	    update: function(element, valueAccessor) {
	        // Whenever the value subsequently changes, slowly fade the element in or out
	        var value = valueAccessor();
	        ko.unwrap(value) ? $(element).fadeIn() : $(element).fadeOut();
	    }
	};
	ko.bindingHandlers.highlightedText = {
    update: function(element, valueAccessor) {
        var options = valueAccessor();
        var value = ko.utils.unwrapObservable(options.text);
        var search = ko.utils.unwrapObservable(options.highlight);
        var css = ko.utils.unwrapObservable(options.css);
        if (options.sanitize) {
            value = $('<div/>').text(value).html(); //could do this or something similar to escape HTML before replacement, if there is a risk of HTML injection in this value
        }
        var replacement = '<span class="' + css + '">' + value.match(new RegExp(search, 'i')) + '</span>';
        element.innerHTML = value.replace(new RegExp(search, 'i'), replacement);
    }
};

}

/*// Just going to assume this is true for arguments sake.
var unsavedChanges = true;

$('a').click(function () {
    // Properties to compare between link and current location
    // https://developer.mozilla.org/en-US/docs/Web/API/Location
    var toCheck = ['host', 'pathname', 'protocol'];
    var toCheckL = toCheck.length;

    return function (e) {
        // Skip this if there's no changes
        if (!unsavedChanges) return true;

        // Just to be sure
        if (this.constructor !== HTMLAnchorElement) {
            return true;
        }

        // Start off assuming they want to stay
        // because we can't stand being left again        
        var staying = true;

        for (var i = 0; i < toCheckL; i++) {
            var arg = toCheck[i];

            if ( this[arg] !== window.location[arg] ) {
                // If anything doesn't match, just move on and let her go
                staying = false;
                break;
            }
        }

        if ( !staying ) {
            // Do whatever you want here
            // Return false to stop the link
            // Recommend you reference this.href if you
            // decide to let them leave after all
            $("#before-unload").fadeIn(200);
            var href=$(this).attr('href');
            $('.btn-confirm').on('click',function(){
				window.location.replace(href);
			});
            return false;
        }

    };
}());
*/

