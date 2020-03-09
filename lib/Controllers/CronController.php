<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 04.02.2020, 5:56
 *
 */

namespace WHMCS\Module\Addon\DomainManager\Controllers;

use Throwable;
use WHMCS\Module\Addon\DomainManager\Configs\ModuleConfig;
use WHMCS\Module\Addon\DomainManager\Interfaces\TaskInterfaces;
use WHMCS\Module\Addon\DomainManager\vendor\CronExpression\CronExpression;

class CronController
{
    private $startCron;
    private $startTask;

    public function runTasks(): void
    {
        $this->recordStartCronTime();
        foreach ($this->getAllFilesTask() as $file) {
            $ClassNameFull = $this->generateFullClassNameFromFileName($file);
            /**
             * @var $task TaskInterfaces
             */
            $task = new $ClassNameFull();
            $this->printLoadTaskName($task);

            if (!$this->isDue($task->getFrequency())) {
                $this->printTaskNextRun($task);
                $this->printLine();
                continue;
            }

            $this->printRunTaskName($task);
            $this->recordStartTaskTime();
            try {
                $task->run();
            } catch (Throwable $e) {
                echo $e->getMessage() . PHP_EOL;;
                echo $e->getTraceAsString() . PHP_EOL;;
            }
            $this->printRunningTimeTask();
            $this->printLine();
        }
        $this->printRunningTimeCron();
    }

    private function recordStartCronTime(): void
    {
        $this->startCron = microtime(true);
    }

    private function getAllFilesTask(): ?array
    {
        $path = ModuleConfig::getBaseFullPath() . '/lib/Tasks';
        $files = scandir($path);
        return array_diff($files, ['..', '.']);
    }

    private function generateFullClassNameFromFileName(string $fileName): string
    {
        $ClassName = substr($fileName, 0, -4);
        $moduleName = ModuleConfig::getModuleName();
        return sprintf('WHMCS\\Module\\Addon\\%s\\Tasks\\%s', $moduleName, $ClassName);
    }

    private function printLoadTaskName(TaskInterfaces $task): void
    {
        echo sprintf('load task-> %s', $task->getName()) . PHP_EOL;
    }

    /**
     * @param string $frequency
     * @return bool
     */
    private function isDue(string $frequency): bool
    {
        $cron = CronExpression::factory($frequency);
        return $cron->isDue();
    }

    private function printTaskNextRun(TaskInterfaces $task): void
    {
        echo 'task->' . $task->getName() . ' next run->' . $this->nextRun($task->getFrequency()) . PHP_EOL;;
    }

    /**
     * @param string $frequency
     * @return string
     */
    private function nextRun(string $frequency): string
    {
        $cron = CronExpression::factory($frequency);
        return $cron->getNextRunDate()->format('Y-m-d H:i:s');
    }

    private function printLine(): void
    {
        echo '----------------' . PHP_EOL;
    }

    private function printRunTaskName(TaskInterfaces $task): void
    {
        echo sprintf('run task-> %s', $task->getName()) . PHP_EOL;
    }

    private function recordStartTaskTime(): void
    {
        $this->startTask = microtime(true);
    }

    private function printRunningTimeTask(): void
    {
        $runningTime = round(microtime(true) - $this->startTask, 4);

        echo sprintf('running time-> %s seconds', $runningTime) . PHP_EOL;
    }

    private function printRunningTimeCron(): void
    {
        $runningTime = round(microtime(true) - $this->startCron, 4);

        echo sprintf('all running time-> %s seconds', $runningTime) . PHP_EOL;

    }

}