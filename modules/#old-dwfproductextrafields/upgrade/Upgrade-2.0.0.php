<?php
/**
 *   Copyright since 2009 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright since 2009 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_0($module)
{
    if ($module->getExtraFieldImagePath(1) == ltrim(_PS_IMG_.'modules/dwfproductextrafields', '/')) {

        require_once _PS_MODULE_DIR_ . 'dwfproductextrafields/models/ProductExtraField.php';

        $fields = Db::getInstance()->executeS('SELECT `fieldname`, `config`, `type`
            FROM `' . _DB_PREFIX_ . 'dwfproductextrafields`
            WHERE `type` IN ("image", "repeater")');

        $needToProcess = false;
        foreach ($fields as &$field) {
            if ($field['type'] == 'image') {
                $needToProcess = true;
            } else {
                if ($field['config']) {
                    $field['image_fields'] = [];
                    $config = json_decode($field['config'], true);
                    foreach ($config['elements'] as $element) {
                        if ($element['type'] == 'image') {
                            $field['image_fields'][] = $element['key'];
                            $needToProcess = true;
                        }
                    }
                }
            }
        }

        if ($needToProcess) {
            $productExtrafieldIds = Db::getInstance()->executeS('SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_extra_field`');
            foreach ($productExtrafieldIds as $productExtrafieldId) {
                $extrafields = ProductExtraField::getByIdProduct($productExtrafieldId['id_product']);
                foreach ($fields as $field) {
                    if ($field['type'] == 'image') {
                        if (!empty($extrafields->{$field['fieldname']})) {
                            if (file_exists(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $extrafields->{$field['fieldname']})
                                && !empty(_PS_ROOT_DIR_._PS_IMG_.'modules' . DIRECTORY_SEPARATOR . 'dwfproductextrafields' . DIRECTORY_SEPARATOR . $extrafields->{$field['fieldname']})) {
                                if (copy(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $extrafields->{$field['fieldname']}, _PS_ROOT_DIR_._PS_IMG_.'modules' . DIRECTORY_SEPARATOR . 'dwfproductextrafields' . DIRECTORY_SEPARATOR . $extrafields->{$field['fieldname']})) {
                                    unlink(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $extrafields->{$field['fieldname']});
                                }
                            }
                        }
                    } else {
                        if ($field['config']) {
                            foreach (Language::getIDs() as $id_lang) {
                                if ($elements = json_decode($extrafields->{$field['fieldname']}[$id_lang], true)) {
                                    foreach ($elements as $element) {
                                        foreach ($element as $elementKey => $elementValue) {
                                            if (!empty($elementValue)) {
                                                if (in_array($elementKey, $field['image_fields'])) {
                                                    if (file_exists(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $elementValue)
                                                        && !empty(_PS_ROOT_DIR_._PS_IMG_.'modules' . DIRECTORY_SEPARATOR . 'dwfproductextrafields' . DIRECTORY_SEPARATOR . $elementValue)) {
                                                        if (copy(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $elementValue, _PS_ROOT_DIR_._PS_IMG_.'modules' . DIRECTORY_SEPARATOR . 'dwfproductextrafields' . DIRECTORY_SEPARATOR . $elementValue)) {
                                                            unlink(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $elementValue);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return true;
}
