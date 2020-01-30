<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 24.04.2019
 * Time: 15:27
 */

namespace WHMCS\Module\Addon\DomainManager\Configs;

class ModuleConfig
{
    private const defaultLanguage = 'russian';
    private const whmcsRootDir = ROOTDIR;
    private const tempPath = self::whmcsRootDir . '/modules/addons/' . self::moduleName . '/temp';
    private const relativePath = '/modules/addons/' . self::moduleName;
    private const moduleName = 'DomainManager';

    /**
     * @return string
     */
    public static function geTempPath(): string
    {
        return self::tempPath;
    }

    /**
     * @return string
     */
    public static function geRelativePath(): string
    {
        return self::relativePath;
    }

    /**
     * @return string
     */
    public static function getWhmcsRootDir(): string
    {
        return self::whmcsRootDir;
    }

    /**
     * @return string
     */
    public static function getDefaultLanguage(): string
    {
        return self::defaultLanguage;
    }

    /**
     * @return string
     */
    public static function getModuleName(): string
    {
        return self::moduleName;
    }

    /**
     * @return string
     */
    public static function getModuleLink(): string
    {
        global $module, $customadminpath;

        return '/' . $customadminpath . '/addonmodules.php?module=' . $module;
    }
}