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
    .text-center{
        text-align: center;
    }
    .text-left{
        text-align:left;
    }
    .text-right{
        text-align: right;
    }
    .fmm_dimension{
        margin-bottom: 20px;
        margin-top: 20px;
        text-align: center;
        font-weight: 600;
    }

            /*REASSURANCE BOX STYLE*/
    @media only screen and (min-device-width : 768px) and (max-device-width : 1023px) {
        .fmm_dimension .fmm_table-grid .rp_span_align {
            font-size: 14px !important;
        }
    }
    .fmm_icon {
        background-color: transparent;
    }
    .fmm-is-opened .fmm_opener_card a {
        text-decoration: none;
    }
    .fmm_span_align{
        display: table-cell;
        vertical-align: middle;
        text-align: left;
        cursor: pointer;
        width: 100%;
    }
    .fmm_image_align{
        box-sizing: border-box;
        display: table-cell;
        vertical-align: middle;
        margin-right: 10px;
        width: 64px;
        height: 64px;
    }
    .fmm_table-grid {
        display: table;
        margin: 20px;
    }
    .text-center.fmm_dimension .col-md-3:not(:last-child) {
        border-right: 2px solid #ededed;
        padding: 0px !important;
    }
    .fmm_dimension span {
        position:relative;
    }
    .fmm_link_style, .fmm_link_style:hover{
        text-decoration: none;
        color: #000000;
    }
          /**********  RESPONSIVE  **********/
       /*This you can edit the responsive tabs*/
    /**********  MIN-WIDTH: 768 AND MAX-WIDTH: 1023  **********/

    @media screen and (max-width: 319px) {
        .fmm_table-grid.con-tooltip.bottom {
            margin: 5px 0px 0px 10px;
        }
        img.table-cell.fmm_image_align {
            margin: 10px 10px 10px 0px;
        }
    }

    @media screen and (max-width: 374px) and (min-width: 320px)  {
        .fmm_table-grid.con-tooltip.bottom {
            margin: 5px 0px 0px 10px;
        }
        img.table-cell.fmm_image_align {
            margin: 10px 10px 10px 0px;
        }
    }
    @media screen and (max-width: 425px){
        .col-md-12.text-center.fmm_dimension .col-md-3 {
            border-bottom: 0px solid #ededed !important;
        }
        .row.text-center.fmm_dimension {
            display: inline-grid !important;
        }
        .text-center.fmm_dimension .col-md-3:not(:last-child) {
            border-bottom: 15px solid #ededed;
        }
        .row.text-center.fmm_dimension {
            display: table-row;
        }
        .col-md-12.text-center.empty_class {
            margin-bottom: 30px;
        }
        .col-md-12.empty_class {
            margin-bottom: 30px;
        }
        .text-center.fmm_dimension.fmm_style_box {
            height: 100%;
        }
        .col-md-3.rp_padding_0.cbc {
            border-bottom: 3px solid #ededed !important;
        }
        .col-md-3.rp_padding_0.rp_style2_box:not(:last-child) {
            border-bottom: 7px solid #ededed !important;
        }
    }
    @media screen and (max-width: 424px) and (min-width: 375px)  {
        .fmm_table-grid.con-tooltip.bottom {
            margin: 5px 0px 0px 10px;
        }
        img.table-cell.fmm_image_align {
            margin: 10px 10px 10px 0px;
        }
    }
    @media screen and (max-width: 425px){
        .card {
            margin: 10px;
        }
        .row.text-center.fmm_dimension.fmm_style_box {
            margin-left: 0px;
            margin-right: 0px;
        }
        #hover2 .row.text-center.fmm_dimension {
            margin-left: 0px;
            margin-right: 0px;
        }
    }

    @media screen and (max-width: 767px) and (min-width: 425px)  {
        .fmm_table-grid.con-tooltip.bottom {
            margin: 5px 0px 0px 10px;
        }
        img.table-cell.fmm_image_align {
            margin: 10px 10px 10px 0px;
        }
    }
    @media screen and (width: 768px) {
        .col-md-12.cards {
            margin-left: 30px;
        }
        h3.rp_title_style2 {
            font-size: 14px;
        }
        p.fmm_margin_10.rp_font_size_13.rp_fmm_sub_title2 {
            font-size: 12px;
        }
        span.fmm_sub_title.rp_black_color {
            left: 5px;
            font-size: 11px;
        }
        .fmm_style_box .fmm_table-grid .rp_margin_10 {
            left: 5px;
        }
        .fmm_table-grid.bottom{
            margin: 5px;
            font-size: 10px;
        }
        .card.fmm-is-closed.col-md-3 {
            width: 50%;
            margin: 10px;
        }
        .empty_class .card.fmm-is-closed.col-md-3 {
            width: calc((100% / 2) - 30px);
        }
        .empty_class .col-md-12.cards{
            margin-left: 0px;
        }
    }

    @media screen and (min-width: 768px) and (max-width: 1023px) {
        .card:nth-of-type(2n+2) .fmm_opener_card {
            margin-left: calc(-100% - 10px);
        }
        .card:nth-of-type(2n+3) .fmm_opener_card {
            margin-left: calc(0% - 0px);
        }
        .card:nth-of-type(2n+4) .fmm_opener_card {
            clear: left;
        }
        .fmm_opener_card {
            width: calc(200% + 10px);
        }
        .col-md-12 .cards {
            margin-left: 40px;
        }
        .fmm_dimension .fmm_table-grid .rp_span_align {
            font-size: 12px !important;
        }
        .fmm_table-grid.con-tooltip.bottom {
            margin: 5px;
        }
        img.table-cell.fmm_image_align {
            margin: 5px 5px 5px 5px;
        }
    }
    @media screen and (min-width: 992px) and (max-width: 1199px) {
        /*.row.text-center.fmm_dimension {*/
            /*width: 92%;*/
        /*}*/
        .fmm_table-grid.con-tooltip.bottom {
            margin: 10px;
        }
    }
        /**********  MIN-WIDTH: 360 AND MAX-WIDTH: 479  **********/
    @media screen and (min-width: 360px) and (max-width: 479px) {
        /*.col-md-12 .cards .card span {*/
            /*font-size: .875rem !important;*/
            /*margin-left: 0px !important;*/
            /*position: relative !important;*/
        /*}*/
    }

        /**********  MAX-WIDTH: 1023  **********/
    @media only screen and (max-width : 1023px) {
        /*.fmm_dimension {*/
            /*display: none;*/
        /*}*/
        /*.cards {*/
            /*display: none;*/
        /*}*/
    }

        /**********  MIN-WIDTH: 1024 AND MAX-WIDTH: 1365  **********/
    @media screen and (min-width: 1024px) and (max-width: 1365px) {
        .cards .card span {
            font-size: 12px;
        }
    }

        /**********  MAX-WIDTH: 1280  **********/
    @media only screen and (max-device-width : 1280px) {
        .fmm_dimension .col-md-3 {
            padding-right: 0px;
            padding-left: 0px;
        }
        /*.row.text-center.fmm_dimension .col-md-3:not(:last-child){*/
            /*border-right: none !important;*/
        /*}*/
    }
    </style>
{/literal}