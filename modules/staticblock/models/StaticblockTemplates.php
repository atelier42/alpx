<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    FME Modules
 *  @copyright © 2018 FME Modules
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class StaticblockTemplates extends ObjectModel
{
    public $template_name;

    public $status;

    public $code;

    public static $definition = array(
        'table' => 'static_block_template',
        'primary' => 'id_static_block_template',
        'multilang' => false,
        'fields' => array(
            'template_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'code' => array('type' => self::TYPE_HTML),
            'status' => array('type' => self::TYPE_INT),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        if (parent::add($autodate, $null_values)) {
            return true;
        }
        return true;
    }

    public function update($null_values = false)
    {
        if (parent::update($null_values)) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $this->deleteStaticBlockTemplate();
        if (parent::delete()) {
            return true;
        }
        return false;
    }

    public function deleteStaticBlockTemplate()
    {
        return Db::getInstance()->update(
            'static_block',
            array('id_static_block_template' => 0),
            'id_static_block_template = ' . (int) $this->id
        );
    }

    public static function getTemplates($active = false)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table']);
        if ($active) {
            $sql->where('status = 1');
        }
        return Db::getInstance()->executeS($sql);
    }

    public static function getTemplateById($id_static_block_template, $active = false)
    {
        if (!$id_static_block_template) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table']);
        $sql->where('id_static_block_template = ' . (int) $id_static_block_template);
        if ($active) {
            $sql->where('status = 1');
        }
        return Db::getInstance()->getRow($sql);
    }

    public static function getStaticBlockTemplate($id_static_block)
    {
        if (!$id_static_block) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('sbt*, sb.*');
        $sql->from(self::$definition['table'], 'sbt');
        $sql->leftJoin(self::$definition['table'], 'sb', 'sbt.id_static_block_template = sb.id_static_block_template');
        $sql->where('sb.id_static_block = ' . (int) $id_static_block);
        return Db::getInstance()->getRow($sql);
    }

    public static function updateStatus($field, $id_static_block_template)
    {
        return (bool) Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'static_block_template`
            SET `' . pSQL($field) . '` = !' . pSQL($field) .
            ' WHERE id_static_block_template = ' . (int) $id_static_block_template);
    }

    public static function countListContent()
    {
        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(id_static_block_template)
            FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`');
    }
}
