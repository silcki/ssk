<?php
class Core_ServiceManager_Basic extends \Pimple
{
    private $_config;

    public function __construct()
    {
        parent::__construct();

        if (is_file(CONFIG_PATH . '/models.php')) {
            $this->_config['models'] = require_once CONFIG_PATH . '/models.php';
        }

        if (is_file(CONFIG_PATH . '/helpers.php')) {
            $this->_config['helpers'] = require_once CONFIG_PATH . '/helpers.php';
        }

        $this->register();
    }

    private function register()
    {
        if (!empty($this->_config['models'])) {
            foreach ($this->_config['models'] as $model) {
                $name = 'models_' . strtolower($model);
                $this[$name] = function () use ($model){
                    return new $model();
                };
            }
        }

        if (!empty($this->_config['helpers'])) {
            foreach ($this->_config['helpers'] as $helper) {
                $name = 'helpers_' . strtolower($helper);
                $this[$name] = function () use ($helper) {
                    return new $helper();
                };
            }
        }

        $this['core.models'] = function ($this) {
            return new Core_ServiceManager_Models($this);
        };

        $this['core.helpres'] = function ($this) {
            return new Core_ServiceManager_Helpers($this);
        };
    }
} 