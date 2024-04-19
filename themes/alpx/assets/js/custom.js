
function searchFilterToggler() {
    $('#category #left-column').addClass('filters_wrapper_mobile');
    $('body#category').css('overflow','hidden');
};
function resultProductsCount() {
    $('#category #left-column').removeClass('filters_wrapper_mobile');
    $('body#category').css('overflow','visible');
    $('#category #left-column').css('transform','translateX(-110%)');
};
$(window).on('resize', function(){
    var win = parseInt($(window).width());
    if (win > 992) {
        $('#category #left-column').removeClass("filters_wrapper_mobile");
        $('body#category').css('overflow','visible');
        $('#category #left-column').css('transform','translateX(0%)');

    }
    else{
        $('#category #left-column').css('transform','translateX(-110%)');
    }
});
$(window).on('load', function() {
    $('#category #left-column #search_filter_controls button.ok').click(function () {
        $('#category #left-column').removeClass('filters_wrapper_mobile');
        $('body#category').css('overflow','visible');
        $('#category #left-column').css('transform','translateX(-110%)');
    });
});


$( document ).ajaxComplete(function( event, xhr, settings ) {
    $('#product .product-images-mobile').slick({
        infinite: false,
        dots: false,
        arrows: false,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 4,
                    arrows: false,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('.images-container .product-images').slick({
        infinite: false,
        dots: false,
        arrows: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        vertical: true,
        verticalSwiping: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1


                }
            }
        ]
    });
});

$(document).ready(function() {
    $('#product .product-images-mobile').slick({
        infinite: false,
        dots: false,
        arrows: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 4,
                    arrows: false,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('.images-container .product-images').slick({
        infinite: false,
        dots: false,
        arrows: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        vertical: true,
        verticalSwiping: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1


                }
            }
        ]
    });
    $('.crossling .products,.product-accessories .products').slick({
        infinite: true,
        dots: false,
        slidesToShow: 4,
        arrows: true,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    arrows: true,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: true,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    arrows: true,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('#product .align-items-center').slick({
        infinite: false,
        dots: false,
        slidesToShow: 3,
        arrows: true,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    arrows: true,
                    slidesToScroll: 1
                }
            }
        ]
    });


});

$(document).ready(function() {
    $("#loop-icon").click(function() {
        var element = $(".search-mobile-down");
        // Check if the element has the "hidden-element" class
        if (element.hasClass("active")) {
            // If it has the class, remove it and fadeIn
            element.removeClass("active");
            element.fadeOut();

        } else {
            // If it doesn't have the class, add it and fadeOut
            element.addClass("active");
            element.fadeIn();
        }
    });
});

// Mobile Only Slider
mobileOnlySlider("#reassurance-home > .elementor-container > .elementor-row", 768);
mobileOnlySlider("#solutions > .elementor-container > .elementor-row", 768);
mobileOnlySlider("#cms .tri-column > .elementor-container > .elementor-row", 641);

function mobileOnlySlider($slidername, $breakpoint) {
    var slider = $($slidername);
    var settings = {
        mobileFirst: true,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: true,
        // infinite: false,
        responsive: [
            {
                breakpoint: $breakpoint,
                settings: "unslick"
            }
        ]
    };

    slider.slick(settings);

    $(window).on("resize", function () {
        if ($(window).width() > $breakpoint) {
            return;
        }
        if (!slider.hasClass("slick-initialized")) {
            return slider.slick(settings);
        }
    });
}
// Mobile Only Slider

// Detect H2 Change Color
$.fn.isInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight() / 2;
    var viewportTop = $(window).scrollTop();
    var viewportHalf = viewportTop + $(window).height() / 2;
    return elementBottom > viewportTop && elementTop < viewportHalf;
};
$(window).on('load resize scroll', function() {
    $('.h3-red').each(function() {
        if ($(this).isInViewport()) {
            $('.h3-red').removeClass("active");
            $(this).addClass("active");

        } else {
            $(this).removeClass("active");
        }
    });
});
// Detect H2 Change Color

$(document).ready(function() {
    $('#images-retours > .elementor-container > .elementor-row').slick({
        slidesToShow: 1,
        variableWidth: true,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1.4,
                    // arrows: false,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 481,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    variableWidth: false
                }
            }
        ]
    });
});

/** sixtrone modal CGV  **/
jQuery(document).ready(() => {
    if( $("body#authentication").length){
        $("#cta-terms-and-conditions-1").on("click", (t) => {
            t.preventDefault();
            var e = $(t.target).attr("href");
            e += "?content_only=1";

            jQuery.get(e, (data) => {
                console.log("yes");
                $(".modalx").find(".js-modal-content").html($(data).find(".page-cms-6").contents());
                $(".modalx").modal("show");
            })
                .fail((t) => {
                    alert('fail')
                })

        })
        return false;
    }
})
jQuery(document).ready(() => {
    if( $("body#authentication").length){
        $("#cta-terms-and-conditions-2").on("click", (t) => {
            t.preventDefault();
            var e = $(t.target).attr("href");
            e += "?content_only=1";

            jQuery.get(e, (data) => {
                console.log('no');
                $(".modalx").find(".js-modal-content").html($(data).find(".page-cms-7").contents());
                $(".modalx").modal("show");
            })
                .fail((t) => {
                    alert('fail')
                })

        })
        return false;
    }
})

// Header Sticky Setup

$(window).scroll(function(){
    if ($(this).scrollTop() > 200) {
        $('.header-top').addClass('fixed');
    } else {
        $('.header-top').removeClass('fixed');
    }
});



jQuery(document).ready(() => {
    let oldText = $('.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left .spn-title').text().trim();
    // console.log(oldText);
    $('.gobackto').hide();

    $(document).on('click', '.changestatus .mm_has_sub .arrow', function() {
        // Find the closest span to the button and get its text content
        let closestSpanText = $(this).closest('.mm_has_sub').find('a.ets_mm_url .mm_menu_content_title').text().trim();
        // console.log('text :',closestSpanText);
        var hasSpecificClass = $(this).hasClass('opened');
        // console.log('SpecificClass',hasSpecificClass);
        if (hasSpecificClass) {
            $('.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left .spn-title').text(closestSpanText);
            // console.log('The container have the specific class.', closestSpanText);
            $('.gobackto').show();
        }
        else {
            $('.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left .spn-title').text(oldText);
            // console.log('The container does not have the specific class.', oldText);
            $('.gobackto').hide();
        }
    });
    $(document).on('click', '.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left', function() {
        $('.changestatus.ets_mm_megamenu .mm_columns_ul').removeClass('active', 500, "easeOutSine");
        $('.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left .spn-title').text(oldText);
        $('.gobackto').hide();
        $('.changestatus .mm_has_sub .arrow').addClass('closed');
        $('.changestatus .mm_has_sub .arrow').removeClass('opened');
    });
});



jQuery(document).ready(() => {
    let oldText = $('.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left .spn-title').text().trim();
    $('.close_menu .pull-right').on('click',function(){
        $('.mm_columns_ul').removeClass('active', 500, "easeOutSine");
        $('.mm_menus_ul').removeClass('active');
        $('.changestatus.ets_mm_megamenu .mm_menus_ul .close_menu .pull-left .spn-title').text(oldText);
        $('body').removeClass('noscroll');
        $('.gobackto').hide();

        $(this).parent().parent().prev().removeClass('opened');


        $(this).parent().parent().prev().addClass('closed');


        $(this).parent().parent().stop(true,true).removeClass('active');


    });
});