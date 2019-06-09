<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 22.07.2017
 * Time: 19:18
 */

namespace WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception;

class PowerDnsClientException extends \Exception {
	public $response;

	public function __construct( string $Response ) {
		$this->response = $Response;
		parent::__construct();
	}

}