<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 15:52
 */

namespace WHMCS\Module\Addon\DomainManager\Menu;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;

class AdminAreaDomainSubMenu extends \WHMCS\View\Menu\MenuFactory {
	protected $rootItemName = "Domain Manager sub nav bar";

	public function navbar() {
		return $this->loader->load( $this->buildMenuStructure( $this->getNavBarStructure() ) );
	}

	protected function getNavBarStructure() {
		$menuItems = [
			[
				"name"       => "records",
				"label"      => 'Записи',
				"uri"        => ModuleConfig::getModuleLink() . "&action=record_list",
				"order"      => 1,
				"attributes" => [
					"class" => ! array_key_exists( 'action', $_GET ) || $_GET['action'] === 'record_list' ? 'active' : ''
				]
			],
			[
				"name"       => "teamSpeakRecords",
				"label"      => 'Записи TeamSpeak3',
				"uri"        => ModuleConfig::getModuleLink() . "&action=teamspeak_record_list",
				"order"      => 1,
				"attributes" => [
					"class" => ! array_key_exists( 'action', $_GET ) || $_GET['action'] === 'teamspeak_record_list' ? 'active' : ''
				]
			],
		];

		return $menuItems;
	}

}


