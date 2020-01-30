<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 24.04.2019
 * Time: 20:23
 */

namespace WHMCS\Module\Addon\DomainManager\Controllers;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use WHMCS\Database\Capsule;

class InstallController
{

    public static function createTableBackupSettings()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_domain_manager_backup_setting')) {
                Capsule::schema()->create('mod_addon_domain_manager_backup_setting', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->smallInteger('limit_local_backup')->nullable();
                    $table->smallInteger('limit_remote_backup')->nullable();
                    $table->boolean('upload_backup');
                    $table->smallInteger('compress_backup');
                    $table->ipAddress('server_ip')->nullable();
                    $table->unsignedSmallInteger('server_port')->nullable();
                    $table->string('server_login')->nullable();
                    $table->string('server_password')->nullable();
                    $table->string('server_type')->nullable();
                    $table->string('server_path')->nullable();
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_domain_manager_backup_setting', $e->getMessage())
            );
        }
        return [];
    }

    public static function createTableLog()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_domain_manager_log')) {
                Capsule::schema()->create('mod_addon_domain_manager_log', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->boolean('status');
                    $table->string('module');
                    $table->text('message');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_domain_manager_log', $e->getMessage())
            );
        }
        return [];
    }

    public static function createTableBlackList()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_domain_manager_black_list')) {
                Capsule::schema()->create('mod_addon_domain_manager_black_list', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->text('domain');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_domain_manager_black_list', $e->getMessage())
            );
        }
        return [];
    }

    public static function createTableServer()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_domain_manager_server')) {
                Capsule::schema()->create('mod_addon_domain_manager_server', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->string('name');
                    $table->ipAddress('ip');
                    $table->string('token');
                    $table->text('ns_list');
                    $table->boolean('status');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_domain_manager_server', $e->getMessage())
            );
        }
        return [];
    }

    public static function createTablePackage()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_domain_manager_package')) {
                Capsule::schema()->create('mod_addon_domain_manager_package', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->string('title');
                    $table->unsignedSmallInteger('rel_id');
                    $table->unsignedSmallInteger('rel_type');
                    $table->string('domain_zone_filter');
                    $table->unsignedInteger('server_id');
                    $table->smallInteger('domain_zone_limit');
                    $table->smallInteger('TXT');
                    $table->smallInteger('NS');
                    $table->smallInteger('DNAME');
                    $table->smallInteger('SRV');
                    $table->smallInteger('MX');
                    $table->smallInteger('CNAME');
                    $table->smallInteger('PTR');
                    $table->smallInteger('DS');
                    $table->smallInteger('AAAA');
                    $table->smallInteger('CAA');
                    $table->smallInteger('A');
                    $table->smallInteger('record_total_limit');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_domain_manager_package', $e->getMessage())
            );
        }
        return [];
    }

    public static function createTableDomainToPackage()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_domain_manager_domain_package')) {
                Capsule::schema()->create('mod_addon_domain_manager_domain_package', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->text('domain');
                    $table->unsignedInteger('rel_id');
                    $table->unsignedInteger('package_id');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_domain_manager_domain_package', $e->getMessage())
            );
        }
        return [];
    }

}