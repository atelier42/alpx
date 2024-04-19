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

function upgrade_module_1_7_37($object)
{
    return $object->unregisterHook('actionProductDelete')
        && $object->registerHook('actionAdminProductsControllerDeleteAfter')
        && $object->registerHook('actionAdminProductsControllerDuplicateAfter');
}
