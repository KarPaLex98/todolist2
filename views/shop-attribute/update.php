<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ShopAttribute */

$this->title = 'Update Shop Attribute: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Shop Attributes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-attribute-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
