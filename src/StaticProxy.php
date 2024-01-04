<?php

namespace StaticProxy;

/**
 * 静的メソッド呼び出しを動的メソッド呼び出しに変換する
 * 
 * @package StaticProxy
 */
abstract class StaticProxy
{
    /**
     * インスタンスの実クラス名を取得する
     * 
     * @return string
     */
    abstract public static function getRealInstanceName(): string;

    /**
     * クラスメソッドを静的関数として呼び出す
     * 
     * @param string $method
     * @param array<int, mixed> $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        $instanceName = static::getRealInstanceName();

        if (!class_exists($instanceName)) throw new \RuntimeException("Class {$instanceName} does not exist");

        $instance = new $instanceName();

        if (!method_exists($instance, $method)) throw new \RuntimeException("Method {$method} does not exist on class {$instanceName}");

        $staticCallableMethods = static::staticCallableMethods();

        if (!empty($staticCallableMethods) && !in_array($method, $staticCallableMethods)) throw new \RuntimeException("Method {$method} is not static callable");

        return $instance->$method(...$parameters);
    }

    /**
     * 静的関数として呼び出すことができるメソッド一覧を取得する
     * 
     * @return array<int, string>
     */
    protected static function staticCallableMethods(): array
    {
        return [];
    }
}
