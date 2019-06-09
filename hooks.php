<?php

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;

add_hook( 'AdminAreaHeadOutput', 99999999, function ( $vars ) {
	try {
		if ( ! isset( $_GET['module'] ) || $_GET['module'] != ModuleConfig::getModuleName() ) {
			return null;
		}

		foreach ( scandir( ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin' ) as $item ) {
			if ( $item == '.' || $item == '..' ) {
				continue;
			}

			echo '<link rel="stylesheet" type="text/css" href="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin/' . $item . '">';
		}

		foreach ( scandir( ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin' ) as $item ) {
			if ( $item == '.' || $item == '..' ) {
				continue;
			}

			echo '<script type="text/javascript" charset="utf8" src="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin/' . $item . '"></script>';
		}
	} catch ( Exception $e ) {
		logActivity( ModuleConfig::getModuleName() . ' [AdminAreaHeadOutput]:' . $e->getMessage(), 0 );
	}
} );