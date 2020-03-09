<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 23:20
 *
 */


namespace WHMCS\Module\Addon\DomainManager\Pages;

use Throwable;
use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\DomainManager\Controllers\LogController;
use WHMCS\Module\Addon\DomainManager\Interfaces\PageInterface;
use WHMCS\Module\Addon\DomainManager\Models\PackageModel;
use WHMCS\Module\Addon\DomainManager\Models\PackageRelative;
use WHMCS\Module\Addon\DomainManager\Models\ServerModel;
use WHMCS\Module\Addon\DomainManager\Traits\IsRequestMethodTraits;
use WHMCS\Product\Addon;
use WHMCS\Product\Product;
use WHMCS\View\Menu\MenuFactory;

class AdminEditPackagePage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_edit_package.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            try {
                Capsule::beginTransaction();
                $package = PackageModel::findOrFail($_GET['id']);
                $package->title = $_POST['package_name'];
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
                PackageRelative::where('package_id', $package->id)->delete();
                foreach ($_POST['rel_id'] as $item) {
                    if (strpos($item, 'a') !== false) {
                        $rel_id = (int)filter_var($item, FILTER_SANITIZE_NUMBER_INT);
                        $rel_type = 2;
                    } elseif (strpos($item, 'd') !== false) {
                        $rel_id = (int)filter_var($item, FILTER_SANITIZE_NUMBER_INT);
                        $rel_type = 3;
                    } else {
                        $rel_id = $item;
                        $rel_type = 1;
                    }

                    $packageRelative = new PackageRelative();
                    $packageRelative->package_id = $package->id;
                    $packageRelative->rel_id = $rel_id;
                    $packageRelative->rel_type = $rel_type;
                    $packageRelative->saveOrFail();
                }
                Capsule::commit();
                LogController::addSuccess(
                    __CLASS__,
                    'Изменение пакета, adminid->' . $_SESSION['adminid'] . ', package_id->' . $_GET['id']
                );
                redir('module=DomainManager&action=package', 'addonmodules.php');
            } catch (Throwable $e) {
                Capsule::rollBack();
                LogController::addError(__CLASS__, 'редактирование пакета с ошибкой, adminid->' . $_SESSION['adminid'], $e);
                $this->vars['message'] = 'Возникла ошибка, детали в логе..';
                $this->vars['return_to'] = 'addonmodules.php?module=DomainManager&action=package';
                $this->templateName = 'admin_custom_error.tpl';
                return;
            }
        }
        $package = PackageModel::with('items')->findOrFail($_GET['id']);
        $this->vars['package'] = $package->toArray();

        $items = $package->getRelation('items')->keyBy(function ($item) {
            return $item->rel_type . ':' . $item->rel_id;
        });

        $packageRelativeNotAllow = PackageRelative::where('package_id', '!=', $_GET['id'])->get()->keyBy(function ($item) {
            return $item->rel_type . ':' . $item->rel_id;
        });

        $this->vars['associateList'] = collect([
            'product' => Product::join('tblproductgroups', 'tblproducts.gid', '=', 'tblproductgroups.id')
                ->orderBy('tblproductgroups.order', 'ASC')
                ->orderBy('tblproducts.order', 'ASC')
                ->orderBy('tblproducts.name', 'ASC')
                ->select('tblproducts.gid', 'tblproducts.id', 'tblproductgroups.name AS groupname', 'tblproducts.name AS productname')
                ->get()->groupBy('gid')->flatten()->transform(function ($item, $key) use ($items, $packageRelativeNotAllow) {

                    return [
                        'id' => $item->id,
                        'groupname' => $item->groupname,
                        'text' => $item->productname,
                        'selected' => $items->has('1:' . $item->id),
                        'exits' => $packageRelativeNotAllow->has('1:' . $item->id)
                    ];
                })->reject(function ($item, $key) {
                    return $item['exits'];
                })->groupBy('groupname')
        ])->merge([
            'addon' => Addon::all()->transform(function ($item, $key) use ($items, $packageRelativeNotAllow) {
                return [
                    'id' => 'a' . $item->id,
                    'groupname' => 'Дополнение',
                    'text' => $item->name,
                    'selected' => $items->has('2:' . $item->id),
                    'exits' => $packageRelativeNotAllow->has('2:' . $item->id)
                ];
            })->reject(function ($item, $key) {
                return $item['exits'];
            })->groupBy('groupname')
        ])->merge([
            'domain' => collect(Capsule::table('tbldomainpricing')->get())->transform(function ($item, $key) use ($items, $packageRelativeNotAllow) {
                return [
                    'id' => 'd' . $item->id,
                    'groupname' => 'Домены',
                    'text' => $item->extension,
                    'selected' => $items->has('3:' . $item->id),
                    'exits' => $packageRelativeNotAllow->has('3:' . $item->id)
                ];
            })->reject(function ($item, $key) {
                return $item['exits'];
            })->groupBy('groupname')
        ])->reject(function ($item, $key) {
            return $item->isEmpty();
        })->toArray();
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
            'Редактирование пакета услуг' => '',
        ];
    }
}