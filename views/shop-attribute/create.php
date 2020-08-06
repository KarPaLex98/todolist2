<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ShopAttribute */

$this->title = 'Create Shop Attribute';
$this->params['breadcrumbs'][] = ['label' => 'Shop Attributes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-attribute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
