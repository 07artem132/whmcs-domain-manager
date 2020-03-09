<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 05.01.2020, 17:00
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\LogModel;
use WHMCS\View\Menu\MenuFactory;

class AdminCronPage implements PageInterface
{
    private $templateName = 'admin_cron.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['lastCronEvent'] = LogModel::where('status', 1)->where('module', 'Работа с резервными копиями по крону')->orderBy('created_at', 'DESC')->first();
        $this->vars['cronPath'] = ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/cron.php';
    }

    /**
     * @return string
     */
    function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @return array
     */
    function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return MenuFactory|null
     */
    function getSubMenu(): ?MenuFactory
    {
        return null;
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array
    {
        return [
            'Главная' => 'addonmodules.php?module=DomainManager',
            'Крон   ' => '',
        ];
    }
}