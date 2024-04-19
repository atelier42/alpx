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
            /**********  COLLAPSE  **********/
        /*This you can edit the box for collapse animation*/
        .cards .card span {
            margin-left: 10px;
            text-align: left;
            align-self: center;
        }
        .cards .card img {
            width: 64px;
            height: 64px;
        }
        .containerTab {
            padding: 20px;
            color: white;
        }
        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        /* Closable button inside the container tab */
        .closebtn {
            float: right;
            color: white;
            font-size: 35px;
            cursor: pointer;
        }
        .cards {
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            flex-flow: row wrap;
        }
        .card {
            padding: 5px;
            position: static;
            transition: all 0.2s ease-in-out;
            box-shadow: none;
            border: none;
            background: transparent;
        }
        @media screen and (max-width: 991px) {
            .card {
                width: calc((100% / 2) - 30px);
            }
        }
        @media screen and (max-width: 767px) {
            .card {
                width: 100%;
            }
        }
        .card:hover .fmm_inner_card {
            background-color:{/literal}{$c_hover|escape:'htmlall':'UTF-8'}{literal};
            transform: scale(1.05);
        }
        .fmm-sub_title {
            color: {/literal}{$c_subtitle|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_cl_desc {
            color:{/literal}{$c_desc|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_inner_card {
            box-shadow: 0px 8px 8px 0px rgba(0,0,0,.2);
            display: flex;
            width: 100%;
            padding: 15px;
            position: relative;
            cursor: pointer;
            background-color: #D3D3D3;
            color: #000000;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            transition: all 0.2s ease-in-out;
        }
        .fmm_inner_card .fa {
            width: 100%;
            margin-top: 0.25em;
        }
        .fmm_opener_card {
            width: 100%;
            position: relative;
            justify-content: center;
            align-items: center;
            text-align: left;
            background-color: {/literal}{$c_back|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_opener_card .fa {
            font-size: 0.75em;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        .fmm_opener_card .fa:hover {
            opacity: 0.9;
        }
        .card.fmm-is-closed .fmm_inner_card:after {
            content: "";
            opacity: 0;
        }
        .card.fmm-is-closed .fmm_opener_card {
            max-height: 0;
            min-height: 0;
            overflow: hidden;
            margin-top: 0;
            opacity: 0;
        }
        .card.fmm-is-opened .fmm_inner_card {
            background-color: {/literal}{$c_hover|escape:'htmlall':'UTF-8'}{literal};
        }
        .fmm_inner_card:before {
            transition: all 0.3s ease-in-out;
        }
        .card.fmm-is-opened .fmm_inner_card:before {
            content: "";
            position: absolute;
            bottom: -33px;
            left: calc(50% - 15px);
            border-left: 17px solid transparent;
            border-right: 17px solid transparent;
            border-bottom: 17px solid {/literal}{$c_arrow|escape:'htmlall':'UTF-8'}{literal};
        }
        .card.fmm-is-opened .fmm_inner_card .fa:before {
            content: "\f115";
        }
        .card.fmm-is-opened .fmm_opener_card {
            max-height: 50%;
            min-height: 100px;
            overflow: visible;
            margin-top: 31px;
            opacity: 1;
            padding: 30px;
        }
        .card.fmm-is-opened:hover .fmm_inner_card {
            transform: scale(1);
        }
        .card.is-inactive .fmm_inner_card {
            pointer-events: none;
            opacity: 0.5;
        }
        .card.is-inactive:hover .fmm_inner_card {
            background-color: #949fb0;
            transform: scale(1);
        }
        @media screen and (min-width: 992px) {
            .card:first-child .fmm_opener_card {
                margin-left: calc(4% - 36px);
            }
            .card:nth-of-type(3n+2) .fmm_opener_card {
                margin-left: calc(-100% - 36px);
            }
            .card:nth-of-type(3n+3) .fmm_opener_card {
                margin-left: calc(-200% - 46px);
            }
            .card:nth-of-type(3n+4) .fmm_opener_card {
                margin-left: calc(-300% - 56px);
            }
            .card:nth-of-type(3n+5) .fmm_opener_card {
                clear: left;
            }
            .fmm_opener_card {
                width: calc(400% + 80px);
            }
        }
        @media screen and (min-width: 768px) and (max-width: 991px) {
            .card:nth-of-type(2n+2) .fmm_opener_card {
                margin-left: calc(-100% - 30px);
            }
            .card:nth-of-type(2n+3) .fmm_opener_card {
                margin-left: calc(-120% - 40px);
            }
            .card:nth-of-type(2n+4) .fmm_opener_card {
                clear: left;
            }
            .fmm_opener_card {
                width: calc(250% + 40px);
            }
        }
        .fmm-title {
            color: {/literal}{$c_title|escape:'htmlall':'UTF-8'}{literal};
        }
    </style>
{/literal}