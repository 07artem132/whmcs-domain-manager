<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 16.01.2020, 20:05
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Pages;

use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Product\Addon;
use WHMCS\Product\Product;
use WHMCS\View\Menu\MenuFactory;

class AdminAddPackagePage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_add_package.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            $package = new PackageModel();
            $package->title = $_POST['package_name'];
            $package->rel_product = $_POST['rel_id'];
            $package->domain_zone_filter = $_POST['zone_filter'];
            $package->server_id = $_POST['server'];
            $package->domain_zone_limit = $_POST['zone_limit'];
            $package->TXT = $_POST['TXT'];
            $package->NS = $_POST['NS'];
            $package->DNAME = $_POST['DNAME'];
            $package->SRV = $_POST['SRV'];
            $package->MX = $_POST['MX'];
            $package->CNAME = $_POST['CNAME'];
            $package->PTR = $_POST['PTR'];
            $package->DS = $_POST['DS'];
            $package->AAAA = $_POST['AAAA'];
            $package->CAA = $_POST['CAA'];
            $package->A = $_POST['A'];
            $package->record_total_limit = $_POST['record_limit'];
            $package->saveOrFail();
            redir('module=DomainManager&action=package', 'addonmodules.php');
        }

        $this->vars['associateList'] = Product::join('tblproductgroups', 'tblproducts.gid', '=', 'tblproductgroups.id')
            ->orderBy('tblproductgroups.order', 'ASC')
            ->orderBy('tblproducts.order', 'ASC')
            ->orderBy('tblproducts.name', 'ASC')
            ->select('tblproducts.gid', 'tblproducts.id', 'tblproductgroups.name AS groupname', 'tblproducts.name AS productname')
            ->get()->groupBy('gid')->flatten()->transform(function ($item, $key) {
                return [
                    'text' => $item->groupname . '\\' . $item->productname,
                    'id' => $item->id,
                ];
            })->merge(Addon::all()->transform(function ($item, $key) {
                return [
                    'id' => 'a' . $item->id,
                    'text' => 'Дополнение\\' . $item->name
                ];
            }))->toArray();
        $this->vars['servers'] = ServerModel::where('status', 1)->get();
    }

    /**
     * @return string
     */
    function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @return array
     */
    function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return MenuFactory|null
     */
    function getSubMenu(): ?MenuFactory
    {
        return null;
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array
    {
        return [
            'Главная' => 'addonmodules.php?module=DomainManager',
            'Пакеты услуг' => 'addonmodules.php?module=DomainManager&action=package',
            'Добавление пакета услуг' => '',

        ];
    }
}