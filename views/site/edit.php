<h1>Edit task</h1>
<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'title')->textInput() ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'description')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'date_time')->widget(DateTimePicker::className(),[
                'name' => 'dp_5',
                'type' => DateTimePicker::TYPE_BUTTON,
                'value' => $model->date_time,
                'layout' => '{picker} {remove} {input}',
                'options' => [
                    'type' => 'text',
                    'readonly' => true,
                    'class' => 'text-muted small',
                    'style' => 'border:none;background:none'
                ],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd hh:ii',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= Html::submitButton('Edit', ['name' => 'Edit',
                'value' => 'Create', 'class'=>'btn brn-success']) ?>
        </div>
    </div>
<?php $form = ActiveForm::end(); ?>