<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ShopAttributeValue */
/* @var $product_name string */
/* @var $product_id integer */

$this->title = 'Update Shop Attribute Value: ' . $model->attribute_title;

$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product_name, 'url' => ['view', 'id' => $product_id]];
$this->params['breadcrumbs'][] = ['label' => 'Update', 'url' => ['update', 'id' => $product_id]];
$this->params['breadcrumbs'][] = 'Update Value';
?>
<div class="shop-attribute-value-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
