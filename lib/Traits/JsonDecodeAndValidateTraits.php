<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 24.04.2019
 * Time: 15:51
 */

namespace WHMCS\Module\Addon\DomainManager\Traits;

use WHMCS\Module\Addon\ServiceNotice\Exceptions\InvalidJsonException;
use WHMCS\Module\Addon\ServiceNotice\Controllers\LanguageController;

trait JsonDecodeAndValidateTraits {
	/**
	 * @param string $string
	 * @param bool $assoc
	 *
	 * @return mixed
	 * @throws InvalidJsonException
	 */
	protected static function jsonDecodeAndValidate( $string, $assoc = false ) {
		// decode the JSON data
		$result = json_decode( $string, $assoc );

		// switch and check possible JSON errors
		switch ( json_last_error() ) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$error = LanguageController::trans('JSON_ERROR_DEPTH');
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = LanguageController::trans('JSON_ERROR_STATE_MISMATCH');
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = LanguageController::trans('JSON_ERROR_CTRL_CHAR');
				break;
			case JSON_ERROR_SYNTAX:
				$error = LanguageController::trans('JSON_ERROR_SYNTAX');
				break;
			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = LanguageController::trans('JSON_ERROR_UTF8');
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = LanguageController::trans('JSON_ERROR_RECURSION');
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = LanguageController::trans('JSON_ERROR_INF_OR_NAN');
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = LanguageController::trans('JSON_ERROR_UNSUPPORTED_TYPE');
				break;
			default:
				$error = LanguageController::trans('JsonUnknownError');
				break;
		}

		if ( $error !== '' ) {
			throw new InvalidJsonException( $error );
		}

		// is OK
		return $result;
	}
}