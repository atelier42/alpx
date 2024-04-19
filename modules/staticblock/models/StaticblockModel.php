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

class StaticblockModel extends ObjectModel
{
    public $id_static_block;

    public $id_static_block_template;

    public $hook;

    public $editor;

    public $status;

    public $custom_css;

    public $title_active;

    public $position;

    public $css;

    public $date_from;

    public $date_to;

    public $block_title;

    public $content;

    public $groupBox;

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    public static $definition = array(
        'table' => 'static_block',
        'primary' => 'id_static_block',
        'multilang' => true,
        'fields' => array(
            'id_static_block_template' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'editor' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'hook' => array('type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true),
            'status' => array('type' => self::TYPE_INT),
            'custom_css' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'title_active' => array('type' => self::TYPE_INT),
            'block_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 1000,
            ),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'css' => array('type' => self::TYPE_STRING),
            'date_from' => array('type' => self::TYPE_STRING),
            'date_to' => array('type' => self::TYPE_STRING),
            'date_add' => array('type' => self::TYPE_STRING),
            'date_upd' => array('type' => self::TYPE_STRING),
        ),
    );

    public function __construct($id_static_block = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_static_block, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = false)
    {
        if (parent::add($autodate, $null_values)) {
            $this->updateGroup($this->groupBox);
            return true;
        }
        return true;
    }

    public function update($null_values = false)
    {
        if (parent::update($null_values)) {
            $this->updateGroup($this->groupBox);
            return true;
        }
        return false;
    }

    public function delete()
    {
        $this->deleteConditions();
        $this->cleanGroups();
        if (parent::delete()) {
            return true;
        }
        return false;
    }

    public function addGroups($groups)
    {
        foreach ($groups as $group) {
            if ($group !== false) {
                Db::getInstance()->insert(
                    'static_block_group',
                    array('id_static_block' => (int) $this->id,
                        'id_group' => (int) $group)
                );
            }
        }
    }

    public function updateGroup($list)
    {
        $this->cleanGroups();
        if (empty($list)) {
            $list = array(
                Configuration::get('PS_UNIDENTIFIED_GROUP'),
                Configuration::get('PS_GUEST_GROUP'),
                Configuration::get('PS_CUSTOMER_GROUP'),
            );
        }
        $this->addGroups($list);
    }

    public function getGroups()
    {
        $sql = new DbQuery();
        $sql->select('sbg.`id_group`');
        $sql->from(self::$definition['table'] . '_group', 'sbg');
        $sql->where('sbg.`id_static_block` = ' . (int) $this->id);
        $result = Db::getInstance()->executeS($sql);
        $groups = array();
        foreach ($result as $group) {
            $groups[] = $group['id_group'];
        }
        return $groups;
    }

    public function cleanGroups()
    {
        return Db::getInstance()->delete(
            'static_block_group',
            'id_static_block = ' . (int) $this->id
        );
    }

    public static function updateStatus($field, $id_static_block)
    {
        return (bool) Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'static_block`
            SET `' . pSQL($field) . '` = !' . pSQL($field) .
            ' WHERE id_static_block = ' . (int) $id_static_block);
    }

    public static function getDetail($id_lang)
    {
        return Db::getInstance()->executeS('SELECT sb.*, sbl.*
            FROM `' . _DB_PREFIX_ . 'static_block`sb
            LEFT JOIN `' . _DB_PREFIX_ . 'static_block_lang`sbl
                ON (sb.`id_static_block` = sbl.`id_static_block`)
            WHERE sbl.`id_lang` =' . (int) $id_lang . '
            ORDER BY sb.position ASC');
    }

    public static function getLastId()
    {
        $result = Db::getInstance()->ExecuteS(
            'SHOW TABLE STATUS WHERE Name = "' . _DB_PREFIX_ . 'static_block" '
        );
        if ($result) {
            $result = array_shift($result);
            return $result['Auto_increment'];
        }
        return false;
    }

    public static function getBlockById($id_static_block, $id_shop = null)
    {
        if (!$id_static_block) {
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
                'sb.id_static_block = sbs.id_static_block AND sbs.id_shop = ' . (int) $id_shop
            );
        }
        $sql->where('sb.id_static_block = ' . (int) $id_static_block);

        $result = Db::getInstance()->getRow($sql);
        if (isset($result) && $result) {
            $result['content'] = self::getLangBlockById('content', $id_static_block);
            $result['block_title'] = self::getLangBlockById('block_title', $id_static_block);
        }
        return $result;
    }

    public static function getLangBlockById($field, $id_static_block)
    {
        if (!$id_static_block || empty($field)) {
            return false;
        }

        $sql = 'SELECT `id_lang`, `' . $field . '`
        FROM `' . _DB_PREFIX_ . 'static_block_lang`
        WHERE `id_static_block` = ' . (int) $id_static_block;

        $final = array();
        $result = Db::getInstance()->executeS($sql);
        if ($result) {
            foreach ($result as $res) {
                $final[$res['id_lang']] = $res[$field];
            }
        }
        return $final;
    }

    public static function insertBlockShop($id_static_block, $id_shop)
    {
        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'static_block_shop` (`id_static_block`,`id_shop`)
        VALUES(' . (int) $id_static_block . ', ' . (int) $id_shop . ')';
        if (Db::getInstance()->execute($sql)) {
            return Db::getInstance()->Insert_ID();
        }
        return false;
    }

    public static function getBlockByHook($hook, $id_shop = null, $id_lang = null, $id_group = 0)
    {
        if (!$hook || !Validate::isString($hook) || !Validate::isHookName($hook)) {
            return false;
        }
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }

        $now = date('Y-m-d H:i:00');
        $sql = new DbQuery();
        $sql->select('sb.*, sbl.*, scg.id_group');
        $sql->from(self::$definition['table'], 'sb');
        $sql->leftJoin(
            self::$definition['table'] . '_lang',
            'sbl',
            'sb.id_static_block = sbl.id_static_block AND sbl.id_lang = ' . (int) $id_lang
        );
        $sql->leftJoin(
            self::$definition['table'] . '_group',
            'scg',
            'sb.id_static_block = scg.id_static_block'
        );
        if (Shop::isFeatureActive() && $id_shop) {
            $sql->leftJoin(
                self::$definition['table'] . '_shop',
                'sbs',
                'sb.id_static_block = sbs.id_static_block AND sbs.id_shop = ' . (int) $id_shop
            );
        }
        $sql->where('sb.status = 1');
        $sql->where('sb.date_from = \'0000-00-00\' OR \'' . pSQL($now) . '\' >= sb.date_from');
        $sql->where('sb.date_to = \'0000-00-00\' OR \'' . pSQL($now) . '\' <= sb.date_to');
        $sql->where('scg.id_group ' . self::formatIntInQuery(0, $id_group));
        $sql->where('sb.hook = "' . pSQL($hook) . '"');
        $sql->orderBy('sb.position');
        $result = Db::getInstance()->executeS($sql);

        if (isset($result) && is_array($result)) {
            foreach ($result as &$res) {
                $res['conditions'] = self::getBlockCondition($res['id_static_block']);
            }
        }
        return $result;
    }

    public static function getBlockCondition($id_static_block)
    {
        if (!$id_static_block) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('sbcg.*, sbc.*');
        $sql->from(self::$definition['table'] . '_html_rule_condition_group', 'sbcg');
        $sql->leftJoin(
            self::$definition['table'] . '_html_rule_condition',
            'sbc',
            'sbc.id_static_block_html_rule_condition_group = sbcg.id_static_block_html_rule_condition_group'
        );
        $sql->where('sbcg.id_static_block = ' . (int) $id_static_block);

        $result = Db::getInstance()->executeS($sql);
        $conditions = array();
        if (isset($result) && is_array($result)) {
            foreach ($result as $res) {
                $conditions[$res['id_static_block_html_rule_condition_group']][] = $res;
            }
        }
        return $conditions;
    }

    public static function getAllBlocks($id_lang, $id_shop = null, $id_group = 0)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        $now = date('Y-m-d H:i:00');
        $sql = new DbQuery();
        $sql->select('sb.*, sbl.*, scg.id_group');
        $sql->from(self::$definition['table'], 'sb');
        $sql->leftJoin(
            self::$definition['table'] . '_lang',
            'sbl',
            'sb.id_static_block = sbl.id_static_block AND sbl.id_lang = ' . (int) $id_lang
        );
        $sql->leftJoin(
            self::$definition['table'] . '_group',
            'scg',
            'sb.id_static_block = scg.id_static_block'
        );
        if (Shop::isFeatureActive() && $id_shop) {
            $sql->leftJoin(
                self::$definition['table'] . '_shop',
                'sbs',
                'sb.id_static_block = sbs.id_static_block AND sbs.id_shop = ' . (int) $id_shop
            );
        }
        $sql->where('sb.status = 1');
        $sql->where('sb.date_from = \'0000-00-00\' OR \'' . pSQL($now) . '\' >= sb.date_from');
        $sql->where('sb.date_to = \'0000-00-00\' OR \'' . pSQL($now) . '\' <= sb.date_to');
        $sql->where('scg.id_group ' . self::formatIntInQuery(0, $id_group));
        $sql->groupBy('sb.id_static_block');
        $sql->orderBy('sb.position');
        $result = Db::getInstance()->executeS($sql);
        if (isset($result) && is_array($result)) {
            foreach ($result as &$res) {
                $res['conditions'] = self::getBlockCondition($res['id_static_block']);
            }
        }
        return $result;
    }

    public static function delBlock($id_static_block)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'static_block`
        WHERE `id_static_block` = ' . (int) $id_static_block;
        Db::getInstance()->execute($sql);

        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'static_block_lang`
        WHERE `id_static_block` = ' . (int) $id_static_block;
        Db::getInstance()->execute($sql);

        self::delShopBlock($id_static_block);
    }

    public static function delShopBlock($id_static_block)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'static_block_shop`
        WHERE `id_static_block` = ' . (int) $id_static_block;
        Db::getInstance()->execute($sql);
    }

    public static function editLangBlock($id_static_block, $id_lang)
    {
        $content = Tools::getValue('content_' . $id_lang, true);
        $title = Tools::getValue('block_title_' . $id_lang, true);

        $sql = 'UPDATE `' . _DB_PREFIX_ . 'static_block_lang`
                SET `block_title`   = "' . pSQL($title, true) . '",
                    `content`       = "' . pSQL($content, true) . '"
                WHERE `id_static_block`    = ' . (int) $id_static_block . '
                AND `id_lang`       = ' . (int) $id_lang;

        Db::getInstance()->execute($sql);
    }

    public static function getCategoryProducts($id_category)
    {
        if (!$id_category) {
            return false;
        } else {
            $sql = new DbQuery();
            $sql->select('id_product');
            $sql->from('category_product');

            $sql->where('id_category = ' . (int) $id_category);
            $result = Db::getInstance()->executeS($sql);

            $products = array();
            if (isset($result) && is_array($result)) {
                foreach ($result as $res) {
                    $products[] = $res['id_product'];
                }
            }
            return $products;
        }
    }

    public static function getProductSuppliers($id_product)
    {
        if (!$id_product) {
            return false;
        } else {
            $sql = new DbQuery();
            $sql->select('id_supplier');
            $sql->from('product_supplier');

            $sql->where('id_product = ' . (int) $id_product);
            $result = Db::getInstance()->executeS($sql);

            $suppliers = array();
            if (isset($result) && is_array($result)) {
                foreach ($result as $res) {
                    $suppliers[] = $res['id_supplier'];
                }
            }
            return $suppliers;
        }
    }

    public function addConditions($conditions)
    {
        if (!is_array($conditions)) {
            return;
        }

        $result = Db::getInstance()->insert('static_block_html_rule_condition_group', array(
            'id_static_block' => (int) $this->id,
        ));

        if (!$result) {
            return false;
        }
        $id_static_block_html_rule_condition_group = (int) Db::getInstance()->Insert_ID();
        foreach ($conditions as $condition) {
            $result = Db::getInstance()->insert('static_block_html_rule_condition', array(
                'id_static_block_html_rule_condition_group' => (int) $id_static_block_html_rule_condition_group,
                'type' => pSQL($condition['type']),
                'operator' => pSQL($condition['operator']),
                'value' => (float) $condition['value'],
            ));
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    public static function getConditions($id_static_block)
    {
        if (!$id_static_block) {
            return false;
        }
        $conditions = Db::getInstance()->executeS(
            'SELECT g.*, c.*
            FROM ' . _DB_PREFIX_ . 'static_block_html_rule_condition_group g
            LEFT JOIN ' . _DB_PREFIX_ . 'static_block_html_rule_condition c
                ON (c.id_static_block_html_rule_condition_group = g.id_static_block_html_rule_condition_group)
            WHERE g.id_static_block=' . (int) $id_static_block
        );
        $conditions_group = array();
        if ($conditions) {
            foreach ($conditions as &$condition) {
                $conditions_group[(int) $condition['id_static_block_html_rule_condition_group']][] = $condition;
            }
        }
        return $conditions_group;
    }

    public function deleteConditions()
    {
        $ids_condition_group = Db::getInstance()->executeS('SELECT id_static_block_html_rule_condition_group
            FROM ' . _DB_PREFIX_ . 'static_block_html_rule_condition_group
            WHERE id_static_block = ' . (int) $this->id);
        if ($ids_condition_group) {
            foreach ($ids_condition_group as $row) {
                Db::getInstance()->delete(
                    'static_block_html_rule_condition_group',
                    'id_static_block_html_rule_condition_group =' .
                    (int) $row['id_static_block_html_rule_condition_group']
                );
                Db::getInstance()->delete(
                    'static_block_html_rule_condition',
                    'id_static_block_html_rule_condition_group =' .
                    (int) $row['id_static_block_html_rule_condition_group']
                );
            }
        }
    }

    protected static function formatIntInQuery($first_value, $second_value)
    {
        $first_value = (int) $first_value;
        $second_value = (int) $second_value;
        if ($first_value != $second_value) {
            return 'IN (' . $first_value . ', ' . $second_value . ')';
        } else {
            return ' = ' . $first_value;
        }
    }

    public static function updatePosition($id, $position, $way = false)
    {
        if (!$res = Db::getInstance()->executeS(
            'SELECT `id_static_block`, `position`
            FROM `' . _DB_PREFIX_ . 'static_block`
            ORDER BY `position` ASC'
        )) {
            return false;
        }

        foreach ($res as $staticblock) {
            if ((int) $staticblock['id_static_block'] == (int) $id) {
                $moved_blocks = $staticblock;
            }
        }

        if (!isset($moved_blocks) || !isset($position)) {
            return false;
        }

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'static_block`
            SET `position`= `position` ' . ($way ? '- 1' : '+ 1') . '
            WHERE `position`
            ' . ($way
            ? '> ' . (int) $moved_blocks['position'] . ' AND `position` <= ' . (int) $position
            : '< ' . (int) $moved_blocks['position'] . ' AND `position` >= ' . (int) $position . '
            '))
            && Db::getInstance()->execute('
            UPDATE `' . _DB_PREFIX_ . 'static_block`
            SET `position` = ' . (int) $position . '
            WHERE `id_static_block` = ' . (int) $moved_blocks['id_static_block']));
    }

    public static function positionOccupied($position)
    {
        if (!$position) {
            return false;
        }

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'static_blcok` WHERE position = ' . (int) $position;
        return (bool) Db::getInstance()->getRow($sql);
    }

    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`) FROM `' . _DB_PREFIX_ . 'static_block`';
        $position = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }

    public static function countListContent()
    {
        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(id_static_block)
            FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`');
    }

    /* Upgrade Functions */
    public static function columnExists($column_name)
    {
        $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
            WHERE table_schema = "' . _DB_NAME_ . '" AND table_name = "' . _DB_PREFIX_ . 'static_block"');
        if (isset($columns) && $columns) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] == $column_name) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function addNewValuesForUpgrade()
    {
        $return = true;
        if (self::columnExists('editor')) {
            $return = true;
        } else {
            $return = Db::getInstance()->execute('
                ALTER TABLE `' . _DB_PREFIX_ . 'static_block`
                ADD `editor` int(10) unsigned NOT NULL DEFAULT 1
           ');
        }
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance`(
                `id_fmm_reassurance`       int(10) unsigned NOT NULL auto_increment,
                `status`                    tinyint(1) unsigned NOT NULL DEFAULT 0,
                `image`                     text,
                `link`                      text,
                `apperance`                 varchar(100) NOT NULL,
                PRIMARY KEY                 (`id_fmm_reassurance`))
            ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8');
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance_lang`(
                `id_fmm_reassurance`           int(10) unsigned NOT NULL,
                `id_lang`                   int(10) unsigned NOT NULL,
                `title`                     VARCHAR(1000) NOT NULL,
                `sub_title`                 VARCHAR(1000) NOT NULL,
                `description`               LONGTEXT,
                `id_shop`                   int(10) unsigned NOT NULL,
                PRIMARY KEY                 (`id_fmm_reassurance`,`id_lang`))
            ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8');
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance_shop`(
                `id_fmm_reassurance`           int(10) unsigned NOT NULL,
                `id_shop`                   int(10) unsigned NOT NULL,
                PRIMARY KEY                 (`id_fmm_reassurance`,`id_shop`))
            ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8');
        return $return;
    }

    public static function addHooks($hook_name, $hook_title)
    {
        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'static_block_hooks` (`hook_name`,`hook_title`)
        VALUES("' . $hook_name . '", "' . $hook_title . '")';
        if (Db::getInstance()->execute($sql)) {
            return Db::getInstance()->Insert_ID();
        }
        return true;
    }

    public static function getAllHooks()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM '._DB_PREFIX_.'static_block_hooks');
    }

    public static function deleteHooks($id_static_block_hook)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'static_block_hooks`
        WHERE `id_static_block_hook` = ' . (int) $id_static_block_hook;
        Db::getInstance()->execute($sql);
        return $sql;
    }
}
