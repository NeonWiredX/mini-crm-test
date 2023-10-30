<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "good".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $price
 *
 * @property Order[] $orders
 * @property-read string $clientName
 */
class Good extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'good';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['good_id' => 'id']);
    }

    public function getClientName(){
        $price = $this->price / 100;
        return "{$this->name}: $price  р.";
    }
}