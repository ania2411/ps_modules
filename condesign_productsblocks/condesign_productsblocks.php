<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class Condesign_Productsblocks extends Module implements WidgetInterface
{
    private $template_file;
    private $js_path;
    private $css_path;

    public function __construct()
    {
        $this->name = 'condesign_productsblocks';
        $this->author = 'Condesign';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->ps_versions_compliancy = [
            'min' => '1.7.1.0',
            'max' => _PS_VERSION_,
        ];

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Blocks with products', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks');
        $this->description = $this->trans('Pick a category and highlight its items.', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks');

        $this->js_path = $this->_path . 'views/js/';
        $this->css_path = $this->_path . 'views/css/';
        $this->template_file = 'module:condesign_productsblocks/views/templates/hook/condesign_productsblocks.tpl';
    }

    public function install()
    {
        $this->_clearCache('*');

        Configuration::updateValue('CON_PB_CAT_1', (int) Context::getContext()->shop->getCategory());
        Configuration::updateValue('CON_PB_CAT_2', (int) Context::getContext()->shop->getCategory());
        Configuration::updateValue('CON_PB_CAT_3', (int) Context::getContext()->shop->getCategory());

        return parent::install()
            && $this->registerHook('actionProductAdd')
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('actionProductDelete')
            && $this->registerHook('displayHome')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('header')
            && $this->registerHook('actionCategoryUpdate')
            && $this->registerHook('actionAdminGroupsControllerSaveAfter')
        ;
    }

    public function hookHeader()
    {
        $js_list = [];
        $css_list = [];

        $css_list[] = $this->css_path . 'slick.css';
        $css_list[] = $this->css_path . 'con-pb.css';
        $js_list[] = $this->js_path . 'slick.min.js';
        $js_list[] = $this->js_path . 'con-pb.js';

        foreach ($css_list as $css_url) {
            $this->context->controller->registerStylesheet(sha1($css_url), $css_url, ['media' => 'all', 'priority' => 80]);
        }
        foreach ($js_list as $js_url) {
            $this->context->controller->registerJavascript(sha1($js_url), $js_url, ['position' => 'bottom', 'priority' => 80]);
        }
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function hookActionProductAdd($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionProductUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionProductDelete($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionAdminGroupsControllerSaveAfter($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addCSS($this->css_path . 'select2.min.css');
        $this->context->controller->addJS($this->js_path . 'select2.min.js');
        $this->context->controller->addJS($this->js_path . 'con-pb-admin.js');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache($this->template_file);
    }

    public function getContent()
    {
        $output = '';
        $errors = [];

        if (Tools::isSubmit('submitProductsBlocks')) {

            $cat_1 = Tools::getValue('CON_PB_CAT_1');
            if (!Validate::isInt($cat_1) || $cat_1 <= 0) {
                $errors[] = $this->trans('The category ID is invalid. Please choose an existing category ID.', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks');
            }

            $cat_2 = Tools::getValue('CON_PB_CAT_2');
            if (!Validate::isInt($cat_2) || $cat_2 <= 0) {
                $errors[] = $this->trans('The category ID is invalid. Please choose an existing category ID.', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks');
            }

            $cat_3 = Tools::getValue('CON_PB_CAT_3');
            if (!Validate::isInt($cat_3) || $cat_3 <= 0) {
                $errors[] = $this->trans('The category ID is invalid. Please choose an existing category ID.', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks');
            }

            if (count($errors)) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                Configuration::updateValue('CON_PB_CAT_1', (int) $cat_1);
                Configuration::updateValue('CON_PB_CAT_2', (int) $cat_2);
                Configuration::updateValue('CON_PB_CAT_3', (int) $cat_3);

                $this->_clearCache('*');

                $output = $this->displayConfirmation($this->trans('The settings have been updated.', [], 'Admin.Notifications.Success'));
            }
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $categories = [];
        $items = Category::getCategories($this->context->language->id, true, false);
        foreach($items as $c) {
            $categories[] = ['id' => $c['id_category'], 'name' => $c['name'] . ' (ID: ' . $c['id_category'] . ')'];
        }

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', [], 'Admin.Global'),
                    'icon' => 'icon-cogs',
                ],

                'input' => [
                    [
                        'type' => 'select',
                        'label' => $this->trans('Category 1', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks'),
                        'name' => 'CON_PB_CAT_1',
                        'required' => true,
                        'options' => [
                            'query' => $categories,
                            'id' => 'id',
                            'name' => 'name',
                        ]
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->trans('Category 2', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks'),
                        'name' => 'CON_PB_CAT_2',
                        'required' => true,
                        'options' => [
                            'query' => $categories,
                            'id' => 'id',
                            'name' => 'name',
                        ]
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->trans('Category 3', [], 'Modules.Condesignproductsblocks.Condesignproductsblocks'),
                        'name' => 'CON_PB_CAT_3',
                        'required' => true,
                        'options' => [
                            'query' => $categories,
                            'id' => 'id',
                            'name' => 'name',
                        ]
                    ],

                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Admin.Actions'),
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProductsBlocks';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fields_form]);
    }

    public function getConfigFieldsValues()
    {
        return [
            'CON_PB_CAT_1' => Tools::getValue('CON_PB_CAT_1', (int) Configuration::get('CON_PB_CAT_1')),
            'CON_PB_CAT_2' => Tools::getValue('CON_PB_CAT_2', (int) Configuration::get('CON_PB_CAT_2')),
            'CON_PB_CAT_3' => Tools::getValue('CON_PB_CAT_3', (int) Configuration::get('CON_PB_CAT_3')),
        ];
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->template_file, $this->getCacheId('condesign_productsblocks'))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);

            if (empty($variables)) {
                return false;
            }

            $this->smarty->assign($variables);
        }

        return $this->fetch($this->template_file, $this->getCacheId('condesign_productsblocks'));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $config = $this->getConfigFieldsValues();
        $array = [];

        for($i = 1; $i <= 3; $i++){
            $id_category = $config['CON_PB_CAT_'.$i];
            if(!Category::existsInDatabase($id_category, 'category')){
                continue;
            }
            $category = new Category($id_category, Context::getContext()->language->id);
            $array[] = [
                'products' => $this->getProducts($config['CON_PB_CAT_'.$i]),
                'link' => Context::getContext()->link->getCategoryLink($id_category),
                'category_name' => $category->name
            ];
        }

        return ['blocks' => $array];
    }

    protected function getProducts($id_category)
    {
        $shop = Context::getContext()->shop;
        $shop_group = $shop->getGroup();
        $id_shop_group = $shop_group->id;
        $share_stock = $shop_group->share_stock;
        $sql = 'SELECT DISTINCT p.`id_product`
                FROM `' . _DB_PREFIX_ . 'product` p
                INNER JOIN `' . _DB_PREFIX_ . 'product_shop` product_shop ON (product_shop.id_product = p.id_product AND product_shop.id_shop = ' . $shop->id . ')';
        $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cp ON cp.`id_product` = p.`id_product`';

        if ($share_stock) {
            $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sav ON (sav.`id_product` = p.`id_product`' .
                 ' AND sav.`id_product_attribute` = 0 AND sav.id_shop = 0 AND  sav.id_shop_group = ' . (int)$id_shop_group . ')';
        } else {
            $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sav ON (sav.`id_product` = p.`id_product`'.
                 ' AND sav.`id_product_attribute` = 0 AND sav.id_shop = ' . $shop->id . ')';
        }

        $sql .= ' WHERE product_shop.active = 1 and cp.id_category = ' . $id_category . ' and sav.quantity > 0';
        $sql .= ' AND product_shop.`visibility` IN ("both", "catalog")';
        $sql .= ' ORDER BY position ASC limit 10';

        $result = \Db::getInstance()->executeS($sql);
        if($result){
            $result = array_column($result, 'id_product');
        } else {
            $result = [];
        }

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];

        foreach ($result as $id_product) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct(['id_product' => $id_product]),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    protected function getCacheId($name = null)
    {
        $cacheId = parent::getCacheId($name);
        if (!empty($this->context->customer->id)) {
            $cacheId .= '|' . $this->context->customer->id;
        }

        return $cacheId;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

}
