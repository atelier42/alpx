{**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2020 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if !empty($block)}
    <div class="modal fade" id="show-popup-{$block['id_static_block']|escape:'htmlall':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        {if $block['title_active'] }
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{$block['block_title']|escape:'htmlall':'UTF-8'}</h4>
            </div>
        {/if}
        <div class="modal-body">
            {$block['content'] nofilter}{*HTML Content*}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    <script>
        var id = {$block['id_static_block']|escape:'htmlall':'UTF-8'};
        $(document).ready(function(){
            $('#show-popup-'+id).modal('show');
        });
    </script>
{/if}