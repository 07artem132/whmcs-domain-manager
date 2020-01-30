<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 05.01.2020, 17:48
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\BlackListModel;
use WHMCS\View\Menu\MenuFactory;

class AdminBlacklistPage implements PageInterface
{
    private $templateName = 'admin_blacklist.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['blacklist'] = BlackListModel::all();

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
            'Черный список' => '',
        ];
    }
}