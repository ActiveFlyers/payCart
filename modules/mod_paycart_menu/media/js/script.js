(function($){
	$(document).ready(function () {
		$('.sidebar-offcanvas').hide();
	  	$('[data-toggle="offcanvas"]').click(function () {
		  	var currentId = $(this).attr('data-target');
		   	$('.sidebar-offcanvas').not(currentId).hide();
			setTimeout( function(){ 
				if($(currentId).is(':visible')){
					$(currentId).hide();
				}
				else{
					$(currentId).show();
				}
			} , 100) ;
		  
	    $('.row-offcanvas').toggleClass('active');
	  });
	});
})(jQuery); 
(function($){
	$('.pc-menu-popover')
    	.popover()  	//pophover
        .mouseleave(	//on mouse leave hide it
        function() {
        	$('.pc-menu-popover').popover('hide')
		});
        var pc_menu = {};
        pc_menu.update = {
    	onSuccess : function(response_data) {
        $('.pc-demo-cart-counter').html();
		// 	take action
        if ( response_data['products_count'] > 0 ) {
    		$('.pc-demo-cart-counter').html(response_data['products_count']);
        }
    },
	onError : function(response_data) {
    	console.log ( {" response contain error :  " : response_data } );
    },    					
    do : function(event) {
	    	var request 	= [];
	    	request['success_callback']	=	pc_menu.update.onSuccess;
	    	request['url'] = 'index.php?option=com_paycart&view=cart&task=getProductCount&format=json';
	    	paycart.request(request);
    	},
	};
    // bind event
    $(document).on( "onPaycartCartUpdateproduct", pc_menu.update.do);
    // on Document ready 
    $(document).ready(function(){
    		pc_menu.update.do();
    		$('body').addClass('row-offcanvas row-offcanvas-left');
    		$('#pc-mob-offcanvas').removeClass('hide');
  		  
    	});
})(paycart.jQuery);