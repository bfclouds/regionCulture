<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 7/26/2017
 * Time: 4:19 PM
 */

namespace PocFramework\Support\File;


interface FileInterface
{
    /**
     * Write one line
     *
     * @param mixed $record
     */
    public function write($record);

    /**
     * Write lines
     *
     * @param array $records
     */
    public function writeBatch(array $records);
}