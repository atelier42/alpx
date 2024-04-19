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
        /**********  HOVER 2  **********/
            /*This you can edit the box for hover animation*/
        .fmm_style2_box {
            display: block;
            float: none;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            text-align: center;
            cursor: pointer;
        }
        .fmm_table_grid2{
            display: block;
            margin: 20px;
        }
        .fmm_icon{
            display: inline-block;
            padding: 1rem;
            border: none;
            -webkit-transition: all 600ms ease-in-out;
            -o-transition: all 600ms ease-in-out;
            transition: all 600ms ease-in-out;
            border-radius: 200px;
        }
        .fmm_dimension .fmm_style2_box:hover .fmm_icon{
            animation-name: rotate;
            animation-duration: 700ms;
            -webkit-animation-name: rotate;
            -webkit-animation-duration: 700ms;
            transition: all 800ms ease-in-out;
            background: {/literal}{$h2_back|escape:'htmlall':'UTF-8'}{literal};
            box-shadow: 0px 0px 0px 5px {/literal}{$h2_hover|escape:'htmlall':'UTF-8'}{literal};
        }
        @-webkit-keyframes rotate {
            from {
                -webkit-transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(360deg);
            }
        }
        .fmm_dimension .fmm_style2_box:hover .fmm_title_style2{
            color: {/literal}{$h2_hover|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_title_style2{
            border-bottom: 1px solid #eee;
            font-size: 16px;
            padding: 0.75em 0;
            margin: 0 0 0.75em 0;
            position: relative;
            text-align: center;
            -webkit-transition: all 600ms ease-in-out;
            -o-transition: all 600ms ease-in-out;
            transition: all 600ms ease-in-out;
            height: 70px;
            color: {/literal}{$h2_title|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_image_size{
            width: 64px;
            height: 64px;
        }
        .fmm_subtitle_style2 p{
            margin: 0;
            position: relative;
            font-size: 14px;
            text-shadow: none;
            color: {/literal}{$h2_subtitle|escape:'htmlall':'UTF-8'}{literal};
        }
        #hover2 {}
        .empty_class{}
    </style>
{/literal}