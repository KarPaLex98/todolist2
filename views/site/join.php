<h1>Join as</h1>
<?php
use \yii\widgets\ActiveForm;
?>
<?php
$form = ActiveForm::begin(['class'=>'form-horizontal']);
?>

<?= $form->field($model,'email')->textInput() ?>


<?= $form->field($model,'password')->passwordInput()?>

<div>
    <button type="submit" class="btn btn-primary">Join</button>
</div>

<?php
ActiveForm::end();
?>