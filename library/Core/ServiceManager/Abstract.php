<?php
class Core_ServiceManager_Abstract
{
    /**
     * @var Core_ServiceManager_Basic
     */
    protected $storage;

    /**
     * @var array
     */
    protected $keys;

    /**
     * @param Core_ServiceManager_Basic $storage
     */
    public function __construct(Core_ServiceManager_Basic $storage)
    {
        $this->storage = $storage;

        $this->keys = $storage->keys();
    }

    /**
     * Проверить наличие класса в хранилище
     *
     * @param string $name имя класса
     *
     * @return bool
     */
    protected function has($name)
    {
        return isset($this->keys[$name]) ? true:false;
    }
} 