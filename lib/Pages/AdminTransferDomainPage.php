<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 22.01.2020, 22:18
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Exception;
use Illuminate\Database\Capsule\Manager;
use WHMCS\Domain\Domain;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageRelative;
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
            $domainPackage->rel_type = $_POST['type'];
            switch ($_POST['type']) {
                case 1:
                    $relative = Service::findOrFail($_POST['relid']);
                    $package = PackageRelative::where('rel_id', '=', $relative->packageid)->where('rel_type', '=', 1)->first();
                    break;
                case 2:
                    $relative = Addon::findOrFail($_POST['relid']);
                    $package = PackageRelative::where('rel_id', '=', $relative->addonid)->where('rel_type', '=', 2)->first();
                    break;
                case 3:
                    $relative = Domain::findOrFail($_POST['relid']);
                    $tldPricing = Manager::table("tbldomainpricing")->where("extension", "=", "." . $relative->getTldAttribute())->first();
                    if (!empty($tldPricing))
                        $package = PackageRelative::where('rel_id', '=', $tldPricing->id)->where('rel_type', '=', 3)->first();
                    else
                        $package = null;
                    break;
            }

            if (empty($package)) {
                LogController::addError(
                    __CLASS__,
                    'При передаче домена к сожалению не удалось найти пакет который привязан к целевому продукту, adminid->' . $_SESSION['adminid'] .
                    ', rel_type->' . $_POST['type'] .
                    ', rel_id->' . $_POST['rel_id'] .
                    ', domain->' . $_POST['domain'],
                    new Exception()
                );
                $this->templateName = 'admin_custom_error.tpl';
                $this->vars['message'] = 'К сожалению не удалось найти пакет который привязан к целевому продукту. Проверьте правельность указатия типа связи или id сервиса.';
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
                return;
            }
            $domainPackage->package_id = $package->package_id;
            $domainPackage->save();
            LogController::addSuccess(
                __CLASS__,
                'Домен ' . $_POST['domain'] . ' передан другому пользователю, adminid->' . $_SESSION['adminid'] .
                ', rel_type->' . $_POST['type'] .
                ', rel_id->' . $_POST['relid']
            );
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