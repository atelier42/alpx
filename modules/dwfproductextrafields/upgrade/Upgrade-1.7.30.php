<?php
/**
 *   2009-2021 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright 2009-2021 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_7_30($object)
{
    return $object->unregisterHook('actionProductUpdate')
        && $object->registerHook('actionAdminProductsControllerSaveAfter');
}
