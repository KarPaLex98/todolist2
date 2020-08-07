<?php

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
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model_product, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_product, 'description')->textInput(['maxlength' => true]) ?>

    <?php if (is_null($attributes_values)):
        ?>
        <?= $form->field($model_product, 'attributes_values')->widget(MultipleInput::className(), [
        'max' => count($attributes),
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'columns' => [
            [
                'name' => 'attribute',
                'type' => 'dropDownList',
                'title' => 'Attribute',
                'defaultValue' => 1,
                'items' => $attributes,
            ],
            [
                'name' => 'value',
                'title' => 'Value',
                'enableError' => true,
            ]
        ]])
        ->label(false) ?>
    <?php else: ?>
        <?= $form->field($model_product, 'attributes_values')->widget(MultipleInput::className(), [
            'max' => count($attributes),
            'allowEmptyList' => false,
            'enableGuessTitle' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
            'columns' => [
                [
                    'name' => 'attribute',
                    'type' => 'dropDownList',
                    'title' => 'Attribute',
                    'defaultValue' => 1,
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

    <?php endif; ?>

    <?php $js = <<<JS
        function getCookie(name) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }
        let attributes_values = JSON.parse(getCookie('attributes_values'));
        attributes_values.forEach((element) => {
            
        })
    
        let item_block = jQuery('#multiple-input-list__item');
        let parent = item_block.parent();
        item_block = item_block.clone();
        item_block.find('select').val()
    JS; ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
