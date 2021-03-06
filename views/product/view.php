<?php

use app\models\ShopAttribute;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $product_model app\models\Product */
/* @var $value_dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\models\ShopAttributeValueSearch */

$this->title = $product_model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $product_model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $product_model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $product_model,
        'attributes' => [
            'id',
            'name',
            'description',
        ],
    ]) ?>

<!--    --><?//= ListView::widget([
//        'dataProvider' => $value_dataProvider,
//        'itemView' => '_item_value',
//    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $value_dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'product_id',
            'attribute_title',
//            [
//                'attribute' => 'shop_attribute',
//                'label' => 'Shop Attribute',
////                    'filter' => ShopAttribute::find()->select('title')->indexBy('title')->column(),
//                'value' => function ($model) {
////                    return ShopAttribute::findOne($model->attribute_id)->title;
//                },
//            ],
            'value',
        ],
    ]) ?>

</div>
