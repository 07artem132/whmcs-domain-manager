<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 05.01.2020, 18:41
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\BackupSettingsModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminBackupPage implements PageInterface
{
    private $templateName = 'admin_backup.tpl';
    private $vars = [];
    use IsRequestMethodTraits;

    /**
     * AdminBackupPage constructor.
     * @throws Throwable
     */
    function __construct()
    {
        $settings = BackupSettingsModel::all()->first();

        if ($this->isRequestMethod('POST')) {
            if (empty($settings)) {
                $settings = new BackupSettingsModel();
            }

            $settings->limit_remote_backup = $_POST['limit_remote_backup'];
            $settings->limit_local_backup = $_POST['backup_local_limit'];
            $settings->upload_backup = $_POST['upload_backup'];
            $settings->compress_backup = $_POST['compress_backup'];
            $settings->server_ip = $_POST['server_ip'];
            $settings->server_port = $_POST['server_port'];
            $settings->server_login = $_POST['server_login'];
            $settings->server_password = $_POST['server_password'];
            $settings->server_type = $_POST['server_type'];
            $settings->server_path = $_POST['server_path'];
            $settings->saveOrFail();
            LogController::addSuccess(__CLASS__,'сохранение изменений, adminid->'.$_SESSION['adminid']);
        }

        if (!empty($settings)) {
            $this->vars['limit_remote_backup'] = $settings->limit_remote_backup;
            $this->vars['backup_local_limit'] = $settings->limit_local_backup;
            $this->vars['upload_backup'] = $settings->upload_backup;
            $this->vars['compress_backup'] = $settings->compress_backup;
            $this->vars['server_ip'] = $settings->server_ip;
            $this->vars['server_port'] = $settings->server_port;
            $this->vars['server_login'] = $settings->server_login;
            $this->vars['server_password'] = $settings->server_password;
            $this->vars['server_type'] = $settings->server_type;
            $this->vars['server_path'] = $settings->server_path;
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
            'Резервное копирование' => '',
        ];
    }
}