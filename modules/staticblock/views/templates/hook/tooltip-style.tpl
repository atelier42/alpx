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
            /**********  TOOLTIP  **********/
            /*This you can edit the box for tooltip animation*/
        .fmm_dimension span[data-tooltip]:hover:before {
            content: attr(data-tooltip);
            position: absolute;
            width: 300px;
            padding: 5px;
            background-color: rgba(51, 51, 51, 0.9);
            color: white;
            text-align: left;
            font-size: 12px;
            bottom: calc(100% + 0px);
            left: 34%;
            -webkit-transform: translate(-50%, -3px);
            transform: translate(-50%, -3px);
        }
        .fmm_dimension .col-md-3 {
            margin-left:3px;
        }
        .fmm-color{
            color: {/literal}{$t_title|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_text {
            display: block;
            width: 100px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /*This you can edit the arrow from tooltip*/
        .fmm_dimension span[data-tooltip]:hover:after {
            position: absolute;
            bottom: calc(100% + -1px);
            right: 63%;
            border-top: 5px solid rgba(51, 51, 51, 0.9);
            border-right: 10px solid transparent;
            border-left: 10px solid transparent;
            content: " ";
        }
        .fmm_sub_titles{
            top: 2px;
            left: 10px;
            color: {/literal}{$t_subtitle|escape:'htmlall':'UTF-8'}{literal};
        }
        .bottom .tooltip {
            top:108%;
            left:0%;
        }
        .fmm_padding_0{
            padding: 0px !important;
        }
        .fmm_margin_10{
            margin: 10px;
            color: {/literal}{$t_desc|escape:'htmlall':'UTF-8'}{literal};
        }
        .con-tooltip {
            position: static;
            border-radius: 9px;
            margin: 20px;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

                    /*Tooltip */
        .tooltip {
            visibility: hidden;
            z-index: 1;
            opacity: .40;
            width: 100%;
            padding: 0px 20px;
            background: #333;
            color: #E086D3;
            position: absolute;
            top:-140%;
            left: -25%;
            border-radius: 9px;
            font-size: 16px;
            transform: translateY(9px);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 0 3px rgba(56, 54, 54, 0.86);
        }
        /* tooltip  after*/
        .tooltip::after {
            content: " ";
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 12px 12.5px 0 12.5px;
            border-color: #333 transparent transparent transparent;
            position: absolute;
            left: 45%;
            top: -12px;
            transform: rotate(180deg);
        }
        /*tooltip hover*/
        .con-tooltip:hover .tooltip{
            position: absolute;
            margin-top: -20px;
            visibility: visible;
            transform: translateY(-10px);
            opacity: 1;
            transition: .3s linear;
            animation: odsoky 1s ease-in-out infinite  alternate;
            background: {/literal}{$t_hover|escape:'htmlall':'UTF-8'}{literal};
        }
        @keyframes odsoky {
            0%{
                transform: translateY(6px);
            }
            100%{
                transform: translateY(1px);
            }
        }
    </style>
{/literal}