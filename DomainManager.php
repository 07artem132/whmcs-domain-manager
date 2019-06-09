<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 20.04.2019
 * Time: 17:57
 */

require __DIR__ . '/vendor/autoload.php';

error_reporting( - 1 );
ini_set( "display_errors", 1 );

use WHMCS\Module\Addon\DomainManager\Controllers\PageController;
use WHMCS\Module\Addon\DomainManager\Menu\AdminAreaMenu;

function DomainManager_config() {
	return [
		"name"        => 'Менеджер доменов',
		"description" => '-',
		"version"     => "1",
		"author"      => "<a href=\"https://github.com/07artem132\">07artem132</a>",
		"language"    => "english",
		"fields"      => [
			"DeleteTableWhenDisabled" => [
				"FriendlyName" => "Удалять данные модуля при отключении ?",
				"Type"         => "yesno",
				"Description"  => " Отметьте здесь дабы удалить данные модуля при отключении оного.",
			]
		]
	];
}

function DomainManager_output( $vars ) {
	$PageController = new PageController( $vars );
	$PageController->setDefaultAction( 'index' );
	$PageController->setSuffixTemplate( 'admin' );

	$PageController->setMenuTemplate( 'include\navbar.tpl' );
	$PageController->setMenu(( new AdminAreaMenu())->navbar() );
	$PageController->run();
}

function DomainManager_activate() {

}

function DomainManager_deactivate() {

}

function DomainManager_clientarea( $vars ) {

}
