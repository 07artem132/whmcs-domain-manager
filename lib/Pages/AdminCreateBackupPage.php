<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 12.01.2020, 19:45
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\BackupController;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\View\Menu\MenuFactory;

class AdminCreateBackupPage implements PageInterface
{
    private $templateName = '';
    private $vars = [];

    /**
     * AdminCreateBackupPage constructor.
     * @throws PowerDnsClientException
     */
    function __construct()
    {
        $server_error = 0;

        $backup = new BackupController();

        if (!array_key_exists('domain', $_GET)) {
            file_put_contents(ModuleConfig::geTempPath() . '/all-domain-backup_' . date('Y-m-d H-i') . '.json', $backup->create(null, $server_error));
            LogController::addSuccess(__CLASS__, 'создана ручная резервная копия всех зон, adminid->' . $_SESSION['adminid'] . ', server_error->' . $server_error);
            redir('module=DomainManager&action=backup_result&date=' . urlencode(date('Y-m-d H-i')) . '&error=' . $server_error, 'addonmodules.php');
        }

        file_put_contents(ModuleConfig::geTempPath() . '/' . $_GET['domain'] . '-backup_' . date('Y-m-d H-i') . '.json', $backup->create($_GET['domain'], $server_error, $_GET['server_id']));
        LogController::addSuccess(__CLASS__, 'создана ручная резервная зоны "' . $_GET['domain'] . '", adminid->' . $_SESSION['adminid'] . ', server_error->' . $server_error . ', server_id->' . $_GET['server_id']);
        redir('module=DomainManager&action=backup_result&domain=' . $_GET['domain'] . '&date=' . urlencode(date('Y-m-d H-i')) . '&error=' . $server_error, 'addonmodules.php');
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

        ];
    }
}