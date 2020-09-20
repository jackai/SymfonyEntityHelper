<?php

namespace Jackai\EntityHelper;

class EntityHelpers
{
    /**
     * 自動呼叫setter方法
     *
     * @param object $entity Entity
     * @param array $values values
     */
    public static function load(&$entity, $values)
    {
        foreach ($values as $k => $v) {
            $setterFunction = lcfirst(implode(array_map('ucfirst', explode('_', 'set' . $k))));
            $entity->{$setterFunction}($v);
        }
    }
}
