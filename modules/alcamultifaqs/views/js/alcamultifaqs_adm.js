/**
 * 2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 *
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
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @author ALCALINK E-COMMERCE & SEO, S.L.L. <info@alcalink.com>
 * @copyright  2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Registered Trademark & Property of ALCALINK E-COMMERCE & SEO, S.L.L.
 */
var setAlcamultifaqsStructure = function () {
    positions = {};
    $('#alcamultifaqsstructure [data-order]').each(function () { 
        p = $(this).attr('data-order');
        positions[p] = getTruePosition(this) - 1;
    });
    $.ajax({
        type: 'POST',
        cache: false,
        dataType: 'json',
        url: alcamultifaqsAdmAjaxUrl,
        data: {
            ajax: 1,
            action: 'changePosition',
            positions: positions
        },
        success: function(data) {
            if (data) {
                if (data.structured_faq) {
                    $(".alcamultifaqs-content-faq-js").html(data.structured_faq);
                    initSortableAlcamultifaqs();
                }
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}


getTruePosition = function getTruePosition(obj) {

    $(obj).attr('temp-data-target', 'true');
    var obj_clean = $(obj).parent().clone();
    $(obj_clean).children().each(function () {
        if (!$(this).children().first().hasClass('themeeditorchild')) {
            $(this).attr('temp-data-position', $(this).index() + 1);
        }
    });
    var posi = $(obj_clean).find('[temp-data-target="true"]').first().attr('temp-data-position');
    $(obj).removeAttr('temp-data-target', 'true');
    return posi;
}

var initSortableAlcamultifaqs = function() {
    var $mySlides = $("#alcamultifaqsstructure ul");
    $mySlides.sortable({
        opacity: 0.6,
        cursor: "move",
        update: function(event, ui) {
            //var index = ui.item.index();
            //var orderinfo = ui.item.data('order');
            setAlcamultifaqsStructure()
        }
    });

    $mySlides.hover(function() {
            $(this).css("cursor", "move");
        },
        function() {
            $(this).css("cursor", "auto");
        });
}

var delAlcamultifaq = function(id) {
    var type = $('.alcamultifaqs-filter-select-type').val();
    var id_object = $('.alcamultifaqs-filter-select-ids').find(':selected').val();

    if (typeof id_object == 'undefined') {
        id_object = null;
    }

    if (confirm(alcamultifaqsTxt_del)) {
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'json',
            url: alcamultifaqsAdmAjaxUrl,
            data: {
                ajax: 1,
                action: 'deleteFaq',
                id_alcamultifaqs: id,
                type,
                id_object
            },
            success: function(data) {
                if (data) {
                    if (data.structured_faq) {
                        $(".alcamultifaqs-content-faq-js").html(data.structured_faq);
                        initSortableAlcamultifaqs();
                    }
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
}

var setVisibilityFields = function(type, recovertype = false, recoveridobject = false) {
    if (recovertype) {
        $('.alcamultifaqs-type').val(type);
        $('.alcamultifaqs-type').trigger('change');
    }

    if (type == 'home' || type == 'footer') {
        $(".alcamultifaqs-id-object").html('');
        $(".group_alcamultifaqs-id-object").hide();
    } else {
        if (recoveridobject) {
            if (alcamultifaq_selected_id_object != '') {
                $('.alcamultifaqs-id-object').val(alcamultifaq_selected_id_object);
                $('.alcamultifaqs-id-object').trigger('change');
            }
        }

        $(".group_alcamultifaqs-id-object").show();  
    }
}

var filterAlcamultifaqList = function(type, id_object = 0) {
    $.ajax({
        type: 'POST',
        cache: false,
        dataType: 'json',
        url: alcamultifaqsAdmAjaxUrl,
        data: {
            ajax: 1,
            action: 'getFilterList',
            type: type,
            id_object: id_object
        },
        success: function(data) {
            $('.alcabigloader').hide();
            if (data) {
                if (data.structured_faq) {
                    $(".alcamultifaqs-content-faq-js").html(data.structured_faq);
                    initSortableAlcamultifaqs();
                }
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

$(document).ready(function() {
    initSortableAlcamultifaqs();
    $('.alcamultifaqs-id-object, .alcamultifaqs-filter-select-ids').select2();

    if (alcamultifaq_selected_type != '') {
        setTimeout(() => {
            setVisibilityFields(alcamultifaq_selected_type, true);
          }, 1000);
    } else {
        setVisibilityFields($('.alcamultifaqs-type').first().val());
    }

    $('.alcamultifaqs-type').on('change', function() {
        var alcamultifaqstype = $(this).val();
        $(".alcamultifaqs-id-object").html('');
        if (alcamultifaqstype != 'home' && alcamultifaqstype != 'footer') {
            setVisibilityFields(alcamultifaqstype);

            $.ajax({
                type: 'POST',
                cache: false,
                dataType: 'json',
                url: alcamultifaqsAdmAjaxUrl,
                data: {
                    ajax: 1,
                    action: 'getIdsType',
                    type: alcamultifaqstype
                },
                success: function(data) {
                    if (data) {
                        if (data.ids) {
                            $(".alcamultifaqs-id-object").html('').select2("destroy");
                            var alcamultifaqs_idsoption;
                            $.each(data.ids, function(index, val) {
                                alcamultifaqs_idsoption += "<option value='" + val.id + "'>" + val.name + "</option>";
                            });
                            $('.alcamultifaqs-id-object').append(alcamultifaqs_idsoption).select2();

                            setVisibilityFields(alcamultifaqstype, false, true);

                        }
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        setVisibilityFields(alcamultifaqstype, false, true);
    });

    /* FILTRO LISTA */

    $('.alcamultifaqs-filter-select-type').on('change', function() {
        var alcamultifaqstype = $(this).val();
        $('.group-filter-item').hide();
        $(".alcamultifaqs-filter-select-ids").html('');

        if (alcamultifaqstype != 'home' && alcamultifaqstype != 'footer') {
            //$(".alcamultifaqs-filter-select-ids").removeAttr('disabled');
            $.ajax({
                type: 'POST',
                cache: false,
                dataType: 'json',
                url: alcamultifaqsAdmAjaxUrl,
                data: {
                    ajax: 1,
                    action: 'getIdsType',
                    type: alcamultifaqstype
                },
                success: function(data) {
                    if (data) {
                        if (data.ids) {
                            $(".alcamultifaqs-filter-select-ids").data('type', alcamultifaqstype);
                            $(".alcamultifaqs-filter-select-ids").html('').select2("destroy");
                            var alcamultifaqs_idsoption;
                            alcamultifaqs_idsoption += "<option value='0' selected>"+ alcamultifaqs_lang_select +"</option>";
                            $.each(data.ids, function(index, val) {
                                alcamultifaqs_idsoption += "<option value='" + val.id + "'>" + val.name + "</option>";
                            });
                            $('.alcamultifaqs-filter-select-ids').append(alcamultifaqs_idsoption).select2();
                            $('.group-filter-item').show();
                        }
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        } else {
            $('.alcabigloader').show();
            filterAlcamultifaqList(alcamultifaqstype);
        }
    });

    $('.alcamultifaqs-filter-select-ids').on('change', function() {
        $('.alcabigloader').show();
        var alcamultifaqstype = $(this).data('type');
        var id_object = $(this).val();

        if (id_object != 0) {
            filterAlcamultifaqList(alcamultifaqstype, id_object);
        }
    });

    $('.alcamultifaqs-content-faq-js').on('click', '.alcamultifaqs-delete', function() {
        delAlcamultifaq($(this).data('alcamultifaqs'));
    });
});