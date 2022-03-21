<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 20:34
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;


use WHMCS\Model\AbstractModel;

class ServerModel extends AbstractModel {
	protected $table = "mod_addon_domain_manager_server";
	protected $primaryKey = 'id';
	public $incrementing = true;
	protected $fillable = [
	];
    protected $casts = [
        'ns_list' => 'array',
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

}