<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 23.01.2020, 23:34
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Exception;
use Throwable;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminDomainDeleteRecordPage implements PageInterface
{
    private $templateName = 'admin_custom_error.tpl';
    private $vars = [];

    function __construct()
    {
        $server = ServerModel::findOrFail($_GET['server_id']);
        try {
            $PowerDNS = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
            $records = collect($PowerDNS->DomainRecordList($_GET['domain']));
            $delete_record = $records->search(function ($item, $key) {
                return $item['type'] === $_GET['type'] && $item['name'] === $_GET['name'];
            });

            if ($delete_record === false) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось удалить из за того что нет совпадений, домен->' . $_GET['domain'] .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', record_name->' . $_GET['name'] .
                    ', record_type->' . $_GET['type'],
                    new Exception()
                );
                $this->vars['message'] = 'Не удалось удалить из за того что нет совпадений';
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_GET['server_id'] . '&domain=' . $_GET  ['domain'];
                return;
            }

            $PowerDNS->DomainRecordDelete(
                $_GET['domain'],
                $records[$delete_record]['name'],
                $records[$delete_record]['type'],
                $records[$delete_record]['ttl'],
                $records[$delete_record]['records']
            );
            LogController::addSuccess(
                __CLASS__,
                'Запись для домена ' . $_POST['domain'] . ' удалена, adminid->' . $_SESSION['adminid'] .
                ', record_name->' . $_GET['name'] .
                ', type->' . $_GET['type']
            );
            redir('module=DomainManager&action=record_list&server_id=' . $_GET['server_id'] . '&domain=' . $_GET['domain'], 'addonmodules.php');
        } catch (Throwable $e) {
            LogController::addError(
                __CLASS__,
                'Не удалось удалить запись из за ошибки, домен->' . $_GET['domain'] .
                ', adminid->' . $_SESSION['adminid'] .
                ', record_name->' . $_GET['name'] .
                ', record_type->' . $_GET['type'],
                $e
            );
            $this->vars['message'] = 'Не удалось из за ошибки:' . $e->response . ' ' . $e->getMessage() . ' ' . get_class($e);
            $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_GET['server_id'] . '&domain=' . $_GET['domain'];
        }
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