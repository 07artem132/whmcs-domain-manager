<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 22.07.2017
 * Time: 18:50
 */

namespace WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception;

class DomainEditNotMatchDomainFromUrlException extends \Exception
{
    public $domain;
    public $name;

    /**
     * DomainEditNotMatchDomainFromUrlException constructor.
     * @param string $domain
     * @param string $name
     */
    public function __construct(string $domain, string $name)
    {
        $this->domain = $domain;
        $this->name = $name;

        parent::__construct();
    }
}