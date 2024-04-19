{**
 *   2009-2021 ohmyweb!
 *
 *   @author	ohmyweb <contact@ohmyweb.fr>
 *   @copyright 2009-2021 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
*}

<div class="alert alert-info" role="alert">
    <p><strong>{l s='To use this field call:' mod='dwfproductextrafields'}</strong></p>
    {if $field.type == 'image'}
        <p><code>{ldelim}if $product.dwf_{$field.fieldname}{rdelim}&lt;img src="{ldelim}$product.dwf_{$field.fieldname}{rdelim}" /&gt;{ldelim}/if{rdelim}</code></p>
    {elseif $field.type == 'repeater'}
        {assign var=config value=$field.config|json_decode:true}
        <p>
        <code>
        {ldelim}foreach from=$product.dwf_{$field.fieldname} item=rep_element{rdelim}<br />
            &nbsp;&nbsp;&lt;div&gt;<br />
            {foreach from=$config.elements item=element}
                {if $element.type == 'image'}
                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;{ldelim}if $rep_element.{$element.key}{rdelim}&lt;img src="{ldelim}$rep_element.{$element.key}{rdelim}" /&gt;{ldelim}/if{rdelim}&lt;/p&gt;<br />
                {elseif $element.type == 'textarea_mce'}
                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;{ldelim}$rep_element.{$element.key} nofilter{rdelim}&lt;/p&gt;<br />
                {else}
                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;{ldelim}$rep_element.{$element.key}{rdelim}&lt;/p&gt;<br />
                {/if}
            {/foreach}
            &nbsp;&nbsp;&lt;/div&gt;<br />
        {ldelim}/foreach{rdelim}
        </code>
        </p>
    {elseif $field.type == 'textarea_mce'}
      <p><code>{ldelim}$product.dwf_{$field.fieldname} nofilter{rdelim}</code></p>
    {elseif $field.type == 'checkbox'}
        <p><code>{ldelim}if $product.dwf_{$field.fieldname}{rdelim}...{ldelim}/if{rdelim}</code></p>
    {else}
        <p><code>{ldelim}$product.dwf_{$field.fieldname}{rdelim}</code></p>
    {/if}
</div>
