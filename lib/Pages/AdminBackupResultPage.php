<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 20.01.2020, 0:35
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminBackupResultPage implements PageInterface
{
    private $templateName = 'admin_backup_result.tpl';
    private $vars = [];
    use IsRequestMethodTraits;

    /**
     * AdminBackupPage constructor.
     * @throws Throwable
     */
    function __construct()
    {

        $this->vars['error'] = $_GET['error'];
        if (!array_key_exists('domain', $_GET)) {
            $this->vars['url'] = ModuleConfig::geRelativePath() . '/temp/all-domain-backup_' . urldecode($_GET['date']) . '.json';
        } else {
            $this->vars['url'] = ModuleConfig::geRelativePath() . '/temp/' . $_GET['domain'] . '-backup_' . urldecode($_GET['date']) . '.json';

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
            'Скачивание резервной копии' => '',
        ];
    }
}