<?php

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;

add_hook('AdminAreaHeadOutput', 99999999, function ($vars) {
    try {
        if (!isset($_GET['module']) || $_GET['module'] != ModuleConfig::getModuleName()) {
            return null;
        }

        foreach (scandir(ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin') as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            echo '<link rel="stylesheet" type="text/css" href="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin/' . $item . '">';
        }

        foreach (scandir(ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin') as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            echo '<script type="text/javascript" charset="utf8" src="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin/' . $item . '"></script>';
        }
    } catch (Exception $e) {
        logActivity(ModuleConfig::getModuleName() . ' [AdminAreaHeadOutput]:' . $e->getMessage(), 0);
    }
});

add_hook('PreModuleTerminate', 1, function ($vars) {
    if ($vars['params']['addonId'] === 0) {
        foreach (DomainPackage::where('rel_type', 1)->where('rel_id', $vars['params']['serviceid'])->get() as $item) {
            try {
                $server = ServerModel::findOrFail($item->server_id);
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $domainList = collect($pdns->DomainList())->keyBy('name');
                if ($domainList->has($item->domain . '.')) {
                    $pdns->DomainDelete($item->domain . '.');
                }
                $item->delete();
                LogController::addSuccess(
                    'PreModuleTerminate',
                    'Домен ' . $item->domain . ' удален, при удалении услуги'
                );
            } catch (PowerDnsClientException $e) {
                LogController::addError(
                    'PreModuleTerminate',
                    'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                    ', domain->' . $item->domain,
                    $e
                );
                return ['abortcmd' => true];
            } catch (Throwable $e) {
                LogController::addError(
                    'PreModuleTerminate',
                    'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                    ', domain->' . $item->domain,
                    $e
                );
                return ['abortcmd' => true];
            }
        }
    } else {
        foreach (DomainPackage::where('rel_type', 2)->where('rel_id', $vars['params']['addonId'])->get() as $item) {
            try {
                $server = ServerModel::findOrFail($item->server_id);
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $domainList = collect($pdns->DomainList())->keyBy('name');
                if ($domainList->has($item->domain . '.')) {
                    $pdns->DomainDelete($item->domain . '.');
                }
                $item->delete();
                LogController::addSuccess(
                    'PreModuleTerminate',
                    'Домен ' . $item->domain . ' удален, при удалении аддона'
                );
            } catch (PowerDnsClientException $e) {
                LogController::addError(
                    'PreModuleTerminate',
                    'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                    ', domain->' . $item->domain,
                    $e
                );
                return ['abortcmd' => true];
            } catch (Throwable $e) {
                LogController::addError(
                    'PreModuleTerminate',
                    'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                    ', domain->' . $item->domain,
                    $e
                );
                return ['abortcmd' => true];
            }
        }
    }
});

add_hook('PreRegistrarRequestDelete', 1, function ($vars) {
    foreach (DomainPackage::where('rel_type', 3)->where('rel_id', $vars['params']['domainid'])->get() as $item) {
        try {
            $server = ServerModel::findOrFail($item->server_id);
            $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
            $domainList = collect($pdns->DomainList())->keyBy('name');
            if ($domainList->has($item->domain . '.')) {
                $pdns->DomainDelete($item->domain . '.');
            }
            $item->delete();
            LogController::addSuccess(
                'PreModuleTerminate',
                'Домен ' . $item->domain . ' удален, при удалении домена'
            );
        } catch (PowerDnsClientException $e) {
            LogController::addError(
                'PreRegistrarRequestDelete',
                'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                ', domain->' . $item->domain,
                $e
            );
            return array(
                'abortWithError' => 'DomainManager->' . $e->getMessage(),
            );
        } catch (Throwable $e) {
            LogController::addError(
                'PreRegistrarRequestDelete',
                'Не удалось удалить домен поскольку целевой сервер недоступен, ошибки:' . $e->response . ' ' . $e->getMessage() .
                ', domain->' . $item->domain,
                $e
            );
            return array(
                'abortWithError' => 'DomainManager->' . $e->getMessage(),
            );
        }
    }

});

/*
add_hook("ClientAreaPrimaryNavbar", 900000000, function (MenuItem $primaryNavbar) {
    global $_LANG;
    $defaultLanguage = 'russian';
    $clientLanguage = $_SESSION['Language'];

    include_once(sprintf(ModuleConfig::getBaseFullPath() . '/lang/%s.php', $defaultLanguage));

    if (file_exists(sprintf(ModuleConfig::getBaseFullPath() . '/lang/%s.php', $clientLanguage))) {
        include_once sprintf(ModuleConfig::getBaseFullPath() . '/lang/%s.php', $clientLanguage);
    }
    $navItem = $primaryNavbar->getChild('Domains');

    if (is_null($navItem)) {
        return;
    }

    $navItem->addChild($_LANG['DomainManager_manager_dns'])
        ->setUri('/?m=DomainManager')
        ->setOrder(30);
});*/