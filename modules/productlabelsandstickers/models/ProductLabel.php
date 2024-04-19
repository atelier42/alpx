<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @author    FMM Modules
* @copyright FMM Modules
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class ProductLabel extends ObjectModel
{
    public $stickersbanners_id;
    public $color;
    public $bg_color;
    public $font;
    public $font_size;
    public $border_color;
    public $font_weight;
    public $start_date;
    public $expiry_date;

    public static $definition = array(
        'table' => 'fmm_stickersbanners',
        'primary' => 'stickersbanners_id',
        'multilang' => true,
        'fields' => array(
            'color' => array('type' => self::TYPE_STRING),
            'bg_color' => array('type' => self::TYPE_STRING),
            'font' => array('type' => self::TYPE_STRING),
            'font_size' => array('type' => self::TYPE_STRING),
            'border_color' => array('type' => self::TYPE_STRING),
            'font_weight' => array('type' => self::TYPE_STRING),
            'expiry_date' => array('type' => self::TYPE_DATE),
            'start_date' => array('type' => self::TYPE_DATE),
        ),
    );

    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);
    }

    public function delete()
    {
        $res = Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'fmm_stickersbanners` WHERE `stickersbanners_id` = '.(int)$this->stickersbanners_id);
        $res &= parent::delete();
        return $res;
    }

    public function deleteSelection($selection)
    {
        if (!is_array($selection)) {
            die(Tools::displayError());
        }

        $result = true;
        foreach ($selection as $id) {
            $this->id = (int)$id;
            $result = $result && $this->delete();
        }
        return $result;
    }

    public static function getFieldTitle($id, $id_lang)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `title`
        FROM `'._DB_PREFIX_.'fmm_stickersbanners_lang`
        WHERE `stickersbanners_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
        return $result['title'];
    }

    public static function getColors($id)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `color`, `bg_color`, `border_color`
        FROM `'._DB_PREFIX_.'fmm_stickersbanners`
        WHERE `stickersbanners_id` = '.(int)$id);
        return $result;
    }

    public static function getStickerIdStatic($id)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `stickersbanners_id`
        FROM `'._DB_PREFIX_.'fmm_stickersbanners_lang`
        WHERE `stickersbanners_id` = '.(int)$id);
        return $result['stickersbanners_id'];
    }

    public function updateLabelText($id, $id_lang, $title)
    {
        Db::getInstance()->execute(' UPDATE '._DB_PREFIX_.'fmm_stickersbanners_lang
            SET `title` = "'.pSQL($title).'"
            WHERE `stickersbanners_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
    }

    public function insertLabelText($id, $id_lang, $title)
    {
        Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'fmm_stickersbanners_lang (`stickersbanners_id`, `id_lang`, `title`)
            VALUES('.(int)$id.', '.(int)$id_lang.', "'.pSQL($title).'")
        ');
    }

    public static function removeShopLabels($stickersbanners_id)
    {
        return (bool)Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'fmm_stickersbanners_shop
            WHERE `stickersbanners_id` = '.(int)$stickersbanners_id);
    }

    public static function insertShopLabels($stickersbanners_id, $id_shop)
    {
        return Db::getInstance()->execute(' INSERT INTO '._DB_PREFIX_.'fmm_stickersbanners_shop (`stickersbanners_id`, `id_shop`)
            VALUES('.(int)$stickersbanners_id.', '.(int)$id_shop.')');
    }

    public static function getShopLabels($stickersbanners_id)
    {
        $result = Db::getInstance()->ExecuteS('SELECT `id_shop` FROM `'._DB_PREFIX_.'fmm_stickersbanners_shop`
            WHERE stickersbanners_id = '.(int)$stickersbanners_id);

        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key] = $value['id_shop'];
            }
        }
        return $result;
    }
}
