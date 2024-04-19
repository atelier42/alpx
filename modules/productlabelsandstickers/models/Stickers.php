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

class Stickers extends ObjectModel
{
    public $sticker_id;
    public $sticker_name;
    public $sticker_size;
    public $sticker_opacity;
    public $sticker_size_list;
    public $sticker_image;
    public $x_align;
    public $y_align;
    public $transparency;
    public $medium_width;
    public $medium_height;
    public $medium_x;
    public $medium_y;
    public $small_width;
    public $small_height;
    public $small_x;
    public $small_y;
    public $thickbox_width;
    public $thickbox_height;
    public $thickbox_x;
    public $thickbox_y;
    public $large_width;
    public $large_height;
    public $large_x;
    public $large_y;
    public $home_width;
    public $home_height;
    public $home_x;
    public $home_y;
    public $cart_width;
    public $cart_height;
    public $cart_x;
    public $cart_y;
    public $creation_date;
    public $updation_date;
    public $color;
    public $bg_color;
    public $font;
    public $font_size;
    public $text_status;
    public $expiry_date;
    public $start_date;
    public $y_coordinate_listing;
    public $y_coordinate_product;
    public $url;
    public $tip;
    public $tip_bg;
    public $tip_color;
    public $tip_txt;
    public $tip_pos;
    public $tip_width;
    public $product;
    public $listing;

    public static $definition = array(
        'table' => 'fmm_stickers',
        'primary' => 'sticker_id',
        'multilang' => true,
        'fields' => array(
            'sticker_name' => array('type' => self::TYPE_STRING),
            'sticker_size' => array('type' => self::TYPE_STRING),
            'sticker_opacity' => array('type' => self::TYPE_STRING),
            'sticker_image' => array('type' => self::TYPE_STRING),
            'x_align' => array('type' => self::TYPE_STRING),
            'y_align' => array('type' => self::TYPE_STRING),
            'transparency' => array('type' => self::TYPE_INT),
            'medium_width' => array('type' => self::TYPE_INT),
            'medium_height' => array('type' => self::TYPE_INT),
            'medium_x' => array('type' => self::TYPE_INT),
            'medium_y' => array('type' => self::TYPE_INT),
            'small_width' => array('type' => self::TYPE_INT),
            'small_height' => array('type' => self::TYPE_INT),
            'small_x' => array('type' => self::TYPE_INT),
            'small_y' => array('type' => self::TYPE_INT),
            'thickbox_width' => array('type' => self::TYPE_INT),
            'thickbox_height' => array('type' => self::TYPE_INT),
            'thickbox_x' => array('type' => self::TYPE_INT),
            'thickbox_y' => array('type' => self::TYPE_INT),
            'large_width' => array('type' => self::TYPE_INT),
            'large_height' => array('type' => self::TYPE_INT),
            'large_x' => array('type' => self::TYPE_INT),
            'large_y' => array('type' => self::TYPE_INT),
            'home_width' => array('type' => self::TYPE_INT),
            'home_height' => array('type' => self::TYPE_INT),
            'home_x' => array('type' => self::TYPE_INT),
            'home_y' => array('type' => self::TYPE_INT),
            'cart_width' => array('type' => self::TYPE_INT),
            'cart_height' => array('type' => self::TYPE_INT),
            'cart_x' => array('type' => self::TYPE_INT),
            'cart_y' => array('type' => self::TYPE_INT),
            'creation_date' => array('type' => self::TYPE_DATE),
            'updation_date' => array('type' => self::TYPE_DATE),
            'sticker_size_list' => array('type' => self::TYPE_STRING),
            'color' => array('type' => self::TYPE_STRING),
            'bg_color' => array('type' => self::TYPE_STRING),
            'font' => array('type' => self::TYPE_STRING),
            'font_size' => array('type' => self::TYPE_STRING),
            'text_status' => array('type' => self::TYPE_INT),
            'expiry_date' => array('type' => self::TYPE_DATE),
            'start_date' => array('type' => self::TYPE_DATE),
            'y_coordinate_listing' => array('type' => self::TYPE_INT),
            'y_coordinate_product' => array('type' => self::TYPE_INT),
            'url' => array('type' => self::TYPE_STRING),
            'tip' => array('type' => self::TYPE_INT),
            'tip_width' => array('type' => self::TYPE_INT),
            'tip_pos' => array('type' => self::TYPE_INT),
            'tip_bg' => array('type' => self::TYPE_STRING),
            'tip_color' => array('type' => self::TYPE_STRING),
            'tip_txt' => array('type' => self::TYPE_STRING, 'lang' => true),
            'product' => array('type' => self::TYPE_INT),
            'listing' => array('type' => self::TYPE_INT),
        ),
    );

    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);
    }

    public function delete()
    {
        $res = Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'fmm_stickers` WHERE `sticker_id` = '.(int)$this->sticker_id);
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

    public function getPids()
    {
        $sql = 'SELECT *
            FROM '._DB_PREFIX_.'fmm_stickers_products
            ORDER BY sticker_id DESC';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getAllStickers()
    {
        $now = date('Y-m-d H:i:s');
        $sql = 'SELECT *
            FROM '._DB_PREFIX_.'fmm_stickers
            WHERE \''.pSQL($now).'\' < `expiry_date` OR `expiry_date` = \'0000-00-00 00:00:00\'
            ORDER BY sticker_id DESC';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getProductStickersStatic($id_product)
    {
        $id_lang = (int)Context::getContext()->language->id;
        $sql = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT ps1.*
            FROM `'._DB_PREFIX_.'fmm_stickers` ps1
            LEFT JOIN '._DB_PREFIX_.'fmm_stickers_products ps2 ON (ps1.sticker_id = ps2.sticker_id)
            WHERE ps2.`id_product` = '.(int)$id_product);
        foreach ($sql as &$row) {
            $row['title'] = $this->getTitleSticker($row['sticker_id'], $id_lang);
            $row['tip_txt'] = $this->getHintTxtSticker($row['sticker_id'], $id_lang);
        }
        return $sql;
    }

    public function getProductStickers($id_product, $type = 'product')
    {
        $id_lang = (int)Context::getContext()->language->id;
        $now = date('Y-m-d H:i:s');
        $sql = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT ps1.*
            FROM `'._DB_PREFIX_.'fmm_stickers` ps1
            LEFT JOIN '._DB_PREFIX_.'fmm_stickers_products ps2 ON (ps1.sticker_id = ps2.sticker_id)
            WHERE ps2.`id_product` = '.(int)$id_product.'
            AND ps1.`'.pSQL($type).'` > 0
            AND
            (
                (ps1.`start_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' >= ps1.`start_date`)
                AND
                (ps1.`expiry_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' <= ps1.`expiry_date`)
            )');
            
        if (isset($sql) && $sql) {
            foreach ($sql as &$row) {
                $row['x_align'] = (empty($row['x_align']))? 'right' : $row['x_align'];
                $row['y_align'] = (empty($row['y_align']))? 'top' : $row['y_align'];
                $row['title'] = $this->getTitleSticker($row['sticker_id'], $id_lang);
                $row['tip_txt'] = $this->getHintTxtSticker($row['sticker_id'], $id_lang);
            }
        }
        return $sql;
    }

    public function getSticker($id, $type = 'product')
    {
        $id_lang = (int)Context::getContext()->language->id;
        $now = date('Y-m-d H:i:s');
        $sql = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT *
            FROM `'._DB_PREFIX_.'fmm_stickers`
            WHERE `sticker_id` = '.(int)$id.'
            AND `'.pSQL($type).'` > 0
            AND
            (
                (`start_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' >= `start_date`)
                AND
                (`expiry_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' <= `expiry_date`)
            )');
        if (isset($sql) && $sql) {
            foreach ($sql as &$row) {
                $row['x_align'] = (empty($row['x_align']))? 'right' : $row['x_align'];
                $row['y_align'] = (empty($row['y_align']))? 'top' : $row['y_align'];
                $row['title'] = $this->getTitleSticker($row['sticker_id'], $id_lang);
                $row['tip_txt'] = $this->getHintTxtSticker($row['sticker_id'], $id_lang);
            }
        }
        return array_shift($sql);
    }

    public static function getColors($id)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `color`, `bg_color`
        FROM `'._DB_PREFIX_.'fmm_stickers`
        WHERE `sticker_id` = '.(int)$id);
        return $result;
    }

    public static function getStickerIdStatic($id)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `sticker_id`
        FROM `'._DB_PREFIX_.'fmm_stickers_lang`
        WHERE `sticker_id` = '.(int)$id);
        return $result['sticker_id'];
    }

    public function updateLabelText($id, $id_lang, $title)
    {
        Db::getInstance()->execute('
            UPDATE '._DB_PREFIX_.'fmm_stickers_lang
            SET `title` = "'.pSQL($title).'"
            WHERE `sticker_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
    }

    public function insertLabelText($id, $id_lang, $title)
    {
        Db::getInstance()->execute('
            INSERT INTO '._DB_PREFIX_.'fmm_stickers_lang (`sticker_id`, `id_lang`, `title`)
            VALUES('.(int)$id.', '.(int)$id_lang.', "'.pSQL($title).'")
        ');
    }

    public static function getFieldTitle($id, $id_lang)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `title`
        FROM `'._DB_PREFIX_.'fmm_stickers_lang`
        WHERE `sticker_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
        return $result['title'];
    }
    
    public static function getFieldHint($id, $id_lang)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `tip_txt`
        FROM `'._DB_PREFIX_.'fmm_stickers_lang`
        WHERE `sticker_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
        return $result['tip_txt'];
    }

    public static function getTitleSticker($id, $id_lang)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `title`
        FROM `'._DB_PREFIX_.'fmm_stickers_lang`
        WHERE `sticker_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
        return $result['title'];
    }
    
    public static function getHintTxtSticker($id, $id_lang)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `tip_txt`
        FROM `'._DB_PREFIX_.'fmm_stickers_lang`
        WHERE `sticker_id` = '.(int)$id.' AND `id_lang` = '.(int)$id_lang);
        return $result['tip_txt'];
    }

    public function getAllBanners()
    {
        $id_lang = (int)Context::getContext()->language->id;
        $sql = 'SELECT b.*, bl.`title`
            FROM '._DB_PREFIX_.'fmm_stickersbanners b
            LEFT JOIN '._DB_PREFIX_.'fmm_stickersbanners_lang bl ON (b.stickersbanners_id = bl.stickersbanners_id)
            WHERE id_lang = '.(int)$id_lang;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public static function getSelectedBanners($id)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `stickersbanners_id`
        FROM `'._DB_PREFIX_.'fmm_stickersbanners_products`
        WHERE `id_product` = '.(int)$id);
        return $result['stickersbanners_id'];
    }

    public static function getProductBanner($id)
    {
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $now = date('Y-m-d H:i:s');
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT b.*, bl.`title`
            FROM `'._DB_PREFIX_.'fmm_stickersbanners` b
            LEFT JOIN '._DB_PREFIX_.'fmm_stickersbanners_products bp
                ON (b.stickersbanners_id = bp.stickersbanners_id)
            LEFT JOIN '._DB_PREFIX_.'fmm_stickersbanners_lang bl
                ON (b.stickersbanners_id = bl.stickersbanners_id)
            LEFT JOIN '._DB_PREFIX_.'fmm_stickersbanners_shop ssh
                ON (b.stickersbanners_id = ssh.stickersbanners_id)
            WHERE bp.`id_product` = '.(int)$id.'
            AND `id_lang` = '.(int)$id_lang.'
            AND ssh.id_shop = '.(int)$id_shop.'
            AND
            (
                (b.`start_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' >= b.`start_date`)
                AND
                (b.`expiry_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' <= b.`expiry_date`)
            )');
        return $result;
    }

    public static function removeShopStickers($id_sticker)
    {
        return (bool)Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'fmm_stickers_shop
            WHERE `sticker_id` = '.(int)$id_sticker);
    }

    public static function insertShopStickers($id_sticker, $id_shop)
    {
        Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'fmm_stickers_shop (`sticker_id`, `id_shop`)
            VALUES('.(int)$id_sticker.', '.(int)$id_shop.')');
    }

    public static function getShopStickers($id_sticker)
    {
        $result = Db::getInstance()->ExecuteS('SELECT `id_shop` FROM `'._DB_PREFIX_.'fmm_stickers_shop`
            WHERE sticker_id = '.(int)$id_sticker);
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key] = $value['id_shop'];
            }
        }
        return $result;
    }
}
