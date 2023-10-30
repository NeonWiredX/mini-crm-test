<?php

namespace common\enums;

class OrderStatus
{
    public const NEW = 'new';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const DEFECT = 'defect';

    public static function getList(): array
    {
        return [
            static::NEW => 'Новая',
            static::APPROVED => 'Принята',
            static::REJECTED => 'Отказана',
            static::DEFECT => 'Брак',
        ];
    }

    public static function getName($value, $default = 'N\A')
    {
        return static::getList()[$value] ?? $default;
    }
}