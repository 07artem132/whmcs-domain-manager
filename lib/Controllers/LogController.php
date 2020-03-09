<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 02.02.2020, 5:31
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Controllers;


use Throwable;
use WHMCS\Module\Addon\DomainManager\Models\LogModel;

class LogController
{
    public static function addSuccess(string $module, string $action): void
    {
        $log = new LogModel();
        $log->status = 1;
        $log->module = self::namespaceToModule($module);
        $log->message = $action;
        $log->saveOrFail();
    }

    private static function namespaceToModule(string $module): string
    {
        if (preg_match('/((?:\\\\{1,2}\w+|\w+\\\\{1,2})(?:\w+\\\\{0,2})+)/', $module, $matches, PREG_OFFSET_CAPTURE, 0) !== 0) {
            switch (basename(str_replace('\\', '/', $module))) {
                case 'AdminRemoveBlacklistPage':
                case 'AdminDomainAddBlacklistPage':
                    return 'Работа с blacklist';
                case 'AdminTransferDomainPage':
                case 'AdminAddDomainPage':
                case 'AdminDeleteDomainPage':
                case 'AdminDomainAddRecordPage':
                case 'AdminDomainEditRecordPage':
                case 'AdminDomainDeleteRecordPage':
                    return 'Работа с доменами';
                case 'AdminAddPackagePage':
                case 'AdminEditPackagePage':
                    return 'Работа с пакетами';
                case 'AdminAddServerPage':
                case 'AdminDeleteServerPage':
                case 'AdminEditServerPage':
                case 'AdminChangeStatusServerPage':
                    return 'Работа с серверами';
                case 'AdminBackupPage':
                case 'AdminCreateBackupPage':
                    return 'Работа с резервными копиями';
                default:
                    return $module;
                    break;
            }
        }
        return $module;
    }

    public static function addError(string $module, string $action, Throwable $e): void
    {
        $log = new LogModel();
        $log->status = 0;
        $log->module = self::namespaceToModule($module);
        $log->message = $action . PHP_EOL . self::formatException($e);
        $log->saveOrFail();
    }

    private static function formatException(Throwable $e): string
    {
        return 'message->' . $e->getMessage() . PHP_EOL .
            'trace->' . $e->getTraceAsString();
    }
}