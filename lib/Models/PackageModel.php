<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 21:43
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;

use WHMCS\Model\AbstractModel;

class PackageModel extends AbstractModel
{
    public $incrementing = true;
    protected $table = "mod_addon_domain_manager_package";
    protected $primaryKey = 'id';
    protected $fillable = [
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function items()
    {
        return $this->hasMany('WHMCS\Module\Addon\DomainManager\Models\PackageRelative', 'package_id', 'id');
    }



    public function getServerNameAttribute(): string
    {
        return ServerModel::findOrFail($this->server_id)->name;
    }

}