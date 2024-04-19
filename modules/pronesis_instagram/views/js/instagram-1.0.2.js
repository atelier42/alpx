/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function(){

  $(".instagram-feed-carousel-item > a").fancybox({
    helpers: {
      overlay: {
        locked: false
      }
    },
		title : function() {
      return $(this).next('figcaption').html();
    },
	});
	

  $('.instagram-feed-carousel').slick({
    infinite: false,
    speed: 200,
    slidesToShow: 5,
    dots: false,
    slidesToScroll: 1,
    lazyLoad: 'ondemand',
    prevArrow: '<div class="slick-prev instagram-feed-arrow instagram-feed-arrow-left"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB3aWR0aD0iMTc5MiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTIwMyA1NDRxMCAxMy0xMCAyM2wtMzkzIDM5MyAzOTMgMzkzcTEwIDEwIDEwIDIzdC0xMCAyM2wtNTAgNTBxLTEwIDEwLTIzIDEwdC0yMy0xMGwtNDY2LTQ2NnEtMTAtMTAtMTAtMjN0MTAtMjNsNDY2LTQ2NnExMC0xMCAyMy0xMHQyMyAxMGw1MCA1MHExMCAxMCAxMCAyM3oiLz48L3N2Zz4=" /></div>',
    nextArrow: '<div class="slick-next instagram-feed-arrow instagram-feed-arrow-right"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB3aWR0aD0iMTc5MiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTE3MSA5NjBxMCAxMy0xMCAyM2wtNDY2IDQ2NnEtMTAgMTAtMjMgMTB0LTIzLTEwbC01MC01MHEtMTAtMTAtMTAtMjN0MTAtMjNsMzkzLTM5My0zOTMtMzkzcS0xMC0xMC0xMC0yM3QxMC0yM2w1MC01MHExMC0xMCAyMy0xMHQyMyAxMGw0NjYgNDY2cTEwIDEwIDEwIDIzeiIvPjwvc3ZnPg==" /></div>',
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
          initialSlide: 2,
          centerMode: true,
          centerPadding: '50px',
          infinite: true,
          arrows: false,
          edgeFriction: 0
        }
      },
      {
        breakpoint: 440,
        settings: {
          slidesToShow: 1,
          initialSlide: 1,
          centerMode: true,
          centerPadding: '80px',
          arrows: false,
          edgeFriction: 0
        }
      }
    ]
  }).on('lazyLoaded', function(event, slick, image, imageSource){
    $(image).css('opacity', '1');
  });
});