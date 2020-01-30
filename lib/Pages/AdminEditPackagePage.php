<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 23:20
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

class AdminEditPackagePage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_edit_package.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            $package = PackageModel::findOrFail($_GET['id']);
            $package->title = $_POST['package_name'];
            $package->rel_product = $_POST['rel_id'];
            $package->domain_zone_filter = $_POST['zone_filter'];
            $package->server_id = $_POST['server'];
            if ($package->domain_zone_limit == $_POST['zone_limit'] || $package->domain_zone_limit !== -1 && $package->domain_zone_limit > $_POST['zone_limit']) {
                $package->domain_zone_limit = $_POST['zone_limit'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'Лимит зон');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->TXT == $_POST['TXT'] || $package->TXT !== -1 && $package->TXT > $_POST['TXT']) {
                $package->TXT = $_POST['TXT'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'TXT');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->NS == $_POST['NS'] || $package->NS !== -1 && $package->NS > $_POST['NS']) {
                $package->NS = $_POST['NS'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'NS');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->DNAME == $_POST['DNAME'] || $package->DNAME !== -1 && $package->DNAME > $_POST['DNAME']) {
                $package->DNAME = $_POST['DNAME'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'DNAME');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->SRV == $_POST['SRV'] || $package->SRV !== -1 && $package->SRV > $_POST['SRV']) {
                $package->SRV = $_POST['SRV'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'SRV');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->MX == $_POST['MX'] || $package->MX !== -1 && $package->MX > $_POST['MX']) {
                $package->MX = $_POST['MX'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'MX');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->CNAME == $_POST['CNAME'] || $package->CNAME !== -1 && $package->CNAME > $_POST['CNAME']) {
                $package->CNAME = $_POST['CNAME'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'CNAME');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->PTR == $_POST['PTR'] || $package->PTR !== -1 && $package->PTR > $_POST['PTR']) {
                $package->PTR = $_POST['PTR'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'PTR');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->DS == $_POST['DS'] || $package->DS !== -1 && $package->DS > $_POST['DS']) {
                $package->DS = $_POST['DS'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'DS');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->AAAA == $_POST['AAAA'] || $package->AAAA !== -1 && $package->AAAA > $_POST['AAAA']) {
                $package->AAAA = $_POST['AAAA'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'AAAA');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->CAA == $_POST['CAA'] || $package->CAA !== -1 && $package->CAA > $_POST['CAA']) {
                $package->CAA = $_POST['CAA'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'CAA');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->A == $_POST['A'] || $package->A !== -1 && $package->A > $_POST['A']) {
                $package->A = $_POST['A'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'A');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            if ($package->record_total_limit == $_POST['record_limit'] || $package->record_total_limit !== -1 && $package->record_total_limit > $_POST['record_limit']) {
                $package->record_total_limit = $_POST['record_limit'];
            } else {
                $error = sprintf('Значение поля %s не может быть изменено в меньшую сторону в виду особенностей работы модуля', 'Лимит записей');
                redir('module=DomainManager&action=edit_package&id=' . $_GET['id'] . '&error=' . $error, 'addonmodules.php');
            }
            $package->saveOrFail();
            redir('module=DomainManager&action=package', 'addonmodules.php');
        }
        $package = PackageModel::findOrFail($_GET['id']);
        $this->vars['package'] = $package->toArray();
        $this->vars['package']['rel_id'] = $this->vars['package']['rel_type'] == 2 ? 'a' . $this->vars['package']['rel_id'] : $this->vars['package']['rel_id'];

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
        $this->vars['servers'] = $servers = ServerModel::all();
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