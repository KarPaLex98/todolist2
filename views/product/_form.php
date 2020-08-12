<?php

use app\models\ShopAttribute;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model_product app\models\Product */
/* @var $model_value app\models\ShopAttributeValue */
/* @var $attributes array */
/* @var $attributes_values array */
/* @var $breadcrumbs array */
/* @var $searchModel \app\models\ShopAttributeValueSearch */

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model_product, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_product, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_product, 'attributes_values')->widget(MultipleInput::className(), [
        'max' => count($attributes),
        'min' => 0,
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'columns' => [
            [
                'name' => 'attribute_id',
                'type' => 'dropDownList',
                'title' => 'Attribute',
//                        'defaultValue' => 1,
                'items' => $attributes,
            ],
            [
                'name' => 'value',
                'title' => 'Value',
                'enableError' => true,
            ]
        ],
    ])
        ->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
