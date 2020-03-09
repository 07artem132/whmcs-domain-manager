<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 16.01.2020, 20:52
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminAddServerPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_add_server.tpl';
    private $vars = [];

    function __construct()
    {
        if (array_key_exists('connect', $_GET)) {
            $this->vars['ns'] = $_GET['ns'];
            $this->vars['token'] = $_GET['token'];
            $this->vars['ip'] = $_GET['ip'];
            $this->vars['port'] = $_GET['port'];
            $this->vars['name'] = $_GET['name'];
            $this->vars['name'] = $_GET['name'];
            $this->vars['error'] = $_GET['error'];
        }

        if ($this->isRequestMethod('POST')) {
            try {
                $pdns = new PowerDNS('http://' . $_POST['ip'] . ':' . $_POST['port'] . '/api/v1/', $_POST['token']);
                $pdns->DomainList();
                $server = new ServerModel();
                $server->name = $_POST['server_name'];
                $server->ip = $_POST['ip'];
                $server->port = $_POST['port'];
                $server->token = $_POST['token'];
                $server->status = 1;
                $server->ns_list = explode("\r\n", $_POST['ns']);
                $server->saveOrFail();
                LogController::addSuccess(__CLASS__, 'Добавление сервера, adminid->' . $_SESSION['adminid']);
            } catch (Throwable $e) {
                LogController::addError(__CLASS__, 'Добавление сервера, adminid->' . $_SESSION['adminid'], $e);
                redir('module=DomainManager&action=add_server&ns=' . $_POST['ns'] . '&token=' . $_POST['token'] . '&ip=' . $_POST['ip'] . '&name=' . $_POST['server_name'] . '&connect=none&error=' . $e->getMessage(), 'addonmodules.php');
            }
            redir('module=DomainManager&action=servers', 'addonmodules.php');
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
            'Добавление сервера' => '',
        ];
    }
}