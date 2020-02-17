(function($) {
"use strict";

/*------------------------------------------------------------------
[Table of contents]


/*-------------------------------------------
  js wow
--------------------------------------------- */
 new WOW().init();
/*-------------------------------------------
  js AOS
--------------------------------------------- */
 AOS.init();
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
  jQuery MeanMenu
--------------------------------------------- */
jQuery(".main-menu").meanmenu();

// smoot scroll nav
jQuery('#nav').onePageNav({
	
});

/*-------------------------------------------
Sticky Header
--------------------------------------------- */
$(window).on('scroll', function(){
    if( $(window).scrollTop()>80 ){
        $('#sticky').addClass('stick');
    } else {
        $('#sticky').removeClass('stick');
    }
}); 

/*-------------------------------------------
magnific popup
--------------------------------------------- */
$('.expand-image').magnificPopup({
  type: 'image',
  gallery: {
      enabled: true
  }
});
$('.expand-image2').magnificPopup({
  type: 'image',
  gallery: {
      enabled: true
  }
});
$('.expand-image3').magnificPopup({
  type: 'image',
  gallery: {
      enabled: true
  }
});

/*-------------------------------------------
slide-brands
--------------------------------------------- */
// $(".slide-screenshort.owl-carousel").owlCarousel({
// 	autoplay:false,
// 	autoplayTimeout:5000,
//   autoplayHoverPause:true,
// 	dots:false,
//   nav:true,	  
// 	items : 3,
// 	navText:["<i class='flaticon-left-arrow'></i>","<i class='flaticon-right-arrow'></i>"],
// 	responsiveClass:true,
//     responsive:{
//         0:{
//             items:1,
//         },
//         600:{
//             items:2,
//         },
//         1000:{
//             items:3,
//         }
//     }
// }); 

var angle = 0;
  setInterval(function(){
    angle+=3;
  $("#img").rotate(angle);
  },50);

  var angle = 0;
  setInterval(function(){
    angle+=3;
  $("#img2").rotate(angle);
  },50);

  var angle = 0;
  setInterval(function(){
    angle+=3;
  $("#img3").rotate(angle);
  },50);


  // tweenmax canvas
  var canvas = document.querySelector('#canvas');
                    var width = canvas.offsetWidth,
                        height = canvas.offsetHeight;

                    var renderer = new THREE.WebGLRenderer({
                        canvas: canvas,
                        antialias: true,
                        alpha: true
                    });
                    renderer.setPixelRatio(window.devicePixelRatio > 1 ? 2 : 1);
                    renderer.setSize(width, height);
                    renderer.setClearColor(0x000000, 0);

                    var canvas = new THREE.Scene();

                    var camera = new THREE.PerspectiveCamera(50, width / height, 0.1, 2000);
                    camera.position.set(0, 0, 80);

                    var loader = new THREE.TextureLoader();
                    loader.crossOrigin = "Anonymous";
                    var dotTexture = loader.load('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABABAMAAABYR2ztAAAAMFBMVEVMaXH////////////////////////////////////////////////////////////6w4mEAAAAD3RSTlMAH6eN8Q6A6sXaVWEGdDZ7wvYCAAABNElEQVR4XoXWv0oDQRAG8BGJKJHD2iZd2rP1AWzSXApJKkEEa6tAuk1nIySQxi4E7M830NIXsPZeIceS+xPCuGhYbpPd+b76B7c3uzuz1Mzlw3Oix90l+RNd8y7zzAfuFmxTfR+CgeJGisd90FbspHh3wdGU91JeOOCND9JrgmP25IpszmMfyD8t+GJvXiyI/SB3ViCtIg2BeleDJAT0fy1OOZjJH/gIgw2ZtFQYFH0DTljIvQFDCYwM+JHA1oCFBCqiiMVkts7Bap/JYEYdGawolUFNTzJYUyyDnKYyKEnJoKJEBpoYBAGNPlHARcLfhIWCpYabBbcbHhh45OChxcceX5xbCdzAywuvP2ogsAXBJgbbIG6kuBXjZo7HAR4oeCThoYbHIh6seDTj4W6fB6/u8+AX8dE3oDCbIgYAAAAASUVORK5CYII=');

                    var radius = 50;
                    var sphereGeom = new THREE.IcosahedronGeometry(radius, 5);
                    var dotsGeom = new THREE.Geometry();
                    var bufferDotsGeom = new THREE.BufferGeometry();
                    var positions = new Float32Array(sphereGeom.vertices.length * 3);
                    for (var i = 0;i<sphereGeom.vertices.length;i++) {
                        var vector = sphereGeom.vertices[i];
                        animateDot(i, vector);
                        dotsGeom.vertices.push(vector);
                        vector.toArray(positions, i * 3);
                    }

                    function animateDot(index, vector) {
                            TweenMax.to(vector, 4, {
                                x: 0,
                                z: 0,
                                ease:Back.easeOut,
                                delay: Math.abs(vector.y/radius) * 2,
                                repeat:-1,
                                yoyo: true,
                                yoyoEase:Back.easeOut,
                                onUpdate: function () {
                                    updateDot(index, vector);
                                }
                            });
                    }
                    function updateDot(index, vector) {
                            positions[index*3] = vector.x;
                            positions[index*3+2] = vector.z;
                    }

                    var attributePositions = new THREE.BufferAttribute(positions, 3);
                    bufferDotsGeom.addAttribute('position', attributePositions);
                    var shaderMaterial = new THREE.ShaderMaterial({
                        uniforms: {
                            texture: {
                                value: dotTexture
                            }
                        },
                        vertexShader: document.getElementById("wrapVertexShader").textContent,
                        fragmentShader: document.getElementById("wrapFragmentShader").textContent,
                        transparent:true
                    });
                    var dots = new THREE.Points(bufferDotsGeom, shaderMaterial);
                    canvas.add(dots);

                    function render(a) {
                        dots.geometry.verticesNeedUpdate = true;
                        dots.geometry.attributes.position.needsUpdate = true;
                        renderer.render(canvas, camera);
                    }

                    function onResize() {
                        canvas.style.width = '';
                        canvas.style.height = '';
                        width = canvas.offsetWidth;
                        height = canvas.offsetHeight;
                        camera.aspect = width / height;
                        camera.updateProjectionMatrix();  
                        renderer.setSize(width, height);
                    }

                    var mouse = new THREE.Vector2(0.8, 0.5);
                    function onMouseMove(e) {
                        mouse.x = (e.clientX / window.innerWidth) - 0.5;
                        mouse.y = (e.clientY / window.innerHeight) - 0.5;
                        TweenMax.to(dots.rotation, 4, {
                            x : (mouse.y * Math.PI * 0.5),
                            z : (mouse.x * Math.PI * 0.2),
                            ease:Power1.easeOut
                        });
                    }

                    TweenMax.ticker.addEventListener("tick", render);
                    window.addEventListener("mousemove", onMouseMove);
                    var resizeTm;
                    window.addEventListener("resize", function(){
                        resizeTm = clearTimeout(resizeTm);
                        resizeTm = setTimeout(onResize, 200);
                    });

})(jQuery);