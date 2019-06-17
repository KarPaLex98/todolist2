<h1>ToDo List</h1>
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
                'value' => NULL,
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
        <?= Html::submitButton('Create task', ['name' => 'Create',
            'value' => 'Create', 'class'=>'btn brn-success']) ?>
    </div>
</div>
<?php $form = ActiveForm::end(); ?>

<script>
    // Прослойка для вызова экшена из контроллера с передачей параметра
    function To_completed(id)
    {
        window.location.replace("completed/"+id);
    }
</script>

<table class="table" style="margin-top: 2%">
    <thead>
    <tr>
        <td>Completed</td>
        <td>Title</td>
        <td>Description</td>
        <td>Time</td>
        <td>Actions</td>
    </tr>
    </thead>
    <tbody>
<!--    Вывод на страницу таблицы со списков задач-->
    <?php foreach ($model2 as $item):?>
        <tr style= "background-color: <?php if ($item->is_completed): ?> #cfe8b7" <?php else: ?> #ffe8a1" <?php endif ?>>
            <td>
                <?= Html::checkbox('', $item->is_completed, ['onclick'=>"To_completed($item->id)"]) ?>
            </td>
            <?php if ($item->is_completed): ?>
                <td><del><?=$item->title?></del></td>
                <td><del><?=$item->description?></del></td>
                <td><del><?= substr ($item->date_time,0, 16)?></del></td>
            <?php else: ?>
                <td><?=$item->title?></td>
                <td><?=$item->description?></td>
                <td><?= substr ($item->date_time,0, 16)?></td>
            <?php endif ?>
            <td>
                <a href="edit/<?=$item->id?>">Edit</a>
                \
                <a href="delete/<?=$item->id?>">Delete</a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>