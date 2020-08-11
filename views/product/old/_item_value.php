<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/* @var $model app\models\ShopAttributeValue */
?>

<div class="item_value">
    <h2><?= $model->attribute_id ?></h2>

    <?= HtmlPurifier::process($model->value) ?>
</div>