<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 15.02.2020, 19:53
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Tasks;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\TaskInterfaces;
use WHMCS\Module\Addon\DomainManager\Models\BackupSettingsModel;
use WHMCS\Module\Addon\DomainManager\vendor\FTPClient\Exceptions\FtpException;
use WHMCS\Module\Addon\DomainManager\vendor\FTPClient\Exceptions\FtpIsNotDirException;
use WHMCS\Module\Addon\DomainManager\vendor\FTPClient\FtpClientController;
use WHMCS\Module\Addon\DomainManager\vendor\SSHClient\Credentials;
use WHMCS\Module\Addon\DomainManager\vendor\SSHClient\SftpClient;

class UploadBackup implements TaskInterfaces
{
    private $frequency = '0 * * * *';

    public $name = 'upload backup';

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
        $backups = collect(array_diff(scandir(ModuleConfig::geBackupPath()), ['..', '.']));

        if ($settings->upload_backup === 1) {
            switch ($settings->server_type) {
                case 'ftp':
                    try {
                        $ftp = new FtpClientController();
                        $ftp->connect($settings->server_ip, $settings->server_port);
                        $ftp->login($settings->server_login, $settings->server_password);
                        if (!$ftp->chdir($settings->server_path)) {
                            LogController::addError('Работа с резервными копиями по крону', 'Не удалось сменить деректорию(ftp)', new \Exception('Не удалось сменить деректорию->' . $settings->server_path));
                            return;
                        }
                        $files = $ftp->nlist();
                    } catch (FtpException $e) {
                        LogController::addError('Работа с резервными копиями по крону', 'При выгрузке резервной копии возникла ошибка', $e);
                        return;
                    } catch (FtpIsNotDirException $e) {
                        LogController::addError('Работа с резервными копиями по крону', 'Не удалось сменить деректорию(ftp)', new \Exception('Не удалось сменить деректорию->' . $settings->server_path));
                        return;
                    }
                    foreach ($backups->diff($files) as $file) {
                        try {
                            $ftp->putFromPath(sprintf('%s/%s', ModuleConfig::geBackupPath(), $file));
                        } catch (FtpException $e) {
                            LogController::addError('Работа с резервными копиями по крону', 'Не удалось загрузить файл (ftp)', new \Exception('Не удалось загрузить файл->' . sprintf('%s/%s', ModuleConfig::geBackupPath(), $file)));
                            continue;
                        }
                        echo 'upload->' . $file . PHP_EOL;
                        LogController::addSuccess('Работа с резервными копиями по крону', sprintf('Выгружена (ftp) резервная копия: %s/%s', ModuleConfig::geBackupPath(), $file));
                    }
                    break;
                case 'sftp':
                    try {
                        $sftp = new SftpClient();
                        $credentials = Credentials::withPassword($settings->server_login, $settings->server_password);
                        $sftp->setCredentials($credentials);
                        $sftp->connect($settings->server_ip, $settings->server_port);
                        $files = $sftp->getFileList($settings->server_path);
                    } catch (\Exception $e) {
                        LogController::addError('Работа с резервными копиями по крону', 'При выгрузке резервной копии возникла ошибка (sftp)', $e);
                        return;
                    }
                    foreach ($backups->diff($files) as $file) {
                        try {
                            $sftp->upload(
                                sprintf('%s/%s', ModuleConfig::geBackupPath(), $file),
                                sprintf('%s/%s', $settings->server_path, $file)
                            );
                        } catch (\Exception $e) {
                            LogController::addError('Работа с резервными копиями по крону', 'Не удалось выгрузить файл (sftp)', new \Exception('Не удалось выгрузить файл->' . sprintf('%s/%s', ModuleConfig::geBackupPath(), $file)));
                            continue;
                        }
                        echo 'upload->' . $file . PHP_EOL;
                        LogController::addSuccess('Работа с резервными копиями по крону', sprintf('Выгружена (sftp) резервная копия: %s/%s', ModuleConfig::geBackupPath(), $file));
                    }
                    break;
            }
        }

    }
}