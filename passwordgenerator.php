<?php
/**
* 2007-2023 PrestaShop
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
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class PasswordGenerator extends Module
{
    public function __construct()
    {
        $this->name = 'passwordgenerator';
        $this->tab = 'administration';
        $this->version = '1.3.0';
        $this->author = 'PrestaEdit';
        $this->ps_versions_compliancy = array('min' => '1.5.0.1', 'max' => _PS_VERSION_);
        $this->need_instance = 0;

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

        if (version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
            $hooks = [
                'displayAdminEmployeesForm',
                'actionObjectEmployeeAddAfter',
                'actionObjectEmployeeUpdateAfter',
                'actionObjectCustomerAddAfter',
                'actionObjectCustomerUpdateAfter',
            ];
        } else if (version_compare(_PS_VERSION_, '1.7.6.0', '<')) {
            $hooks = [
                'actionObjectCustomerAddAfter',
                'actionObjectCustomerUpdateAfter',
            ];
        } else {
            $hooks = [
                'actionAdminControllerSetMedia',
                'actionCustomerFormBuilderModifier',
                'actionAfterCreateCustomerFormHandler',
                'actionAfterUpdateCustomerFormHandler',
            ];
        }

        if (!$this->registerHook($hooks)) {
            return false;
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
        $this->hookActionObjectEmployeeUpdateAfter($params);
    }

    public function hookActionObjectCustomerUpdateAfter($params)
    {
        $this->hookActionObjectEmployeeUpdateAfter($params);
    }

    public function hookActionObjectEmployeeAddAfter($params)
    {
        $this->hookActionObjectEmployeeUpdateAfter($params);
    }

    public function hookActionObjectEmployeeUpdateAfter($params)
    {
        $params['password'] = Tools::getValue('passwd');
        $this->performProcess($params, (bool) Tools::isSubmit('sendMail'));
    }

    /**
     * Hook allows to modify Customers form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     */
    public function hookActionCustomerFormBuilderModifier(array $params)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder->add('send_mail', \PrestaShopBundle\Form\Admin\Type\SwitchType::class, [
            'label' => $this->getTranslator()->trans('Send by mail', [], 'Modules.PasswordGenerator.Displayadmincustomersform'),
            'required' => false,
        ]);
        $formBuilder->add('generator', \Symfony\Component\Form\Extension\Core\Type\ButtonType::class, [
            'label' => $this->getTranslator()->trans('Generate code', [], 'Modules.PasswordGenerator.Displayadmincustomersform'),
            'row_attr' => ['class' => 'row'],
            'attr' => [
                'class' => 'offset-4 col-sm-3 input-container btn btn-default',
                'id' => 'passwd-generate-field',
                'onclick' => 'passwordGeneratorGenerateCode(10);',
            ],
        ]);

        $params['data']['send_mail'] = false;

        $formBuilder->setData($params['data']);
    }

    /**
     * Hook allows to modify Customers form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     *
     * @throws CustomerException
     */
    public function hookActionAfterUpdateCustomerFormHandler(array $params)
    {
        $process = $params['form_data']['send_mail'];
        $params['password'] = $params['form_data']['password'];
        $this->performProcess($params, $process);
    }

    /**
     * Hook allows to modify Customers form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     *
     * @throws CustomerException
     */
    public function hookActionAfterCreateCustomerFormHandler(array $params)
    {
        $this->hookActionAfterUpdateCustomerFormHandler($params);
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        if ('AdminCustomers' !== $this->context->controller->controller_name) {
            return;
        }

        $action = Tools::getValue('action');

        if ($action !== 'updatecustomer' && $action !== 'addcustomer') {
            return;
        }

        $this->context->controller->addJS('modules/' . $this->name . '/views/js/customers.js');
    }

    protected function performProcess($params, $process = false)
    {
        if (!$process) {
            return;
        }

        $this->sendMail($params, $params['password']);
    }

    protected function sendMail($params, $passwd)
    {
        if (isset($params['object']) && is_object($params)) {
            $templateVars = array(
                '{email}' => $params['object']->email,
                '{passwd}' => $params['password'],
                '{firstname}' => $params['object']->firstname,
                '{lastname}' => $params['object']->lastname,
            );
            $langId = (int) $params['object']->id_lang;
        } else {
            $templateVars = array(
                '{email}' => $params['form_data']['email'],
                '{passwd}' => $params['password'],
                '{firstname}' => $params['form_data']['first_name'],
                '{lastname}' => $params['form_data']['last_name'],
            );
            $langId = (int) Context::getContext()->language->id;
        }

        $emailTo = $templateVars['{email}'];
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $templateVars['{email}'] .= '<br /><span style="color:#333"><strong>'.$this->l('Password').' :</strong></span> '.$templateVars['{passwd}'];
        }

        $mailPath = _PS_MAIL_DIR_;
        if (version_compare(_PS_VERSION_, '1.7.6.0', '>=')) {
            $mailPath = _PS_MODULE_DIR_ . $this->name . '/mails';
        }

        Mail::Send(
            (int) $langId,
            'password',
            Mail::l('Your new password', (int) $langId),
            $templateVars,
            $emailTo,
            $templateVars['{firstname}'].' '.$templateVars['{lastname}'],
            null,
            null,
            null,
            null,
            $mailPath
        );
    }
}
