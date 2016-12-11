<?php

namespace Chess\Services\Storages;

use Chess\Services\Storages\Exceptions\StorageException;

/**
 * Class FileStorage
 * @package services\Storages
 */
class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $storageFileName;

    /**
     * @var bool|resource
     */
    protected $innerStorage = false;

    /**
     * FileStorage constructor.
     * @param $fileName
     * @throws StorageException
     */
    public function __construct($fileName)
    {
        $this->innerStorage = fopen($fileName, 'a+');

        if ($this->innerStorage == false) {
            throw new StorageException("Saving to storage failed");
        }
        $this->storageFileName = $fileName;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $list = [];
        while (($buffer = fgets($this->innerStorage)) !== false) {
            $list[] = explode(' ', $buffer)[0];
        }

        return $list;
    }

    /**
     * @param $stamp
     * @return object|false
     */
    public function get($stamp)
    {
        fseek($this->innerStorage, 0);
        while (($buffer = fgets($this->innerStorage)) !== false) {
            if (strpos($buffer, $stamp) === 0) {
                return unserialize(trim(explode(' ', $buffer)[1]));
            }
        }

        return false;
    }

    /**
     * @param $object
     * @return string
     * @throws \Exception
     */
    public function set($object): string
    {
        $object = serialize($object);
        $stamp = uniqid();

        if (fwrite($this->innerStorage, "$stamp $object\n") === false) {
            throw new StorageException("Saving to storage failed");
        }

        return $stamp;
    }

    /**
     * @param $stamp
     * @return bool
     * @throws StorageException
     */
    public function delete($stamp): bool
    {
        exec("sed -i -- '/^{$stamp}.*$/d' {$this->innerStorage}", $output, $return);

        if ($return === false) {
            throw new StorageException("Delete from storage failed");
        }

        return $return;
    }

    public function __destruct()
    {
        fclose($this->innerStorage);
    }
}