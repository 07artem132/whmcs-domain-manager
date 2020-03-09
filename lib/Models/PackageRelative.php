<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 26.02.2020, 21:09
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;

use Exception;
use Throwable;
use WHMCS\Database\Capsule;
use WHMCS\Model\AbstractModel;
use WHMCS\Product\Addon;
use WHMCS\Product\Group;
use WHMCS\Product\Product;

class PackageRelative extends AbstractModel
{
    public $incrementing = true;
    protected $table = "mod_addon_domain_manager_package_relative";
    protected $primaryKey = 'id';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getFullTextAttribute(): string
    {
        try {
            switch ($this->rel_type) {
                case 1:
                    $product = Product::findOrFail($this->rel_id);
                    return Group::find($product->gid)->name . '\\' . $product->name;
                case 2:
                    return 'Дополнение\\' . Addon::findOrFail($this->rel_id)->name;
                case 3:
                    $domain = Capsule::table('tbldomainpricing')->where('id', $this->rel_id)->first();
                    if (empty($domain)) {
                        throw new Exception('Вероятно удален домен');
                    }
                    return 'Домен\\' . $domain->extension;
                default:
                    return 'unknown  type';
            }
        } catch (Throwable $e) {
            return 'Вероятно удален продукт';
        }
    }
}