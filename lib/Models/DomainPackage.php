<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 20.01.2020, 1:34
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;

use WHMCS\Database\Capsule;
use WHMCS\Domain\Domain;
use WHMCS\Model\AbstractModel;
use WHMCS\Service\Addon;
use WHMCS\Service\Service;
use WHMCS\User\Client;

class DomainPackage extends AbstractModel
{
    public $incrementing = true;
    protected $table = "mod_addon_domain_manager_domain_package";
    protected $primaryKey = 'id';
    protected $fillable = [
        'domain'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getClientNameAttribute(): string
    {
        $client = Client::findOrFail($this->client_id);
        return $client->firstname . ' ' . $client->lastname;
    }


    public function getServerIdAttribute(): string
    {
        return PackageModel::findOrFail($this->package_id)->server_id;
    }

    public function getServerNameAttribute(): string
    {
        return ServerModel::findOrFail(PackageModel::findOrFail($this->package_id)->server_id)->name;
    }

    public function getClientIdAttribute(): int
    {
        switch ($this->rel_type) {
            case 1:
                return (int)Service::findOrFail($this->rel_id)->userid;
            case 2:
                return (int) Addon::findOrFail($this->rel_id)->userid;
            case 3:
                return (int) Domain::findOrFail($this->rel_id)->userid;
        }
    }

    public function getProductNameAttribute(): string
    {
        switch ($this->rel_type) {
            case 1:
                $rel_id= Service::findOrFail($this->rel_id)->packageid;
                break;
            case 2:
                $rel_id=  Addon::findOrFail($this->rel_id)->addonid;
                break;
            case 3:
                $rel_id= Capsule::table('tbldomainpricing')->where('extension', '.' . Domain::findOrFail($this->rel_id)->tld)->first()->id;
                break;
        }
        return PackageRelative::where('rel_id',$rel_id)->where('rel_type',$this->rel_type)->firstOrFail()->full_text;
    }

    public function getServiceUrlAttribute(): string
    {
        switch ($this->rel_type) {
            case 1:
                return 'clientsservices.php?productselect=' . $this->rel_id;
            case 2:
                return 'clientsservices.php?aid=' . $this->rel_id;
            case 3:
                return 'clientsdomains.php?id=' . $this->rel_id;
        }
    }
}