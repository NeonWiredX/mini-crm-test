<?php

namespace common\enums;

class Role
{
    public const MANAGER = 'manager';
    public const ADMIN = 'admin';


    public static function getList(): array
    {
        return [
            static::MANAGER => 'Менеджер',
            static::ADMIN => 'Админ',
        ];
    }

    public static function getName($value, $default = 'N\A')
    {
        return static::getList()[$value] ?? $default;
    }
}