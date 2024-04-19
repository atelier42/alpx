/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @author    FMM Modules
 * @copyright Copyright 2021 © FMM Modules
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$(function() {
    var selected_products = $(".relproductcheckbox:checked");
    if (selected_products.length > 0) {
        selected_products.each(function(e) {
            var button = $(this).closest(".rp_select_button").get(0);
            addRelatedProducts($(this).data("price"), button, false);
        });
    } else {
        $("#relProductsPriceBlock").hide();
    }

    if (rp_view == "grid") {
        initCarousel();

        if (
            (ps_version === 0 && current_page == "orderopc") ||
            current_page == "order"
        ) {
            //if cart updates - ps 1.6
            $(
                ".cart_quantity_delete, .cart_quantity_input, .cart_quantity_down, .cart_quantity_up"
            ).on("click change", function() {
                setTimeout(function() {
                    initCarousel();
                }, 600);
            });
        }
    }
});

$(document).on("click", ".add_related_products", addRelatedToCart);

function addRelatedProducts(related_price, button, checked, key) {
    var _checkBox = $(button).children("input.input");
    if (ps_version === 0) {
        _checkBox = $(button).find("input");
    }
    if (_checkBox.prop("checked")) {
        _checkBox.removeAttr("checked");
    } else {
        _checkBox.prop("checked", true);
    }
    if (typeof checked === "undefined" || typeof checked === "null") {
        checked = true;
    }

    if (checked) {
        _checkBox.attr("checked", _checkBox.is(":checked"));
    }
    if (_checkBox.prop("checked")) {
        _checkBox.closest(".item").find(".image_overlay").addClass("selected");
        $(button).removeClass("btn-info").addClass("btn-success");
    } else {
        _checkBox.closest(".item").find(".image_overlay").removeClass("selected");
        $(button).removeClass("btn-success").addClass("btn-info");
    }

    related_price = parseFloat(related_price);
    var objRp = $("#related_base_price" + key);
    pPrice = objRp.val();
    pPrice = parseFloat(pPrice);
    if (_checkBox.prop("checked")) {
        related_price = ps_round(pPrice + related_price);
        _checkBox.parent().addClass("rel_product_checked");
    } else {
        related_price = ps_round(pPrice - related_price);
        related_price = related_price < 0.0 ? 0.0 : related_price;
        _checkBox.parent().removeClass("rel_product_checked");
    }

    if (related_price > 0.0) {
        $("#relProductsPriceBlock" + key).show();
    } else {
        $("#relProductsPriceBlock" + key).hide();
    }
    objRp.val(related_price);
    objRp
        .parent()
        .find("span")
        .text(currency_sign + related_price);
}

function addRelatedToCart() {
    var selected_products = $(".relproductcheckbox:checked");
    if (selected_products.length <= 0) {
        alert(unselectedmessage);
    } else {
        $(".add_related_products").attr("disabled", true);
        var success = [];
        selected_products.each(function(e) {
            var item = $(this);
            e = e + 1;
            setTimeout(function() {
                item
                    .closest(".item")
                    .find(".image_overlay")
                    .toggleClass(function() {
                        if ($(this).hasClass("selected")) {
                            $(this).removeClass("selected");
                            return "rp_loader";
                        }
                    });
            }, 500 * e);

            if (ps_version) {
                success.push(
                    ajaxCustomCart(item.val(), item.data("ipa"),prestashop.urls.pages.cart)
                );
            } else {
                if (selected_products.length > e) {
                    success.push(ajaxCustomCart(item.val(), item.data("ipa"), cart_link));
                }
                //ajaxCart.add(item.val(), item.data('ipa'), true, null, 1, null);
            }
            setTimeout(function() {
                item
                    .closest(".item")
                    .find(".image_overlay")
                    .toggleClass(function() {
                        if ($(this).hasClass("rp_loader")) {
                            $(this).removeClass("rp_loader");
                            return "selected";
                        }
                    });

                if (selected_products.length === e) {
                    if (ps_version) {
                        if (success.length && $.inArray(false, success) === -1) {
                            prestashop.emit("updateCart", {
                                reason: {
                                    idProduct: item.val(),
                                    idProductAttribute: item.data("ipa"),
                                    idCustomization: 0,
                                    linkAction: "add-to-cart",
                                },
                                resp: {},
                            });
                            //location.reload();
                        }
                    } else {
                        ajaxCart.add(item.val(), item.data("ipa"), true, null, 1, null);
                        if (current_page == "orderopc" || current_page == "order") {
                            $(".layer_cart_cart")
                                .find(".continue")
                                .on("click", function() {
                                    location.reload(true);
                                });
                        }
                    }
                }
            }, 800 * e);
        });
        $(".add_related_products").removeAttr("disabled");
    }
}

function ajaxCustomCart(idProduct, idCombination, actionUrl) {
    if (parseInt(idProduct)) {
        var jsonData = {
            type: "POST",
            headers: { "cache-control": "no-cache" },
            url: actionUrl,
            async: false,
            cache: false,
            dataType: "json",
            data: {
                add: 1,
                qty: 1,
                ajax: true,
                action: "update",
                controller: "cart",
                token: static_token,
                id_product: idProduct,
                ipa: parseInt(idCombination) && idCombination != null ?
                    parseInt(idCombination) :
                    0,
            },
            success: function(response) {
                if (typeof response !== "undefined" && response) {
                    if (response.errors) {
                        alert(response.errors);
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus + "<br>" + errorThrown);
            },
        };
        //send the ajax request to the server
        var xhr = $.ajax(jsonData);
        if (
            typeof xhr !== "undefined" &&
            xhr &&
            typeof xhr.responseJSON !== "undefined" &&
            xhr.responseJSON
        ) {
            if (xhr.responseJSON.success) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

function initCarousel() {
    var owl = $(".owl-related-products-block");
    owl.owlCarousel({
        // Most important owl features
        items: 4,
        itemsCustom: false,
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [980, 3],
        itemsTablet: [768, 2],
        itemsTabletSmall: false,
        itemsMobile: [479, 1],
        singleItem: false,
        itemsScaleUp: false,
        //Basic Speeds
        slideSpeed: 200,
        paginationSpeed: 800,
        rewindSpeed: 1000,
        //Autoplay
        autoPlay: false,
        stopOnHover: false,
        // Navigation
        navigation: true,
        navigationText: ["prev", "next"],
        navigationText: false,
        rewindNav: true,
        scrollPerPage: false,
        // Responsive
        responsive: true,
        responsiveRefreshRate: 200,
        responsiveBaseWidth: window,
        //Mouse Events
        dragBeforeAnimFinish: true,
        mouseDrag: true,
        touchDrag: true,
    });
}

function ps_round(value, places) {
    if (typeof roundMode === "undefined") {
        roundMode = 2;
    }
    if (typeof places === "undefined") {
        places = 2;
    }

    var method = roundMode;

    if (method === 0) {
        return ceilf(value, places);
    } else if (method === 1) {
        return floorf(value, places);
    } else if (method === 2) {
        return ps_round_half_up(value, places);
    } else if (method == 3 || method == 4 || method == 5) {
        // From PHP Math.c
        var precision_places = 14 - Math.floor(ps_log10(Math.abs(value)));
        var f1 = Math.pow(10, Math.abs(places));

        if (precision_places > places && precision_places - places < 15) {
            var f2 = Math.pow(10, Math.abs(precision_places));
            if (precision_places >= 0) {
                tmp_value = value * f2;
            } else {
                tmp_value = value / f2;
            }

            tmp_value = ps_round_helper(tmp_value, roundMode);

            /* now correctly move the decimal point */
            f2 = Math.pow(10, Math.abs(places - precision_places));
            /* because places < precision_places */
            tmp_value /= f2;
        } else {
            /* adjust the value */
            if (places >= 0) tmp_value = value * f1;
            else tmp_value = value / f1;

            if (Math.abs(tmp_value) >= 1e15) return value;
        }

        tmp_value = ps_round_helper(tmp_value, roundMode);
        if (places > 0) {
            tmp_value = tmp_value / f1;
        } else {
            tmp_value = tmp_value * f1;
        }
        return tmp_value;
    }
}

function ps_round_helper(value, mode) {
    // From PHP Math.c
    if (value >= 0.0) {
        tmp_value = Math.floor(value + 0.5);
        if (
            (mode == 3 && value == -0.5 + tmp_value) ||
            (mode == 4 && value == 0.5 + 2 * Math.floor(tmp_value / 2.0)) ||
            (mode == 5 && value == 0.5 + 2 * Math.floor(tmp_value / 2.0) - 1.0)
        )
            tmp_value -= 1.0;
    } else {
        tmp_value = Math.ceil(value - 0.5);
        if (
            (mode == 3 && value == 0.5 + tmp_value) ||
            (mode == 4 && value == -0.5 + 2 * Math.ceil(tmp_value / 2.0)) ||
            (mode == 5 && value == -0.5 + 2 * Math.ceil(tmp_value / 2.0) + 1.0)
        )
            tmp_value += 1.0;
    }
    return tmp_value;
}

function ps_round_half_up(value, precision) {
    var mul = Math.pow(10, precision);
    var val = value * mul;

    var next_digit = Math.floor(val * 10) - 10 * Math.floor(val);
    if (next_digit >= 5) {
        val = Math.ceil(val);
    } else {
        val = Math.floor(val);
    }

    return val / mul;
}