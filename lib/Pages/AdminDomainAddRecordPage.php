<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 23.01.2020, 3:14
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\DomainEditNotMatchDomainFromUrlException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminDomainAddRecordPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_add_record.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            $server = ServerModel::findOrFail($_POST['server_id']);
            try {
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $record_name = $_POST['record_name'];
                if ($_POST['type'] === 'a') {
                    if ($record_name === '@') $record_name = $_POST['domain'] . '.';

                    $regex = '/.*' . str_replace('.', '\.', $_POST['domain'] . '.') . '$/';
                    if (preg_match($regex, $record_name, $matches, PREG_OFFSET_CAPTURE, 0) !== false) {
                        if (empty($matches)) $record_name .= '.' . $_POST['domain'] . '.';
                    }
                }

                $pdns->DomainRecordCreate(
                    $_POST['domain'],
                    $record_name,
                    $_POST['type'],
                    $_POST['record_ttl'],
                    collect(explode("\r\n", $_POST['record']))->transform(function ($item) {
                        return ['content' => $item, 'disabled' => false];
                    })->toArray()
                );
                LogController::addSuccess(
                    __CLASS__,
                    'Запись для домена ' . $_POST['domain'] . ' создана, adminid->' . $_SESSION['adminid'] .
                    ', record_name->' . $_POST['record_name'] .
                    ', type->' . $_POST['type'] .
                    ', records->' . $_POST['record']
                );
                redir('module=DomainManager&action=record_list&server_id=' . $_POST['server_id'] . '&domain=' . $_POST['domain'], 'addonmodules.php');
            } catch (DomainEditNotMatchDomainFromUrlException $e) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось создать так как имя записи не соответствует целевому домену (на конце записи должна быть точка), домен->' . $_POST['domain'] .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', record_name->' . $_POST['record_name'] .
                    ', type->' . $_POST['type'] .
                    ', records->' . $_POST['record'],
                    $e
                );
                $this->vars['message'] = 'Не удалось создать так как имя записи не соответствует целевому домену (на конце записи должна быть точка)';
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_POST['server_id'] . '&domain=' . $_POST['domain'];
            } catch (Throwable $e) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось создать из за ошибки, домен->' . $_POST['domain'] .
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
            'Записи домена' => 'addonmodules.php?module=DomainManager&action=record_list&server_id=' . $_POST['server_id'] . '&domain=' . $_POST['domain'],
            'Добавление записи' => '',
        ];
    }
}