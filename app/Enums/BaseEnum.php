<?php


namespace App\Enums;

abstract class BaseEnum
{
    public static function all()
    {
        $constants = (new \ReflectionClass(static::class))->getConstants();

        return array_values($constants);
    }

    public static function validate($value)
    {
        return in_array($value, self::all());
    }
}
