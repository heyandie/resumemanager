/**
* This module contains methods for globally required js.
* @module Main
*/
var Main = {

	/**
	* This method initializes the module.
	* @method init
	*/
	init: function(){
		$(function () {
	        

	        // When clicking on the button close or the mask layer the popup closed
			$('body').on('click','.data-dismiss',function() { 
			 	Main.removeDialog(); 
				return false;
			});


	    });

		this.setActiveMenuItem();
	},

	

	/**
	* This method sets the active main menu item upon page load.
	* @method setActiveMenuItem
	*/
	setActiveMenuItem: function(){
        var currentPHP = window.location.href;

        $("ul#main-menu li.active ").removeClass('active');
       	       	
        if(currentPHP.indexOf('report')>-1)
        	$('a#reports-link').parent().addClass('active');
        else if(currentPHP.indexOf('/bookmarks')>-1)
        	$('a#bookmarks-link').parent().addClass('active');
        else if(currentPHP.indexOf('/review')>-1)
        	$('a#reviews-link').parent().addClass('active');
       
	},

	/**
	* This method creates a popup div
	* @method createPopUp
	* @return jquery Object
	*/
	createMask: function(){
		//Get the screen height and width
	    var mask_height = $(document).prop('scrollHeight');
	    var mask_width = $(window).prop('clientWidth');
	    // Add the mask to body
	    $('body').append('<div id="mask" class="data-dismiss"></div>');
	    //Set heigth and width to mask to fill up the whole screen
	    $('#mask').css({'width':mask_width,'height':mask_height});

	    
	    $('#mask').show();
	},

	hideSelectDropdown: function(){
		$('.selectize-dropdown').hide();
		$('.selectize-input').removeClass('focus input-active dropdown-active');
		$('div.selectize-input > input').blur();
	},

	createDisabledMask: function(white){

		//Get the screen height and width
	    var mask_height = $(document).prop('scrollHeight');
	    var mask_width = $(window).prop('clientWidth');
	    // Add the mask to body
	    $('body').append('<div id="mask"></div>');
	    //Set heigth and width to mask to fill up the whole screen
	    $('#mask').css({'width':mask_width,'height':mask_height});
	    
	    if(white){
	    	$('#mask').css('background-color','#fff');
	    }
	    
	    $('#mask').show();

	},

	createDialog: function(dialog_function,str){
		var _this = this;
		//Getting the variable's value from a link 
    	var pop_up_box = $('<div id="dialog" class="popup confirm"/>');
    	$(pop_up_box).appendTo('body').hide();
    	var dialog_buttons = $('<div class="dialog-buttons"/>');
    	var submit_button = $('<a class="btn btn-primary btn-xs pull-right">Yes</a>');

    	submit_button.on('click',function(){
    		dialog_function();
    		Main.removeDialog();
    	});

    	pop_up_box.append('<div class="popup-header">Confirm</div>');
    	pop_up_box.append('<div class="dialog-message">'+ str +'</div>');
    	
    	dialog_buttons.append(submit_button);
    	dialog_buttons.append('<a class="btn btn-default btn-xs pull-right data-dismiss">No</a>');
    	
    	pop_up_box.append(dialog_buttons);
    	//Fade in the Popup
    	$(pop_up_box).delay(150).fadeIn(200);
    	
    
    	//Set the center alignment padding + border see css style
    	var pop_marg_top = ($(pop_up_box).height() + 20) / 2; 
    	var pop_marg_left = ($(pop_up_box).width() + 20) / 2; 
    
    	$(pop_up_box).css({ 
        'margin-top' : -pop_marg_top,
        'margin-left' : -pop_marg_left
    	});

    	Main.createMask();

     	        
	    return false;
	},

	removeDialog: function(){
		$('#mask , .popup').fadeOut(300 , function() {
		    $('#mask').remove();  
		    });
	},	

	createLoadingSpinner: function(){
	
		Main.createDisabledMask(1);
		Main.revealModal($('#loading'));

	},

	removeLoadingSpinner: function(){
	
		$('#mask , #loading').fadeOut(300 , function() {
		    $(this).remove();
		});
	},

	revealAlert: function(string,_class){
		
		$('body').prepend('<div id="note" class="animate '+_class +'">'+string+'</div>');
		setTimeout(function(){
							$("#note").remove();
						},5000);
					
	},

	revealModal: function(pop_up_box){

		$(pop_up_box).fadeIn('normal');

		//Set the center alignment padding + border see css style
    	var pop_marg_top = ($(pop_up_box).height()+100) / 2; 
    	var pop_marg_left = ($(pop_up_box).width()+34) / 2; 

		$(pop_up_box).css({ 
        'margin-top' : -pop_marg_top,
        'margin-left' : -pop_marg_left
    	});
    

     	//Get the screen height and width
	    var mask_height = $(document).prop('scrollHeight');
	    var mask_width = $(window).prop('clientWidth');
	    // Add the mask to body
	    $('body').append('<div id="mask" class="data-dismiss"></div>');
	    //Set heigth and width to mask to fill up the whole screen
	    $('#mask').css({'width':mask_width,'height':mask_height});

	    
	    $('#mask').show();

	    $(window).resize(function(){
	    		//Set the center alignment padding + border see css style
		    	var pop_marg_top = ($('div.popup').height() + 34) / 2; 
		    	var pop_marg_left = ($('div.popup').width() + 34) / 2; 
		    
		    	$('div.popup').css({ 
		        'margin-top' : -pop_marg_top,
		        'margin-left' : -pop_marg_left
		    	});

	    	});
	    return false;
	}


}

$(function(){
	Main.init();
	$('a.reveal-popup').click(function(){
		var id = $(this).attr('href');
		var div = 'div'+id;
    	Main.revealModal($(div));
    	return false;
    });

	    	
});