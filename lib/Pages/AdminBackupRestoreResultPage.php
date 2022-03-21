<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 24.01.2020, 3:59
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminBackupRestoreResultPage implements PageInterface
{
    private $templateName = 'admin_backup_restore_result.tpl';
    private $vars = [];
    use IsRequestMethodTraits;

    /**
     * AdminBackupPage constructor.
     * @throws \Throwable
     */
    function __construct()
    {
        $this->vars['error'] = $_GET['error'];
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