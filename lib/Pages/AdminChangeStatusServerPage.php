<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 21:40
 *
 */


namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\View\Menu\MenuFactory;

class AdminChangeStatusServerPage implements PageInterface
{
    private $templateName = 'admin_custom_error.tpl';
    private $vars = [];

    function __construct()
    {
        if (empty($package = PackageModel::where('server_id', '=', $_GET['id'])->first())) {
            $server = ServerModel::findOrFail($_GET['id']);
            $server->status = !(bool)$server->status;
            $server->saveOrFail();
            LogController::addSuccess(
                __CLASS__,
                'Изменения статуса сервера, adminid->' . $_SESSION['adminid'] .
                ', server_id->' . $_GET['id'] . ', status->' . (int)!(bool)$server->status);
            redir('module=DomainManager&action=servers', 'addonmodules.php');
        } else {
            LogController::addError(
                __CLASS__,
                'Неудачное изменения статуса сервера, нельзя отключить сервер пока он назначен пакету: ' . $package->title . ', adminid->' . $_SESSION['adminid'] .
                ', server_id->' . $_GET['id'],
                new \Exception()
            );
            $this->vars['message'] = 'Нельзя отключить сервер пока он назначен пакету: ' . $package->title;
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
            'Изменение статуса сервера' => '',
        ];
    }
}