<?php

namespace common\models;

class OrderLog extends Order
{

    public function behaviors()
    {
        return [];
    }

    public static function tableName()
    {
        return 'order_log';
    }
}