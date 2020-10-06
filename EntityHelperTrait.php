<?php

namespace Jackai\EntityHelper;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Contracts\Cache\ItemInterface;

trait EntityHelperTrait
{
    private $_loadedMagicTrait = false;
    private $_getter = [];
    private $_setter = [];
    private $_hasser = [];
    private $_isser = [];
    private $_toArrayColumn = [];

    /**
     * 動態呼叫方法
     *
     * @param $method
     * @param $params
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function __call($method, $params)
    {
        if (method_exists($this, $method)) {
            return;
        }

        if (!$this->_loadedMagicTrait) {
            $cache = new FilesystemAdapter('app.jackai.entity_helper');

            $magicCalls = [
                '_getter' => 'Jackai\EntityHelper\Annotations\Getter',
                '_setter' => 'Jackai\EntityHelper\Annotations\Setter',
                '_isser' => 'Jackai\EntityHelper\Annotations\Isser',
                '_hasser' => 'Jackai\EntityHelper\Annotations\Hasser',
            ];

            foreach ($magicCalls as $name => $class) {
                $cacheKey = sprintf("%s.%s", str_replace('\\', '.', get_class($this)), $name);
                $matchProperties = $cache->get($cacheKey , function (ItemInterface $item) use ($class) {
                    $annotationReader = new AnnotationReader();
                    $reflection = new \ReflectionClass($this);
                    $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);

                    $matchedProperties = [];
                    foreach ($properties as $reflectionProperty) {
                        if ($annotationReader->getPropertyAnnotation($reflectionProperty, $class) !== null) {
                            $matchedProperties[] = $reflectionProperty->name;
                        }
                    }

                    return $matchedProperties;
                });

                $this->{$name} = $matchProperties;
            }

            $this->_loadedMagicTrait = true;
        }

        $var = lcfirst(substr($method, 3));

        if (in_array($var, $this->_getter) && strncasecmp($method, "get", 3) === 0) {
            return $this->$var;
        }

        if (in_array($var, $this->_setter) && strncasecmp($method, "set", 3) === 0) {
            $this->$var = $params[0];
            return $this;
        }

        if (in_array($var, $this->_hasser) && strncasecmp($method, "has", 3) === 0) {
            return 0 !== count($this->$var);
        }

        $var = lcfirst(substr($method, 2));

        if (in_array($var, $this->_isser) && strncasecmp($method, "is", 2) === 0) {
            return (boolean)$this->$var;
        }

        throw new \Error(sprintf('Call to undefined method %s::%s()', get_class($this), $method));
    }

    /**
     * 轉陣列
     *
     * @return array
     * @throws \ReflectionException
     */
    function toArray()
    {
        if (!$this->_toArrayColumn) {
            $cache = new FilesystemAdapter('app.jackai.entity_helper');
            $cacheKey = sprintf("%s.%s", str_replace('\\', '.', get_class($this)), '_toArrayColumn');
            $this->_toArrayColumn = $cache->get($cacheKey, function (ItemInterface $item) {
                $allowColumns = [];
                $annotationReader = new AnnotationReader();
                $reflection = new \ReflectionClass($this);
                $vars = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);

                foreach ($vars as $var) {
                    if ($annotationReader->getPropertyAnnotation($var, 'Doctrine\ORM\Mapping\Column') === null) {
                        continue;
                    }

                    $name = $var->name;
                    $outputName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
                    $allowColumns[$outputName] = $name;
                }

                return $allowColumns;
            });
        }

        $ret = [];

        foreach ($this->_toArrayColumn as $outputName => $name) {
            $output = $this->{$name};

            if ($output instanceof \DateTime) {
                $output = $output->format(\DateTime::ISO8601);
            }

            $ret[$outputName] = $output;
        }

        return $ret;
    }
}
