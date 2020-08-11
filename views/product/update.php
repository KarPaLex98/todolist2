<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model_product app\models\Product */
/* @var $attributes */
/* @var $attributes_values \yii\data\ActiveDataProvider */
/* @var $searchModel \app\models\ShopAttributeValueSearch */

$this->title = 'Update Product: ' . $model_product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_product->name, 'url' => ['view', 'id' => $model_product->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model_product' => $model_product,
        'attributes' => $attributes,
        'attributes_values' => $attributes_values,
        'breadcrumbs' => $this->params['breadcrumbs'],
        "searchModel" =>$searchModel,
    ]) ?>

</div>
