<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 04.02.2020, 5:56
 *
 */
use WHMCS\Module\Addon\DomainManager\Controllers\CronController;

require __DIR__ . '/../../../init.php';

$cron = new CronController();
$cron->runTasks();