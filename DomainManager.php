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
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\InstallController;
use WHMCS\Module\Addon\DomainManager\Controllers\PageController;
use WHMCS\Module\Addon\DomainManager\Controllers\UninstallController;
use WHMCS\Module\Addon\DomainManager\Menu\AdminAreaMenu;
use WHMCS\Module\Addon\DomainManager\Models\BlackListModel;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
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
        "description" => 'Для работы модуля требуется  php-intl',
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
        }
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно деактивирован'
    );

}

function DomainManager_clientarea($vars)
{
    $error = '';

    if (array_key_exists('error', $_GET)) {
        $error = $_GET['error'];
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] === 'record_delete') {
        $domainPackage = DomainPackage::where('domain', '=', $_GET['domain'])->firstOrFail();
        $serverId = $domainPackage->serverId;
        $server = ServerModel::find($serverId);
        $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
        $pdns->DomainRecordDelete($_GET['domain'], $_GET['name'], $_GET['type'], $_GET['ttl'], [['content' => $_GET['content']]]);
        redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $recordTypeCount = [];
        $domainPackage = DomainPackage::where('domain', '=', $_GET['domain'])->firstOrFail();
        $serverId = $domainPackage->serverId;
        $server = ServerModel::find($serverId);
        $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
        $records = $pdns->DomainRecordFormatedList($_GET['domain']);
        foreach ($records as $record_type => $record_list) {
            array_key_exists($record_type, $recordTypeCount) ? $recordTypeCount[$record_type]++ : $recordTypeCount[$record_type] = 1;
        }
        $package = PackageModel::find($domainPackage->package_id);
        foreach ($recordTypeCount as $recordType => $recordCount) {
            if ($recordType == $_POST['record_type']) {
                $recordCount++;
            }
            if ($recordType == 'SOA') continue;
            if ($package->{$recordType} !== -1 && $package->{$recordType} <= $recordCount) {
                $error = sprintf('Превышен лимит записи типа %s', $recordType);
                redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
            }
        }
        if (array_key_exists($_POST['record_type'], $records)) {
            for ($i = 0; $i < count($records[$_POST['record_type']]); $i++) {
                if ($records[$_POST['record_type']][$i]['name'] === $_POST['record_name']) {
                    $record = $records[$_POST['record_type']][$i];
                    $flagAdd = true;
                    for ($j = 0; $j < count($record['records']); $j++) {
                        if ($record['records'][$j]->content == $_POST['record_context']) {
                            $flagAdd = false;
                        }
                    }
                    if ($flagAdd) {
                        $record['records'][] = [
                            'content' => $_POST['record_context'],
                            'disabled' => false
                        ];
                        $pdns->DomainRecordCreate(
                            $_GET['domain'],
                            $_POST['record_name'],
                            $_POST['record_type'],
                            $_POST['record_ttl'],
                            $record['records'],
                            );
                    }
                    redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
                }
            }
            $pdns->DomainRecordCreate(
                $_GET['domain'],
                $_POST['record_name'],
                $_POST['record_type'],
                $_POST['record_ttl'],
                [
                    [
                        'content' => $_POST['record_context'],
                        'disabled' => false
                    ]
                ]
            );
        } else {
            $pdns->DomainRecordCreate(
                $_GET['domain'],
                $_POST['record_name'],
                $_POST['record_type'],
                $_POST['record_ttl'],
                [
                    [
                        'content' => $_POST['record_context'],
                        'disabled' => false
                    ]
                ]
            );
        }

        redir("m=DomainManager&api=edit&domain=" . $_GET['domain'], "/");
    }

    if (array_key_exists('api', $_GET) && $_GET['api'] == 'edit') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recordTypeCount = [];
            $domainPackage = DomainPackage::where('domain', $_GET['domain'])->firstOrFail();
            $serverId = $domainPackage->serverId;
            $server = ServerModel::find($serverId);
            $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
            $records = array_values($_POST['record']);
            array_walk_recursive($records, function (&$item, $key) use (&$recordTypeCount) {
                $item = $key == 'disabled' ? (bool)$item : $item;
                if ($key == 'type') {
                    array_key_exists($item, $recordTypeCount) ? $recordTypeCount[$item]++ : $recordTypeCount[$item] = 1;
                }
            });
            $package = PackageModel::find($domainPackage->package_id);

            foreach ($recordTypeCount as $recordType => $recordCount) {
                if ($package->{$recordType} !== -1 && $package->{$recordType} <= $recordCount) {
                    $error = sprintf('Превышен лимит записи типа %s', $recordType);
                    redir("m=DomainManager&api=edit&domain=" . $_GET['domain'] . "&error=" . $error, "/");
                }
            }

            $pdns->DomainRecordsCreate($_GET['domain'], $records);
        }

        $domainPackage = DomainPackage::where('domain', $_GET['domain'])->firstOrFail();
        $serverId = $domainPackage->serverId;
        $package_id = $domainPackage->package_id;
        $package = PackageModel::findOrFail($package_id);
        $server = ServerModel::find($serverId);

        $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
        $records = $pdns->DomainRecordFormatedList($_GET['domain']);
        unset($records['SOA']);
        $type_stats = array_flip(array_keys($records));

        foreach ($type_stats as $type => &$count) {
            $count = count($records[$type]);
        }

        return array(
            'pagetitle' => 'DNS Manager',
            'breadcrumb' => array('index.php?m=DomainManager' => 'DNS Manager'),
            'templatefile' => 'templates/client_records',
            'requirelogin' => false,
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
                $DeleteDomain->server_id;
                $server = ServerModel::find($DeleteDomain->server_id);
                $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
                $pdns->DomainDelete($_GET['domain'] . '.');
                $DeleteDomain->delete();
                redir("m=DomainManager", "/");
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                redir("m=DomainManager&error=" . $error, "/");
            } catch (ModelNotFoundException $e) {
                $error = 'Невозможно удалить домен, так как он не привязан к аккаунту в whmcs';
                redir("m=DomainManager&error=" . $error, "/");
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (DomainPackage::where('domain', $_POST['zone_name'])->count() != 0) {
            $error = sprintf('Домен %s уже создан другим пользователем', $_POST['zone_name']);
            redir("m=DomainManager&error=" . $error, "/");
        }

        if (!empty($error = blacklistCheck($_POST['zone_name']))) {
            redir("m=DomainManager&error=" . $error, "/");
        }

        switch ($_POST['rel_type']) {
            case 1:
                $rel_id = Service::findOrFail($_POST['rel_id'])->packageid;
                break;
            case 2:
                $rel_id = Addon::findOrFail($_POST['rel_id'])->addonid;
                break;
        }
        $package = PackageModel::where('rel_type', '=', $_POST['rel_type'])
            ->where('rel_id', '=', $rel_id)
            ->firstOrFail();

        if (!empty($error = ZoneLimitCheck($package->id, $_POST['zone_name']))) {
            redir("m=DomainManager&error=" . $error, "/");
        }

        $domainAssPackage = DomainPackage::where('rel_id', '=', $_POST['rel_id'])
            ->where('package_id', '=', $package->id)->count();
        if ($package->domain_zone_limit == -1 || $package->domain_zone_limit > $domainAssPackage) {
            $packageDomain = new DomainPackage();
            $packageDomain->domain = $_POST['zone_name'];
            $packageDomain->rel_id = $_POST['rel_id'];
            $packageDomain->package_id = $package->id;
            $server = ServerModel::find($package->server_id);
            $canonical_ns = $server->ns_list;
            array_walk($canonical_ns, function (&$item) {
                $item .= '.';
            });
            try {
                $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
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
                }

                if (array_key_exists('create_root_record', $_POST)) {
                    $pdns->DomainRecordCreate(
                        $_POST['zone_name'],
                        $_POST['zone_name'] . '.',
                        'A',
                        '60',
                        [['content' => $ip, 'disabled' => false]],
                        );
                }
                $packageDomain->saveOrFail();
            } catch (PowerDnsClientException $e) {
                $error = json_decode($e->response);
                $error = pdnsTranslate($error->error);
                redir("m=DomainManager&error=" . $error, "/");
            } catch (DomainEditNotMatchDomainFromUrlException $e) {
                $error = 'Вы пытаетесь создать запись с именем домена отличного от текушего';
                redir("m=DomainManager&error=" . $error, "/");
            }
        } else {
            $error = 'Действие невозможно, превышен лимит доменов для пакета.';
            redir("m=DomainManager&error=" . $error, "/");
        }
    }

    return array(
        'pagetitle' => 'DNS Manager',
        'breadcrumb' => array('index.php?m=DomainManager' => 'DNS Manager'),
        'templatefile' => 'templates/client_index',
        'requirelogin' => false,
        'forcessl' => false,
        'vars' => array(
            'error' => $error,
            'service_packages' => get_client_service_package(1),
            'addon_packages' => get_client_addon_package(1),
            'ip_list' => get_client_ip_list(1),
        ),
    );
}


function pdnsTranslate(string $error): string
{
    switch (true) {
        case preg_match('/Could not find domain \'(.*)\'/', $error, $matches, PREG_OFFSET_CAPTURE, 0):
            return sprintf('Домен %s не найден', $matches[1][0]);
            break;
        case preg_match('/Domain \'(.*)\' already exists/', $error, $matches, PREG_OFFSET_CAPTURE, 0):
            return sprintf('Домен %s уже создан другим пользователем', $matches[1][0]);
            break;
        default:
            return $error;
    }
}

function get_client_ip_list(int $client_id): array
{
    $services = Service::where('userid', '=', $client_id)->get();
    $assIpList = $services->pluck('assignedips');
    $dedicatedIpList = $services->pluck('dedicatedip')->transform(function ($item, $key) {
        if ($item != "") {
            return explode("\r\n", $item);
        }
        return $item;
    });

    $ipList = $assIpList->merge($dedicatedIpList)->filter(function ($item, $key) {
        return $item != "" ? true : false;
    });
    return $ipList->flatten()->toArray();
}

function get_client_service_package(int $client_id): array
{
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
        ->where('mod_addon_domain_manager_package.rel_type', '=', 1)
        ->join(
            'mod_addon_domain_manager_package',
            'mod_addon_domain_manager_package.rel_id',
            '=',
            'tblhosting.packageid'
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

function get_client_addon_package(int $client_id): array
{
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
        ->where('mod_addon_domain_manager_package.rel_type', '=', 2)
        ->join(
            'mod_addon_domain_manager_package',
            'mod_addon_domain_manager_package.rel_id',
            '=',
            'tblhostingaddons.addonid'
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

function blacklistCheck(string $domain): ?string
{
    $blackList = BlackListModel::all();
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
        return sprintf('Возникла ошибка при выполнении регулярного выражения, свяжитесь с администратором');
    }

    if ($result > 0) {
        return sprintf('Домен %s занесен в черный список администратором', $domain);
    }

    return null;
}


function ZoneLimitCheck(int $package_id, string $domain): ?string
{
    $allowZone = explode(',', PackageModel::findOrFail($package_id)->domain_zone_filter);
    $regex = "/(";
    for ($i = 0; $i < count($allowZone); $i++) {
        if (strpos($allowZone[$i], '*.') !== false) {
            $part1 = substr($allowZone[$i], 0, 2);
            $part2 = substr($allowZone[$i], 2);

            $regex .= str_replace("*.", "^.*?\.", $part1 . str_replace('.', '\.', $part2));
        } else {
            $regex .= '^' . str_replace('.', '\.', $allowZone[$i]);
        }
        if ($i + 1 !== count($allowZone))
            $regex .= "|";
    }
    $regex .= ")$/";

    $result = preg_match($regex, $domain, $matches, PREG_OFFSET_CAPTURE, 0);

    if ($result === false) {
        return sprintf('Возникла ошибка при выполнении регулярного выражения, свяжитесь с администратором');
    }

    if ($result === 0) {
        return sprintf('Вы не можете создать домен в этой зоне');
    }

    return null;
}