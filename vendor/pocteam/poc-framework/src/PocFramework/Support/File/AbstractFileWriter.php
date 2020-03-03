<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 7/26/2017
 * Time: 4:21 PM
 */

namespace PocFramework\Support\File;


abstract class AbstractFileWriter
{
    protected function insureFileExists($file)
    {
        if (is_dir($file)) {
            throw new OutputFilenameNotSetException('file must be a file instead of a directory');
        }

        $dir = dirname($file);

        return (is_dir($dir) or mkdir($dir, '0755', true)) && touch($file);
    }
}