<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 21:43
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Models;

use Throwable;
use WHMCS\Model\AbstractModel;
use WHMCS\Product\Addon;
use WHMCS\Product\Group;
use WHMCS\Product\Product;

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

    public function getRelProductAttribute(): string
    {
        try {
            switch ($this->rel_type) {
                case 1:
                    $product = Product::findOrFail($this->rel_id);
                    return Group::find($product->gid)->name . '\\' . $product->name;
                case 2:
                    return 'Дополнение\\' . Addon::findOrFail($this->rel_id)->name;
                default:
                    return 'unknown  type';
            }
        } catch (Throwable $e) {
            // dd($this->rel_type);
            return 'Вероятно удален продукт';
        }
    }

    public function setRelProductAttribute($value)
    {

        if (strpos($value, 'a') !== false) {
            $this->attributes['rel_id'] = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            $this->attributes['rel_type'] = 2;
        } else {
            $this->attributes['rel_id'] = $value;
            $this->attributes['rel_type'] = 1;
        }
    }

    public function getServerNameAttribute(): string
    {
        return ServerModel::findOrFail($this->server_id)->name;
    }

}