<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 18.01.2020, 14:52
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;


use WHMCS\Model\AbstractModel;

class BackupSettingsModel extends AbstractModel {
	protected $table = "mod_addon_domain_manager_backup_setting";
	protected $primaryKey = 'id';
	public $incrementing = true;
	protected $fillable = [
	];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

}