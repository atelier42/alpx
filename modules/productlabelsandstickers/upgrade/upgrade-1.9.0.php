<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FMM Modules
*  @copyright FME Modules 2018
*  @license   Single domain
*/

if (!defined('_PS_VERSION_')) {
    exit;
}


function upgrade_module_1_9_0($module)
{
    if (columnExist('y_coordinate_listing') && columnExist('y_coordinate_product')) {
        return true;
    } else {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'fmm_stickers` ADD `y_coordinate_listing` INT(10) NOT NULL DEFAULT 0, ADD `y_coordinate_product` INT(10) NOT NULL DEFAULT 0');
    }
    return ($module->registerHook('displayProductPageCss'));
}

function columnExist($column_name)
{
    $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "'._DB_NAME_.'" AND table_name = "'._DB_PREFIX_.'fmm_stickers"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }
    return false;
}
