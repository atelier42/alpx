{**
 *   Copyright since 2009 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright since 2009 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 *}

{if !empty($fields_name) AND $fields_name|count}
{foreach $fields_name as $field}
<div class="row extraField_{$field.id|intval}">
    <div class="col-12 col-sm-3 text-right"><label>{$field.label}</label></div>
	<div class="col-12 col-sm-9">{$field.content nofilter}</div>
</div>
{/foreach}
{/if}
