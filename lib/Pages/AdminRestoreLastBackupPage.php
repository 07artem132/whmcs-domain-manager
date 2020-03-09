<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 16.02.2020, 20:35
 *
 */


namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\BackupController;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Abstracts\CompressManagerFactoryAbstract;
use WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Abstracts\CompressMethodAbstract;
use WHMCS\View\Menu\MenuFactory;

class AdminRestoreLastBackupPage implements PageInterface
{
    private $templateName = 'admin_backup_restore_result.tpl';
    private $vars = [];
    use IsRequestMethodTraits;

    /**
     * AdminBackupPage constructor.
     */
    function __construct()
    {
        $server_error = 0;
        $lastBackup = collect(array_diff(scandir(ModuleConfig::geBackupPath()), ['..', '.']))
            ->transform(function ($item, $key) {
                return [
                    'last_edit' => filemtime(sprintf('%s/%s', ModuleConfig::geBackupPath(), $item)),
                    'name' => $item
                ];
            })->sortByDesc('last_edit')->first();

        $file = file_get_contents(sprintf('%s/%s', ModuleConfig::geBackupPath(), $lastBackup['name']));
        $extension = pathinfo($lastBackup['name'], PATHINFO_EXTENSION);

        if (CompressMethodAbstract::isValidExtension($extension)) {
            $compressManager = CompressManagerFactoryAbstract::create(CompressMethodAbstract::$extensionToMethod[$extension]);
            $file = $compressManager->decompressString($file);
        }

        try {
            $backup = json_decode($file, true, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            $this->vars['error'] = 'Ошибка при работе с резервной копией:' . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
            return;
        }

        if (!array_key_exists($_GET['domain'] . ':' . $_GET['server_id'], $backup)) {
            $this->vars['error'] = 'К сожалению в последней резервной копии данного домена нет.';
            return;
        }

        $BackupController = new BackupController();
        $BackupController->restore(
            [
                $_GET['domain'] . ':' . $_GET['server_id'] => $backup[$_GET['domain'] . ':' . $_GET['server_id']]
            ],
            $server_error
        );

        if ($server_error !== 0) {
            $this->vars['error'] = 'При работе с резервной копией возникли ошибки, детали в логах.';
            return;
        }
        LogController::addSuccess('Работа с резервными копиями', 'Резервная копия успешно развернута для домена ' . $_GET['domain']);
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
            'Отчет о восстановлении из резервной копии' => '',
        ];
    }
}