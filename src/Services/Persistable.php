<?php

namespace Chess\Services;


use Chess\Services\Storages\Exceptions\StorageException;
use Chess\Services\Storages\StorageInterface;

trait Persistable
{
    /**
     * @var StorageInterface
     */
    protected $innerStorage;

    public function setStorage(StorageInterface $storage)
    {
        $this->innerStorage = $storage;

        return $this;
    }

    /**
     * @return string
     */
    public function save()
    {
        return $this->innerStorage->set(static::getState());
    }

    /**
     * @param $stamp
     * @return $this
     * @throws StorageException
     */
    public function load($stamp)
    {
        $state = $this->innerStorage->get($stamp);
        if ($state !== false) {
            static::setState($state);

            return $this;
        }

        throw new StorageException("No state found to $stamp");
    }
}