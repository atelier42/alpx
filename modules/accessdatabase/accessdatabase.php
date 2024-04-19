<?php
/**
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Accessdatabase extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'accessdatabase';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'sx_tr';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Quick Access DB');
        $this->description = $this->l('Quick access data base');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->context->smarty->assign('module_dir', $this->_path);

        $parameters = array();
        if(_PS_VERSION_ > '1.7'){
            $config = include(_PS_ROOT_DIR_.'/app/config/parameters.php');
            if(!empty($config['parameters'])){
                $parameters = $config['parameters'];
            }
        }else{
            $parameters['_DB_SERVER_'] = _DB_SERVER_;
            $parameters['_DB_NAME_'] = _DB_NAME_;
            $parameters['_DB_USER_'] = _DB_USER_;
            $parameters['_DB_PASSWD_'] = _DB_PASSWD_;
            $parameters['_DB_PREFIX_'] = _DB_PREFIX_;
        }
        $this->context->smarty->assign('parameters', $parameters);
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output;
    }
}


