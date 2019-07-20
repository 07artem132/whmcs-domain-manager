<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 24.04.2019
 * Time: 15:27
 */

namespace WHMCS\Module\Addon\DomainManager\Configs;

class ModuleConfig {
	private static $defaultLanguage = 'russian';
	private static $whmcsRootDir = ROOTDIR;
    private static $moduleName = 'DomainManager';

	/**
	 * @return mixed
	 */
	public static function getWhmcsRootDir() {
		return self::$whmcsRootDir;
	}

	/**
	 * @return string
	 */
	public static function getDefaultLanguage() {
		return self::$defaultLanguage;
	}

	/**
	 * @return string
	 */
	public static function getModuleName() {
        return self::$moduleName;
	}

	public static function getModuleLink() {
		global $module, $customadminpath;

		return '/' . $customadminpath . '/addonmodules.php?module=' . $module;
	}
}