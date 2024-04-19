/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 *  @author    FME Modules <info@fmemodules.com>
 *  @copyright © 2015 FME Modules
 *  @version   1.1
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
$(function() {
    $("#btn-click-me").click(function() {
        $("#top-container").toggle();
    });
});
$(document).ready(function() {
    var cards = $('.card');
    //open and close card
    cards.find('.fmm_js_opener').click(function() {
        var thisCard = $(this).closest('.card');
        if (thisCard.hasClass('fmm-is-closed')) {
            $(document).find(".fmm-is-opened").addClass('fmm-is-closed').removeClass('fmm-is-opened');
            thisCard.removeClass('fmm-is-closed').addClass('fmm-is-opened');
            if (cards.not(thisCard).hasClass('is-inactive')) {} else {}
        } else {
            thisCard.removeClass('fmm-is-opened').addClass('fmm-is-closed');
            cards.not(thisCard).removeClass('is-inactive');
        }
    });

    //close card
    cards.find('.js-collapser').click(function() {
        var $thisCard = $(this).closest('.card');
        thisCard.removeClass('fmm-is-opened').addClass('fmm-is-closed');
        cards.not($thisCard).removeClass('is-inactive');
    });

    //hover
    $('.fmm_ch').hover(function(e) {
        var hoverColor = $(this).data('hover-color');
        $(this).css({ 'background-color': hoverColor });
    }, function(e) {
        $(this).removeAttr('style');
    });
    $('.fmm-hovers').hover(function(l) {
        var hoverColor = $(this).data('hover-color');
        $(this).css({ 'background-color': hoverColor });
    }, function(l) {
        $(this).removeAttr('style');
    });
});