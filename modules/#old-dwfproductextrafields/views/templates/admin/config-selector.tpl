{**
 *   Copyright since 2009 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright since 2009 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 *}

<div class="config-container">
    <input type="hidden" name="config" value="{$jsonConfig|escape:'html'}" />
    <div id="cef_config" class="col-xs-12 col-lg-6">
        <div class="banner_up">
            <div class="col-xs-offset-1 col-xs-4"><span>{l s='Value' mod='dwfproductextrafields'}</span></div>
            <div class="col-xs-7"><span> {l s='Option text' mod='dwfproductextrafields'}</span></div>
        </div>
        <ul class="admin_selector clearfix" id="selector_list">
    {assign var=config value=$jsonConfig|json_decode:true}
    {if $config.values}
        {foreach from=$config.values item=val name=configRow}
            <li id="selector_{$smarty.foreach.configRow.index}" class="selector_item">
                <div class="row">
                    <div class="drp-drag col-xs-1"><i class="icon-bars" aria-hidden="true"></i></div>
                    <div class="drp-val col-xs-4"><input value="{$val.value}" placeholder="{l s='Value' mod='dwfproductextrafields'}" type="text"></div>
                    <div class="drp-opt col-xs-6">
                    {foreach from=$languages item=lang}
                        {if $languages|count > 1}
                        <div class="translatable-field lang-{$lang.id_lang}"{if $lang.id_lang != $default_lang} style="display:none;"{/if}>
                            <div class="col-xs-9">
                        {/if}

                        {assign var=key value=($lang.id_lang|array_search:($val.label|array_column:'id_lang'))}
                        <input value="{if isset($val.label[$key].value)}{$val.label[$key].value}{/if}" placeholder="{l s='Option text' mod='dwfproductextrafields'}" type="text">
                        {if $languages|count > 1}
                            </div>
                            <div class="col-xs-2">
                               <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$lang.iso_code}<i class="icon-caret-down"></i></button>
                               <ul class="dropdown-menu">
                                   {foreach from=$languages item=lng}
                                   <li><a href="javascript:hideOtherLanguage({$lng.id_lang});" tabindex="-1">{$lng.name}</a></li>
                                   {/foreach}
                               </ul>
                           </div>
                        </div>
                    {/if}
                {/foreach}
                    </div>
                    <div class="drp-rmv col-xs-1"><button type="button" class="btn btn-small" title="{l s='Remove' d='Admin.Actions'}"><i class="icon-trash" aria-hidden="true"></i></button></div>
                </div>
            </li>
        {/foreach}
    {else}
        <li id="selector_0" class="selector_item">
            <div class="row">
                <div class="drp-drag col-xs-1"><i class="icon-bars" aria-hidden="true"></i></div>
                <div class="drp-val col-xs-4"><input value="" placeholder="{l s='Value' mod='dwfproductextrafields'}" type="text"></div>
                <div class="drp-opt col-xs-6">
                {foreach from=$languages item=lang}
                    {if $languages|count > 1}
                    <div class="translatable-field lang-{$lang.id_lang}"{if $lang.id_lang != $default_lang} style="display:none;"{/if}>
                        <div class="col-xs-9">
                    {/if}
                            <input value="" placeholder="{l s='Option text' mod='dwfproductextrafields'}" type="text">
                    {if $languages|count > 1}
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$lang.iso_code}<i class="icon-caret-down"></i></button>
                            <ul class="dropdown-menu">
                            {foreach from=$languages item=lng}
                                <li><a href="javascript:hideOtherLanguage({$lng.id_lang});" tabindex="-1">{$lng.name}</a></li>
                            {/foreach}
                            </ul>
                        </div>
                    </div>
                    {/if}
                {/foreach}
                </div>
                <div class="drp-rmv col-xs-1"><button type="button" class="btn btn-small" title="{l s='Remove' d='Admin.Actions'}"><i class="icon-trash" aria-hidden="true"></i></button></div>
            </div>
        </li>
    {/if}
        </ul>
        <div class="banner_down">
           <div class="col-xs-6"><label for="selector_multi"><span>{l s='Multiple:' mod='dwfproductextrafields'}</span> <input id="selector_multi" type="checkbox"{if isset($config.multiple) && $config.multiple} checked="checked"{/if} /></label></div>
           <div class="col-xs-6"><button type="button" id="selector_ad-optn" class="btn btn-small">{l s='Add option' mod='dwfproductextrafields'} <i class="icon-plus" aria-hidden="true"></i></button></div>
        </div>

        <script type="text/javascript">
        {literal}
        $(document).ready(function() {
            template_new_row = "<div class=\"row\"><div class=\"drp-drag col-xs-1\"><i class=\"icon-bars\" aria-hidden=\"true\"></i></div><div class=\"drp-val col-xs-4\"><input placeholder=\"{/literal}{l s='Value' mod='dwfproductextrafields'}{literal}\" type=\"text\"></div><div class=\"drp-opt col-xs-6\">";
            languages.map(function (lang) {
                if (languages.length > 1) {
                    template_new_row += "<div class=\"translatable-field lang-" + lang.id_lang + "\"" + (lang.id_lang != id_language ? " style=\"display:none\"" : "") + "><div class=\"col-xs-9\">";
                }
                template_new_row += "<input value=\"\" placeholder=\"{/literal}{l s='Option text' mod='dwfproductextrafields'}{literal}\" type=\"text\">";
                if (languages.length > 1) {
                    template_new_row += "</div><div class=\"col-xs-2\"><button type=\"button\" class=\"btn btn-default dropdown-toggle\" tabindex=\"-1\" data-toggle=\"dropdown\">" + lang.iso_code + "<i class=\"icon-caret-down\"></i></button><ul class=\"dropdown-menu\">";
                    languages.map(function (lng) {
                        template_new_row += "<li><a href=\"javascript:hideOtherLanguage(" + lng.id_lang + ");\" tabindex=\"-1\">" + lng.name + "</a></li>";
                    });
                    template_new_row += "</ul></div></div>";
                }
            });
            template_new_row += "</div><div class=\"drp-rmv col-xs-1\"><button type=\"button\" class=\"btn btn-small\" title=\"{/literal}{l s='Remove' d='Admin.Actions'}{literal}\"><i class=\"icon-trash\" aria-hidden=\"true\"></i></button></div></div>";
        });
        {/literal}
        </script>
    </div>
</div>
