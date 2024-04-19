<?php
/**
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
 */

class StaticReassuranceModel extends ObjectModel
{
    public $id_fmm_reassurance;

    public $status;

    public $image;

    public $link;

    public $apperance;

    public $title;

    public $sub_title;

    public $description;

    public static $definition = array(
        'table' => 'fmm_reassurance',
        'primary' => 'id_fmm_reassurance',
        'multilang' => true,
        'multishop' => true,
        'fields' => array(
            'status' => array('type' => self::TYPE_INT),
            'image' => array('type' => self::TYPE_STRING),
            'link' => array('type' => self::TYPE_STRING),
            'apperance' => array('type' => self::TYPE_STRING),
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 1000,
            ),
            'sub_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 1000,
            ),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );

    public function __construct($id_fmm_reassurance = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_fmm_reassurance, $id_lang, $id_shop);
    }

    public static function getReassuranceBlock($id_lang)
    {
        $sql = new DbQuery();
        $sql->select('rb.*, rbl.*');
        $sql->from(self::$definition['table'], 'rb');
        $sql->leftJoin(
            self::$definition['table'] . '_lang',
            'rbl',
            'rbl.id_fmm_reassurance = rb.id_fmm_reassurance'
        );
        $sql->where('rbl.id_lang=' . (int) $id_lang);
        $sql->orderBy('rb.id_fmm_reassurance');
        return Db::getInstance()->executeS($sql);
    }

    public static function getReassuranceDetail($id_lang, $id_shop, $apperance)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table'], 'rb');
        $sql->leftJoin(
            self::$definition['table'] . '_lang',
            'rbl',
            'rbl.id_fmm_reassurance = rb.id_fmm_reassurance'
        );
        if (Shop::isFeatureActive() && $id_shop) {
            $sql->leftJoin(
                self::$definition['table'] . '_shop',
                'rbs',
                'rb.id_fmm_reassurance = rbs.id_fmm_reassurance AND rbs.id_shop = ' . (int) $id_shop
            );
        }
        $sql->where('rb.apperance = "' . pSQL($apperance) . '"');
        $sql->where('rbl.id_lang=' . (int) $id_lang);
        $sql->where('rb.status=1');
        $sql->orderBy('rb.id_fmm_reassurance');
        return Db::getInstance()->executeS($sql);
    }

    public static function countListContent()
    {
        $sql = new DbQuery();
        $sql->select('COUNT(id_fmm_reassurance)');
        $sql->from('fmm_reassurance');
        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public static function updateStatus($field, $id_fmm_reassurance)
    {
        return (bool) Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'fmm_reassurance`
            SET `' . pSQL($field) . '` = !' . pSQL($field) .
            ' WHERE id_fmm_reassurance = ' . (int) $id_fmm_reassurance);
    }

    public static function getReassuranceBlockById($id_fmm_reassurance, $id_shop = null)
    {
        if (!$id_fmm_reassurance) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }
        $sql = new DbQuery();
        $sql->select('sb.*');
        $sql->from(self::$definition['table'], 'sb');
        if (Shop::isFeatureActive() && $id_shop) {
            $sql->leftJoin(
                self::$definition['table'] . '_shop',
                'sbs',
                'sb.id_fmm_reassurance = sbs.id_fmm_reassurance AND sbs.id_shop = ' . (int) $id_shop
            );
        }
        $sql->where('sb.id_fmm_reassurance = ' . (int) $id_fmm_reassurance);
        $result = Db::getInstance()->getRow($sql);
        if (isset($result) && $result) {
            $result['title'] = self::getLangBlockById('title', $id_fmm_reassurance);
            $result['sub_title'] = self::getLangBlockById('sub_title', $id_fmm_reassurance);
            $result['description'] = self::getLangBlockById('description', $id_fmm_reassurance);
        }
        return $result;
    }

    public static function getLangBlockById($field, $id_fmm_reassurance)
    {
        if (!$id_fmm_reassurance || empty($field)) {
            return false;
        }
        $sql = 'SELECT `id_lang`, `' . $field . '`
        FROM `' . _DB_PREFIX_ . 'fmm_reassurance_lang`
        WHERE `id_fmm_reassurance` = ' . (int) $id_fmm_reassurance;
        $final = array();
        $result = Db::getInstance()->executeS($sql);
        if ($result) {
            foreach ($result as $res) {
                $final[$res['id_lang']] = $res[$field];
            }
        }
        return $final;
    }

    public static function addDefaultValues()
    {
        $context = Context::getContext();
        return (Configuration::updateValue(
            'REASSURANCE_HOME_ANIMATION',
            1,
            false,
            $context->shop->id_shop_group,
            $context->shop->id
        ) &&
            Configuration::updateValue(
                'REASSURANCE_FOOTER_ANIMATION',
                2,
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_PRODUCT_ANIMATION',
                3,
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&

            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_TITLE_COLOR',
                '#000000',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_SUB_TITLE_COLOR',
                '#ffffff',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_DESCRIPTION_COLOR',
                '#7a7a7a',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_HOVER_COLOR',
                '#2fb5d2',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_BACKGROUND_COLOR',
                '#D3D3D3',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_LINK_COLOR',
                '#2fb5d2',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_COLLAPSE_ARROW_COLOR',
                '#333a45',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_TOOLTIP_TITLE_COLOR',
                '#000000',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_TOOLTIP_SUB_TITLE_COLOR',
                '#ffffff',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_TOOLTIP_DESCRIPTION_COLOR',
                '#7a7a7a',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_TOOLTIP_BACKGROUND_COLOR',
                '#ffffff',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_TOOLTIP_BACK_HOVER_COLOR',
                '#383636',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            // Hover Fields
            Configuration::updateValue(
                'REASSURANCE_HOVER_TITLE_COLOR',
                '#000000',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER_SUB_TITLE_COLOR',
                '#7a7a7a',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER_COLOR',
                '#ededed',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER_BACKGROUND_COLOR',
                '#ffffff',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER2_TITLE_COLOR',
                '#777777',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER2_SUB_TITLE_COLOR',
                '#777777',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER2_COLOR',
                '#2592a9',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ) &&
            Configuration::updateValue(
                'REASSURANCE_HOVER2_BACKGROUND_COLOR',
                '#ffffff',
                false,
                $context->shop->id_shop_group,
                $context->shop->id
            ));
    }

    public static function deleteDefaultValues()
    {
        return (Configuration::deleteByName('REASSURANCE_HOME_ANIMATION') &&
            Configuration::deleteByName('REASSURANCE_FOOTER_ANIMATION') &&
            Configuration::deleteByName('REASSURANCE_PRODUCT_ANIMATION') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_SUB_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_DESCRIPTION_COLOR') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_HOVER_COLOR') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_BACKGROUND_COLOR') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_LINK_COLOR') &&
            Configuration::deleteByName('REASSURANCE_COLLAPSE_ARROW_COLOR') &&
            Configuration::deleteByName('REASSURANCE_TOOLTIP_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_TOOLTIP_SUB_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_TOOLTIP_DESCRIPTION_COLOR') &&
            Configuration::deleteByName('REASSURANCE_TOOLTIP_BACKGROUND_COLOR') &&
            Configuration::deleteByName('REASSURANCE_TOOLTIP_BACK_HOVER_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER_SUB_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER_BACKGROUND_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER2_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER2_SUB_TITLE_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER2_COLOR') &&
            Configuration::deleteByName('REASSURANCE_HOVER2_BACKGROUND_COLOR')
        );
    }
}
