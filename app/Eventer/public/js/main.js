(function($) {

	"use strict";

	var fullHeight = function() {

		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

	$('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
	});
	


 
	$('#sidebarCollapse').on('click', function(){
		if($('#sidebar').hasClass('active')){
			$('main').addClass('blurred');
		} else{
			$('main').removeClass('blurred');
		}
	})

})(jQuery);
