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

function upgrade_module_1_6_14()
{
    $res = true;

    if (Configuration::get('dwfproductextrafields_has_tab') === false) {
        $res &= Configuration::updateValue('dwfproductextrafields_has_tab', '0');
    }

    return $res;
}
