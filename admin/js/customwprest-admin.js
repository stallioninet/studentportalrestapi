(function( $ ) {
	'use strict';

 

	 $(window).load(function () {
	    $(".trigger_popup_fricc").click(function(){
	       $('.hover_bkgr_fricc').show();
	    });
	    $('.hover_bkgr_fricc').click(function(){
	       // $('.hover_bkgr_fricc').hide();
	    });
	    $('.popupCloseButton').click(function(){
	        $('.hover_bkgr_fricc').hide();
	    });
	});

//	 $(document).ready(function(){

// 	 	$('#wpr_add_params').on('click' , function(){
// 		    var html = '<tr><td colspan="2"><input type="text" name="wpr_params[]"></td></tr>';
// 		    $('#wpr_edpts_tab').append(html);
// 		  });

		
// 		$('[data-toggle="tooltip"]').tooltip({html: "true",});   
		 

// 		   $('._delete_endpoints').on('click' , function(){
// 		    if(confirm("Confirm Deletion!")){
// 		      return true;
// 		    }else{
// 		      return false;
// 		    }
// 		   });

		


// 	 	 $('#wpr_set_backlog').on('change' , function(){
// 		 	if($(this).is(":checked")){
// 		 		$('.wpr_intervel_html').fadeIn(700);
// 		 	}else{
// 		 		$('.wpr_intervel_html').fadeOut(200);
// 		 	}
// 		 });

// 		  $('#wpr_reset_settings').on('click' , function(){
// 		 	if(confirm('This action will reset all settings to deafult')){
// 		 		window.location = wcraObj.settings_url+'&reset=1';
// 		 	}else{
		 		
// 		 	}
// 		 });

		 

///	 });

})( jQuery );
