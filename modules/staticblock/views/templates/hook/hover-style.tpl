{*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2020 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{literal}
    <style>
                /**********  HOVER  **********/
        /*This you can edit the box for hover animation*/
        .fmm_style_box {
            line-height: 15px;
            border: 1px solid #c2b9b3;
        }
        .fmm_style_box div {
            display: table;
            text-decoration: none;
            vertical-align: middle;
        }
        .fmm_dimensions{
            margin-bottom: 20px;
            margin-top: 20px;
            text-align: center;
            font-weight: 600;
            background-color: {/literal}{$h_back_hover|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_black_color{
            font-size:15px;
            color: {/literal}{$h_title|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_span_align p{
            color: {/literal}{$h_subtitle|escape:'htmlall':'UTF-8'}{literal};
            
        }
        .fmm_style_box .fmm_table-grid img {
            width: 64px !important;
            height: 64px !important;
            margin-top: 3px;
            position: relative;
            left: -5%;
            top: 0;
            margin-right: 0px;
        }
        .fmm_style_box .fmm_table-grid .fmm_margin_10 {
            position: relative;
            top: 2px;
            left: 10px;
            margin: 0px;
        }
        .fmm_style_box .fmm_padding_0:hover{
            background-color: #F0EDEC;
            text-decoration: none;
        }
        .text-center.fmm_dimensions.fmm_style_box {
            height: 120px;
        }
        .text-center.fmm_dimensions.fmm_style_box > * {
            height: 100%;
            display: flex;
        }
        .text-center.fmm_dimensions.fmm_style_box a.fmm_link_style {
            align-self: center;
        }
        .fmm_style_box .fmm_padding_0:last-child:hover {
            border-radius: 0 10px 10px 0;
        }
        .fmm_style_box .fmm_padding_0:first-child:hover {
            border-radius: 9px 0 0 9px;
        }
    </style>
{/literal}