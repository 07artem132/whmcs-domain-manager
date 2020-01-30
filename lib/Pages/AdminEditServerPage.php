<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 20.01.2020, 2:55
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminEditServerPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_edit_server.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            $server = ServerModel::findOrFail($_GET['id']);
            $server->name = $_POST['server_name'];
            $server->ip = $_POST['ip'];
            $server->token = $_POST['token'];
            $server->ns_list = explode("\r\n", $_POST['ns']);
            $server->saveOrFail();
            redir('module=DomainManager&action=servers', 'addonmodules.php');
        }
        $this->vars['server'] = ServerModel::findOrFail($_GET['id']);

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
            'Редактирование сервера' => '',
        ];
    }
}