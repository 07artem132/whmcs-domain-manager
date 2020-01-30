<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 20.01.2020, 3:03
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;


use WHMCS\Model\AbstractModel;

class LogModel extends AbstractModel
{
    public $incrementing = true;
    protected $table = "mod_addon_domain_manager_log";
    protected $primaryKey = 'id';
    protected $fillable = [
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

}