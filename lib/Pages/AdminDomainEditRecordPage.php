<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 23.01.2020, 23:56
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\DomainEditNotMatchDomainFromUrlException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminDomainEditRecordPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_edit_record.tpl';
    private $vars = [];

    function __construct()
    {
        $server = ServerModel::findOrFail($_GET['server_id']);
        try {
            $PowerDNS = new PowerDNS('http://' . $server->ip .':'.$server->port. '/api/v1/', $server->token);
            $records = collect($PowerDNS->DomainRecordList($_GET['domain']));
            $record_index = $records->search(function ($item, $key) {
                return $item['type'] === $_GET['type'] && $item['name'] === $_GET['name'];
            });
            $record = $records[$record_index];

            foreach ($record['records'] as &$item) {
                $item = $item->content;
            }
            $this->vars['record'] = $record;
        } catch (PowerDnsClientException $e) {
            LogController::addError(
                __CLASS__,
                'Не удалось загрузить из за ошибки, домен->' . $_GET['domain'] .
                ', adminid->' . $_SESSION['adminid'] .
                ', record_name->' . $_GET['name'] .
                ', type->' . $_GET['type'],
                $e
            );
            $this->templateName = 'admin_custom_error.tpl';
            $this->vars['message'] = 'Не удалось из за ошибки:' . $e->getMessage() . ' ' . get_class($e);
            $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_GET['server_id'] . '&domain=' . $_GET['domain'];
            return;
        } catch (Throwable $e) {
            LogController::addError(
                __CLASS__,
                'Не удалось загрузить из за ошибки, домен->' . $_GET['domain'] .
                ', adminid->' . $_SESSION['adminid'] .
                ', record_name->' . $_GET['name'] .
                ', type->' . $_GET['type'],
                $e
            );
            $this->templateName = 'admin_custom_error.tpl';
            $this->vars['message'] = 'Не удалось из за ошибки:' . $e->response . ' ' . $e->getMessage() . ' ' . get_class($e);
            $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_GET['server_id'] . '&domain=' . $_GET['domain'];
            return;
        }

        if ($this->isRequestMethod('POST')) {
            $server = ServerModel::findOrFail($_POST['server_id']);
            try {
                $pdns = new PowerDNS('http://' . $server->ip .':'.$server->port. '/api/v1/', $server->token);
                $pdns->DomainRecordCreate(
                    $_POST['domain'],
                    $_POST['record_name'],
                    $_POST['type'],
                    $_POST['record_ttl'],
                    collect(explode("\r\n", $_POST['record']))->transform(function ($item) {
                         return ['content' => html_entity_decode($item), 'disabled' => false];
                    })->toArray()
                );
                LogController::addSuccess(
                    __CLASS__,
                    'Запись для домена ' . $_POST['domain'] . ' изменена, adminid->' . $_SESSION['adminid'] .
                    ', record_name->' . $_POST['record_name'] .
                    ', type->' . $_POST['type'] .
                    ', records->' . $_POST['record']
                );
                redir('module=DomainManager&action=record_list&server_id=' . $_POST['server_id'] . '&domain=' . $_POST['domain'], 'addonmodules.php');
            } catch (DomainEditNotMatchDomainFromUrlException $e) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось изменить так как имя записи не соответствует целевому домену (на конце записи должна быть точка), домен->' . $_POST['domain'] .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', record_name->' . $_POST['record_name'] .
                    ', type->' . $_POST['type'] .
                    ', records->' . $_POST['record'],
                    $e
                );
                $this->vars['message'] = 'Не удалось изменить так как имя записи не соответствует целевому домену (на конце записи должна быть точка)';
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_POST['server_id'] . '&domain=' . $_POST['domain'];
            } catch (Throwable $e) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось изменить из за ошибки, домен->' . $_POST['domain'] .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', record_name->' . $_POST['record_name'] .
                    ', type->' . $_POST['type'] .
                    ', records->' . $_POST['record'],
                    $e
                );
                $this->vars['message'] = 'Не удалось из за ошибки:' . $e->response . ' ' . $e->getMessage() . ' ' . get_class($e);
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_POST['server_id'] . '&domain=' . $_POST['domain'];
            }
            $this->templateName = 'admin_custom_error.tpl';
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
            'Сервера' => 'addonmodules.php?module=DomainManager&action=servers',
            'Записи домена' => 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_GET['server_id'] . '&domain=' . $_GET['domain'],
            'Добавление записи' => '',
        ];
    }
}