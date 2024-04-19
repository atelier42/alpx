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
 *  @copyright © 2020 FME Modules
 *  @version   1.1
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$(document).ready(function() {
    if (typeof img_src !== 'undefined' && img_src != '') {
        $('.image_preview div').append('<img width="200" id="preview" class="imgm img-thumbnail" src ="' + img_src + '" >').parent().removeClass('hidden');
    }
    $('#static_block_reassurance_image').on('change', function() {
        readImageURL(this);
    });
});

function readImageURL(input) {
    $('#preview').remove();
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.image_preview div').append('<img width="200" id="preview" class="imgm img-thumbnail" src ="' + e.target.result + '" >').parent().removeClass('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}