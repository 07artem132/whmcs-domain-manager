<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 20.01.2020, 1:37
 *
 */

/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 16.01.2020, 20:52
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Exception;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\PackageRelative;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\View\Menu\MenuFactory;

class AdminDeletePackagePage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_custom_error.tpl';
    private $vars = [];

    function __construct()
    {
        $domainPackage = DomainPackage::where('package_id', '=', $_GET['id'])->get();

        if ($domainPackage->count() === 0) {
            PackageModel::findOrFail($_GET['id'])->delete();
            PackageRelative::where('package_id', $_GET['id'])->delete();
            LogController::addSuccess(__CLASS__, 'Удаление пакета, adminid->' . $_SESSION['adminid'] . ', package_id->' . $_GET['id']);
            redir('module=DomainManager&action=package', 'addonmodules.php');
        } else {
            LogController::addError(
                __CLASS__,
                'Неудачное удаление пакета Нельзя удалить пакет пока ему назначены домены: ' . $domainPackage->implode('domain', ',') . ', adminid->' . $_SESSION['adminid'] .
                ', package_id->' . $_GET['id'],
                new Exception()
            );
            $this->vars['message'] = 'Нельзя удалить пакет пока ему назначены домены: ' . $domainPackage->implode('domain', ',');
            $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=package';
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
            'Пакеты услуг' => 'addonmodules.php?module=DomainManager&action=package',
            'Добавление пакета услуг' => '',

        ];
    }
}