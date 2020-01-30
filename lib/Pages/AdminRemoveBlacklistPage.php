<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 18.01.2020, 15:23
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\BlackListModel;
use WHMCS\View\Menu\MenuFactory;

class AdminRemoveBlacklistPage implements PageInterface
{
    private $templateName = '';
    private $vars = [];

    function __construct()
    {
        BlackListModel::findOrFail($_GET['id'])->delete();
        redir('module=DomainManager&action=blacklist', 'addonmodules.php');

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
        return [];
    }
}