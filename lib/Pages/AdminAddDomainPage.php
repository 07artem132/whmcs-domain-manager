<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 17.01.2020, 15:30
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Database\Capsule;
use WHMCS\Domain\Domain;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageRelative;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\Service\Addon;
use WHMCS\Service\Service;
use WHMCS\View\Menu\MenuFactory;

class AdminAddDomainPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_add_domain.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {

            try {
                $server = ServerModel::findOrFail($_POST['server']);
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $canonical_ns = $server->ns_list;
                array_walk($canonical_ns, function (&$item) {
                    $item .= '.';
                });
                $canonical_domain = $_POST['domain'] . '.';
                $pdns->DomainCreate($canonical_domain, 'Master', $canonical_ns);

                if (array_key_exists('relid', $_POST) && array_key_exists('type', $_POST)) {
                    $domainPackage = DomainPackage::firstOrNew(['domain' => $_POST['domain']]);
                    $domainPackage->rel_id = $_POST['relid'];
                    $domainPackage->rel_type = $_POST['type'];
                    switch ($_POST['type']) {
                        case 1:
                            $relative = Service::findOrFail($domainPackage->rel_id);
                            $packageRelative = PackageRelative::where('rel_id', '=', $relative->packageid)->where('rel_type', '=', 1)->first();
                            break;
                        case 2:
                            $relative = Addon::findOrFail($domainPackage->rel_id);
                            $packageRelative = PackageRelative::where('rel_id', '=', $relative->addonid)->where('rel_type', '=', 2)->first();
                            break;
                        case 3:
                            $relative = Domain::findOrFail($domainPackage->rel_id);
                            $domainPricing = Capsule::table('tbldomainpricing')->where('extension', '.' . $relative->tld)->first();
                            $packageRelative = PackageRelative::where('rel_id', $domainPricing->id)->where('rel_type', 3)->firstOrFail();
                            break;
                    }

                    if (empty($packageRelative)) {
                        $this->templateName = 'admin_custom_error.tpl';
                        LogController::addError(
                            __CLASS__,
                            'Домен создан однако к сожалению не удалось найти пакет который привязан к целевому продукту, adminid->' . $_SESSION['adminid'] .
                            ', rel_id->' . $_POST['relid'] .
                            ', domain->' . $_POST['domain'] .
                            ', rel_type->' . $_POST['type'],
                            new \Exception()
                        );
                        $this->vars['message'] = 'Домен создан однако к сожалению не удалось найти пакет который привязан к целевому продукту. Проверьте правельность указатия типа связи или id сервиса.';
                        $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
                        return;
                    }
                    $domainPackage->package_id = $packageRelative->package_id;
                    LogController::addSuccess(__CLASS__, 'Домен ' . $_POST['domain'] . ' создан, adminid->' . $_SESSION['adminid']);
                    $domainPackage->save();
                }

                redir('module=DomainManager&action=domain', 'addonmodules.php');
            } catch (PowerDnsClientException  $e) {
                LogController::addError(
                    __CLASS__,
                    'К сожалению не удалось создать зону, adminid->' . $_SESSION['adminid'] .
                    ', rel_id->' . $_POST['relid'] .
                    ', domain->' . $_POST['domain'] .
                    ', rel_type->' . $_POST['type'],
                    $e
                );
                $this->templateName = 'admin_custom_error.tpl';
                $this->vars['message'] = 'К сожалению не удалось создать зону, ошибка:' . $e->response . PHP_EOL . $e->getTraceAsString();
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
                return;
            }
        }
        $this->vars['servers'] = $servers = ServerModel::all();
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
            'Пакеты услуг' => 'addonmodules.php?module=DomainManager&action=domain',
            'Добавление домена' => '',
        ];
    }
}