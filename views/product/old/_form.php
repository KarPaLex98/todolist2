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
//        'max' => count($attributes),
        'max' => 10,
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
//            'max' => count($attributes),
            'max' => 10,
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


    <?php

//JS, Добавляющий пары аттрибут-значение
    $js = <<<JS
        function getCookie(name) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }
        let source_block = jQuery('.multiple-input-list__item');
        let parent = source_block.parent();
        item_block = source_block.clone();
        source_block.remove();
        let id = window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1, window.location.pathname.length);
        jQuery.post( "", { 'id': id }, function( attributes_values ) {  
            let current_block;
            // let i = 0;
            let tmp_block;
            attributes_values = JSON.parse(attributes_values);
            attributes_values.forEach((element, i, attributes_values) => {
                current_block = item_block.clone();
                tmp_block = current_block.find('.field-product-attributes_values-0-attribute');
                tmp_block.attr('class', 'field-product-attributes_values-' +  i.toString() + '-attribute form-group');
                
                tmp_block = current_block.find('#product-attributes_values-0-attribute');
                tmp_block.attr('name', 'product-attributes_values-' +  i.toString() + '-attribute');
                tmp_block.val(element['attribute']);
                
                tmp_block = current_block.find('.field-product-attributes_values-0-value');
                tmp_block.attr('class', 'field-product-attributes_values-' +  i.toString() + '-value form-group');
                
                tmp_block = current_block.find('#product-attributes_values-0-value')
                tmp_block.attr('name', 'product-attributes_values-' +  i.toString() + '-value');
                tmp_block.val(element['value']);
                
                let remove_button = current_block.find('.list-cell__button');
                remove_button.append('<div class="multiple-input-list__btn js-input-remove btn btn-danger"><i class="glyphicon glyphicon-remove"></i></div>')
                
                parent.append(current_block);
                i += 1;
            })
        });
    
        item_block.find('select').val()
    JS;
    $this->registerJs(
        $js,
        View::POS_READY,
        'attributes-values'
    );
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
