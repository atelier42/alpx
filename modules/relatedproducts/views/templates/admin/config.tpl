{*
* Related Products
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
*  @author    FME Modules
*  @copyright 2021 fmemodules All right reserved
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*
*}
<script type="text/javascript">
var selected_shops = "{$selected_shops|escape:'htmlall':'UTF-8'}";
$(document).ready(function()
{
    // shop association

    $(".tree-item-name input[type=checkbox]").each(function()
    {
        $(this).prop("checked", false);
        $(this).removeClass("tree-selected");
        $(this).parent().removeClass("tree-selected");
        if ($.inArray($(this).val(), selected_shops) != -1)
        {
            $(this).prop("checked", true);
            $(this).parent().addClass("tree-selected");
            $(this).parents("ul.tree").each(
                function()
                {
                    $(this).children().children().children(".icon-folder-close")
                        .removeClass("icon-folder-close")
                        .addClass("icon-folder-open");
                    $(this).show();
                }
            );
        }
    });
})
</script>