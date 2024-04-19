<?php
/**
 *   2009-2021 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright 2009-2021 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 */

class DwfProductExtraFieldsClass extends ObjectModel
{
    /** @var string dwfproductextrafields fieldname */
    public $fieldname;

    /** @var string dwfproductextrafields label */
    public $label;

    /** @var string dwfproductextrafields hint */
    public $hint;

    /** @var string dwfproductextrafields type */
    public $type;

    /** @var string dwfproductextrafields config */
    public $config;

    /** @var string dwfproductextrafields location */
    public $location;

    /** @var int position */
    public $position;

    /** @var boolean is active */
    public $active;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'dwfproductextrafields',
        'primary' => 'id_dwfproductextrafields',
        'multilang' => true,
        'fields' => array(
            'fieldname' => array('type' => self::TYPE_STRING, 'validate' => 'isDbColumn', 'required' => true, 'size' => 128),
            'type'      => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'config'    => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'location'  => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'position'  => array('type' => self::TYPE_INT),
            'active'    => array('type' => self::TYPE_BOOL),

            /* Lang fields */
            'label'     => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true),
            'hint'      => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
        )
    );

    public static function createTable()
    {
        $prefix = _DB_PREFIX_;
        $engine = _MYSQL_ENGINE_;

        $statements = array();

        $statements[] = "
            CREATE TABLE IF NOT EXISTS `${prefix}dwfproductextrafields`(
                `id_dwfproductextrafields` INT(10) unsigned NOT NULL AUTO_INCREMENT,
                `fieldname` VARCHAR(255),
                `type` VARCHAR(255) NOT NULL DEFAULT 'text',
                `config` TEXT NOT NULL DEFAULT '',
                `location` VARCHAR(255) NOT NULL DEFAULT 'tab',
                `position` INT(10) unsigned NOT NULL DEFAULT '0',
                `active` TINYINT(1) unsigned NOT NULL DEFAULT '0',
                PRIMARY KEY (`id_dwfproductextrafields`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ";

        $statements[] = "
            CREATE TABLE IF NOT EXISTS `${prefix}dwfproductextrafields_lang`(
                `id_dwfproductextrafields` INT(10) unsigned NOT NULL,
                `id_lang` INT(10) unsigned NOT NULL,
                `label` VARCHAR(255),
                `hint` VARCHAR(255),
                PRIMARY KEY (`id_dwfproductextrafields`, `id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ";

        foreach ($statements as $statement) {
            if (!Db::getInstance()->Execute($statement)) {
                return false;
            }
        }

        return true;
    }

    public static function deleteDbTable()
    {
        $prefix = _DB_PREFIX_;

        $statements = array();

        $statements[] = "DROP TABLE IF EXISTS `${prefix}dwfproductextrafields`";
        $statements[] = "DROP TABLE IF EXISTS `${prefix}dwfproductextrafields_lang`";

        foreach ($statements as $statement) {
            if (!Db::getInstance()->Execute($statement)) {
                return false;
            }
        }

        return true;
    }

    public function copyFromPost()
    {
        Tools::safePostVars();

        /* Classical fields */
        foreach ($_POST as $key => $value) {
            if (key_exists($key, $this) && $key != 'id_'.$this->table) {
                if ($key == 'fieldname') {
                    $this->{$key} = self::slugify(Tools::htmlentitiesDecodeUTF8($value));
                } elseif ($key == 'config') {
                    $this->{$key} = html_entity_decode($value);
                } else {
                    $this->{$key} = $value;
                }
            }
        }

        /* Multilingual fields */
        if (count($this->fieldsValidateLang)) {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                foreach (array_keys($this->fieldsValidateLang) as $field) {
                    if (Tools::getIsset($field.'_'.(int)$language['id_lang'])) {
                        $this->{$field}[(int)$language['id_lang']] = Tools::htmlentitiesDecodeUTF8(Tools::getValue($field.'_'.(int)$language['id_lang']));
                    }
                }
            }
        }
    }

    public function validateField($field, $value, $id_lang = null, $skip = array(), $human_errors = false)
    {
        $data = $this->def['fields'][$field];
        if (!empty($data['validate']) && $data['validate'] == 'isDbColumn') {
            if (Tools::isEmpty($value)) {
                if ($human_errors) {
                    return $this->trans('The %s field is required.', array($this->displayFieldName($field, get_class($this))), 'Admin.Notifications.Error');
                } else {
                    return $this->trans('Property %s is empty.', array(get_class($this) . '->' . $field), 'Admin.Notifications.Error');
                }
            } elseif (!empty($value)) {
                $res = true;
                if (!preg_match('/^[a-z0-9_]{0,127}$/', $value)) {
                    $res = false;
                }

                if (!$res) {
                    if ($human_errors) {
                        return $this->trans('The %s field is invalid.', array($this->displayFieldName($field, get_class($this))), 'Admin.Notifications.Error');
                    } else {
                        return $this->trans('Property %s is not valid', array(get_class($this) . '->' . $field), 'Admin.Notifications.Error');
                    }
                }
            }
            return $res;
        } else {
            return parent::validateField($field, $value, $id_lang, $skip, $human_errors);
        }
    }

    public function updatePosition($way, $position)
    {
        $sql = '
            SELECT pef.`id_dwfproductextrafields`, pef.`position`
            FROM `'._DB_PREFIX_.'dwfproductextrafields` pef
            WHERE 1
            ORDER BY pef.`position` ASC';

        if (!$res = Db::getInstance()->executeS($sql)) {
            return false;
        }

        foreach ($res as $highlight) {
            if ((int)$highlight['id_dwfproductextrafields'] == (int)$this->id) {
                $moved_highlight = $highlight;
            }
        }

        if (!isset($moved_highlight) || !isset($position)) {
            return false;
        }

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        $res1 = Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'dwfproductextrafields`
            SET `position`= `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                        ? '> '.(int)$moved_highlight['position'].' AND `position` <= '.(int)$position
                        : '< '.(int)$moved_highlight['position'].' AND `position` >= '.(int)$position)
        );

        $res2 = Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'dwfproductextrafields`
            SET `position` = '.(int)$position.'
            WHERE `id_dwfproductextrafields` = '.(int)$moved_highlight['id_dwfproductextrafields']
        );

        return ($res1 && $res2);
    }

    /**
     * Reorder attribute position in group $id_attribute_group.
     * Call it after deleting an attribute from a group.
     *
     * @param int $id_attribute_group
     * @param bool $use_last_attribute
     * @return bool $return
     */
    public function cleanPositions($use_last_highlight = null)
    {
        $return = true;

        $sql = '
            SELECT `id_dwfproductextrafields`
            FROM `'._DB_PREFIX_.'dwfproductextrafields`
            WHERE 1';

        // when delete, you must use $use_last_attribute
        if ($use_last_highlight) {
            $sql .= ' AND `id_dwfproductextrafields` != '.(int)$this->id;
        }

        $sql .= ' ORDER BY `position`';

        $result = Db::getInstance()->executeS($sql);

        $i = 0;
        foreach ($result as $value) {
            $return = Db::getInstance()->execute(
                'UPDATE `'._DB_PREFIX_.'dwfproductextrafields`
                SET `position` = '.(int)$i++.'
                WHERE 1
                AND `id_dwfproductextrafields` = '.(int)$value['id_dwfproductextrafields']
            );
        }

        return $return;
    }

    /**
     * getHigherPosition
     *
     * Get the higher highlight position from a group highlight
     *
     * @param integer $id_highlight_group
     * @return integer $position
     */
    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`)
                FROM `'._DB_PREFIX_.'dwfproductextrafields`
                WHERE 1';

        $position = DB::getInstance()->getValue($sql);

        return (is_numeric($position)) ? $position : - 1;
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '_', $text);

        // trim
        $text = trim($text, '_');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = Tools::strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return null;
        }

        return $text;
    }

    public function update($null_values = false)
    {
        $this->cleanPositions();

        $res = true;
        $oldRecord = new DwfProductExtraFieldsClass($this->id);
        if ($oldRecord->id) {
            $table_name = _DB_PREFIX_.'product_extra_field';
            $newType = $this->getFieldType();
            if ($oldRecord->isMultilangField() == $this->isMultilangField()) {
                if ($this->isMultilangField()) {
                    $table_name .= '_lang';
                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($oldRecord->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` CHANGE `'.pSQL($oldRecord->fieldname).'` `'.pSQL($this->fieldname).'` '.$newType);
                    } else {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` ADD `'.pSQL($this->fieldname).'` '.$newType);
                    }
                } else {
                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($oldRecord->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` CHANGE `'.pSQL($oldRecord->fieldname).'` `'.pSQL($this->fieldname).'` '.$newType);
                    } else {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` ADD `'.pSQL($this->fieldname).'` '.$newType);
                    }

                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($oldRecord->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` CHANGE `'.pSQL($oldRecord->fieldname).'` `'.pSQL($this->fieldname).'` '.$newType);
                    } else {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` ADD `'.pSQL($this->fieldname).'` '.$newType);
                    }
                }
            } else {
                if (!$oldRecord->isMultilangField() && $this->isMultilangField()) {
                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($oldRecord->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `' . pSQL($table_name) . '` DROP `' . pSQL($oldRecord->fieldname) . '`');
                    }

                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($oldRecord->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` DROP `'.pSQL($oldRecord->fieldname).'`');
                    }

                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_lang' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_lang` ADD `'.pSQL($this->fieldname).'` '.$newType.' NULL');
                    }
                } else {
                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_lang' AND COLUMN_NAME = '".pSQL($oldRecord->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_lang` DROP `'.pSQL($oldRecord->fieldname).'`');
                    }

                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` ADD `'.pSQL($this->fieldname).'` '.$newType.' NULL');
                    }

                    if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                        $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` ADD `'.pSQL($this->fieldname).'` '.$newType.' NULL');
                    }
                }
            }
        }

        return ($res && parent::update($null_values));
    }

    public function getFieldType()
    {
        $type = null;

        if ($this->type == 'text' || $this->type == 'image') {
            $type = 'VARCHAR(255)';
        } elseif ($this->type == 'textarea' || $this->type == 'textarea_mce' || $this->type == 'non-translatable_text' || $this->type == 'color' || $this->type == 'selector' || $this->type == 'repeater') {
            $type = 'TEXT';
        } elseif ($this->type == 'date') {
            $type = 'DATE';
        } elseif ($this->type == 'datetime') {
            $type = 'DATETIME';
        } elseif ($this->type == 'decimal') {
            $type = 'DECIMAL(20,6)';
        } elseif ($this->type == 'price') {
            $type = 'DECIMAL(20,2)';
        } elseif ($this->type == 'checkbox') {
            $type = 'TINYINT(4)';
        } else {
            $type = 'INT(10)';
        }

        return $type;
    }

    public function add($null_values = false, $autodate = true)
    {
        $table_name = _DB_PREFIX_.'product_extra_field';

        $type = $this->getFieldType();

        $res = true;
        if ($this->isMultilangField()) {
            $table_name .= '_lang';
            if (!Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` ADD `'.pSQL($this->fieldname).'` '.$type.' NULL');
            }
        } else {
            if (!Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` ADD `'.pSQL($this->fieldname).'` '.$type.' NULL');
            }
            if (!Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` ADD `'.pSQL($this->fieldname).'` '.$type.' NULL');
            }
        }

        if ($this->position <= 0) {
            $this->position = self::getHigherPosition() + 1;
        }

        return ($res && parent::add($null_values, $autodate));
    }

    public function delete()
    {
        $table_name = _DB_PREFIX_.'product_extra_field';

        $res = true;
        if ($this->isMultilangField()) {
            $table_name .= '_lang';
            if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` DROP `'.pSQL($this->fieldname).'`');
            }
        } else {
            if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` DROP `'.pSQL($this->fieldname).'`');
            }
            if (Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."' AND COLUMN_NAME = '".pSQL($this->fieldname)."'")) {
                $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'` DROP `'.pSQL($this->fieldname).'`');
            }
        }

        $result = ($res && parent::delete());

        $this->cleanPositions($this->id);

        return $result;
    }

    public static function getActiveFields()
    {
        $module_version = Db::getInstance()->getValue('SELECT version FROM `'._DB_PREFIX_.'module` WHERE name = \'dwfproductextrafields\'');

        $productextrafields = new Collection('DwfProductExtraFieldsClass');
        $productextrafields->where('active', '=', 1);
       
        if (version_compare($module_version, '1.6.4', '>=')) {
            $productextrafields->orderBy('position', 'ASC');
        }

        return $productextrafields;
    }

    public function isMultilangField()
    {
        if ($this->type == 'text' || $this->type == 'textarea' || $this->type == 'textarea_mce' || $this->type == 'repeater') {
            return true;
        }

        return false;
    }

    public function isValidFieldname()
    {
        $productextrafields = new Collection('DwfProductExtraFieldsClass');
        $productextrafields->where('fieldname', '=', $this->fieldname);

        if (count($productextrafields) && ($productextrafields->getFirst()->id != $this->id)) {
            return false;
        }

        if (array_key_exists($this->fieldname, Product::$definition["fields"]) || $this->fieldname == 'id') {
            return false;
        }

        return true;
    }
}
