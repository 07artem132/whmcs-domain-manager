<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 11.01.2020, 17:21
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use Carbon\Carbon;
use Throwable;
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\View\Menu\MenuFactory;

class AdminIndexPage implements PageInterface
{
    private $templateName = 'admin_index.tpl';
    private $vars = [];
    private const tdl = '[".ru",".com",".su",".pro"]';

    function __construct()
    {
        $tdl = array_flip(json_decode(self::tdl));
        $servers = ServerModel::where('status', '=', 1)->get();
        $this->vars['request_server'] = false;
        $backup_files = collect(array_diff(scandir(ModuleConfig::geBackupPath()), ['..', '.']))
            ->transform(function ($item, $key) {
                return [
                    'last_edit' => filemtime(sprintf('%s/%s', ModuleConfig::geBackupPath(), $item)),
                    'name' => $item
                ];
            })->sortByDesc('last_edit');

        $last_backup_date = $backup_files->first()['last_edit'];
        if (!empty($last_backup_date)) {
            if (Carbon::createFromTimestamp($last_backup_date)->diffInHours(Carbon::now()) > 24) {
                $icon = 'fa fa-times fa-2x';
                $text = 'Прошло более 24х часов с момента последней резервной копии.';
                $DiffLastRunHours = Carbon::createFromTimestamp($last_backup_date)->diffInHours(Carbon::now());
                $color = 'red';
            } else {
                $icon = 'fa fa-check fa-2x';
                $text = 'Прошло менее 24х часов с момента последней резервной копии.';
                $DiffLastRunHours = Carbon::createFromTimestamp($last_backup_date)->diffInHours(Carbon::now());
                $color = 'green';
            }
        } else {
            $icon = 'fa fa-check fa-2x';
            $color = 'red';
            $text = 'Резервная копия ещё не выполнялась ни разу!';
            $DiffLastRunHours = null;
        }
        $local_backup_count = $backup_files->count();
        $this->vars['icon'] = $icon;
        $this->vars['color'] = $color;
        $this->vars['text'] = $text;
        $this->vars['diffLastRunHours'] = $DiffLastRunHours;

        $zone_top = [];
        $server_error = [];
        $domain_error = [];
        $domain_server = [];
        $stats_record_type = [];
        $client_package_use = [];
        $package_top = [];

        if ($servers->count() === 0) {
            $this->vars['request_server'] = true;
        }

        foreach ($servers as $server) {
            try {
                $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);
                $domains = $pdns->DomainList();
                $domain_server[$server->ip] = count((array)$domains);
                foreach ($domains as $item) {
                    $other = true;
                    $domain = substr($item->id, 0, -1);
                    $records = $pdns->DomainRecordFormatedList($domain);
                    array_walk($records, function ($records, $type) use (&$stats_record_type) {
                        if (array_key_exists($type, $stats_record_type)) {
                            $stats_record_type[$type] += count($records);
                        } else {
                            $stats_record_type[$type] = count($records);
                        }
                    });
                    array_walk($tdl, function ($key, $tdl) use ($domain, &$zone_top, &$other) {
                        if (preg_match('/' . $tdl . '$/', $domain, $matches) === 1) {
                            $other = false;
                            if (array_key_exists($tdl, $zone_top)) {
                                $zone_top[$tdl]++;
                            } else {
                                $zone_top[$tdl] = 1;
                            }
                        }
                    });
                    if ($other) {
                        if (array_key_exists('другие', $zone_top)) {
                            $zone_top['другие']++;
                        } else {
                            $zone_top['другие'] = 1;
                        }
                    }
                }
            } catch (Throwable $e) {
                $server_error[$server->name] = $e->getMessage();
            }
        }

        $package = PackageModel::all()->keyBy('id');
        $client_package_use = DomainPackage::all()->groupBy('package_id')->transform(function ($item, $key) use (&$domain_error, $package) {
            $server = ServerModel::findOrFail($package[$key]->server_id);
            $pdns = new PowerDNS('http://' . $server->ip . ':' . $server->port . '/api/v1/', $server->token);

            return $item->transform(function ($item) use ($pdns, &$domain_error) {
                try {
                    $records = $pdns->DomainRecordFormatedList($item->domain);
                } catch (PowerDnsClientException $e) {
                    $domain_error[$item->domain] = $e->getMessage();
                    return false;
                }
                unset($records['SOA'], $records['NS']);
                return count($records) > 0 ? true : false;
            });
        })->flatten();
        $client_package_not_use = $client_package_use->count() - $client_package_use->sum();
        $client_package_use = $client_package_use->sum();
        $package_top = DomainPackage::all()->groupBy('package_id')->transform(function ($item, $key) {
            return $item->count();
        })->keyBy(function ($item, $key) use ($package) {
            return $package[$key]->title;
        })->toArray();

        $this->vars['server_error'] = $server_error;
        $this->vars['domain_error'] = $domain_error;
        $this->vars['stats'] = [
            'domain_total' => array_sum($domain_server),
            'records_total' => array_sum($stats_record_type),
            'client_package_total' => array_sum($package_top),
            'domain_server' => [
                'label' => array_keys($domain_server),
                'data' => array_values($domain_server),
            ],
            'zone_top' => [
                'label' => array_keys($zone_top),
                'data' => array_values($zone_top),
            ],
            'package_top' => [
                'label' => array_keys($package_top),
                'data' => array_values($package_top),
            ],
            'stats_record_type' => [
                'label' => array_keys($stats_record_type),
                'data' => array_values($stats_record_type),
            ],
            'client_package_use' => [
                'label' => [
                    'Без записей',
                    'С записями',
                ],
                'data' => [
                    $client_package_not_use,
                    $client_package_use,
                ],
            ],
        ];
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
            'Главная' => '',
        ];
    }
}