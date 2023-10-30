<?php

use backend\modules\users\models\UserSearch;
use common\enums\UserStatus;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->user->identity->role === \common\enums\Role::ADMIN):
        ?>
        <p>
            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'password_hash',
                'visible' => Yii::$app->user->identity->role === \common\enums\Role::ADMIN
            ],
            'email:email',
            [
                'attribute' => 'role',
                'value' => function (User $model) {
                    return \common\enums\Role::getName($model->role);
                },
                'filter' => \common\enums\Role::getList(),
            ],
            [
                'attribute' => 'status',
                'value' => function (User $model) {
                    return UserStatus::getName($model->status);
                },
                'filter' => UserStatus::getList(),
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'visible' => Yii::$app->user->identity->role === \common\enums\Role::ADMIN
            ],
        ],
    ]); ?>


</div>