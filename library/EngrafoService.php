<?php

namespace devtoolboxuk\engrafo;

use devtoolboxuk\engrafo\Adapter\AdapterFactory;

class EngrafoService
{
    protected $adapter;
    protected $adapterName;

    public function __construct($options = [])
    {
        if (isset($options['adapter'])) {
            $this->adapterName = $options['adapter'];
            $factory = AdapterFactory::instance();
            $this->adapter = $factory->getAdapter($options['adapter'], $options);
        }
    }

    public function getAdapter()
    {
        if (isset($this->adapter)) {
            return $this->adapter;
        } else {
            if ($this->adapterName) {
                throw new \RuntimeException(sprintf('The specified adapter "%s" is not configured', $this->adapterName));
            } else {
                throw new \RuntimeException('No adapter has been specified');
            }
        }
    }

}