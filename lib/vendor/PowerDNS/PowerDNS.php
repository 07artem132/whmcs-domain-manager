<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 11.07.2017
 * Time: 22:11
 */

namespace WHMCS\Module\Addon\DomainManager\vendor\PowerDNS;

use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Exception\RequestException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\DomainEditNotMatchDomainFromUrlException;


/**
 * Class PowerDNS
 * @package Api\Services\Domain
 */
class PowerDNS {
	/**
	 * @var string Адрес API сервера с PowerDNS
	 */
	private $url;
	/**
	 * @var string Ключ для работы с PowerDns
	 */
	private $key;
	/**
	 * @var string уникальный идентификатор сервера на PowerDNS
	 */
	private $server_id;
	/**
	 * @var HTTPClient Клиент для запросов к PowerDNS
	 */
	private $pdns_client;
	/**
	 * @var array Параметры/Заголовки которые передаются в месте с запросом ( HEADER)
	 */
	private $request_option = [];

	/**
	 * PowerDNS constructor.
	 */
	function __construct( $url, $key, $server_id = 'localhost' ) {
		$this->url       = $url;
		$this->key       = $key;
		$this->server_id = $server_id;

		$this->pdns_client = new HTTPClient( [
			// Base URI is used with relative requests
			'base_uri' => $this->url,
			// You can set any number of default request options.
			'timeout'  => 2.0,

			'headers' => [ 'X-API-Key' => $this->key ]
		] );
	}

	/**
	 * Удалить домен
	 * @throws  PowerDnsClientException
	 *
	 * @param string $domain домен
	 */
	public function DomainDelete( string $domain ) {
		$this->SendHttpRequest( 'DELETE', 'servers/' . $this->server_id . '/zones/' . $domain );

		return;
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	private function AddRequestOption( string $key, string $value ) {
		$this->request_option[ $key ] = $value;

		return;
	}

	/**
	 * @param string $domain изменяемый домен
	 * @param string $name Полное доменное имя (включая суб домен)
	 * @param string $type тип записи
	 * @param int $ttl ttl записи
	 * @param array $records Содержимое записи
	 *
	 * @throws DomainEditNotMatchDomainFromUrlException
	 * @throws PowerDnsClientException
	 * @return string закодированные данные в json
	 */
	public function DomainRecordCreate( string $domain, string $name, string $type, int $ttl, array $records ) {
		$this->VerifiEditDomain( $domain, $name );

		$this->AddRequestOption( 'body', $this->BildJsonRecordCreateOrEdit( $name, $type, $ttl, $records ) );

		$this->SendHttpRequest( 'PATCH', 'servers/' . $this->server_id . '/zones/' . $domain );

		return;
	}

	/**
	 * @param string $domain Домен из url
	 * @param string $name полная запись которую хотят изменить
	 *
	 * @throws DomainEditNotMatchDomainFromUrlException Возникает в том случае если домен из URL не совпадает с доменом в записи которую необходимо изменить.
	 */
	private function VerifiEditDomain( string $domain, string $name ) {
		$re = '/(' . quotemeta( $domain ) . '\.)$/';

		preg_match( $re, $name, $matches, PREG_OFFSET_CAPTURE, 0 );

		if ( empty( $matches ) ) {
			throw new DomainEditNotMatchDomainFromUrlException( $domain, $name );
		}

		return;
	}

	/**
	 * @param string $domain изменяемый домен
	 * @param string $name Полное доменное имя (включая суб домен)
	 * @param string $type тип записи
	 * @param int $ttl ttl записи
	 * @param array $records Содержимое записи
	 *
	 * @throws PowerDnsClientException
	 * @throws DomainEditNotMatchDomainFromUrlException
	 * @return string закодированные данные в json
	 */
	public function DomainRecordDelete( string $domain, string $name, string $type, int $ttl, array $records ) {
		$this->VerifiEditDomain( $domain, $name );

		$this->AddRequestOption( 'body', $this->BildJsonRecordDelete( $name, $type, $ttl, $records ) );

		$this->SendHttpRequest( 'PATCH', 'servers/' . $this->server_id . '/zones/' . $domain );

		return;
	}

	/**
	 * @param string $name Полное доменное имя (включая суб домен)
	 * @param string $type тип записи
	 * @param int $ttl ttl записи
	 * @param array $records Содержимое записи
	 *
	 * @return string закодированные данные в json
	 */
	private function BildJsonRecordCreateOrEdit( string $name, string $type, int $ttl, array $records ): string {
		$array['rrsets'][0]['name']       = $name;
		$array['rrsets'][0]['type']       = $type;
		$array['rrsets'][0]['ttl']        = $ttl;
		$array['rrsets'][0]['changetype'] = 'REPLACE';
		$array['rrsets'][0]['records']    = $records;

		return json_encode( $array );
	}

	/**
	 * @param string $name Полное доменное имя (включая суб домен)
	 * @param string $type тип записи
	 * @param int $ttl ttl записи
	 * @param array $records Содержимое записи
	 *
	 * @return string закодированные данные в json
	 */
	private function BildJsonRecordDelete( string $name, string $type, int $ttl, array $records ): string {
		$array['rrsets'][0]['name']       = $name;
		$array['rrsets'][0]['type']       = $type;
		$array['rrsets'][0]['ttl']        = $ttl;
		$array['rrsets'][0]['changetype'] = 'DELETE';
		$array['rrsets'][0]['records']    = $records;

		return json_encode( $array );
	}

	/**
	 * @param string $domain Домен
	 * @param string $kind Тип домена (мастер/слейв/натив)
	 * @param array $nameservers Массив с нейм серверами
	 *
	 * @return string закодированные данные в json
	 */
	private function BildJsonDomainCreate( string $domain, string $kind, array $nameservers ): string {
		$array['name']        = $domain;
		$array['kind']        = $kind;
		$array['nameservers'] = $nameservers;

		return json_encode( $array );
	}

	/**
	 * @param string $domain Домен
	 * @param string $kind Тип домена (мастер/слейв/натив)
	 * @param array $nameservers Массив с нейм серверами
	 *
	 * @throws  PowerDnsClientException
	 * @return \stdClass
	 */
	public function DomainCreate( string $domain, string $kind, array $nameservers ): \stdClass {
		$this->AddRequestOption( 'body', $this->BildJsonDomainCreate( $domain, $kind, $nameservers ) );

		$Response = $this->SendHttpRequest( 'POST', 'servers/' . $this->server_id . '/zones' );

		unset( $Response->url );
		unset( $Response->account );

		return $Response;
	}

	/**
	 * @param string $domain доменное имя
	 *
	 * @throws  PowerDnsClientException
	 * @return array Список записей домена
	 */
	public function DomainRecordList( string $domain ): array {
		$Response = $this->SendHttpRequest( 'GET', 'servers/' . $this->server_id . '/zones/' . $domain );

		for ( $i = 0; $i < count( $Response->rrsets ); $i ++ ) {
			$DomainRecordList[] = [
				'type'     => $Response->rrsets[ $i ]->type,
				'name'     => $Response->rrsets[ $i ]->name,
				'records'  => $Response->rrsets[ $i ]->records,
				'ttl'      => $Response->rrsets[ $i ]->ttl,
				'comments' => $Response->rrsets[ $i ]->comments,
			];
		}

		return $DomainRecordList;

	}

	/**
	 * @param string $domain
	 *
	 * @throws PowerDnsClientException
	 * @return array
	 */
	public function DomainRecordFormatedList( string $domain ): array {
		$Response = $this->SendHttpRequest( 'GET', 'servers/' . $this->server_id . '/zones/' . $domain );

		for ( $i = 0; $i < count( $Response->rrsets ); $i ++ ) {
			$DomainRecordList[ $Response->rrsets[ $i ]->type ][] = [
				'name'     => $Response->rrsets[ $i ]->name,
				'records'  => $this->RecordsContentFormated( (string) $Response->rrsets[ $i ]->type, (array) $Response->rrsets[ $i ]->records ),
				'ttl'      => $Response->rrsets[ $i ]->ttl,
				'comments' => $Response->rrsets[ $i ]->comments,
			];
		}

		return $DomainRecordList;
	}

	/**
	 * @param string $Type Тип записи
	 * @param array $Records Содержимое записи
	 *
	 * @return array
	 */
	private function RecordsContentFormated( string $Type, array $Records ): array {
		switch ( $Type ) {
			case 'SRV';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$result                     = explode( " ", $Records[ $i ]->content );
					$formated[ $i ]['Priority'] = $result[0];
					$formated[ $i ]['Weight']   = $result[1];
					$formated[ $i ]['Port']     = $result[2];
					$formated[ $i ]['Target']   = $result[3];
					$formated[ $i ]['disabled'] = $Records[ $i ]->disabled;
				}
				break;
			case 'A';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$formated[ $i ]['ipv4']     = $Records[ $i ]->content;
					$formated[ $i ]['disabled'] = $Records[ $i ]->disabled;
				}
				break;
			case 'AAAA';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$formated[ $i ]['ipv6']     = $Records[ $i ]->content;
					$formated[ $i ]['disabled'] = $Records[ $i ]->disabled;
				}
				break;
			case 'CNAME';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$formated[ $i ]['CanonicalName'] = $Records[ $i ]->content;
					$formated[ $i ]['disabled']      = $Records[ $i ]->disabled;
				}
				break;
			case 'NS';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$formated[ $i ]['NameServer'] = $Records[ $i ]->content;
					$formated[ $i ]['disabled']   = $Records[ $i ]->disabled;
				}
				break;
			case 'MX';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$result                      = explode( " ", $Records[ $i ]->content );
					$formated[ $i ]['Priority']  = $result[0];
					$formated[ $i ]['MailRelay'] = $result[1];
					$formated[ $i ]['disabled']  = $Records[ $i ]->disabled;
				}
				break;
			case 'PTR';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$formated[ $i ]['HostName'] = $Records[ $i ]->content;
					$formated[ $i ]['disabled'] = $Records[ $i ]->disabled;
				}
				break;
			case 'TXT';
				for ( $i = 0; $i < count( $Records ); $i ++ ) {
					$formated[ $i ]['Text']     = $Records[ $i ]->content;
					$formated[ $i ]['disabled'] = $Records[ $i ]->disabled;
				}
				break;

			default;
				$formated = $Records;
		}

		return $formated;
	}

	/**
	 * @return array
	 * @throws PowerDnsClientException
	 */
	public function DomainList(): \stdClass {
		$Response = $this->SendHttpRequest( 'GET', 'servers/' . $this->server_id . '/zones' );

		foreach ( $Response as &$item ) {
			unset( $item->account );
			unset( $item->url );
		}

		return $Response;
	}

	/**
	 * @param string $Method Метод запроса
	 * @param string $Url URL к которому необходимо выполнить запрос
	 *
	 * @return mixed Декодированный из json'a ответ
	 * @throws PowerDnsClientException
	 */
	private function SendHttpRequest( string $Method, string $Url ): \stdClass {
		try {
			$res = $this->pdns_client->request( $Method, $Url, $this->request_option );
		} catch ( RequestException $e ) {
			throw new PowerDnsClientException( $e->getResponse()->getBody( true ) );
		}

		$return = (object) json_decode( $res->getBody()->getContents() );

		return $return;
	}

}