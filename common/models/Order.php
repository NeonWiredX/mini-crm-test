<?php

namespace common\models;

use common\components\PhoneValidator;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
 * @property User $updatedBy
 * @property-read float $prettyPrice
 */
class Order extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('now()')
            ]
        ];
    }

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
            ['client_phone', PhoneValidator::class],
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
            'good_id' => 'Товар',
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

    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getPrettyPrice(){
        return $this->total_price / 100;
    }


    public function beforeSave($insert)
    {
        if ($this->good_id) {
            $this->total_price = $this->good->price;
        }
        if ($this->getDirtyAttributes()) {
            Yii::$app->user && $this->updated_by = Yii::$app->user->id;
            $this->hasAttribute('v') && $this->setAttribute('v', (int)$this->attributes['v'] + 1);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($changedAttributes) {
            $this->log();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    protected function log()
    {
        $this->refresh();
        $attrs = $this->attributes;
        $attrs['good_raw'] = json_encode($attrs['good_raw']); //TODO: не до конца победил

        Yii::$app->db->createCommand()->insert(OrderLog::tableName(), $attrs)->execute();
    }
}