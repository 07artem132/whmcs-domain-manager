<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 23.01.2020, 2:52
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Exception;
use Throwable;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminDeleteDomainPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_custom_error.tpl';
    private $vars = [];

    function __construct()
    {
        $domainPackage = DomainPackage::where('domain', '=', $_GET['domain'])->first();

        if (empty($domainPackage)) {
            $server_error = 0;
            $errorMessages = [];
            $deleted = false;
            foreach (ServerModel::all() as $server) {
                try {
                    $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                    foreach ($pdns->DomainList() as $domain) {
                        $domainFormatted = substr($domain->name, 0, -1);
                        if ($domainFormatted === $_GET['domain']) {
                            $pdns->DomainDelete($domain->name);
                            $deleted = true;
                            break;
                        }
                    }
                } catch (PowerDnsClientException $e) {
                    $errorMessages[] = $e->response;
                    $server_error++;
                } catch (Throwable $e) {
                    $errorMessages[] = $e->getMessage();
                    $server_error++;
                }
            }

            if (!$deleted) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось удалить домен поскольку недоступны ' . $server_error . ' сервера из ' . ServerModel::count() . ' Ошибки:' . implode(',', $errorMessages) .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', domain->' . $_GET['domain'],
                    new Exception
                );
                $this->vars['message'] = 'Не удалось удалить домен поскольку недоступны ' . $server_error . ' сервера из ' . ServerModel::count() . ' Ошибки:' . implode(',', $errorMessages);
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
                return;
            }

            LogController::addSuccess(
                __CLASS__,
                'Домен ' . $_POST['domain'] . ' удален, adminid->' . $_SESSION['adminid']
            );
            redir('module=DomainManager&action=domain', 'addonmodules.php');
        } else {
            try {
                $server = ServerModel::findOrFail($domainPackage->server_id);
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $domainList = collect($pdns->DomainList())->keyBy('name');
                if ($domainList->has($_GET['domain'] . '.')) {
                    $pdns->DomainDelete($_GET['domain'] . '.');
                }
                $domainPackage->delete();
                LogController::addSuccess(
                    __CLASS__,
                    'Домен ' . $_GET['domain'] . ' удален, adminid->' . $_SESSION['adminid']
                );
                redir('module=DomainManager&action=domain', 'addonmodules.php');
            } catch (PowerDnsClientException $e) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', domain->' . $_GET['domain'],
                    $e
                );
                $this->vars['message'] = 'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage();
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
            } catch (Throwable $e) {
                LogController::addError(
                    __CLASS__,
                    'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                    ', adminid->' . $_SESSION['adminid'] .
                    ', domain->' . $_GET['domain'],
                    $e
                );
                $this->vars['message'] = 'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage();
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=domain';
            }
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
            'Пакеты услуг' => 'addonmodules.php?module=DomainManager&action=domain',
            'Добавление домена' => '',
        ];
    }
}