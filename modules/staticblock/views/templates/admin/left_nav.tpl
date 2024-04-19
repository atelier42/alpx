{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2018 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="fme-nav-menu">
    <!-- <ul> -->
        <a class="tab-page" id="static_block_blocks" href="javascript:displayStaticBlocks('blocks');">
            <span class="tab-row">
                <i class="icon-archive"></i> {l s='Static Blocks' mod='staticblock'}
            </span>
        </a>
        <a class="tab-page" id="static_block_templates" href="javascript:displayStaticBlocks('templates');">
            <span class="tab-row">
                <i class="icon-tag"></i> {l s='Templates' mod='staticblock'}
            </span>
        </a>

        <a class="tab-page" id="static_block_customhook" href="javascript:displayStaticBlocks('customhook');">
            <span class="tab-row">
                <i class="icon-archive"></i> {l s='Custom hook' mod='staticblock'}
            </span>
        </a>
        <a class="tab-page" id="static_block_settings" href="javascript:displayStaticBlocks('settings');">
            <span class="tab-row">
                <i class="icon-cogs"></i> {l s='Settings' mod='staticblock'}
            </span>
        </a>
        <a class="tab-page" id="static_block_reassurance" href="javascript:displayStaticBlocks('reassurance');">
            <span class="tab-row">
                <i class="icon-archive"></i> {l s='Reassurance Block' mod='staticblock'}
            </span>
        </a>
        <a class="tab-page" id="static_block_rsetting" href="javascript:displayStaticBlocks('rsetting');">
            <span class="tab-row">
                <i class="icon-cogs"></i> {l s='Reassurance Setting' mod='staticblock'}
            </span>
        </a>
    <!-- </ul> -->
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    var currentTab = "{if isset($smarty.get.currentTab)}{$smarty.get.currentTab|escape:'htmlall':'UTF-8'}{elseif isset($currentTab) && $currentTab}{$currentTab|escape:'htmlall':'UTF-8'}{else}blocks{/if}";
    $(document).ready(function(){
        displayStaticBlocks(currentTab);
        $('#static_block_' + currentTab).find('.tab-row').trigger('click');
    })
    $(document).on('hover','.config', function(){
        $('.tab').show();
    });
    $(document).on('click', '.tab-row', function() {
        $('.inner-nav').removeClass('selected-nav');
        if ($(this).hasClass('home')) {
            $(this).find('.inner-nav').addClass('selected-nav');
        } else {
            $(this).parent().parent().parent().addClass('selected-nav');
        }
    });

    function displayStaticBlocks(tab) {
        $(".loader").show();
        $('.static_block_tab').hide();
        $('.tab-page').removeClass('selected');
        $('#staticblock_' + tab).show();
        $('#static_block_' + tab).addClass('selected');
        $('#currentTab').val(tab);
        $('.tab').hide();
        $(".loader").fadeOut("slow");
    }
</script>
{literal}
<style type="text/css">
    #fme-nav-menu {
        background: #fff none repeat scroll 0 0;
        border-radius: 4px;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
    }
    #fme-nav-menu a.tab-page:hover, #fme-nav-menu a.tab-page.selected {
        background: #282B30 none repeat scroll 0 0;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
        color: #ddd!important;
        opacity: 1;
    }
    #fme-nav-menu .tab-page {
        border-bottom: 1px solid #eee;
        display: block;
        padding: 15px;
    }
    #fme-nav-menu a {
        color: #555;
        text-decoration: none;
        font-weight: bold;
    }
    .loader {
      background: url({/literal}{$smarty.const.__PS_BASE_URI__}{literal}modules/staticblock/views/img/spinner.gif) no-repeat scroll center center #fff;
      height: 100%;
      left: 0;
      opacity: 0.85;
      position: absolute;
      top: 0;
      width: 100%;
      z-index: 99;
    }
</style>
{/literal}
