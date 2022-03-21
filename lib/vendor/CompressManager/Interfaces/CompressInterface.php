<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 04.02.2020, 6:28
 *
 */

namespace WHMCS\Module\Addon\DomainManager\vendor\CompressManager\Interfaces;

use Exception;

interface CompressInterface
{
    /**
     * @param string $filename
     * @param string $mode
     * @throws Exception
     */
    public function open(string $filename, string $mode='w');

    /**
     * @param string $data
     * @param int $level
     * @return string
     */
    public function compressString(string $data, int $level = 9): string;

    /**
     * @param string $data
     * @return string
     */
    public function decompressString(string $data): string;

    /**
     * @param string $str
     * @throws Exception
     * @return mixed
     */
    public function write(string $str);

    /**
     * @return mixed
     */
    public function close();

    /**
     * @return string
     */
    public function getFileExtension(): string;

}