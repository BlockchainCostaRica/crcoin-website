(function($) {
"use strict";

/*------------------------------------------------------------------
[Table of contents]


/*-------------------------------------------
  js wow
--------------------------------------------- */
 new WOW().init();
/*-------------------------------------------
  js scrollup
--------------------------------------------- */
$.scrollUp({
	scrollText: '<i class="fa fa-angle-up"></i>',
	easingType: 'linear',
	scrollSpeed: 900,
	animation: 'fade'
}); 

	
/*-------------------------------------------
testimonial-slide
--------------------------------------------- */
$(".slider-proimg.owl-carousel").owlCarousel({
	loop:true,
	autoplay:true,
	autoplayTimeout:1000,
	autoplayHoverPause:true,
	smartSpeed:3000,
	autoplaySpeed:true,
	dots:false,
	nav:true,	  
	items : 1,
	navText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
}); 

/*------------- Start metismenu active -------------*/

$("#menu").metisMenu();

// sidebar active

$('.bars').on('click', function() {
	$('.left-sidebar, .body-class').toggleClass('active');
});
$('.close').on('click', function() {
	$('.left-sidebar').toggleClass('active');
});
// notifications-area
$('.notifications-area ul li ').on('click', 'a', function () {
	$('.notifications-area ul li a').toggleClass('active');
});
$('.notifications-area ul li ').on('click', 'a', function () {
	$('.notifications-area .notifications-box').toggleClass('active');
});

//Circular Bars - Knob 	
//=================
if(typeof($.fn.knob) != 'undefined') {
	$('.knob').each(function () {
		var $this = $(this),
			knobVal = $this.attr('data-rel');   		

		$this.knob({
		'draw' : function () {
			$(this.i).val(this.cv + '%')
		}
		});
		$this.appear(function() {
		$({
			value: 0
		}).animate({
			value: knobVal
		}, {
			duration : 2000,
			easing   : 'swing',
			step     : function () {
			$this.val(Math.ceil(this.value)).trigger('change');
			}
		});
		}, {accX: 0, accY: -150});
	});
};
// Start top bar dropdown menu
	$(document).on('click',function(){
		$('.drop-down').removeClass('drop-down-show');
		});
		$('li').on('click', function(e) {
			$('.drop-down').removeClass('drop-down-show');
			$(this).children('ul').addClass('drop-down-show');
			e.stopPropagation();
	});
  /*-------------------------------------------
niceSelect
--------------------------------------------- */
// $('select').niceSelect();


})(jQuery);