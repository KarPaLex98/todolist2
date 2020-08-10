<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ShopAttributeValue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-attribute-value-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?//= $form->field($model, 'product_id')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'attribute_id')->textInput() ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
