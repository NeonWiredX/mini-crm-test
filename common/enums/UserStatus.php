<?php

namespace common\enums;

class UserStatus
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    public static function getList(): array
    {
        return [
            static::STATUS_DELETED => 'Удален',
            static::STATUS_INACTIVE => 'Не активен',
            static::STATUS_ACTIVE => 'Активен',
        ];
    }

    public static function getName($value, $default = 'N\A')
    {
        return static::getList()[$value] ?? $default;
    }
}