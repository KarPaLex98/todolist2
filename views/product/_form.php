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

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model_product, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_product, 'description')->textInput(['maxlength' => true]) ?>

    <?php if (is_null($attributes_values)):
        ?>
        <?= $form->field($model_product, 'attributes_values')->widget(MultipleInput::className(), [
        'max' => count($attributes),
//        'max' => 10,
        'min' => 0,
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
//            'max' => 10,
            'min' => 0,
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

        <?= GridView::widget([
            'dataProvider' => $attributes_values,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'product_id',
                [
                    'attribute' => 'attribute',
                    'label' => 'Attribute',
                    'filter' => ShopAttribute::find()->select('title, id')->indexBy('title')->column(),
                    'value' => function ($model) {
                        return ShopAttribute::findOne($model->attribute_id)->title;
                    },
                ],
                'value',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) use ($model_product) {
                            $iconName = "pencil";

                            $title = \Yii::t('yii', 'Info');

                            $id = 'info-' . $key;
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                                'id' => $id
                            ];

                            $url = Url::to(['update-value', 'product_id' => $model_product->id, 'value_id' => $model->id]);

                            //Для стилизации используем библиотеку иконок
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                            return Html::a($icon, $url, $options);
                        },
                        'delete' => function ($url, $model, $key) use ($model_product) {
                            $iconName = "remove-sign";

                            //Текст в title ссылки, что виден при наведении
                            $title = \Yii::t('yii', 'Info');

                            $id = 'info-' . $key;
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                                'id' => $id
                            ];

                            $url = Url::to(['delete-value', 'product_id' => $model_product->id, 'value_id' => $model->id]);

                            //Для стилизации используем библиотеку иконок
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                            return Html::a($icon, $url, $options);
                        },
                    ],
                ]
            ],
        ]) ?>

    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
