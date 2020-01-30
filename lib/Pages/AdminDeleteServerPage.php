<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 16.01.2020, 20:52
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminDeleteServerPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_custom_error.tpl';
    private $vars = [];

    function __construct()
    {
        if (empty($package = PackageModel::where('server_id', '=', $_GET['id'])->first())) {
            ServerModel::findOrFail($_GET['id'])->delete();
            redir('module=DomainManager&action=servers', 'addonmodules.php');
        } else {
            $this->vars['message'] = 'Нельзя удалить сервер пока он назначен пакету: ' . $package->title;
            $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=servers';
        }
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
            'Сервера' => 'addonmodules.php?module=DomainManager&action=servers',
            'Удаление сервера' => '',
        ];
    }
}