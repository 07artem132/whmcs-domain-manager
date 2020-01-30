<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 12.01.2020, 19:33
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Controllers;


use Throwable;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;

class BackupController
{
    /**
     * @param string|null $domain
     * @param int $server_error
     * @param int $server_id
     * @return string
     */
    function create(?string $domain = null, int &$server_error = 0, ?int $server_id = null): string
    {
        $backup = [];
        if (empty($domain)) {
            foreach (ServerModel::all() as $server) {
                try {
                    $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
                    foreach ($pdns->DomainList() as $domain) {
                        $domainFormatted = substr($domain->name, 0, -1);
                        $backup[$domainFormatted . ':' . $server->id] = $pdns->DomainRecordList($domainFormatted);
                    }
                } catch (Throwable $e) {
                    $server_error++;
                }
            }
        } else {
            try {
                $server = ServerModel::findOrFail($server_id);
                $pdns = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
                $backup[$domain . ':' . $server_id] = $pdns->DomainRecordList($domain);
            } catch (Throwable $e) {
                $server_error++;
            }
        }

        return json_encode($backup);
    }

    /**
     * @param array $backup
     * @param int $server_error
     */
    function restore(array $backup, int &$server_error = 0)
    {
        foreach ($backup as $domain_server => $records) {
            try {
                list($domain, $server_id) = explode(':', $domain_server);

                $server = ServerModel::findOrFail($server_id);

                $PowerDNS = new PowerDNS('http://' . $server->ip . '/api/v1/', $server->token);
                $exitsRecord = $PowerDNS->DomainRecordList($domain);

                $PowerDNS->DomainRecordsDelete($domain, $exitsRecord);
                $PowerDNS->DomainRecordsCreate($domain, $records);
            } catch (Throwable $e) {
                $server_error++;
            }
        }
    }
}