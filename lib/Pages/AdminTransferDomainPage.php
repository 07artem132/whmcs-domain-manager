<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 22.01.2020, 22:18
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Service\Addon;
use WHMCS\Service\Service;
use WHMCS\View\Menu\MenuFactory;

class AdminTransferDomainPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_transfer_domain.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['domain'] = $_GET['domain'];
        if ($this->isRequestMethod('POST')) {
            $domainPackage = DomainPackage::firstOrNew(['domain' => $_POST['domain']]);
            $domainPackage->rel_id = $_POST['relid'];
            switch ($_POST['type']) {
                case 1:
                    $relative = Service::findOrFail($_POST['relid']);
                    $package = PackageModel::where('rel_id', '=', $relative->packageid)->where('rel_type', '=', 1)->first();
                    break;
                case 2:
                    $relative = Addon::findOrFail($domainPackage->rel_id);
                    $package = PackageModel::where('rel_id', '=', $relative->addonid)->where('rel_type', '=', 2)->first();
                    break;
            }

            if (empty($package)) {
                $this->templateName = 'admin_custom_error.tpl';
                $this->vars['message'] = 'К сожалению не удалось найти пакет который привязан к целевому продукту. Проверьте правельность указатия типа связи или id сервиса.';
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
                return;
            }
            $domainPackage->package_id = $package->id;
            $domainPackage->save();
            redir('module=DomainManager&action=domain', 'addonmodules.php');
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
            'Домены' => 'addonmodules.php?module=DomainManager&action=domain',
            'Трансфер домена' => '',
        ];
    }
}