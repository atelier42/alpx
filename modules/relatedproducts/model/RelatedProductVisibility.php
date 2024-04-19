<?php
/**
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
 */

class RelatedProductVisibility extends ObjectModel
{
    public $id_related_products_visibility;

    public $id_related_products_rules;

    public $selected_opt;

    public $selected_cat;

    public $selected_prod;

    public $active;

    public $title;

    public static $definition = array(
        'table' => 'related_products_visibility',
        'primary' => 'id_related_products_visibility',
        'fields' => array(
            'id_related_products_rules' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
            ),
            'selected_opt' => array('type' => self::TYPE_INT),
            'selected_cat' => array('type' => self::TYPE_STRING),
            'selected_prod' => array('type' => self::TYPE_STRING),
            'title' => array('type' => self::TYPE_STRING),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    public function selectRelatedProducts()
    {
        $id_shop = Context::getContext()->shop->id;
        $id_lang = Context::getContext()->language->id;
        $sql = new DbQuery();
        $sql->select('rl.id_related_products_rules, rll.titles, rl.id_page');
        $sql->from('related_products_rules', 'rl');
        $sql->innerJoin(
            'related_products_rules_shop',
            'rls',
            'rl.id_related_products_rules=rls.id_related_products_rules'
        );
        $sql->innerJoin(
            'related_products_rules_lang',
            'rll',
            'rl.id_related_products_rules=rll.id_related_products_rules'
        );
        $sql->where('rl.active=1');
        $sql->where('rls.id_shop=' . (int) $id_shop);
        $sql->where('rll.id_lang=' . (int) $id_lang);
        return Db::getInstance()->executeS($sql);
    }

    public static function getImageTypes()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('image_type');
        return Db::getInstance()->executeS($sql);
    }
}
