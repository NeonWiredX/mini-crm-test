<?php
/** @var \yii\data\ActiveDataProvider $dataProvider */


use common\enums\OrderStatus;
use common\models\OrderLog;
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'v',
        'updated_at',
        [
            'attribute' => 'updated_by',
            'value' => function(OrderLog $model){
                if (!$model->updated_by){
                    return null;
                }
                return $model->updatedBy->email;
            }
        ],
        'client_name',
        'client_phone',
        [
            'attribute' => 'status',
            'value' => function (OrderLog $model) {
                return OrderStatus::getName($model->status);
            },
            'filter' => OrderStatus::getList(),
        ],
        'comment',
        [
            'attribute' => 'total_price',
            'value' => function (OrderLog $model) {
                return $model->total_price / 100;
            },
        ],
    ],
]);