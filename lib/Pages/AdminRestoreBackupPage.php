<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 12.01.2020, 21:24
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\BackupController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminRestoreBackupPage implements PageInterface
{
    use IsRequestMethodTraits;

    private $templateName = 'admin_restore_backup.tpl';
    private $vars = [];

    /**
     * AdminCreateBackupPage constructor.
     */
    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            if (move_uploaded_file(
                $_FILES['backup']['tmp_name'],
                ModuleConfig::geTempPath() . '/' . $_FILES['backup']['name']
            )) {
                $server_error = 0;
                $backup = json_decode(file_get_contents(ModuleConfig::geTempPath() . '/' . $_FILES['backup']['name']), true);
                $backupController = new BackupController();
                $backupController->restore($backup, $server_error);
                redir('module=DomainManager&action=backup_restore_result&error=' . $server_error, 'addonmodules.php');
            } else {
                dd(0);
            }
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
            'Резервное копирование' => 'addonmodules.php?module=DomainManager&action=backup',
            'Восстановление из резервной копии' => '',
        ];
    }
}