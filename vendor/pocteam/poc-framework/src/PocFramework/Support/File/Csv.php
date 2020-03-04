<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 7/26/2017
 * Time: 4:18 PM
 */

namespace PocFramework\Support\File;


class Csv extends AbstractFileWriter implements FileInterface
{
    protected $outputFilename;

    /**
     * @var resource
     */
    protected $fp;

    public function __construct($filename)
    {
        $this->outputFilename = $filename;
        $r = $this->insureFileExists($filename);
        if ($r === false) {
            throw new CanNotOpenFileException('file ' . $filename . ' can not be open');
        }

        $this->fp = fopen($this->outputFilename, 'rwb+');
    }

    /**
     * Write one line
     *
     * @param mixed $record
     */
    public function write($record)
    {
        $line = iconv('UTF-8', 'GBK//IGNORE', implode(',', $record)) . "\n";

        $this->doWrite($line);
    }

    /**
     * Write lines
     *
     * @param array $records
     */
    public function writeBatch(array $records)
    {
        foreach ($records as $record) {
            $this->write($record);
        }
    }

    protected  function doWrite($line)
    {
        if (null === $this->outputFilename) {
            throw new OutputFilenameNotSetException('No output filename is set to write to');
        }

        fwrite($this->fp, $line);
    }

    public function __destruct()
    {
        fclose($this->fp);
    }
}