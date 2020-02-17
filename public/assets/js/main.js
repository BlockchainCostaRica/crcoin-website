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

	   $(".rev").on('click',function() {
		   var $pwd = $(".look-pass");
		   if ($pwd.attr('type') === 'password') {
			   $pwd.attr('type', 'text');
		   } else {
			   $pwd.attr('type', 'password');
		   }
	   });

	   $(".rev-1").on('click',function() {
		   var $pwd = $(".look-pass-1");
		   if ($pwd.attr('type') === 'password') {
			   $pwd.attr('type', 'text');
		   } else {
			   $pwd.attr('type', 'password');
		   }
	   });

	   
	   $(".eye").on('click',function() {
		var $pwd = $(".look-pass-a");
		if ($pwd.attr('type') === 'password') {
			$pwd.attr('type', 'text');
		} else {
			$pwd.attr('type', 'password');
		}
	});

	$(".pass-l").on('click',function() {
		var $pwd = $(".look-pass-l");
		if ($pwd.attr('type') === 'password') {
			$pwd.attr('type', 'text');
		} else {
			$pwd.attr('type', 'password');
		}
	});

	$(".pass-m").on('click',function() {
		var $pwd = $(".look-pass-m");
		if ($pwd.attr('type') === 'password') {
			$pwd.attr('type', 'text');
		} else {
			$pwd.attr('type', 'password');
		}
	});

	$(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye-slash fa-eye");
    });

        function showHidePassword(id) {

            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";

            } else {
                x.type = "password";
            }
        }
        function readURL(input,img) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+img).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document.body).on('click','#password_btn',function () {
            console.log($('#password').input.type);
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
$('select').niceSelect();


})(jQuery);