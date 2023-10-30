<?php

/** @var yii\web\View $this */
/** @var \frontend\models\MakeOrderForm $model */

use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оставить заявку';
?>
<div class="site-index">
    <div class="body-content">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'order-form']); ?>

                <?= $form->field($model, 'client_name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'client_phone') ?>

                <?= $form->field($model, 'comment')->textarea() ?>

                <?= $form->field($model, 'good_id')->dropDownList(
                    ArrayHelper::map(\common\models\Good::find()->all(),'id','clientName')
                ) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</div>
