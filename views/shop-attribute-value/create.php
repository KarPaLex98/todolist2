<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ShopAttributeValue */

$this->title = 'Create Shop Attribute Value';
$this->params['breadcrumbs'][] = ['label' => 'Shop Attribute Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-attribute-value-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
