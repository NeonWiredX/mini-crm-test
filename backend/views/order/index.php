<?php

use common\enums\OrderStatus;
use common\models\Order;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Modal::widget([
    'size' => Modal::SIZE_EXTRA_LARGE,
    'title' => "<h3>История изменения статусов заявки</h3>",
    'options' => ['id' => 'history-modal'],
]) ?>

    <div class="order-index">


        <h1><?= Html::encode($this->title) ?></h1>

        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= Html::a(
            'Выгрузить в csv',
            ['', 'export' => 'csv'] + Yii::$app->request->queryParams,
            [
                'class' => 'btn btn-success',
                'target' => '_blank',
                'data-pjax' => 0,
            ]
        ) ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                'client_name',
                'client_phone',
                [
                    'attribute' => 'good_id',
                    'value' => function (Order $model) {
                        if (!$model->good) {
                            return null;
                        }
                        return $model->good->clientName;
                    },
                    'filter' => ArrayHelper::map(\common\models\Good::find()->all(), 'id', 'clientName')
                ],
                [
                    'attribute' => 'status',
                    'value' => function (Order $model) {
                        return OrderStatus::getName($model->status);
                    },
                    'filter' => OrderStatus::getList(),
                ],
                'comment:ntext',
                [
                    'attribute' => 'total_price',
                    'value' => function (Order $model) {
                        return $model->total_price / 100;
                    },
                ],
                'created_at',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{history} {view} {update} {delete}',
                    'buttons' => [
                        'history' => function ($url, $model, $key) {
                            return Html::a(
                                'History',
                                Url::to(['order/history', 'id' => $model->id]),
                                [
                                    'class' => 'showHistory',
                                    'data-pjax' => 0
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>

<?php

$this->registerJs(<<<JS
    let historyModal = $('#history-modal');

    $(document).on('click','.showHistory', function(e){
        e.preventDefault();
        
        var self = $(this);          
        historyModal.modal('show');
        historyModal.find('.modal-body').html('').load($(this).attr('href'), function(response, status, xhr){
            if ( status === "error" ) {
                historyModal.modal('hide');
                alert('Ошибка');
            } else {
                $("[data-toggle='popover']", historyModal).popover();
            }
        });
        return false;
    });
JS, \yii\web\View::POS_READY);
