<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'happycolis/include.php';

class HappyColis extends Module
{
    private $ORDER_SAVE_EVENT;
    private $ORDER_UPDATE_STATUS_EVENT;
    private $ORDER_CANCEL_EVENT;
    private $ORDER_PAID_EVENT;
    private $PRODUCT_UPDATE_EVENT;

    private static $isOrderStatusUpdate = false;
    private static $isOrderUpdate = false;

    /** @var GetBiggerMessageIncomingClient */
    private $getBiggerClient;

    /** @var int */
    private $idLang;

    public function __construct()
    {
        $this->name = 'happycolis';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'HappyColis';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->idLang = (int)$this->context->language->id;

        $this->displayName = $this->l('HappyColis');
        $this->description = $this->l('La logistique des plus grands pour tous les e-commerçants');

        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller le module HappyColis ?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->getBiggerClient = new GetBiggerMessageIncomingClient();

        $this->ORDER_SAVE_EVENT = GetBiggerMessageIncomingClient::$ORDER_HOOK_EVENT['order/save'];
        $this->ORDER_UPDATE_STATUS_EVENT = GetBiggerMessageIncomingClient::$ORDER_HOOK_EVENT['order/status-update'];
        $this->ORDER_CANCEL_EVENT = GetBiggerMessageIncomingClient::$ORDER_HOOK_EVENT['order/cancel'];
        $this->ORDER_PAID_EVENT = GetBiggerMessageIncomingClient::$ORDER_HOOK_EVENT['order/paid'];
        $this->PRODUCT_UPDATE_EVENT = GetBiggerMessageIncomingClient::$PRODUCT_HOOK_EVENT['product/update'];
    }

    public function install()
    {
        $config = new GetBiggerConfiguration();

        if (!Configuration::hasKey('GETBIGGER_HAPPY_COLIS_ENDPOINT')) {
            Configuration::updateValue('GETBIGGER_HAPPY_COLIS_ENDPOINT', $config->getHappyColisEndpoint());
        }

        if (!Configuration::hasKey('GETBIGGER_CREATE_CARRIERS')) {
            Configuration::updateValue('GETBIGGER_CREATE_CARRIERS', '');
        }

        if (!Configuration::hasKey('GETBIGGER_CREATED_CARRIERS_IDS')) {
            Configuration::updateValue('GETBIGGER_CREATED_CARRIERS_IDS', null);
        }

        return parent::install() &&
            $this->installOverrideClasses() &&
            $this->registerHook('actionOrderStatusPostUpdate') && // 1.7 & 1.6
            $this->registerHook('addWebserviceResources') && // 1.7 only
            $this->registerHook('actionAdminProductsControllerSaveAfter') && // 1.7 & 1.6
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('actionObjectOrderUpdateAfter') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('actionOrderEdited'); // 1.7 & 1.6
    }

    private function installOverrideClasses()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            return true;
        }

        $classes = [
            'WebserviceRequest',
            'WebserviceSpecificManagementGborders',
            'WebserviceSpecificManagementGbproducts',
            'WebserviceSpecificManagementGbsuppliers'
        ];

        foreach ($classes as $class) {
            if (file_exists(_PS_OVERRIDE_DIR_ . '/classes/webservice/' . $class . '.php')) {
                @rename(_PS_OVERRIDE_DIR_ . '/classes/webservice/' . $class . '.php', _PS_OVERRIDE_DIR_ . '/classes/webservice/' . mktime() . '_HappyColis_' . $class . '.php');
            }

            $result = copy(__DIR__ . '/classes/webservice/' . $class . '.php', _PS_OVERRIDE_DIR_ . '/classes/webservice/' . $class . '.php');

            if (false === $result) {
                return false;
            }
        }

        @unlink(_PS_CACHE_DIR_ . 'class_index.php');

        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output = '';

        if (Tools::getValue('submitProductSync')) {
            $numSync = $this->syncProduct();
            $output .= $this->displayConfirmation($this->l('Nombre de produits synchronisés : ' . $numSync, 'happycolis'));
        }

        if (Tools::getValue('submitOrderSync')) {
            $numSync = $this->syncOrders();
            $output .= $this->displayConfirmation($this->l('Nombre de commandes synchronisées : ' . $numSync, 'happycolis'));
        }

        if (Tools::getValue('submitSupplierSync')) {
            $numSync = $this->syncSuppliers();
            $output .= $this->displayConfirmation($this->l('Nombre de fournisseurs synchronisés : ' . $numSync, 'happycolis'));
        }

        if (Tools::isSubmit('submitGetbiggerModule') && !Tools::getValue('submitOrderSync') && !Tools::getValue('submitProductSync') && !Tools::getValue('submitSupplierSync')) {
            $output .= $this->postProcess();
            $output .= $this->displayConfirmation($this->l('Settings saved.', 'happycolis'));
        }

        $this->context->smarty->assign([
            'module_dir' => $this->_path,
            'url_admin' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name,
        ]);

        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        $output .= $this->renderForm();

        return $output;
    }

    /**
     * Create the form that will be displayed in the configuration page.
     */
    protected function renderForm()
    {
        $warehouses = array_map(function ($warehouse) {
            return [
                'id' => $warehouse['reference'],
                'name' => $warehouse['name'],
                'val' => $warehouse['reference'],
            ];
        }, self::getWarehouses());

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGetbiggerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $fieldValues = $this->getConfigFormValues();

        $selectedWarehouses = [];
        if (!empty($fieldValues['GETBIGGER_WAREHOUSE'])) {
            $references = explode(',', $fieldValues['GETBIGGER_WAREHOUSE']);
            foreach ($references as $reference) {
                $selectedWarehouses['GETBIGGER_WAREHOUSE_'.$reference] = true;
            }
        }

        $fieldValues = array_merge($selectedWarehouses, $fieldValues, [
            'order_status_filter[]' => '',
            'order_date_from_filter' => '',
            'order_date_to_filter' => '',
            'active_product_filter' => '',
        ]);

        $helper->tpl_vars = [
            'fields_value' => $fieldValues,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        ];

        return $helper->generateForm([
            $this->getAPIConfigForm($warehouses),
            $this->getGetBiggerCarrierConfigForm(),
            $this->getSyncOrderForm(),
            $this->getSyncProductForm(),
            $this->getSyncSupplierForm(),
        ]);
    }

    /**
     * Create the structure of the API configuration form.
     */
    protected function getAPIConfigForm($warehouses)
    {
        $configForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('API Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'col' => 3,
                        'required' => true,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-user"></i>',
                        'desc' => $this->l('Enter your HappyColis Application ID'),
                        'name' => 'GETBIGGER_APP_ID',
                        'label' => $this->l('Application ID'),
                    ],
                    [
                        'col' => 3,
                        'required' => true,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-user"></i>',
                        'desc' => $this->l('Enter your HappyColis API identifier'),
                        'name' => 'GETBIGGER_IDENTIFIER',
                        'label' => $this->l('API Identifier'),
                    ],
                    [
                        'col' => 5,
                        'required' => true,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('Enter your HappyColis API secret'),
                        'name' => 'GETBIGGER_SECRET',
                        'label' => $this->l('API Secret'),
                    ],
                    [
                        'col' => 5,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-globe"></i>',
                        'name' => 'GETBIGGER_HAPPY_COLIS_ENDPOINT',
                        'label' => $this->l('HappyColis URL'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        if (!empty($warehouses)) {
            $configForm['form']['input'][] = [
                'label' => $this->l('Entrepôts gérés par HappyColis'),
                'desc' => $this->l('Veuillez sélectionner les entrepôts qui seront gérés par HappyColis. Si aucun entrepôts n`est sélectionné alors ils seront tous gérés.'),
                'type' => 'checkbox',
                'name' => 'GETBIGGER_WAREHOUSE',
                'values' => [
                    'query' => $warehouses,
                    'id' => 'id',
                    'name' => 'name'
                ]
            ];
        }

        return $configForm;
    }

    /**
     * Carriers configuration form
     */
    protected function getGetBiggerCarrierConfigForm()
    {
        $carriersCreated = Configuration::get('GETBIGGER_CREATE_CARRIERS', null, null, null, false);
        $linkConfigureCarriers = $this->context->link->getAdminLink('AdminCarriers', true);
        $linkConfigureCarriers = sprintf('<a href="%s"> Configure </a>', $linkConfigureCarriers);
        $help = $carriersCreated ? $this->l('HappyColis carriers have been successfully created.') . $linkConfigureCarriers : $this->l('This will create predefined HappyColis carriers');

        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Carriers'),
                    'icon' => 'icon-bug',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Create HappyColis carriers'),
                        'name' => 'GETBIGGER_CREATE_CARRIERS',
                        'is_bool' => true,
                        'desc' => $help,
                        'disabled' => $carriersCreated ? true : false,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    protected function getSyncOrderForm()
    {
        $statuses = OrderStateCore::getOrderStates($this->idLang);

        return array(
            'form' => [
                'legend' => [
                    'title' => $this->l('Synchronisation des commandes'),
                    'icon' => 'icon-bug',
                ],
                'input' => [
                    [
                        'type' => 'select',
                        'label' => $this->l('Status de commande'),
                        'name' => 'order_status_filter[]',
                        'multiple' => true,
                        'class' => 'fixed-width-xxl',
                        'desc' => $this->l('Veuillez sélectionner les status des commandes qui seront synchronisées. Toutes les commande seront synchronisées si aucun n\'est sélectionné'),
                        'options' => [
                            'query' => $statuses,
                            'name' => 'name',
                            'id' => 'id_order_state'
                        ]
                    ],
                    [
                        'type' => 'date',
                        'name' => 'order_date_from_filter',
                        'label' => $this->l('Date début de création '),
                    ],
                    [
                        'type' => 'date',
                        'name' => 'order_date_to_filter',
                        'label' => $this->l('Date fin de création '),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Synchronize'),
                    'name' => 'submitOrderSync'
                ],
            ],
        );
    }

    protected function getSyncProductForm()
    {
        return array(
            'form' => [
                'legend' => [
                    'title' => $this->l('Synchronisation des produits'),
                    'icon' => 'icon-bug',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Active'),
                        'name' => 'active_product_filter',
                        'is_bool' => true,
                        'desc' => $this->l('Synchroniser uniquement les produits actives'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Synchronize'),
                    'name' => 'submitProductSync'
                ],
            ],
        );
    }

    protected function getSyncSupplierForm()
    {
        return array(
            'form' => [
                'legend' => [
                    'title' => $this->l('Synchronisation des fournisseurs'),
                    'icon' => 'icon-bug',
                ],
                'input' => [],
                'submit' => [
                    'title' => $this->l('Synchronize'),
                    'name' => 'submitSupplierSync'
                ],
            ],
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return Configuration::getMultiple([
            'GETBIGGER_APP_ID',
            'GETBIGGER_IDENTIFIER',
            'GETBIGGER_SECRET',
            'GETBIGGER_HAPPY_COLIS_ENDPOINT',
            'GETBIGGER_CREATE_CARRIERS',
            'GETBIGGER_WAREHOUSE',
        ]);
    }

    /**
     * Save form data.
     *
     * @return string Error or success messages
     */
    protected function postProcess()
    {
        foreach (
            [
                'GETBIGGER_APP_ID',
                'GETBIGGER_IDENTIFIER',
                'GETBIGGER_SECRET',
                'GETBIGGER_HAPPY_COLIS_ENDPOINT'
            ] as $configName) {
            Configuration::updateValue($configName, Tools::getValue($configName));
        }

        $selectedWarehouse = array_map(function ($key) {
            return str_replace(' ', '_', strtolower(Tools::getValue($key)));
        }, array_filter(array_keys(Tools::getAllValues()), function ($key) {
            return strpos($key, 'GETBIGGER_WAREHOUSE') === 0;
        }));

        Configuration::updateValue('GETBIGGER_WAREHOUSE', implode(',', $selectedWarehouse));

        $carrierCreated = Configuration::get('GETBIGGER_CREATE_CARRIERS');

        $resultMessages = '';

        if (Tools::getValue('GETBIGGER_CREATE_CARRIERS') && !$carrierCreated) {
            $resultMessages = (new GetBiggerCarrierCreator())->create();
        }

        return $resultMessages;
    }

    /**
     * Hook triggered when webservice resources are listed in the admin part
     *
     * @param array $data
     */
    public function hookAddWebserviceResources(array $data)
    {
        return [
            'gbproducts' => [
                'description' => 'API personnalisée HappyColis synchronisation des produits',
                'specific_management' => true,
            ],
            'gborders' => [
                'description' => 'API personnalisée HappyColis synchronisation des commandes',
                'specific_management' => true,
            ],
            'gbsuppliers' => [
                'description' => 'API personnalisée HappyColis synchronisation des fournisseurs',
                'specific_management' => true,
            ],
        ];
    }

    public function hookActionOrderStatusUpdate(array $data)
    {
        self::$isOrderStatusUpdate = true;
    }

    public function hookActionValidateOrder(array $data)
    {
        $this->getBiggerClient->send($this->ORDER_SAVE_EVENT, $data['order']);
    }

    public function hookActionAdminProductsControllerSaveAfter(array $data)
    {
        /** @var \Product $product */
        $product = $data['return'];

        $this->getBiggerClient->send($this->PRODUCT_UPDATE_EVENT, $product);
    }

    /**
     * @param array $data
     */
    public function hookActionOrderEdited(array $data)
    {
        $this->getBiggerClient->send($this->ORDER_SAVE_EVENT, $data['order']);
    }

    /**
     * @param array $data
     */
    public function hookActionObjectOrderUpdateAfter(array $data)
    {
        if (self::$isOrderStatusUpdate || self::$isOrderUpdate) {
            return;
        }

        $this->getBiggerClient->send($this->ORDER_SAVE_EVENT, $data['object']);
        self::$isOrderUpdate = true;
    }

    public function hookActionOrderStatusPostUpdate(array $data)
    {
        $event = $this->ORDER_UPDATE_STATUS_EVENT;

        // if the order status si cancelled send event order/cancel
        if (isset($data['newOrderStatus']) && $data['newOrderStatus'] instanceof \OrderState) {
            $orderState = $data['newOrderStatus'];
            if ($orderState->id == Configuration::get('PS_OS_CANCELED')) {
                $event = $this->ORDER_CANCEL_EVENT;
            }

            if (in_array($orderState->id, [Configuration::get('PS_OS_PAYMENT'), Configuration::get('PS_OS_WS_PAYMENT')])) {
                $event = $this->ORDER_PAID_EVENT;
            }
        }

        $this->getBiggerClient->send($event, new Order($data['id_order']));
    }

    public static function getWarehouses()
    {
        $query = new DbQuery();
        $query->select('w.*');
        $query->from('warehouse', 'w');
        $query->orderBy('w.name ASC');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    private function syncProduct()
    {
        $active = Tools::getValue('active_product_filter') === '1';

        return (new GetBiggerSynchronizationClient())->syncProducts($active);
    }

    private function syncOrders()
    {
        $statusFilter = Tools::getValue('order_status_filter');
        $dateFromFilter = Tools::getValue('order_date_from_filter');
        $dateToFilter = Tools::getValue('order_date_to_filter');

        return (new GetBiggerSynchronizationClient())->syncOrders($statusFilter, $dateFromFilter, $dateToFilter);
    }

    private function syncSuppliers()
    {
        return (new GetBiggerSynchronizationClient())->syncSuppliers();
    }
}
