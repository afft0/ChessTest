<?php

namespace Chess\Services\Storages;

/**
 * Interface StorageInterface
 * @package services\Storages
 */
interface StorageInterface
{
    /**
     * @return array
     */
    public function getList(): array;

    /**
     * @param $stamp
     * @return mixed
     */
    public function get($stamp);

    /**
     * @param $object
     * @return string
     */
    public function set($object): string;

    /**
     * @param $stamp
     * @return bool
     */
    public function delete($stamp): bool;
}