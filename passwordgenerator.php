<?php
/**
* 2007-2016 PrestaShop
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
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

if (!class_exists('PrestaEditCoreClass')) {
    include_once _PS_ROOT_DIR_ . '/modules/passwordgenerator/PrestaEditCoreClass.php';
}

class PasswordGenerator extends PrestaEditCoreClass
{
    public function __construct()
    {
        $this->name = 'passwordgenerator';
        $this->tab = 'administration';
        $this->version = '1.2.5';
        $this->author = 'PrestaEdit';
        $this->ps_versions_compliancy = array('min' => '1.5.0.1', 'max' => _PS_VERSION_);
        $this->need_instance = 0;
        $this->module_key = '59c64ca74a5fa7a97d17b9ebc4746c7f';

        parent::__construct();

        $this->bootstrap = true;

        $this->displayName = $this->l('Password Generator');
        $this->description = $this->l('Password generator and sends e-mail notifications to customers and employees.');
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        // Customer
        if (!$this->registerHook('actionObjectCustomerAddAfter') || !$this->registerHook('actionObjectCustomerUpdateAfter')
            || !$this->registerHook('displayAdminCustomersForm')) {
            return false;
        }

        // Employee
        if (version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
            if (!$this->registerHook('actionObjectEmployeeAddAfter') || !$this->registerHook('actionObjectEmployeeUpdateAfter')
                || !$this->registerHook('displayAdminEmployeesForm')) {
                return false;
            }
        }

        return true;
    }

    public function hookDisplayAdminEmployeesForm()
    {
        return $this->display(__FILE__, 'views/templates/hook/displayAdminEmployeesForm.tpl');
    }

    public function hookDisplayAdminCustomersForm()
    {
        return $this->display(__FILE__, 'views/templates/hook/displayAdminCustomersForm.tpl');
    }

    public function hookActionObjectCustomerAddAfter($params)
    {
        $this->performProcess($params);
    }

    public function hookActionObjectCustomerUpdateAfter($params)
    {
        $this->performProcess($params);
    }

    public function hookActionObjectEmployeeAddAfter($params)
    {
        $this->performProcess($params);
    }

    public function hookActionObjectEmployeeUpdateAfter($params)
    {
        $this->performProcess($params);
    }

    protected function performProcess($params)
    {
        if (Tools::isSubmit('sendMail')) {
            $this->sendMail($params['object'], Tools::getValue('passwd'));
        }
    }

    protected function sendMail($object, $passwd)
    {
        $template_vars = array(
            '{email}' => $object->email,
            '{passwd}' => $passwd,
            '{firstname}' => $object->firstname,
            '{lastname}' => $object->lastname
        );

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $template_vars['{email}'] = $object->email.'<br /><span style="color:#333"><strong>'.$this->l('Password').' :</strong></span> '.$passwd;
        }

        Mail::Send((int)$object->id_lang, 'password', Mail::l('Your new password', (int)$object->id_lang), $template_vars, $object->email, $object->firstname.' '.$object->lastname);
    }
}
