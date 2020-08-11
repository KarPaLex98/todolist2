<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model_product app\models\Product */
/* @var $attributes */
/* @var $attributes_values array */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model_product' => $model_product,
        'attributes' => $attributes,
        'attributes_values' => $attributes_values,
    ]) ?>

</div>
