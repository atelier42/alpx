<?php
/**
 * 2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @author ALCALINK E-COMMERCE & SEO, S.L.L. <info@alcalink.com>
 * @copyright  2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Registered Trademark & Property of ALCALINK E-COMMERCE & SEO, S.L.L.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class AlcaMultiFaq extends ObjectModel
{
    public $id;

    /** @var int id_alcamultifaqs */
    public $id_alcamultifaqs;

    /** @var string type */
    public $type;

    /** @var int id_object */
    public $id_object;

    /** @var int position */
    public $position;

    /** @var string title */
    public $title;

    /** @var string content */
    public $content;

    /** @var int id_shop */
    public $id_shop;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'alcamultifaqs',
        'primary' => 'id_alcamultifaqs',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'type' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 255,
                'required' => true,
            ],
            'id_object' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
            ],
            'position' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
            ],
            'title' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'shop' => true,
                'lang' => true,
            ],
            'content' => [
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHtml',
                'shop' => true,
                'lang' => true,
            ],
            'id_shop' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
            ],
        ],
    ];

    /**
     * Comprobar si producto FAQ existe
     *
     * @param int $id_product
     * @param int $id_shop
     *
     * @return array
     */
    public static function existsFaqForProduct($id_product, $id_shop = 1)
    {
        $sql = 'SELECT f.*
                FROM `' . _DB_PREFIX_ . 'alcamultifaqs` f
                WHERE f.`type` = "product" AND f.`id_object` = ' . $id_product . '
                AND f.`id_shop` = ' . (int) $id_shop;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Consulta todo el faq en base de datos
     *
     * @param int $id_lang
     * @param string|null $type
     * @param int $id_object
     * @param int $id_shop
     *
     * @return array
     */
    public static function getAllFaq($id_lang, $type = null, $id_object = 0, $id_shop = 1)
    {
        $sql = 'SELECT f.*, fl.*
                FROM `' . _DB_PREFIX_ . 'alcamultifaqs` f
                LEFT JOIN `' . _DB_PREFIX_ . 'alcamultifaqs_lang` fl ON (f.`id_alcamultifaqs` = fl.`id_alcamultifaqs`)
                WHERE fl.`id_lang` = ' . $id_lang . ' AND f.`type` = "' . $type . '"'
        . ($id_object != 0 ? 'AND f.`id_object` = ' . $id_object : '')
        . ' AND f.`id_shop` = ' . (int) $id_shop;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return $result;
    }

    /**
     * Creación principal de la estructura de menú
     *
     * @param string|null $type
     * @param int|null $id_object
     * @param int|null $id_lang
     * @param int $id_shop
     * @param bool $showSimple
     *
     * @return array
     */
    public static function getStructureFaq($type = null, $id_object = null, $id_lang = null, $id_shop = 1, $showSimple = false)
    {
        if (!$id_lang) {
            $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        }

        $faqs = self::getAllFaq($id_lang, $type, $id_object, $id_shop);
        $structure = [];

        foreach ($faqs as $faq) {
            $faqObj = new AlcaMultiFaq((int) $faq['id_alcamultifaqs']);
            $structure[$faq['id_alcamultifaqs']]['i'] = (int) $faq['id_alcamultifaqs'];
            $structure[$faq['id_alcamultifaqs']]['title'] = $faq['title'];
            $structure[$faq['id_alcamultifaqs']]['content'] = $faq['content'];
            $structure[$faq['id_alcamultifaqs']]['pos'] = (int) $faq['position'];
            $structure[$faq['id_alcamultifaqs']]['type'] = $faq['type'];
            $structure[$faq['id_alcamultifaqs']]['id_object'] = (int) $faq['id_object'];
        }

        usort($structure, function ($a, $b) {
            return $a['pos'] <=> $b['pos'];
        });

        if ($showSimple) {
            $resultFaq = self::redefineFaqSimple($structure);
        } else {
            $resultFaq = $structure;
        }

        return $resultFaq;
    }

    /**
     * Eliminar algunos index del array del FAQ
     * (en el caso de parámetro showSimple=true de getStructureFaq)
     *
     * @param array &$faq
     *
     * @return array
     */
    public static function redefineFaqSimple(&$faq)
    {
        $hideFields = ['i', 'p', 'pos', 'type', 'id_object'];

        foreach ($faq as &$item) {
            if (is_array($item)) {
                foreach ($hideFields as $field) {
                    if (isset($item[$field])) {
                        unset($item[$field]);
                    }
                }

                self::redefineFaqSimple($item);
            }
        }

        return $faq;
    }

    /**
     * Refresca mediante AJAX el sistema FAQ creado en back
     *
     * @param string $type
     * @param int $id_object
     *
     * @return array
     */
    public static function getAjaxStructuredFaq($type, $id_object)
    {
        $context = Context::getContext();
        $context->cookie->__set('alcamultifaq_filter_type', $type);
        $context->cookie->__set('alcamultifaq_filter_id', $id_object);
        $context->cookie->write();

        $id_lang = $context->language->id;
        $id_shop = $context->shop->id;

        $alcamultifaqsResult = AlcaMultiFaq::getStructureFaq($type, $id_object, $id_lang, $id_shop, false);
        $context->smarty->assign(
            [
                'alcamultifaqsResult' => $alcamultifaqsResult,
                'urlconfigModule' => $context->link->getAdminLink('AdminModules', true)
                . '&configure=alcamultifaqs&module_name=alcamultifaqs',
            ]
        );

        $urlStructuredFaqTpl = _PS_MODULE_DIR_ . 'alcamultifaqs/views/templates/admin/alcamultifaqs-list.tpl';
        $resp['structured_faq'] = $context->smarty->fetch($urlStructuredFaqTpl);

        return $resp;
    }

    /**
     * Cargar IDs de los ítems del tipo
     *
     * @param string $type
     *
     * @return array
     */
    public static function getIdsByType($type)
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $id_shop = $context->shop->id;
        $result = [];

        switch ($type) {
            case 'category':
                $root_category = Category::getRootCategory((int) $id_lang, $context->shop);
                $category_tree = Category::getAllCategoriesName((int) $root_category->id, (int) $id_lang);

                foreach ($category_tree as $y => $c) {
                    $result[$y]['id'] = (int) $c['id_category'];
                    $result[$y]['name'] = $c['name'];
                }

                break;
            case 'product':
                $products = Product::getSimpleProducts((int) $id_lang, $context);

                if ($products) {
                    foreach ($products as $k => $p) {
                        $result[$k]['id'] = (int) $p['id_product'];
                        $result[$k]['name'] = $p['name'];
                    }
                }

                break;
            case 'cms':
                $cms = CMS::getCMSPages((int) $id_lang, null, true, (int) $id_shop);

                if ($cms) {
                    foreach ($cms as $k => $c) {
                        $result[$k]['id'] = (int) $c['id_cms'];
                        $result[$k]['name'] = $c['meta_title'];
                    }
                }

                break;
            case 'manufacturer':
                $manufacturers = Manufacturer::getManufacturers(false, (int) $id_lang);

                if ($manufacturers) {
                    foreach ($manufacturers as $k => $m) {
                        $result[$k]['id'] = (int) $m['id_manufacturer'];
                        $result[$k]['name'] = $m['name'];
                    }
                }

                break;
        }

        return $result;
    }

    /**
     * Cambio de poisición
     *
     * @param array $positions
     * @param int $id_shop
     *
     * @return void
     */
    public static function changePosition($positions, $id_shop = 1)
    {
        $module = Module::getInstanceByName('alcamultifaqs');

        foreach ($positions as $k) {
            if ($k['id_alcamultifaqs'] > 0) {
                $alcamultifaqs_other = new AlcaMultiFaq((int) $k['id_alcamultifaqs']);
                $alcamultifaqs_other->id_shop = (int) $id_shop;
                $alcamultifaqs_other->position = (int) $k['position'];
                $alcamultifaqs_other->update();
                $module->clearCacheObject($alcamultifaqs_other);
            }
        }

        return false;
    }

    /**
     * Última posición de los elementos de un mismo nivel
     *
     * @param int $id_alcamultifaqs
     * @param string $type
     * @param int $id_object
     *
     * @return array
     */
    public static function getLastPosition($id_alcamultifaqs, $type, $id_object = 0)
    {
        $sql = 'SELECT f.`position`
        FROM `' . _DB_PREFIX_ . 'alcamultifaqs` f
        WHERE f.`type` = "' . $type . '"'
            . ($id_object ? 'AND f.`id_object` = ' . $id_object : '') . '
        ORDER BY f.`position` DESC';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}
