<?php

namespace devtoolboxuk\engrafo\Adapter;

class AdapterFactory
{
    protected static $instance;

    protected $adapters = [
        'xml' => 'devtoolboxuk\engrafo\Adapter\XmlAdapter',
        'json' => 'devtoolboxuk\engrafo\Adapter\JsonAdapter',
        'csv' => 'devtoolboxuk\engrafo\Adapter\CsvAdapter',
        'text' => 'devtoolboxuk\engrafo\Adapter\TextAdapter',
        'txt' => 'devtoolboxuk\engrafo\Adapter\TextAdapter',
    ];

    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function registerAdapter($name, $class)
    {
        if (!is_subclass_of($class, 'devtoolboxuk\engrafo\AdapterInterface')) {
            throw new \RuntimeException(sprintf(
                'Adapter class "%s" must implement devtoolboxuk\\engrafo\\Adapter\\AdapterInterface',
                $class
            ));
        }
        $this->adapters[$name] = $class;

        return $this;
    }

    public function getAdapter($name, array $options)
    {
        $class = $this->getClass($name);
        return new $class($options);
    }

    /**
     * Get an adapter class by name.
     *
     * @param string $name
     * @return string
     * @throws \RuntimeException
     */
    protected function getClass($name)
    {
        if (empty($this->adapters[$name])) {
            throw new \RuntimeException(sprintf(
                'Adapter "%s" has not been registered',
                $name
            ));
        }

        return $this->adapters[$name];
    }
}
