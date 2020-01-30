<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 17.01.2020, 15:30
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
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
                $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
                $canonical_ns = $server->ns_list;
                array_walk($canonical_ns, function (&$item) {
                    $item .= '.';
                });
                $canonical_domain = $_POST['domain'] . '.';
                $pdns->DomainCreate($canonical_domain, 'Master', $canonical_ns);

                if (array_key_exists('relid', $_POST) && array_key_exists('type', $_POST)) {
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
                        $this->vars['message'] = 'Домен создан однако к сожалению не удалось найти пакет который привязан к целевому продукту. Проверьте правельность указатия типа связи или id сервиса.';
                        $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
                        return;
                    }
                    $domainPackage->package_id = $package->id;
                    $domainPackage->save();
                }

                redir('module=DomainManager&action=domain', 'addonmodules.php');
            } catch (PowerDnsClientException  $e) {
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