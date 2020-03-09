<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 16:32
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminRecordListPage implements PageInterface
{
    private $templateName = 'admin_record_list.tpl';
    private $vars = [];

    function __construct()
    {
        $server = ServerModel::findOrFail($_GET['server_id']);
        $PowerDNS = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
        $this->vars['recordList'] = $PowerDNS->DomainRecordList($_GET['domain']);
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
            'Записи домена' => '',
        ];
    }

}