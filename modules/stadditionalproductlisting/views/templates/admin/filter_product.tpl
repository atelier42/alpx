{*
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*}

<style>
#ajax_list{
	list-style: none;
	position: absolute;
	z-index: 3;
	background: #fff;
	width: 98%;
	box-shadow: 0px 10px 15px 0 rgba(154, 154, 154, 0.8);
	padding: 5px;
	display: none;
	max-height: 388px;
    overflow: auto;
}
#ajax_list > div, #product-list > div {
	border-bottom: 1px solid #eee;
	padding: 2px 10px;
	margin-bottom: 5px;
}
#ajax_list > div{
	cursor: pointer;
}
#ajax_list i.icon-trash{
	display: none;
}
#product-list{
	float: left;
    width: 100%;
	max-height: 300px;
	overflow: auto;
}
#product-list i.icon-trash{
	cursor: pointer;
	margin-top: -10px;
}
#ajax_list > div:hover{
	background: #edf7fb;
}
#filter_product .panel-body{
	padding: 0;
}
.list-group-item img{
	width: 55px;
}
.no-data {
    display: none;
	margin: 0px !important;
}
.panel-body #product-list:empty {
    display: none;
}
.panel-body #product-list:empty + .no-data {
    display: block;
}
</style>
<div id="filter_product" class="panel">
	<div class="tree-panel-heading-controls clearfix">
		<div class="input-group">
		    <input type="text" id="product-search" class="search-field tt-query" placeholder="search..." autocomplete="off" spellcheck="false" dir="auto" />
			<div class="input-group-addon">
				<i class="icon-search"></i>
			</div>
	    </div>
		<div id="ajax_list"></div>
	</div>
	<div class="panel-body">
	    <div class="list-group" id="product-list">{foreach from=$products item="product"}
			<div class="list-group-item col-lg-12">
				<div class="col-lg-2">
					<img src="{$product.image|escape:'htmlall':'UTF-8'}" alt="{$product.name|escape:'htmlall':'UTF-8'}" />
				</div>
				<div class="col-lg-10">
					<h4>{$product.name|escape:'htmlall':'UTF-8'}</h4>
					<em>{l s='Reference:' mod='stadditionalproductlisting'} {$product.reference|escape:'htmlall':'UTF-8'}</em>
					<i class="icon-trash pull-right"></i>
					<input type="hidden" name="filter_product[]" value="{$product.id_product|intval}" />
				</div>
			</div>
	    {/foreach}</div>
		<h4 class="no-data">{l s='There is no product selected yet, you can choose specific products by using search box.' mod='stadditionalproductlisting'}</h4>
	</div>
</div>
