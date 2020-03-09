<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 15.02.2020, 16:55
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Tasks;

use Exception;
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\BackupController;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\TaskInterfaces;
use WHMCS\Module\Addon\DomainManager\Models\BackupSettingsModel;
use WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Abstracts\CompressManagerFactoryAbstract;
use WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Abstracts\CompressMethodAbstract;

class BackupCreate implements TaskInterfaces
{
    public $name = 'create backup';
    private $frequency = '0 * * * *';

    function __construct()
    {
    }

    function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     */
    function run(): void
    {
        $settings = BackupSettingsModel::all()->first();
        $fileName = 'all-domain-backup_' . date('Y-m-d H-i') . '.json';

        if (empty($compressMethod = CompressMethodAbstract::$enums[$settings->compress_backup])) {
            LogController::addError('Работа с резервными копиями по крону', 'Резервная копия НЕ выполнена', new Exception('Неизвестный тип сжатия'));
            return;
        }
        $server_error = 0;
        $backup = new BackupController();
        $compressManager = CompressManagerFactoryAbstract::create($compressMethod);

        try {
            $compressManager->open(sprintf('%s/%s%s', ModuleConfig::geBackupPath(), $fileName, $compressManager->getFileExtension()));
            $compressManager->write($backup->create(null, $server_error));
            $compressManager->close();
            echo 'create->' . $fileName . $compressManager->getFileExtension() . PHP_EOL;
        } catch (Exception $e) {
            LogController::addError('Работа с резервными копиями по крону', 'Резервная копия НЕ выполнена', $e);
            return;
        }

        if ($server_error === 0) {
            LogController::addSuccess('Работа с резервными копиями по крону', 'Резервная копия создана успешно');
        } else {
            LogController::addError('Работа с резервными копиями по крону', 'Резервная копия выполнена частично', new Exception('Серверов с ошибками->' . $server_error));
            echo 'Серверов с ошибкой->' . $server_error . PHP_EOL;
        }
    }
}