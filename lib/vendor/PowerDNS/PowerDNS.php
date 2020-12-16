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
use stdClass;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\DomainEditNotMatchDomainFromUrlException;
use WHMCS\Module\Addon\DomainManager\vendor\PowerDNS\Exception\PowerDnsClientException;


/**
 * Class PowerDNS
 * @package Api\Services\Domain
 */
class PowerDNS
{
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
     * @param $url
     * @param $key
     * @param string $server_id
     */
    function __construct(string $url, string $key, string $server_id = 'localhost')
    {
        $this->url = $url;
        $this->key = $key;
        $this->server_id = $server_id;

        $this->pdns_client = new HTTPClient([
            'base_url' => $this->url,
            'defaults' => [
                'timeout' => 2,
                'allow_redirects' => false,
                //'proxy' => '192.168.16.1:10'
                'headers' => [
                    'X-API-Key' => $this->key
                ]
            ]
        ]);
    }

    /**
     * Удалить домен
     * @param string $domain домен
     * @throws  PowerDnsClientException
     *
     */
    public function DomainDelete(string $domain)
    {
        $this->SendHttpRequest('DELETE', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));

        return;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function AddRequestOption(string $key, string $value)
    {
        $this->request_option[$key] = $value;

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
     */
    public function DomainRecordCreate(string $domain, string $name, string $type, int $ttl, array $records): void
    {
        $this->VerifiEditDomain($domain, $name);
        $this->AddRequestOption('body', $this->BildJsonRecordCreateOrEdit($name, $type, $ttl, $records));

        $this->SendHttpRequest('PATCH', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));

        return;
    }

    /**
     * @param string $domain
     * @param array $records
     * @throws PowerDnsClientException
     */
    public function DomainRecordsCreate(string $domain, array $records): void
    {
        $this->AddRequestOption('body', $this->BildJsonRecordsCreateOrEdit($records));

        $this->SendHttpRequest('PATCH', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));

        return;
    }

    /**
     * @param string $domain Домен из url
     * @param string $name полная запись которую хотят изменить
     *
     * @throws DomainEditNotMatchDomainFromUrlException Возникает в том случае если домен из URL не совпадает с доменом в записи которую необходимо изменить.
     */
    private function VerifiEditDomain(string $domain, string $name)
    {
        $re = '/(' . quotemeta($domain) . '\.)$/';
        preg_match($re, $name, $matches, PREG_OFFSET_CAPTURE, 0);

        if (empty($matches)) {
            throw new DomainEditNotMatchDomainFromUrlException($domain, $name);
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
     * @return string закодированные данные в json
     * @throws DomainEditNotMatchDomainFromUrlException
     * @throws PowerDnsClientException
     */
    public function DomainRecordDelete(string $domain, string $name, string $type, int $ttl, array $records)
    {
        $this->VerifiEditDomain($domain, $name);

        $this->AddRequestOption('body', $this->BildJsonRecordDelete($name, $type, $ttl, $records));

        $this->SendHttpRequest('PATCH', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));

        return;
    }

    public function DomainRecordsDelete(string $domain, array $records)
    {

        $this->AddRequestOption('body', $this->BildJsonRecordsDelete($records));

        $this->SendHttpRequest('PATCH', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));

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
    private function BildJsonRecordCreateOrEdit(string $name, string $type, int $ttl, array $records): string
    {
        $array['rrsets'][0]['name'] = idn_to_ascii($name);
        $array['rrsets'][0]['type'] = $type;
        $array['rrsets'][0]['ttl'] = $ttl;
        $array['rrsets'][0]['changetype'] = 'REPLACE';
        $array['rrsets'][0]['records'] = $records;
        if (strcasecmp('TXT', $type) === 0) {
            for ($i = 0; $i < count($array['rrsets'][0]['records']); $i++) {
                if (is_array($array['rrsets'][0]['records'][$i])) {
                    if ($array['rrsets'][0]['records'][$i]['content'][0] != '"') {
                        $array['rrsets'][0]['records'][$i]['content'] = '"' . $array['rrsets'][0]['records'][$i]['content'] . '"';
                    }
                } else {
                    if ($array['rrsets'][0]['records'][$i]->content[0] != '"') {
                        $array['rrsets'][0]['records'][$i]->content = '"' . $array['rrsets'][0]['records'][$i]->content . '"';
                    }
                }
            }
        }
        return json_encode($array);
    }

    private function BildJsonRecordsCreateOrEdit(array $records): string
    {
        $array['rrsets'] = $records;
        foreach ($array['rrsets'] as &$rrset) {
            $rrset['name'] = idn_to_ascii($rrset['name']);
            $rrset['changetype'] = 'REPLACE';
            if (strcasecmp('TXT', $rrset['type']) === 0) {
                for ($i = 0; $i < count($rrset['records']); $i++) {
                    if (is_array($rrset['records'][$i])) {
                        if ($rrset['records'][$i]['content'][0] != '"') {
                            $rrset['records'][$i]['content'] = '"' . $rrset['records'][$i]['content'] . '"';
                        }
                    } else {
                        if ($rrset['records'][$i]->content[0] != '"') {
                            $rrset['records'][$i]->content = '"' . $rrset['records'][$i]->content . '"';
                        }
                    }
                }
            }
        }
        return json_encode($array);
    }

    /**
     * @param string $name Полное доменное имя (включая суб домен)
     * @param string $type тип записи
     * @param int $ttl ttl записи
     * @param array $records Содержимое записи
     *
     * @return string закодированные данные в json
     */
    private function BildJsonRecordDelete(string $name, string $type, int $ttl, array $records): string
    {
        $array['rrsets'][0]['name'] = idn_to_ascii($name);
        $array['rrsets'][0]['type'] = $type;
        $array['rrsets'][0]['ttl'] = $ttl;
        $array['rrsets'][0]['changetype'] = 'DELETE';
        $array['rrsets'][0]['records'] = $records;

        return json_encode($array);
    }

    private function BildJsonRecordsDelete(array $records): string
    {
        $array['rrsets'] = $records;

        for ($i = 0; $i < count($records); $i++) {
            if ($array['rrsets'][$i]['type'] == 'SOA' ) {
                unset($array['rrsets'][$i]);
                continue;
            }
            $array['rrsets'][$i]['name'] = idn_to_ascii($array['rrsets'][$i]['name']);
            $array['rrsets'][$i]['changetype'] = 'DELETE';
        }

        $array['rrsets'] = array_values($array['rrsets']);

        return json_encode($array);
    }

    /**
     * @param string $domain Домен
     * @param string $kind Тип домена (мастер/слейв/натив)
     * @param array $nameservers Массив с нейм серверами
     *
     * @return string закодированные данные в json
     */
    private function BildJsonDomainCreate(string $domain, string $kind, array $nameservers): string
    {
        $array['name'] = idn_to_ascii($domain);
        $array['kind'] = $kind;
        $array['nameservers'] = $nameservers;

        return json_encode($array);
    }

    /**
     * @param string $domain Домен
     * @param string $kind Тип домена (мастер/слейв/натив)
     * @param array $nameservers Массив с нейм серверами
     *
     * @return stdClass
     * @throws  PowerDnsClientException
     */
    public function DomainCreate(string $domain, string $kind, array $nameservers): stdClass
    {
        $this->AddRequestOption('body', $this->BildJsonDomainCreate($domain, $kind, $nameservers));

        $Response = $this->SendHttpRequest('POST', 'servers/' . $this->server_id . '/zones');

        unset($Response->url);
        unset($Response->account);

        return $Response;
    }

    /**
     * @param string $domain доменное имя
     *
     * @return array Список записей домена
     * @throws  PowerDnsClientException
     */
    public function DomainRecordList(string $domain): array
    {
        $Response = $this->SendHttpRequest('GET', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));
        for ($i = 0; $i < count($Response->rrsets); $i++) {
            $DomainRecordList[$i]['type'] = $Response->rrsets[$i]->type;
            $DomainRecordList[$i]['name'] = idn_to_utf8($Response->rrsets[$i]->name);
            $DomainRecordList[$i]['records'] = $Response->rrsets[$i]->records;
            $DomainRecordList[$i]['ttl'] = $Response->rrsets[$i]->ttl;
            $DomainRecordList[$i]['comments'] = $Response->rrsets[$i]->comments;
            if (strcasecmp('TXT', $DomainRecordList[$i]['type']) === 0) {
                for ($j = 0; $j < count($DomainRecordList[$i]['records']); $j++) {
                    $DomainRecordList[$i]['records'][$j]->content = substr(substr($DomainRecordList[$i]['records'][$j]->content, 0, strlen($DomainRecordList[$i]['records'][$j]->content) - 1), 1);
                }
            }
        }
        return $DomainRecordList;

    }

    /**
     * @param string $domain
     *
     * @return array
     * @throws PowerDnsClientException
     */
    public function DomainRecordFormatedList(string $domain): array
    {
        $Response = $this->SendHttpRequest('GET', 'servers/' . $this->server_id . '/zones/' . idn_to_ascii($domain));

        for ($i = 0; $i < count($Response->rrsets); $i++) {
            $DomainRecordList[$Response->rrsets[$i]->type][] = [
                'name' => idn_to_utf8($Response->rrsets[$i]->name),
                'records' => (array)$Response->rrsets[$i]->records,
                'ttl' => $Response->rrsets[$i]->ttl,
                'comments' => $Response->rrsets[$i]->comments,
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
    private function RecordsContentFormated(string $Type, array $Records): array
    {
        switch ($Type) {
            case 'SRV';
                for ($i = 0; $i < count($Records); $i++) {
                    $result = explode(" ", $Records[$i]->content);
                    $formated[$i]['Priority'] = $result[0];
                    $formated[$i]['Weight'] = $result[1];
                    $formated[$i]['Port'] = $result[2];
                    $formated[$i]['Target'] = $result[3];
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'A';
                for ($i = 0; $i < count($Records); $i++) {
                    $formated[$i]['ipv4'] = $Records[$i]->content;
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'AAAA';
                for ($i = 0; $i < count($Records); $i++) {
                    $formated[$i]['ipv6'] = $Records[$i]->content;
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'CNAME';
                for ($i = 0; $i < count($Records); $i++) {
                    $formated[$i]['CanonicalName'] = $Records[$i]->content;
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'NS';
                for ($i = 0; $i < count($Records); $i++) {
                    $formated[$i]['NameServer'] = $Records[$i]->content;
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'MX';
                for ($i = 0; $i < count($Records); $i++) {
                    $result = explode(" ", $Records[$i]->content);
                    $formated[$i]['Priority'] = $result[0];
                    $formated[$i]['MailRelay'] = $result[1];
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'PTR';
                for ($i = 0; $i < count($Records); $i++) {
                    $formated[$i]['HostName'] = $Records[$i]->content;
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
                }
                break;
            case 'TXT';
                for ($i = 0; $i < count($Records); $i++) {
                    $formated[$i]['Text'] = $Records[$i]->content;
                    $formated[$i]['disabled'] = $Records[$i]->disabled;
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
    public function DomainList(): stdClass
    {
        $Response = $this->SendHttpRequest('GET', 'servers/' . $this->server_id . '/zones');

        foreach ($Response as &$item) {
            $item->id = idn_to_utf8($item->id);
            $item->name = idn_to_utf8($item->name);
            unset($item->account);
            unset($item->url);
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
    private function SendHttpRequest(string $Method, string $Url): stdClass
    {
        try {
            $res = $this->pdns_client->{strtolower($Method)}($Url, $this->request_option);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if (empty($response)) {
                throw new PowerDnsClientException($e->getMessage());
            } else {
                throw new PowerDnsClientException($response->getBody());
            }
        }

        $return = (object)json_decode($res->getBody()->getContents());

        return $return;
    }

}