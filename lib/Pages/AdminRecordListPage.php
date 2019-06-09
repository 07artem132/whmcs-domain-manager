<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 16:32
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\PowerDNS;
use WHMCS\Module\Addon\DomainManager\Menu\AdminAreaDomainSubMenu;

class AdminRecordListPage implements PageInterface {
	private $templateName = 'admin_record_list.tpl';
	private $vars = [];

	function __construct(   ) {
		$PowerDNS                 = new PowerDNS( 'http://ns01.service-voice.com/api/v1/', '8FVofCuKHICIrC700xCTi4RRb' );
		$this->vars['recordList'] = $PowerDNS->DomainRecordList( $_GET['domain'] );
	}

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
		return ( new AdminAreaDomainSubMenu())->navbar();
	}

}