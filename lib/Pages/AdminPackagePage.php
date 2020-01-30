<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 11.01.2020, 15:42
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\View\Menu\MenuFactory;

class AdminPackagePage implements PageInterface
{
    private $templateName = 'admin_package.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['packages'] = PackageModel::all();
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
            'Пакеты услуг' => '',
        ];
    }
}