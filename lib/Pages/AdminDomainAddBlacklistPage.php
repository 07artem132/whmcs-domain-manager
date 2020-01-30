<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 16.01.2020, 19:53
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\BlackListModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminDomainAddBlacklistPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_add_blacklist.tpl';
    private $vars = [];

    /**
     * AdminDomainAddBlacklistPage constructor.
     * @throws Throwable
     */
    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            $blackList = new BlackListModel();
            $blackList->domain = $_POST['domain'];
            $blackList->saveOrFail();
            redir('module=DomainManager&action=blacklist', 'addonmodules.php');
        }
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
            'Черный список' => 'addonmodules.php?module=DomainManager&action=blacklist',
            'Добавление домена в черный список' => '',
        ];
    }
}