<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 15:52
 */

namespace WHMCS\Module\Addon\DomainManager\Menu;

use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\View\Menu\MenuFactory;

class AdminAreaMenu extends MenuFactory
{
    protected $rootItemName = "Domain Manager nav bar";

    public function navbar()
    {
        return $this->loader->load($this->buildMenuStructure($this->getNavBarStructure()));
    }

    protected function getNavBarStructure()
    {
        $menuItems = [
            [
                "name" => "index",
                "label" => 'DashBoard',
                "uri" => ModuleConfig::getModuleLink() . "&action=index",
                "order" => 1,
                "attributes" => [
                    "class" => !array_key_exists('action', $_GET) || $_GET['action'] === 'index' ? 'active' : ''
                ]
            ],
            [
                "name" => "domain",
                "label" => 'Домены',
                "uri" => ModuleConfig::getModuleLink() . "&action=domain",
                "order" => 2,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'domain' ? 'active' : ''
                ]
            ],
            [
                "name" => "servers",
                "label" => 'Сервера',
                "uri" => ModuleConfig::getModuleLink() . "&action=servers",
                "order" => 3,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'servers' ? 'active' : ''
                ]
            ],
            [
                "name" => "package",
                "label" => 'Пакеты услугы',
                "uri" => ModuleConfig::getModuleLink() . "&action=package",
                "order" => 4,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'package' ? 'active' : ''
                ]
            ],
            [
                "name" => "blacklist",
                "label" => 'Запрещенные домены',
                "uri" => ModuleConfig::getModuleLink() . "&action=blacklist",
                "order" => 5,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'blacklist' ? 'active' : ''
                ]
            ],
            [
                "name" => "log",
                "label" => 'Лог',
                "uri" => ModuleConfig::getModuleLink() . "&action=log",
                "order" => 6,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'log' ? 'active' : ''
                ]
            ],
            [
                "name" => "backup",
                "label" => 'Резервное копирование',
                "uri" => ModuleConfig::getModuleLink() . "&action=backup",
                "order" => 6,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'backup' ? 'active' : ''
                ]
            ],
            [
                "name" => "cron",
                "label" => 'Крон',
                "uri" => ModuleConfig::getModuleLink() . "&action=cron",
                "order" => 6,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'cron' ? 'active' : ''
                ]
            ],
        ];

        return $menuItems;
    }

}


