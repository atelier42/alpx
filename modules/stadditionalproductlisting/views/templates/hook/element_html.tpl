{*
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*}

{if $element_type eq 'duplicate_button_link'}
	<li>
		<a href="{$st_data.link}" title="{$st_data.name}">
		<i class="icon-copy"></i> {$st_data.name}
		</a>
	</li>
{/if}

{if $element_type eq 'form_desc'}
	{if $st_data.found}
	<a href="javascript:void(0)" class="check-uncheck"
	id="filter_{$st_data.element|escape:'htmlall':'UTF-8'}">{l s='Select All/Unselect All' mod='stadditionalproductlisting'}</a><br/>
	{else}
	<h4>{l s="There is no [1] found. Please create new [1] first from" sprintf=['[1]' => $st_data.element|escape:'htmlall':'UTF-8'] mod='stadditionalproductlisting'}
	<a href="{$st_data.element_link|escape:'htmlall':'UTF-8'}" target="_blank">{l s='here' mod='stadditionalproductlisting'}</a>
	</h4>
	{/if}
	{l s="Select the specific [1]s from which the products will be displayed." sprintf=['[1]' => $st_data.element|escape:'htmlall':'UTF-8'] mod='stadditionalproductlisting'}
{/if}
