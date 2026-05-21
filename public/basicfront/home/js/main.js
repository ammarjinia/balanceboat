(function ($) {
"use strict";
// TOP Menu Sticky
$(window).on('scroll', function () {
	var scroll = $(window).scrollTop();
	if (scroll < 100) {
    $("#sticky-header").removeClass("sticky");
    $('#back-top').fadeIn(500);
	} else {
    $("#sticky-header").addClass("sticky");
    $('#back-top').fadeIn(500);
	}
});

$(document).ready(function(){

// mobile_menu
var menu = $('ul#navigation');
if(menu.length){
	menu.slicknav({
		prependTo: ".mobile_menu",
		closedSymbol: '+',
		openedSymbol:'-'
	});
};
// blog-menu
  // $('ul#blog-menu').slicknav({
  //   prependTo: ".blog_menu"
  // });

// review-active
$('.slider_active').owlCarousel({
  loop:true,
  margin:0,
  items:1,
  autoplay:true,
  navText:['<i class="ti-angle-left"></i>','<i class="ti-angle-right"></i>'],
  nav:false,
  dots:false,
  autoplayHoverPause: true,
  autoplaySpeed: 800,
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  responsive:{
      0:{
          items:1,
          nav:false,
      },
      767:{
          items:1
      },
      992:{
          items:1
      },
      1200:{
          items:1
      },
      1600:{
          items:1
      }
  }
});


// Budget Retreats

if($(window).width() > 767)
{
 $('.budget-retreats').owlCarousel({
  loop:false,
  margin:20,
  items:3,
  autoplay:true,
  navText:['<i class="ti-angle-left"></i>','<i class="ti-angle-right"></i>'],
  nav:false,
  dots:true,
  autoplayHoverPause: true,
  autoplaySpeed: 500,
  responsive:{
      0:{
          items:1,
      },
      767:{
          items:2,
      },
      992:{
          items:3,
      },
      1200:{
          items:3,
      },
      1500:{
          items:3
      }
  }
});
$('.budget-retreats').removeClass('mobile-scrollb-bar');


} else {
    $('.budget-retreats').addClass('mobile-scrollb-bar');
	$('.budget-retreats').removeClass('owl-carousel');
}





});



//------- Mailchimp js --------//  

        // Search Toggle
        $("#search_input_box").hide();
        $("#search").on("click", function () {
            $("#search_input_box").slideToggle();
            $("#search_input").focus();
        });
        $("#close_search").on("click", function () {
            $('#search_input_box').slideUp(500);
        });
        // Search Toggle
        $("#search_input_box").hide();
        $("#search_1").on("click", function () {
            $("#search_input_box").slideToggle();
            $("#search_input").focus();
        });
        
        $(document).on("change", ".global_site_currency", function(){
            $("#frmGlobalCurrency").submit();
        }); 
})(jQuery);	