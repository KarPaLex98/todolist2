<h1>Log in as</h1>
<?php
use \yii\widgets\ActiveForm;
?>
<?php
$form = ActiveForm::begin(['class'=>'form-horizontal']);
?>

<?= $form->field($model,'email')->textInput() ?>


<?= $form->field($model,'password')->passwordInput()?>

<div>
    <button type="submit" class="btn btn-success">Login</button>
</div>

<?php
ActiveForm::end();
?>