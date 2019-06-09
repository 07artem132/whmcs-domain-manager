<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 16:32
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;

class AdminSettingsPage implements PageInterface {
	private $templateName = 'admin_settings.tpl';
	private $vars = [];


	function getTemplateName() {
		return $this->templateName;
	}

	/**
	 * @return array
	 */
	function getVars() {
		return $this->vars;
	}
	function getSubMenu() {
		return null;
	}

}