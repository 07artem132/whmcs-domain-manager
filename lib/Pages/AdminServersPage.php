<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 05.01.2020, 16:40
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\View\Menu\MenuFactory;

class AdminServersPage implements PageInterface
{
    private $templateName = 'admin_servers.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['serverList'] = ServerModel::all()->toArray();
    }

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
            'Сервера' => ''
        ];
    }
}