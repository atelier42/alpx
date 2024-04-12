<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2022 WebshopWorks.com
 * @license   One domain support license
 */
defined('_PS_VERSION_') or die;
class Category extends CategoryCore
{
    /*
    * module: creativeelements
    * date: 2023-10-30 10:35:13
    * version: 2.5.11
    */
    const CE_OVERRIDE = true;
    /*
    * module: creativeelements
    * date: 2023-10-30 10:35:13
    * version: 2.5.11
    */
    public function __construct($idCategory = null, $idLang = null, $idShop = null)
    {
        parent::__construct($idCategory, $idLang, $idShop);
        $ctrl = Context::getContext()->controller;
        if ($ctrl instanceof CategoryController && !CategoryController::$initialized && !$this->active && Tools::getIsset('id_employee') && Tools::getIsset('adtoken')) {
            $tab = 'AdminCategories';
            if (Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . (int) Tools::getValue('id_employee')) == Tools::getValue('adtoken')) {
                $this->active = 1;
            }
        }
    }
}
