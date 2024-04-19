/**
 *   2009-2021 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright 2009-2021 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 */


DwfProductExtraFields = new function() {
    var self = this;
    var fieldType = null;

    this.init = function () {
        $('#configuration_form select[name="type"]').bind('change', function () {
            $('#configuration_form').addClass('loading');

            var id_field = null;
            if (getE('id_dwfproductextrafields')) {
                id_field = getE('id_dwfproductextrafields').value;
            }

            fieldType = $(this).val();

            params = {
                action: 'getConfigByType',
                configure: 'dwfproductextrafields',
                ajax: '1',
                type: fieldType,
                id_field: id_field
            };

            $.ajax({
                type: 'POST',
                async: false,
                dataType: "json",
                cache: false,
                url: currentIndex + '&token=' + token,
                data: params,
                success: function (data) {
                    if (!data) {
                        window.location.href = 'index.php';
                    }
                    else if (undefined !== data.html) {
                        if (data.html) {
                            $('#cef_config').replaceWith($(data.html).find('#cef_config'));
                        }
                        else {
                            $('#cef_config').empty();
                        }
                        $('#configuration_form input[name="config"]').val(data.config);

                        if ($('#configuration_form select[name="type"]').parent('div').parent('div.form-group').length) {
                            if (!data.location) {
                                $('#configuration_form select[name="location"]').val('vars');
                                $('#configuration_form select[name="location"]').parent('div').parent('div.form-group').css('display', 'none');
                            }
                            else {
                                $('#configuration_form select[name="location"]').parent('div').parent('div.form-group').css('display', 'block');
                            }
                        }
                        else {
                            if (!data.location) {
                                $('#configuration_form select[name="location"]').val('vars');
                                $('#configuration_form select[name="location"]').parent('div').css('display', 'none');
                                $('#configuration_form select[name="location"]').parent('div').prev('label').css('display', 'none');
                            }
                            else {
                                $('#configuration_form select[name="location"]').parent('div').css('display', 'block');
                                $('#configuration_form select[name="location"]').parent('div').prev('label').css('display', 'block');
                            }
                        }
                        $('#configuration_form').removeClass('loading');
                    }
                }
            });

            $('#' + fieldType + '_list').sortable({
                axis: 'y',
                placeholder: "ui-state-highlight",
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    self.serializeValues();
                }
            });

            $('#configuration_form').submit(function () {
                self.serializeValues();
            });

            $('#' + fieldType + '_ad-optn').bind('click', function () {
                $('#' + fieldType + '_list').append("<li id=\"" + fieldType + "_" + $('#' + fieldType + '_list > li').length + "\" class=\"selector_item\">" + template_new_row + "</li>");
                hideOtherLanguage(id_language);
                self.bindDeleteButtonsAction();
                self.keyboadFilterConfigValue();
                self.serializeValues();
            });

            self.bindDeleteButtonsAction();
            self.keyboadFilterConfigValue();
        })
            .trigger('change');

    };

    this.showHideConfigField = function () {
        if ($('#configuration_form select[name="type"]').parent('div').parent('div.form-group').length) {
            if ($('#configuration_form select[name="type"]').val() != 'textarea' && $('#configuration_form select[name="type"]').val() != 'textarea_mce') {
                $('#configuration_form select[name="location"]').val('vars');
                $('#configuration_form select[name="location"]').parent('div').parent('div.form-group').css('display', 'none');
            }
            else {
                $('#configuration_form select[name="location"]').parent('div').parent('div.form-group').css('display', 'block');
            }
        }
        else {
            if ($('#configuration_form select[name="type"]').val() != 'textarea' && $('#configuration_form select[name="type"]').val() != 'textarea_mce') {
                $('#configuration_form select[name="location"]').val('vars');
                $('#configuration_form select[name="location"]').parent('div').css('display', 'none');
                $('#configuration_form select[name="location"]').parent('div').prev('label').css('display', 'none');
            }
            else {
                $('#configuration_form select[name="location"]').parent('div').css('display', 'block');
                $('#configuration_form select[name="location"]').parent('div').prev('label').css('display', 'block');
            }
        }
    };

    this.keyboadFilterConfigValue = function () {
        $('.drp-val input').each(function () {
            $(this).keyup(function (e) {
                var regex = new RegExp(/[^a-z0-9\-\_]/gi);
                $(this).val($(this).val().replace(regex, ''));
            });
        });
    };

    this.bindDeleteButtonsAction = function () {
        $('#' + fieldType + '_list > li').each(function () {
            if ($(this).find('.drp-rmv button').length) {
                $(this).find('.drp-rmv button').on('click', function (e) {
                    e.preventDefault();
                    $(this).closest('li.selector_item').remove();

                    self.serializeValues();
                });
            }
        });
    };

    this.serializeValues = function () {
        if (fieldType == 'selector') {
            self.serializeSelectorValues();
        }
        else if (fieldType == 'repeater') {
            self.serializeRepeaterValues();
        }
    };

    this.serializeSelectorValues = function () {
        $('#configuration_form input[name="config"]').val('');
        if ($('#cef_config:visible').length) {
            var serializedSelectorValue = {};
            serializedSelectorValue.values = [];
            var data = $.unserialize($('#selector_list').sortable('serialize')).selector;
            if (data.length) {
                var i = 0;
                data.map(function (el) {
                    serializedSelectorValue.values[i] = {};
                    serializedSelectorValue.values[i].value = $('#selector_' + el + ' .drp-val input').val().replace(",", "").trim();
                    serializedSelectorValue.values[i].label = [];
                    languages.map(function (lang) {
                        if (languages.length > 1) {
                            serializedSelectorValue.values[i].label.push({
                                id_lang: lang.id_lang,
                                value: $('#selector_' + el + ' .drp-opt .translatable-field.lang-' + lang.id_lang + ' input').val().trim()
                            });
                        }
                        else {
                            serializedSelectorValue.values[i].label.push({
                                id_lang: lang.id_lang,
                                value: $('#selector_' + el + ' .drp-opt input').val().trim()
                            });
                        }
                    });
                    i++;
                });
                serializedSelectorValue.multiple = $('#selector_multi:checked').length;
                $('#configuration_form input[name="config"]').val(JSON.stringify(serializedSelectorValue));
            }
        }
    };

    this.serializeRepeaterValues = function () {
        $('#configuration_form input[name="config"]').val('');
        if ($('#cef_config:visible').length) {
            var serializedRepeaterValue = {};
            serializedRepeaterValue.elements = [];
            var data = $.unserialize($('#repeater_list').sortable('serialize')).repeater;
            if (data.length) {
                var i = 0;
                data.map(function (el) {
                    serializedRepeaterValue.elements[i] = {};
                    serializedRepeaterValue.elements[i].key = $('#repeater_' + el + ' .drp-key input').val().replace(",", "").trim();
                    serializedRepeaterValue.elements[i].type = $('#repeater_' + el + ' .drp-type select option:selected').val();
                    serializedRepeaterValue.elements[i].name = [];
                    languages.map(function (lang) {
                        if (languages.length > 1) {
                            serializedRepeaterValue.elements[i].name.push({
                                id_lang: lang.id_lang,
                                value: $('#repeater_' + el + ' .drp-name .translatable-field.lang-' + lang.id_lang + ' input').val()
                            });
                        }
                        else {
                            serializedRepeaterValue.elements[i].name.push({
                                id_lang: lang.id_lang,
                                value: $('#repeater_' + el + ' .drp-name input').val().trim()
                            });
                        }
                    });
                    i++;
                });
                serializedRepeaterValue.collapsed_default = $('#collapsed_default:checked').length;
                $('#configuration_form input[name="config"]').val(JSON.stringify(serializedRepeaterValue));
            }
        }
    };
};

$(document).ready(function() {
    DwfProductExtraFields.init();
});
