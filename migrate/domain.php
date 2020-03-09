<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 26.02.2020, 2:45
 *
 */


require_once '../../../../init.php';

use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\DomainManager\Models\DomainPackage;

$package_domain = 10;
$package_service = 14;
$package_addon = 10;
$db_create = true;

$colors = new Colors();

foreach (DB::table('dns_manager2_zone')->get() as $item) {
    if ($item->type === 1) {
        ConsoleNewLine('Тип-> домен');
        $domain = DB::table('tbldomains')->find($item->relid);
        if (empty($domain)) {
            ConsoleNewLine('  Подлежит переносу-> Нет');
            $colors->printError('Внимание!!');
            continue;
        }
        ConsoleNewLine('  id->' . $item->relid);
        ConsoleNewLine('  Домен->' . $item->name);
        if ($domain->status !== 'Active') {
            ConsoleNewLine('  Подлежит переносу-> Нет');
            $colors->printError('Внимание!!');
            continue;
        }
        ConsoleNewLine('  Подлежит переносу-> да');
        ConsoleNewLine(sprintf('  insert: domain->%s rel_id->%s rel_type->3 package_id->%s', $item->name, $item->relid, $package_domain));
        if ($db_create) {
            $domainPackage = DomainPackage::firstOrNew(['domain' => $item->name]);
            $domainPackage->rel_id = $item->relid;
            $domainPackage->rel_type = 3;
            $domainPackage->package_id = $package_domain;
            $domainPackage->saveOrFail();
            ConsoleNewLine('  db inserted');
        }
        ConsoleLineHR();
    } elseif ($item->type === 2) {
        ConsoleNewLine('Тип-> услуга');
        $service = DB::table('tblhosting')->find($item->relid);
        if (empty($service)) {
            ConsoleNewLine('  Подлежит переносу-> Нет');
            $colors->printError('Внимание!!');
            continue;
        }
        ConsoleNewLine('  id->' . $item->relid);
        ConsoleNewLine('  Домен->' . $item->name);
        if ($service->domainstatus !== 'Active') {
            ConsoleNewLine('  Подлежит переносу-> Нет');
            $colors->printError('Внимание!!');
            continue;
        }
        ConsoleNewLine('  Подлежит переносу-> да');
        ConsoleNewLine(sprintf('  insert: domain->%s rel_id->%s rel_type->1 package_id->%s', $item->name, $item->relid, $package_service));
        if ($db_create) {
            $domainPackage = DomainPackage::firstOrNew(['domain' => $item->name]);
            $domainPackage->rel_id = $item->relid;
            $domainPackage->rel_type = 1;
            $domainPackage->package_id = $package_service;
            $domainPackage->saveOrFail();
            ConsoleNewLine('  db inserted');
        }
        ConsoleLineHR();
    } else {
        ConsoleNewLine('Тип-> дополнение');
        $addon = DB::table('tblhostingaddons')->find($item->relid);
        if (empty($addon)) {
            ConsoleNewLine('  Подлежит переносу-> Нет');
            $colors->printError('Внимание!!');
            continue;
        }
        ConsoleNewLine('  id->' . $item->relid);
        ConsoleNewLine('  Домен->' . $item->name);
        if ($addon->status !== 'Active') {
            ConsoleNewLine('  Подлежит переносу-> Нет');
            $colors->printError('Внимание!!');
            continue;
        }
        ConsoleNewLine('  Подлежит переносу-> да');
        ConsoleNewLine(sprintf('  insert: domain->%s rel_id->%s rel_type->2 package_id->%s', $item->name, $item->relid, $package_addon));
        if ($db_create) {
            $domainPackage = DomainPackage::firstOrNew(['domain' => $item->name]);
            $domainPackage->rel_id = $item->relid;
            $domainPackage->rel_type = 2;
            $domainPackage->package_id = $package_addon;
            $domainPackage->saveOrFail();
            ConsoleNewLine('  db inserted');
        }
        ConsoleLineHR();
    }
}

function ConsoleNewLine($string)
{
    echo $string . PHP_EOL;
}

function ConsoleLineHR()
{
    echo PHP_EOL . '--------' . PHP_EOL;
}

function ConsoleLine($string)
{
    echo $string . ' ';
}

class Colors
{
    private $foreground_colors = array();
    private $background_colors = array();

    public function __construct()
    {
        // Set up shell colors
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';
    }

    public function printError($message)
    {
        echo $this->getColoredString($message, null, "red") . PHP_EOL;
    }

    // Returns colored string
    public function getColoredString($string, $foreground_color = null, $background_color = null)
    {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }

    // Returns all foreground color names
    public function getForegroundColors()
    {
        return array_keys($this->foreground_colors);
    }

    // Returns all background color names
    public function getBackgroundColors()
    {
        return array_keys($this->background_colors);
    }
}
