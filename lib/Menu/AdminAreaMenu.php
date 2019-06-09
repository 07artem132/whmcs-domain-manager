<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 15:52
 */

namespace WHMCS\Module\Addon\DomainManager\Menu;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;

class AdminAreaMenu extends \WHMCS\View\Menu\MenuFactory {
	protected $rootItemName = "Domain Manager nav bar";

	public function navbar() {
		return $this->loader->load( $this->buildMenuStructure( $this->getNavBarStructure() ) );
	}

	protected function getNavBarStructure() {
		$menuItems = [
			[
				"name"       => "index",
				"label"      => 'Домены',
				"uri"        => ModuleConfig::getModuleLink() . "&action=index",
				"order"      => 1,
				"attributes" => [
					"class" => ! array_key_exists( 'action', $_GET ) || $_GET['action'] === 'index' ? 'active' : ''
				]
			],
			[
				"name"       => "settings",
				"label"      => 'Настройки',
				"uri"        => ModuleConfig::getModuleLink() . "&action=settings",
				"order"      => 2,
				"attributes" => [
					"class" => array_key_exists( 'action', $_GET ) && $_GET['action'] === 'settings' ? 'active' : ''
				]
			],
			[
				"name"       => "integrations",
				"label"      => 'Интеграция с PowerDNS',
				"uri"        => ModuleConfig::getModuleLink() . "&action=integrations",
				"order"      => 3,
				"attributes" => [
					"class" => array_key_exists( 'action', $_GET ) && $_GET['action'] === 'integrations' ? 'active' : ''
				]
			],
		];

		return $menuItems;
	}

}


