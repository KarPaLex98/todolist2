<h1>Groups</h1>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<table class="table" style="margin-top: 2%">
    <thead>
    <tr>
        <td>Number</td>
        <td>Photo</td>
        <td>Group's name</td>
    </tr>
    </thead>
    <tbody>
    <!--    Вывод на страницу таблицы со списков задач-->
    <?php foreach ($groups as $i => $group): ?>
        <tr style="background-color: #ffe8a1">
            <td><?= $i + 1 ?></td>
            <td><img src="<?= $group->photo_100 ?>" alt="альтернативный текст"></td>
            <td><?= $group->name?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>