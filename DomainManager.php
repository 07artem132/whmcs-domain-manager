<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 20.04.2019
 * Time: 17:57
 */


error_reporting(-1);
ini_set("display_errors", 1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use WHMCS\Database\Capsule;
use WHMCS\Domain\Domain;
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\InstallController;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Controllers\PageController;
use WHMCS\Module\Addon\DomainManager\Controllers\UninstallController;
use WHMCS\Module\Addon\DomainManager\Menu\AdminAreaMenu;
use WHMCS\Module\Addon\DomainManager\Models\BlackListModel;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\PackageRelative;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\DomainEditNotMatchDomainFromUrlException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\Module\Addon\Setting;
use WHMCS\Service\Addon;
use WHMCS\Service\Service;

function DomainManager_config()
{
    return [
        "name" => 'Менеджер доменов',
        "description" => 'Для работы модуля требуется  php-intl а так же php-ssh2',
        "version" => "1",
        "author" => "<a href=\"https://github.com/07artem132\">07artem132</a>",
        "language" => "russian",
        "fields" => [
            "DeleteTableWhenDisabled" => [
                "FriendlyName" => "Удалять данные модуля при отключении ?",
                "Type" => "yesno",
                "Description" => " Отметьте здесь дабы удалить данные модуля при отключении оного.",
            ]
        ]
    ];
}

function DomainManager_output($vars)
{
    $PageController = new PageController($vars);
    $PageController->setDefaultAction('index');
    $PageController->setSuffixTemplate('admin');
    $PageController->setMenuTemplate('include\navbar.tpl');
    $PageController->setBreadcrumbTemplate('include\breadcrumb.tpl');
    $PageController->setMenu((new AdminAreaMenu())->navbar());
    $PageController->run();
}

function DomainManager_activate()
{
    if (!extension_loaded('ssh2')) {
        return array(
            'status' => 'error',
            'description' => 'Необходимо расширение php-ssh2'
        );
    }

    if (!extension_loaded('intl')) {
        return array(
            'status' => 'error',
            'description' => 'Необходимо расширение php-intl'
        );
    }

    if (!empty($error = InstallController::createTableBackupSettings())) {
        return $error;
    }

    if (!empty($error = InstallController::createTableBlackList())) {
        return $error;
    }

    if (!empty($error = InstallController::createTableDomainToPackage())) {
        return $error;
    }

    if (!empty($error = InstallController::createTableLog())) {
        return $error;
    }

    if (!empty($error = InstallController::createTablePackage())) {
        return $error;
    }

    if (!empty($error = InstallController::createTableServer())) {
        return $error;
    }

    if (!empty($error = InstallController::createTablePackageToRelative())) {
        return $error;
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно активирован',
    );

}

function DomainManager_deactivate()
{
    if (!empty($dropTable = Setting::Module(ModuleConfig::getModuleName())->where('setting', '=', 'DeleteTableWhenDisabled')->first())) {
        if ($dropTable->value === 'on') {
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_backup_setting'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_log'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_black_list'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_server'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_package'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_domain_package'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_domain_manager_package_relative'))) {
                return $error;
            }
        }
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно деактивирован'
    );

}

function DomainManager_clientarea($vars)
{
    global $_LANG;
    $error = '';
    $defaultLanguage = 'russian';
    $clientLanguage = $_SESSION['Language'];

    include_once(sprintf(ModuleConfig::getBaseFullPath() . '/lang/%s.php', $defaultLanguage));

    if (file_exists(sprintf(ModuleConfig::getBaseFullPath() . '/lang/%s.php', $clientLanguage))) {
        include_once sprintf(ModuleConfig::getBaseFullPath() . '/lang/%s.php', $clientLanguage);
    }

    if (array_key_exists('error', $_GET)) {
        $error = $_GET['error'];
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] === 'record_delete') {
        $domainPackage = DomainPackage::where('domain', '=', $_GET['domain'])->firstOrFail();
        $serverId = $domainPackage->serverId;
        $server = ServerModel::find($serverId);
        $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
        if ($_GET['countRecords'] == 1) {
            $pdns->DomainRecordDelete($_GET['domain'], $_GET['name'], $_GET['type'], $_GET['ttl'], [['content' => $_GET['content']]]);
        } else {
            $record = collect($pdns->DomainRecordList($_GET['domain']))->filter(function ($item, $key) {
                if (strcasecmp($_GET['name'], $item['name']) === 0 && strcasecmp($_GET['type'], $item['type']) === 0) {
                    return true;
                }
                return false;
            })->transform(function ($item, $key) {
                for ($i = 0; $i < count($item['records']); $i++) {
                    if (strcasecmp($item['records'][$i]->content, $_GET['content']) === 0) {
                        unset($item['records'][$i]);
                    }
                }
                return $item;
            })->first();
            $pdns->DomainRecordCreate($_GET['domain'], $_GET['name'], $_GET['type'], $_GET['ttl'], $record['records']);
        }

        LogController::addSuccess(
            'Клиентская область',
            'Была удалена запись для домена ' . $_GET['domain'] .
            ', client_id->' . $_SESSION['uid']
        );
        redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $recordTypeCount = [];
        $domainPackage = DomainPackage::where('domain', '=', $_GET['domain'])->firstOrFail();
        $serverId = $domainPackage->serverId;
        $server = ServerModel::find($serverId);
        $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
        $records = $pdns->DomainRecordFormatedList($_GET['domain']);
        $recordTypeCount = array_flip(array_keys($records));
        foreach ($recordTypeCount as $type => &$count) {
            $count = collect($records[$type])->sum(function ($item) {
                return count($item['records']);
            });
        }
        $package = PackageModel::find($domainPackage->package_id);
        foreach ($recordTypeCount as $recordType => $recordCount) {
            if ($recordType == $_POST['record_type']) {
                $recordCount++;
            }
            if ($recordType == 'SOA') continue;
            if ($package->{$recordType} !== -1 && $package->{$recordType} <= $recordCount) {
                $error = sprintf($_LANG['DomainManager_zone_record_limit_reached'], $recordType);
                LogController::addError(
                    'Клиентская область',
                    sprintf('Превышен лимит записи типа %s', $recordType) .
                    ', client_id->' . $_SESSION['uid'],
                    new Exception()
                );
                redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
            }
        }
        if ($_POST['record_type'] === 'SRV') {
            $record_context = sprintf('%s %s %s %s', $_POST['record_context']['Priority'], $_POST['record_context']['weight'], $_POST['record_context']['port'], $_POST['record_context']['text']);
        } elseif ($_POST['record_type'] === 'MX') {
            $record_context = sprintf('%s %s', $_POST['record_context']['preference'], $_POST['record_context']['exchange']);
        } else {
            $record_context = $_POST['record_context'];
        }
        $record_name = $_POST['record_name'];
        if ($record_name === '@') {
            $record_name = $_GET['domain'] . '.';
        } else {
            $regex = '/.*' . str_replace('.', '\.', $_GET['domain'] . '.') . '$/';
            if (preg_match($regex, $record_name, $matches, PREG_OFFSET_CAPTURE, 0) !== false) {
                if (empty($matches)) $record_name .= '.' . $_GET['domain'] . '.';
            }
        }

        if (array_key_exists($_POST['record_type'], $records)) {
            for ($i = 0; $i < count($records[$_POST['record_type']]); $i++) {
                if ($records[$_POST['record_type']][$i]['name'] === $record_name) {
                    $record = $records[$_POST['record_type']][$i];
                    $flagAdd = true;
                    for ($j = 0; $j < count($record['records']); $j++) {
                        if ($record['records'][$j]->content == $record_context) {
                            $flagAdd = false;
                        }
                    }
                    if ($flagAdd) {
                        $record['records'][] = [
                            'content' => $record_context,
                            'disabled' => false
                        ];
                        try {
                            $pdns->DomainRecordCreate(
                                $_GET['domain'],
                                $record_name,
                                $_POST['record_type'],
                                $_POST['record_ttl'],
                                $record['records'],
                                );
                        } catch (DomainEditNotMatchDomainFromUrlException $e) {
                            LogController::addError(
                                'Клиентская область',
                                'Вы пытаетесь создать запись с именем домена отличного от текушего (возможно забыли точку на конеце), домен->' . $_GET['domain'] .
                                ', client_id->' . $_SESSION['uid'] .
                                ', server_id->' . $server->id,
                                $e
                            );
                            $error = $_LANG['DomainManager_domain_edit_not_match_domain'];
                            redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
                        } catch (PowerDnsClientException $e) {
                            $error = json_decode($e->response);
                            $error = pdnsTranslate($error->error);
                            LogController::addError(
                                'Клиентская область',
                                'Во время изменения зоны ' . $_POST['zone_name'] . ' возникла ошибка,' .
                                'client_id->' . $_SESSION['uid'] .
                                ', server_id->' . $server->id,
                                $e
                            );
                            redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
                        }
                    }
                    LogController::addSuccess(
                        'Клиентская область',
                        'Была создана запись для домена ' . $_GET['domain'] .
                        'client_id->' . $_SESSION['uid']
                    );
                    redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
                }
            }
            try {
                $pdns->DomainRecordCreate(
                    $_GET['domain'],
                    $record_name,
                    $_POST['record_type'],
                    $_POST['record_ttl'],
                    [
                        [
                            'content' => $record_context,
                            'disabled' => false
                        ]
                    ]
                );
            } catch (DomainEditNotMatchDomainFromUrlException $e) {
                LogController::addError(
                    'Клиентская область',
                    'Вы пытаетесь создать запись с именем домена отличного от текушего (возможно забыли точку на конеце), домен->' . $_GET['domain'] .
                    ', client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                $error = $_LANG['DomainManager_domain_edit_not_match_domain'];
                redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                LogController::addError(
                    'Клиентская область',
                    'Во время изменения зоны ' . $_POST['zone_name'] . ' возникла ошибка,' .
                    'client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
            }
        } else {
            try {
                $pdns->DomainRecordCreate(
                    $_GET['domain'],
                    $record_name,
                    $_POST['record_type'],
                    $_POST['record_ttl'],
                    [
                        [
                            'content' => $record_context,
                            'disabled' => false
                        ]
                    ]
                );
            } catch (DomainEditNotMatchDomainFromUrlException $e) {
                LogController::addError(
                    'Клиентская область',
                    'Вы пытаетесь создать запись с именем домена отличного от текушего (возможно забыли точку на конеце), домен->' . $_GET['domain'] .
                    ', client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                $error = $_LANG['DomainManager_domain_edit_not_match_domain'];
                redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                LogController::addError(
                    'Клиентская область',
                    'Во время изменения зоны ' . $_POST['zone_name'] . ' возникла ошибка,' .
                    'client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                redir("m=DomainManager&error=" . $error, "/");
            }
        }
        LogController::addSuccess(
            'Клиентская область',
            'Была создана запись для домена ' . $_GET['domain'] .
            'client_id->' . $_SESSION['uid']
        );
        redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] == 'edit') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recordTypeCount = [];
            $domainPackage = DomainPackage::where('domain', $_GET['domain'])->firstOrFail();
            $serverId = $domainPackage->serverId;
            $server = ServerModel::find($serverId);
            $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
            $records = array_values($_POST['record']);
            array_walk_recursive($records, function (&$item, $key) {
                $item = $key == 'disabled' ? (bool)$item : $item;
            });
            $recordTypeCount = $records;
            foreach ($recordTypeCount as $key => $record) {
                if (array_key_exists($record['type'], $recordTypeCount)) {
                    $recordTypeCount[$record['type']] += count($record['records']);
                } else {
                    $recordTypeCount[$record['type']] = count($record['records']);
                }
                unset($recordTypeCount[$key]);
            }
            array_walk($records, function (&$item, $key) {
                for ($i = 0; $i < count($item['records']); $i++) {
                    if (is_array($item['records'][$i]['content'])) {
                        if ($item['type'] === 'SRV') {
                            $item['records'][$i]['content'] = sprintf('%s %s %s %s', $item['records'][$i]['content']['priority'], $item['records'][$i]['content']['weight'], $item['records'][$i]['content']['port'], $item['records'][$i]['content']['target']);
                        } elseif ($item['type'] === 'MX') {
                            $item['records'][$i]['content'] = sprintf('%s %s', $item['records'][$i]['content']['preference'], $item['records'][$i]['content']['exchange']);
                        }
                    }
                    if ($item['name'] === '@') {
                        $item['name'] = $_GET['domain'] . '.';
                    } else {
                        $regex = '/.*' . str_replace('.', '\.', $_GET['domain'] . '.') . '$/';
                        if (preg_match($regex, $item['name'], $matches, PREG_OFFSET_CAPTURE, 0) !== false) {
                            if (empty($matches)) $item['name'] .= '.' . $_GET['domain'] . '.';
                        }
                    }
                }
            });

            $package = PackageModel::find($domainPackage->package_id);

            foreach ($recordTypeCount as $recordType => $recordCount) {
                if ($package->{$recordType} !== -1 && $package->{$recordType} <= $recordCount) {
                    $error = sprintf($_LANG['DomainManager_zone_record_limit_reached'], $recordType);
                    LogController::addError(
                        'Клиентская область',
                        sprintf('Превышен лимит записи типа %s', $recordType) .
                        ', client_id->' . $_SESSION['uid'],
                        new Exception()
                    );
                    redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
                }
            }
            try {
                $pdns->DomainRecordsCreate($_GET['domain'], $records);
                LogController::addSuccess(
                    'Клиентская область',
                    'Были изменены записи домена ' . $_GET['domain'] .
                    ', client_id->' . $_SESSION['uid']
                );
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                LogController::addError(
                    'Клиентская область',
                    'Во время изменения зоны ' . $_POST['zone_name'] . ' возникла ошибка,' .
                    'client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
            }
            redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
        }

        $domainPackage = DomainPackage::where('domain', $_GET['domain'])->firstOrFail();
        $serverId = $domainPackage->serverId;
        $package_id = $domainPackage->package_id;
        $package = PackageModel::findOrFail($package_id);
        $server = ServerModel::find($serverId);

        $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
        $records = $pdns->DomainRecordFormatedList($_GET['domain']);
        unset($records['SOA']);
        $type_stats = array_flip(array_keys($records));

        foreach ($type_stats as $type => &$count) {
            $count = collect($records[$type])->sum(function ($item) {
                return count($item['records']);
            });
        }

        return array(
            'pagetitle' => 'DNS Manager',
            'breadcrumb' => array('index.php?m=DomainManager' => 'DNS Manager'),
            'templatefile' => 'templates/client_records',
            'requirelogin' => true,
            'forcessl' => false,
            'vars' => array(
                'records' => $pdns->DomainRecordList($_GET['domain']),
                'domain' => $_GET['domain'],
                'type_stats' => $type_stats,
                'total_count' => array_sum($type_stats),
                "limit_TXT" => $package->TXT,
                "limit_NS" => $package->NS,
                "limit_DNAME" => $package->DNAME,
                "limit_SRV" => $package->SRV,
                "limit_MX" => $package->MX,
                "limit_CNAME" => $package->CNAME,
                "limit_PTR" => $package->PTR,
                "limit_DS" => $package->DS,
                "limit_AAAA" => $package->AAAA,
                "limit_CAA" => $package->CAA,
                "limit_A" => $package->A,
                "limit_record_total_limit" => $package->record_total_limit,
                'error' => $error,
            ),
        );
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] == 'delete') {
        if (array_key_exists('domain', $_GET)) {
            try {
                $DeleteDomain = DomainPackage::where('domain', '=', $_GET['domain'])->firstOrFail();
                if ($DeleteDomain->client_id !== $_SESSION['uid']) {
                    $error = $_LANG['DomainManager_domain_is_not_assigned_account'];
                    LogController::addError(
                        'Клиентская область',
                        'Невозможно удалить домен, так как он не привязан к аккаунту клиента в whmcs (принадлежит другому), client_id->' . $_SESSION['uid'],
                        new Exception()
                    );
                    redir("m=DomainManager&error=" . $error, "/");
                }
                $server = ServerModel::find($DeleteDomain->server_id);
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $pdns->DomainDelete($_GET['domain'] . '.');
                $DeleteDomain->delete();
                LogController::addSuccess(
                    'Клиентская область',
                    'Был удален домен ' . $_GET['domain'] .
                    'client_id->' . $_SESSION['uid']
                );
                redir("m=DomainManager", "/");
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                redir("m=DomainManager&error=" . $error, "/");
            } catch (ModelNotFoundException $e) {
                $error = $_LANG['DomainManager_domain_is_not_assigned_account'];
                LogController::addError(
                    'Клиентская область',
                    'Невозможно удалить домен, так как он не привязан к аккаунту в whmcs, client_id->' . $_SESSION['uid'],
                    new Exception()
                );
                redir("m=DomainManager&error=" . $error, "/");
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (DomainPackage::where('domain', $_POST['zone_name'])->count() != 0) {
            $error = sprintf($_LANG['DomainManager_domain_already_created'], $_POST['zone_name']);
            LogController::addError(
                'Клиентская область',
                sprintf('Домен %s уже создан другим пользователем', $_POST['zone_name']) .
                ', client_id->' . $_SESSION['uid'],
                new Exception()
            );
            redir("m=DomainManager&error=" . $error, "/");
        }

        if (!empty($error = blacklistCheck($_POST['zone_name']))) {
            LogController::addError(
                'Клиентская область',
                $error .
                ', client_id->' . $_SESSION['uid'],
                new Exception()
            );
            redir("m=DomainManager&error=" . $error, "/");
        }

        switch ($_POST['rel_type']) {
            case 1:
                $rel_id = Service::findOrFail($_POST['rel_id'])->packageid;
                break;
            case 2:
                $rel_id = Addon::findOrFail($_POST['rel_id'])->addonid;
                break;
            case 3:
                $domainTld = '.' . Domain::findOrFail($_POST['rel_id'])->tld;
                $domainPricing = Capsule::table('tbldomainpricing')->where('extension', $domainTld)->first();
                if (empty($domainPricing)) {
                    $rel_id = 0;
                }
                $rel_id = $domainPricing->id;
                break;
        }
        $packageRelative = PackageRelative::where('rel_type', '=', $_POST['rel_type'])
            ->where('rel_id', '=', $rel_id)
            ->firstOrFail();
        $package = PackageModel::findOrFail($packageRelative->package_id);

        if (!empty($error = ZoneLimitCheck($package->id, $_POST['zone_name']))) {
            LogController::addError(
                'Клиентская область',
                $error .
                ', client_id->' . $_SESSION['uid'],
                new Exception()
            );
            redir("m=DomainManager&error=" . $error, "/");
        }

        $domainAssPackage = DomainPackage::where('rel_id', '=', $_POST['rel_id'])
            ->where('package_id', '=', $package->id)->count();
        if ($package->domain_zone_limit == -1 || $package->domain_zone_limit > $domainAssPackage) {
            $packageDomain = new DomainPackage();
            $packageDomain->domain = $_POST['zone_name'];
            $packageDomain->rel_id = $_POST['rel_id'];
            $packageDomain->rel_type = $_POST['rel_type'];
            $packageDomain->package_id = $package->id;
            $server = ServerModel::find($package->server_id);
            $canonical_ns = $server->ns_list;
            array_walk($canonical_ns, function (&$item) {
                $item .= '.';
            });
            try {
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $pdns->DomainCreate($_POST['zone_name'] . '.', 'Master', $canonical_ns);
                if ($_POST['ip'] == 'other') {
                    $ip = $_POST['custom_ip'];
                } else {
                    $ip = $_POST['ip'];
                }

                if (array_key_exists('create_www_record', $_POST)) {
                    $pdns->DomainRecordCreate(
                        $_POST['zone_name'],
                        'www.' . $_POST['zone_name'] . '.',
                        'A',
                        '60',
                        [['content' => $ip, 'disabled' => false]],
                        );
                    LogController::addSuccess(
                        'Клиентская область',
                        'Была создана запись www типа А для зоны ' . $_POST['zone_name'] .
                        ', client_id->' . $_SESSION['uid'] .
                        'ip->' . $ip
                    );
                }

                if (array_key_exists('create_root_record', $_POST)) {
                    $pdns->DomainRecordCreate(
                        $_POST['zone_name'],
                        $_POST['zone_name'] . '.',
                        'A',
                        '60',
                        [['content' => $ip, 'disabled' => false]],
                        );
                    LogController::addSuccess(
                        'Клиентская область',
                        'Была создана запись @ типа А для зоны ' . $_POST['zone_name'] .
                        ', client_id->' . $_SESSION['uid'] .
                        'ip->' . $ip
                    );
                }
                $packageDomain->saveOrFail();
                LogController::addSuccess(
                    'Клиентская область',
                    'Была создана зона ' . $_POST['zone_name'] .
                    ', client_id->' . $_SESSION['uid']
                );
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                LogController::addError(
                    'Клиентская область',
                    'Во время создания зоны ' . $_POST['zone_name'] . ' возникла ошибка,' .
                    'client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                redir("m=DomainManager&error=" . $error, "/");
            } catch (DomainEditNotMatchDomainFromUrlException $e) {
                LogController::addError(
                    'Клиентская область',
                    'Не удалось создать домен с записями так как имя записи не соответствует целевому домену (на конце записи должна быть точка), домен->' . $_POST['zone_name'] .
                    ', client_id->' . $_SESSION['uid'] .
                    ', server_id->' . $server->id,
                    $e
                );
                $error = $_LANG['DomainManager_domain_edit_not_match_domain'];
                redir("m=DomainManager&error=" . $error, "/");
            }
        } else {
            $error = $_LANG['DomainManager_zone_limit_reached'];
            LogController::addError(
                'Клиентская область',
                'Действие невозможно, превышен лимит доменов для пакета, client_id->' . $_SESSION['uid'],
                new Exception()
            );
            redir("m=DomainManager&error=" . $error, "/");
        }
    }
    return array(
        'pagetitle' => 'DNS Manager',
        'breadcrumb' => array('index.php?m=DomainManager' => 'DNS Manager'),
        'templatefile' => 'templates/client_index',
        'requirelogin' => true,
        'forcessl' => false,
        'vars' => array(
            'error' => $error,
            'service_packages' => get_client_service_package($_SESSION['uid']),
            'addon_packages' => get_client_addon_package($_SESSION['uid']),
            'domain_packages' => get_client_domain_package($_SESSION['uid']),
            'ip_list' => get_client_ip_list($_SESSION['uid']),
        ),
    );
}


function pdnsTranslate(string $error): string
{
    global $_LANG;
    switch (true) {
        case preg_match('/Could not find domain \'(.*)\'/', $error, $matches, PREG_OFFSET_CAPTURE, 0):
            return sprintf($_LANG['DomainManager_domain_not_found'], $matches[1][0]);
            break;
        case preg_match('/Domain \'(.*)\' already exists/', $error, $matches, PREG_OFFSET_CAPTURE, 0):
            return sprintf($_LANG['DomainManager_domain_busy'], $matches[1][0]);
            break;
        case preg_match('/Key \'name\' not present or not a String/', $error, $matches, PREG_OFFSET_CAPTURE, 0):
            return sprintf($_LANG['DomainManager_name_value_is_not_correct']);
            break;
        case preg_match('/Record (.*): Not in expected format \(parsed as \'(.*)\'\)/', $error, $matches, PREG_OFFSET_CAPTURE, 0):
            return sprintf($_LANG['DomainManager_not_in_expected_format'], $matches[1][0], $matches[2][0]);
            break;
        default:
            return $error;
    }
}

function get_client_ip_list(?int $client_id): array
{
    if (empty($client_id)) return [];
    $services = Service::where('userid', '=', $client_id)->where('domainstatus', '=', 'Active')->get();
    $assIpList = $services->pluck('assignedips');
    $dedicatedIpList = $services->pluck('dedicatedip');

    $ipList = $assIpList->merge($dedicatedIpList)->transform(function ($item, $key) {
        if ($item != "") {
            return explode("\r\n", $item);
        }
        return $item;
    })->filter(function ($item, $key) {
        return $item != "" ? true : false;
    });
    return $ipList->flatten()->toArray();
}

function get_client_service_package(?int $client_id): array
{
    if (empty($client_id)) return [];


    return Service::select(
        'tblhosting.id',
        'tblhosting.domain',
        'mod_addon_domain_manager_package.id as package_id',
        // 'mod_addon_domain_manager_package.rel_id',
        //'mod_addon_domain_manager_package.rel_type',
        'mod_addon_domain_manager_package.domain_zone_filter',
        'mod_addon_domain_manager_package.domain_zone_limit',
        'mod_addon_domain_manager_package.server_id',
        )
        ->where('userid', '=', $client_id)
        ->where('domainstatus', '=', 'Active')
        ->where('mod_addon_domain_manager_package_relative.rel_type', '=', 1)
        ->join(
            'mod_addon_domain_manager_package_relative',
            'mod_addon_domain_manager_package_relative.rel_id',
            '=',
            'tblhosting.packageid'
        )
        ->join(
            'mod_addon_domain_manager_package',
            'mod_addon_domain_manager_package_relative.package_id',
            '=',
            'mod_addon_domain_manager_package.id'
        )
        ->get()->keyBy('id')->transform(function ($item, $key) {
            $domainPackage = DomainPackage::where('rel_id', $key)->where('package_id', $item->package_id)->get();
            return [
                'domain' => $item->domain,
                'package_domains' => $domainPackage->pluck('domain')->toArray(),
                'product_id' => $key,
                'domain_zone_limit' => $item->domain_zone_limit,
                'domain_zone_filter' => $item->domain_zone_filter,
                'domain_count' => $domainPackage->count(),
            ];
        })->toArray();
}

function get_client_addon_package(?int $client_id): array
{
    if (empty($client_id)) return [];

    return Addon::select(
        'tblhostingaddons.id',
        'tblhostingaddons.hostingid',
        'mod_addon_domain_manager_package.id as package_id',
        // 'mod_addon_domain_manager_package.rel_id',
        //'mod_addon_domain_manager_package.rel_type',
        'mod_addon_domain_manager_package.domain_zone_filter',
        'mod_addon_domain_manager_package.domain_zone_limit',
        'mod_addon_domain_manager_package.server_id',
        )
        ->with('service')
        ->where('userid', '=', $client_id)
        ->where('status', '=', 'Active')
        ->where('mod_addon_domain_manager_package_relative.rel_type', '=', 2)
        ->join(
            'mod_addon_domain_manager_package_relative',
            'mod_addon_domain_manager_package_relative.rel_id',
            '=',
            'tblhostingaddons.addonid'
        )
        ->join(
            'mod_addon_domain_manager_package',
            'mod_addon_domain_manager_package_relative.package_id',
            '=',
            'mod_addon_domain_manager_package.id'
        )
        ->get()->keyBy('id')->transform(function ($item, $key) {
            $domainPackage = DomainPackage::where('rel_id', $key)->where('package_id', $item->package_id)->get();
            return [
                'domain' => $item->service->domain,
                'package_domains' => $domainPackage->pluck('domain')->toArray(),
                'product_id' => $item->service->id,
                'addon_id' => $key,
                'domain_zone_limit' => $item->domain_zone_limit,
                'domain_zone_filter' => $item->domain_zone_filter,
                'domain_count' => $domainPackage->count(),
            ];
        })->toArray();
}


function get_client_domain_package(?int $client_id): array
{
    if (empty($client_id)) return [];
    $domains = Domain::select('tbldomains.id', 'tbldomains.domain')
        ->where('userid', '=', $client_id)
        ->where('status', '=', 'Active')
        ->get();


    return $domains->keyBy('id')->transform(function ($item, $key) {
        $domainPricing = Capsule::table('tbldomainpricing')->where('extension', '.' . $item->tld)->first();
        if (empty($domainPricing)) {
            return [];
        }
        $packageRelative = PackageRelative::where('rel_id', $domainPricing->id)->where('rel_type', 3)->first();
        if (empty($packageRelative)) {
            return [];
        }
        $package = PackageModel::findOrFail($packageRelative->package_id);
        $domainPackage = DomainPackage::where('rel_id', $key)
            ->where('package_id', $packageRelative->package_id)
            ->where('rel_type', 3)
            ->get();
        return [
            'domain' => $item->domain,
            'package_domains' => $domainPackage->pluck('domain')->toArray(),
            'domain_id' => $key,
            'domain_zone_limit' => $package->domain_zone_limit,
            'domain_zone_filter' => $package->domain_zone_filter,
            'domain_count' => $domainPackage->count(),
        ];
    })->reject(function ($item, $key) {
        return empty($item);
    })->toArray();
}

function blacklistCheck(string $domain): ?string
{
    global $_LANG;
    $blackList = BlackListModel::all();

    if ($blackList->count() === 0) return null;

    $regex = "/(";
    for ($i = 0; $i < $blackList->count(); $i++) {
        if (strpos($blackList[$i]->domain, '*.') !== false) {
            $part1 = substr($blackList[$i]->domain, 0, 2);
            $part2 = substr($blackList[$i]->domain, 2);

            $regex .= str_replace("*.", "^.*?\.", $part1 . str_replace('.', '\.', $part2));
        } else {
            $regex .= '^' . str_replace('.', '\.', $blackList[$i]->domain);
        }
        if ($i + 1 !== $blackList->count())
            $regex .= "|";
    }
    $regex .= ")$/";

    $result = preg_match($regex, $domain, $matches, PREG_OFFSET_CAPTURE, 0);

    if ($result === false) {

        return sprintf($_LANG['DomainManager_zone_regex_error']);
    }

    if ($result > 0) {
        return sprintf($_LANG['DomainManager_domain_blacklisted'], $domain);
    }

    return null;
}


function ZoneLimitCheck(int $package_id, string $domain): ?string
{
    global $_LANG;
    $allowZone = explode(',', PackageModel::findOrFail($package_id)->domain_zone_filter);

    $regex = "/(";
    for ($i = 0; $i < count($allowZone); $i++) {
        if (strpos($allowZone[$i], '*.') !== false) {
            $part1 = substr($allowZone[$i], 0, 2);
            $part2 = substr($allowZone[$i], 2);

            $regex .= str_replace("*.", "^.*?\.", $part1 . str_replace('.', '\.', $part2));
        } elseif ($allowZone[$i] == '*') {
            $regex .= '.*';
        } else {
            $regex .= '^' . str_replace('.', '\.', $allowZone[$i]);
        }
        if ($i + 1 !== count($allowZone))
            $regex .= "|";
    }
    $regex .= ")$/";
    $result = preg_match($regex, $domain, $matches, PREG_OFFSET_CAPTURE, 0);

    if ($result === false) {
        return sprintf($_LANG['DomainManager_zone_regex_error']);
    }

    if ($result === 0) {
        return sprintf($_LANG['DomainManager_zone_not_allowed']);
    }

    return null;
}