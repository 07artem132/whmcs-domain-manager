<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.06.2017
 * Time: 18:13
 */

namespace WHMCS\Module\Addon\DomainManager\Exceptions;

use Exception;

/**
 * Class InvalidJSON
 * @package Api\Exceptions
 */
class InvalidJsonException extends Exception
{
    /**
     * InvalidJsonException constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);

    }
}