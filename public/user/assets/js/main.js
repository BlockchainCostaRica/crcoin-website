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
    sidebar
--------------------------------------------- */
//=================
$('.show-post button').on('click', function () {
	$('.show-post .address-list').toggleClass('show');
});
$('.profile-name h4 ').on('click', 'a', function () {
	$('.profile-area .profile-menu').toggleClass('active');
});

/*-------------------------------------------
slider-video
--------------------------------------------- */
$(".slider-video").owlCarousel({
	loop:true,
	margin:15,
	nav:true,
	navText:["<i class='flaticon-back'></i>","<i class='flaticon-right-arrow'></i>"],
	dots: false,
	autoplay: true,
	smartSpeed: 1000,
	autoplayTimeout: 5000,
	autoplayHoverPause:true,
    animateIn: 'fadeIn',
	responsive:{
		0:{items:1},
		600:{items:1},
		1000:{items:1}
	}
}); 
/*-------------------------------------------
niceSelect
--------------------------------------------- */
// $('select').niceSelect();

/*-------------------------------------------
basictable
--------------------------------------------- */
$('#table-breakpoint').basictable({
	breakpoint: 768,
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
}

/*-------------------------------------------
    sidebar
--------------------------------------------- */
//=================
$('.menu-bar ').on('click',  function () {
	$('.sidebar-box ').addClass('show');
});
$('.menu-bar ').on('click',  function () {
	$('.sidebar-box ').addClass('show');
});
$('.close-menu ').on('click',  function () {
	$('.sidebar-box ').removeClass('show');
});

})(jQuery);