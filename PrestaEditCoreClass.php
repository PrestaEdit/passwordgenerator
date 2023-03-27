<?php
/**
* 2007-2016 PrestaShop
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
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class PrestaEditCoreClass extends Module
{
    protected $returnContent = '';

    public function getContent()
    {
        $this->returnContent .= $this->processGetContent();

        $this->returnContent .= $this->showCrossSelling();

        return $this->returnContent;
    }

    protected function processGetContent()
    {
    }

    private function showCrossSelling()
    {
        if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
            $this->context->controller->addCSS($this->_path . 'views/css/core/crossselling.css', 'all');
            $this->context->smarty->assign(array(
                'modules' => $this->getAddonsModules(),
                'module_name' => $this->name,
            ));

            return $this->display($this->getLocalPath(), 'views/templates/admin/core/display_crossselling.tpl');
        }
    }

    private function getAddonsModules()
    {
        $modules = Configuration::get('PRESTAEDIT_MODULES');
        $modules_date = Configuration::get('PRESTAEDIT_MODULES_DATE');

        if ($modules && strtotime('+1 WEEK', $modules_date) > time()) {
            return Tools::jsonDecode($modules);
        }

        $post_data = http_build_query(array(
            'version' => _PS_VERSION_,
            'iso_lang' => Tools::strtolower(Context::getContext()->language->iso_code),
            'iso_code' => Tools::strtolower(Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'))),
            'module_key' => $this->module_key,
            'method' => 'contributor',
            'action' => 'all_products'
        ));

        $context = stream_context_create(array(
            'http' => array(
                'method'    => 'POST',
                'content' => $post_data,
                'header'    => 'Content-type: application/x-www-form-urlencoded',
                'timeout' => 5
            )
        ));
        $content = Tools::file_get_contents('https://api.addons.prestashop.com', false, $context);

        if (!$content) {
            return false;
        }

        $json = Tools::jsonDecode($content);

        if (!isset($json->products)) {
            return false;
        }

        Configuration::updateValue('PRESTAEDIT_MODULES', Tools::jsonEncode($json->products));
        Configuration::updateValue('PRESTAEDIT_MODULES_DATE', time());

        return $json->products;
    }
}
