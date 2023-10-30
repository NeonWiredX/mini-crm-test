<?php

namespace frontend\models;

use common\components\PhoneValidator;
use common\enums\OrderStatus;
use common\models\Good;
use common\models\Order;
use yii\base\Model;

class MakeOrderForm extends Model
{
    public $client_name;
    public $client_phone;
    public $comment;
    public $good_id;
    public $verifyCode;

    public function rules()
    {
        return [
            [['client_name', 'client_phone', 'good_id', 'verifyCode'], 'required'],
            ['comment', 'string'],
            ['client_phone', PhoneValidator::class],
//            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'client_name' => 'Имя',
            'client_phone' => 'Телефон',
            'comment' => 'Комментарий',
            'good_id' => 'Товар',
            'verifyCode' => 'Капча',
        ];
    }

    public function save(): bool
    {
        $goodRaw = $this->getGoodRaw();

        $newOrder = new Order([
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'good_id' => $this->good_id,
            'good_raw' => $goodRaw,
            'comment' => $this->comment,
            'status' => OrderStatus::NEW,
            'total_price' => $goodRaw['price'] ?? 0,
        ]);

        return $newOrder->save();
    }

    protected function getGoodRaw(){
        if (!$this->good_id || !($good = Good::findOne($this->good_id))){
            return [];
        }

        return [
            'id' => $good->id,
            'name' => $good->name,
            'price' => $good->price
        ];
    }
}