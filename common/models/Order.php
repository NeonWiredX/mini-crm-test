<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string|null $client_name
 * @property string|null $client_phone
 * @property int|null $good_id
 * @property string|null $good_raw
 * @property string|null $status
 * @property string|null $comment
 * @property int|null $total_price
 * @property int|null $v
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Good $good
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good_id', 'total_price', 'v', 'updated_by'], 'integer'],
            [['good_raw', 'created_at', 'updated_at'], 'safe'],
            [['comment'], 'string'],
            [['client_name', 'client_phone', 'status'], 'string', 'max' => 255],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Good::class, 'targetAttribute' => ['good_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_name' => 'Client Name',
            'client_phone' => 'Client Phone',
            'good_id' => 'Good ID',
            'good_raw' => 'Good Raw',
            'status' => 'Status',
            'comment' => 'Comment',
            'total_price' => 'Total Price',
            'v' => 'V',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[Good]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGood()
    {
        return $this->hasOne(Good::class, ['id' => 'good_id']);
    }
}