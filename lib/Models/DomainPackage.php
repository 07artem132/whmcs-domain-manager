<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 20.01.2020, 1:34
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;

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

    public function getClientIdAttribute(): string
    {
        $package = PackageModel::findOrFail($this->package_id);
        switch ($package->rel_type) {
            case 1:
                return Service::findOrFail($this->rel_id)->userid;
            case 2:
                return Addon::findOrFail($this->rel_id)->userid;
        }
    }

    public function getProductNameAttribute(): string
    {
        return PackageModel::findOrFail($this->package_id)->rel_product;
    }

    public function getServiceUrlAttribute(): string
    {
        $package = PackageModel::findOrFail($this->package_id);
        switch ($package->rel_type) {
            case 1:
                return 'clientsservices.php?productselect=' . $this->rel_id;
            case 2:
                return 'clientsservices.php?aid=' . $this->rel_id;
        }
    }
}