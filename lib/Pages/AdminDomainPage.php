<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 16:32
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminDomainPage implements PageInterface
{
    private $templateName = 'admin_domain.tpl';
    private $vars = [];

    function __construct()
    {

        $servers = ServerModel::where('status', 1)->get();
        $domainPackage = DomainPackage::all()->keyBy('domain');
        $this->vars['domainList'] = collect([]);
        foreach ($servers as $server) {
            $PowerDNS = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
            try {
                $domains = collect($PowerDNS->DomainList())->transform(function ($item, $key) use ($server, $domainPackage) {
                    $domain = substr($item->id, 0, -1);
                    if ($domainPackage->has($domain)) {
                        $status = 'Синхронизирован';
                        $client_name = $domainPackage[$domain]->client_name;
                        $client_id = $domainPackage[$domain]->client_id;
                        $product_name = $domainPackage[$domain]->product_name;
                        $product_url = $domainPackage[$domain]->service_url;
                    } else {
                        $status = 'Нет информации';
                        $client_name = 'Нет информации';
                        $product_name = 'Нет информации';
                        $product_url = null;
                        $client_id = 'Нет информации';
                    }

                    return [
                        'domain' => $domain,
                        'server' => $server->name,
                        'server_id' => $server->id,
                        'status' => $status,// есть в биллинге но нет на dns сервере
                        'product_name' => $product_name, // id услуги или аддона
                        'product_url' => $product_url, // услуга или аддон
                        'client_id' => $client_id,
                        'client_name' => $client_name
                    ];
                })->toArray();
                $this->vars['domainList'] = $this->vars['domainList']->merge($domains);

                foreach ($domainPackage as $item) {
                    $result = $this->vars['domainList']->first(function ($key, $domain) use ($item) {
                        return $domain['domain'] === $item->domain;
                    });
                    if (empty($result)) {
                        $this->vars['domainList']->push([
                            'domain' => $item->domain,
                            'server' => $item->server_name,
                            'server_id' => $item->server_id,
                            'status' => 'Нет на powerDNS сервере',
                            'product_name' => $item->product_name, // id услуги или аддона
                            'product_url' => $item->service_url, // услуга или аддон
                            'client_id' => $item->client_id,
                            'client_name' => $item->client_name
                        ]);
                    }
                }
            } catch (Throwable $e) {
                echo '<div class="alert alert-danger" style="margin-top: 10px" role="alert">server ip ->' . $server->ip . ' error message->' . $e->getMessage() . '</div>';
            }
        }
        $this->vars['domainList'] = $this->vars['domainList']->toArray();

    }

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
            'Домены' => '',
        ];
    }
}