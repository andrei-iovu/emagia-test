<?php

namespace App\Business\Traits;

trait Tests
{
    /**
     * @param $object
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    private function callMethod($object, string $method , array $parameters = [])
    {
        try {
            $className = get_class($object);
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage());
        }

        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}