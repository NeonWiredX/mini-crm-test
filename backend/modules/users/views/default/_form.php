<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'email') ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password') ?>
    <?php endif; ?>
    <?= $form->field($model, 'role')->dropDownList(\common\enums\Role::getList()) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\enums\UserStatus::getList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>