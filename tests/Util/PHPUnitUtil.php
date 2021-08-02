<?php
declare(strict_types=1);

namespace Tests\Util;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

trait PHPUnitUtil {

    public static function callMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}