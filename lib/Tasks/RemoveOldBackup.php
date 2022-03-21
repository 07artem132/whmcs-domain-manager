<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 15.02.2020, 18:49
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

class RemoveOldBackup implements TaskInterfaces
{
    private $frequency = '0 * * * *';

    public $name = 'remove old backup';

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

        if ($settings->limit_local_backup !== -1) {
            $files = collect(array_diff(scandir(ModuleConfig::geBackupPath()), ['..', '.']))
                ->transform(function ($item, $key) {
                    return [
                        'last_edit' => filemtime(sprintf('%s/%s', ModuleConfig::geBackupPath(), $item)),
                        'name' => $item
                    ];
                })->sortBy('last_edit');

            if ($settings->limit_local_backup < $files->count()) {
                $removeFiles = $files->take($files->count() - $settings->limit_local_backup);
                foreach ($removeFiles as $removeFile) {
                    if(!unlink(sprintf('%s/%s', ModuleConfig::geBackupPath(), $removeFile['name']))){
                        LogController::addError('Работа с резервными копиями по крону', 'Не удалось удалить старую резервную копию', new \Exception('Не удалось удалить->' . sprintf('%s/%s', ModuleConfig::geBackupPath(), $removeFile['name'])));
                        continue;
                    }
                    echo 'local remove->' . $removeFile['name'] . PHP_EOL;
                    LogController::addSuccess('Работа с резервными копиями по крону', sprintf('Удалена старая локальная резервная копия: %s/%s', ModuleConfig::geBackupPath(), $removeFile['name']));
                }
            }
        }

        if ($settings->upload_backup === 1 && $settings->limit_remote_backup !== -1) {
            switch ($settings->server_type) {
                case 'ftp':
                    try {
                        $ftp = new FtpClientController();
                        $ftp->connect($settings->server_ip, $settings->server_port);
                        $ftp->login($settings->server_login, $settings->server_password);
                        if (!$ftp->chdir($settings->server_path)) {
                            LogController::addError('Работа с резервными копиями по крону', 'Не удалось сменить деректорию(ftp)', new \Exception('Не удалось сменить деректорию->' .$settings->server_path));
                            return;
                        }
                        $files = collect($ftp->nlist())
                            ->transform(function ($item, $key) use ($ftp, $settings) {
                                return [
                                    'last_edit' => $ftp->modifiedTime($item),
                                    'name' => $item
                                ];
                            })->sortBy('last_edit');
                    } catch (FtpException $e) {
                        LogController::addError('Работа с резервными копиями по крону', 'При удалении старой резервной копии возникла ошибка', $e);
                        return;
                    } catch (FtpIsNotDirException $e) {
                        LogController::addError('Работа с резервными копиями по крону', 'Не удалось сменить деректорию(ftp)', new \Exception('Не удалось сменить деректорию->' .$settings->server_path));
                        return;
                    }
                    if ($settings->limit_remote_backup < $files->count()) {
                        $removeFiles = $files->take($files->count() - $settings->limit_remote_backup);
                        foreach ($removeFiles as $removeFile) {
                            if (!$ftp->remove(sprintf('%s/%s', $settings->server_path, $removeFile['name']))) {
                                LogController::addError('Работа с резервными копиями по крону', 'Не удалось удалить файл (ftp)', new \Exception('Не удалось удалить файл->' .sprintf('%s/%s', $settings->server_path, $removeFile['name'])));
                                continue;
                            }
                            echo 'ftp remove->' . $removeFile['name'] . PHP_EOL;
                            LogController::addSuccess('Работа с резервными копиями по крону', sprintf('Удалена старая удаленная (ftp) резервная копия: %s/%s', $settings->server_path, $removeFile['name']));
                        }
                    }
                    break;
                case 'sftp':
                    try {
                        $sftp = new SftpClient();
                        $credentials = Credentials::withPassword($settings->server_login, $settings->server_password);
                        $sftp->setCredentials($credentials);
                        $sftp->connect($settings->server_ip, $settings->server_port);
                        $files = collect($sftp->getFileList($settings->server_path))
                            ->transform(function ($item, $key) use ($sftp, $settings) {
                                return [
                                    'last_edit' => $sftp->stat(sprintf('%s/%s', $settings->server_path, $item))['mtime'],
                                    'name' => $item
                                ];
                            })->sortBy('last_edit');
                    } catch (\Exception $e) {
                        LogController::addError('Работа с резервными копиями по крону', 'При удалении старой резервной копии возникла ошибка (sftp)', $e);
                        return;
                    }
                    if ($settings->limit_remote_backup < $files->count()) {
                        $removeFiles = $files->take($files->count() - $settings->limit_remote_backup);
                        foreach ($removeFiles as $removeFile) {
                            try {
                                $sftp->remove(sprintf('%s/%s', $settings->server_path, $removeFile['name']));
                            } catch (\Exception $e) {
                                LogController::addError('Работа с резервными копиями по крону', 'Не удалось удалить файл (sftp)', new \Exception('Не удалось удалить файл->' .sprintf('%s/%s', $settings->server_path, $removeFile['name'])));
                                continue;
                            }
                            echo 'sftp remove->' . $removeFile['name'] . PHP_EOL;
                            LogController::addSuccess('Работа с резервными копиями по крону', sprintf('Удалена старая удаленная (sftp) резервная копия: %s/%s', $settings->server_path, $removeFile['name']));
                        }
                    }
                    break;
            }
        }
    }
}