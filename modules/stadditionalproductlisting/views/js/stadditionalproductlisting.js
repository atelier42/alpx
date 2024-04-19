/*
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*/

jQuery(document).ready(function ($) {
    $('section[class^="stadditionalproductlisting_"] .owl-carousel').each(function(){
        var config = {
          loop: true,
          margin: 0,
         /* center: true,*/
          autoWidth: true,
          dots: $(this).data('dots'),
          autoplay: $(this).data('auto'),
          autoplayHoverPause: $(this).data('pause'),
          nav: $(this).data('nav'),
          responsiveClass: true,
          responsive: {
            0: {
              items: 1,
              margin: 30,
              center: true,
             /* nav: false,*/
            },
            640: {
              items: 2,
              margin: 5,
              center: true,
             /* nav: false,*/
            },
            1000: {
              items: 4,
            }
          }
      };
      $(this).owlCarousel(config);
    });
});
