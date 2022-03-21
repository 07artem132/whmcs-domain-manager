<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 04.02.2020, 6:26
 *
 */

namespace WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Abstracts;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Interfaces\CompressInterface;

abstract class CompressManagerFactoryAbstract
{
    /**
     * @param string $c
     * @return CompressInterface
     * @throws  \Exception
     */
    public static function create($c): CompressInterface
    {
        $c = ucfirst(strtolower($c));
        if (!CompressMethodAbstract::isValid($c)) {
            throw new \Exception("Compression method ($c) is not defined yet");
        }

        $method = "WHMCS\\Module\\Addon\\" . ModuleConfig::getModuleName() . "\\vendor\\CompressManager\\Compress" . $c . 'Controller';

        return new $method;
    }
}