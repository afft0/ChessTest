<?php

namespace Chess\Services\Storages;

use Chess\Services\Storages\Exceptions\StorageException;

/**
 * Class RedisStorage
 * @package services\Storages
 */
class RedisStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $host;
    /**
     * @var \Redis
     */
    protected $innerStorage;


    public function __construct($host)
    {
        $this->innerStorage = new \Redis();
        $this->innerStorage->connect($host);

    }

    public function getList(): array
    {
        return $this->innerStorage->keys('*');
    }

    public function get($stamp)
    {
        return $this->innerStorage->get($stamp);
    }

    public function set($object): string
    {
        $stamp = uniqid();
        $result = $this->innerStorage->set($stamp, $object);
        if ($result !== true) {
            throw new StorageException("Saving to storage failed");
        }

        return $stamp;
    }

    public function delete($stamp): bool
    {
        return $this->innerStorage->del($stamp) !== 1;
    }
}