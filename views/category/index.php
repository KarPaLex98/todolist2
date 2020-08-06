<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

use app\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $breadcrumbs */

$this->title = Yii::t('app', 'Categories');

if ($breadcrumbs) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
    $end_element = array_pop($breadcrumbs);
    foreach ($breadcrumbs as $breadcrumb) {
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $breadcrumb->name), 'url' => ['category/childrens/' . $breadcrumb->id]];
    }
    $this->params['breadcrumbs'][] = $end_element->name;
} else {
    $this->params['breadcrumbs'][] = 'Categories';
}

?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'options' => ['width' => '70px']
            ],
            [
                'attribute' => 'name',
                'label' => 'Name',
                'filter' => Category::find()->roots()->select('name, id, lft')->indexBy('lft')->column(),
                'value' => function ($model) {
                    if (($model->rgt - $model->lft) !== 1) {
                        return Html::a(Html::encode($model->name), Url::to(['childrens', 'id' => $model->id]));
                    }
                    return $model->name;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'tree',
                'label' => 'Root',
                'filter' => Category::find()->roots()->select('name, id')->indexBy('id')->column(),
                'value' => function ($model) {
                    if (!$model->isRoot()) {
                        return $model->parents()->one()->name;
                    }
                    return 'No Parent';
                }
            ],
//            'parent.name',
            // 'lft',
            // 'rgt',
            'depth',
//            'position',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {up} {down}',
                'buttons' => [
                    'up' => function ($url, $model, $key) {
                        $iconName = "arrow-up";

                        $title = \Yii::t('yii', 'Info');

                        $id = 'info-' . $key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'id' => $id
                        ];

                        $url = Url::to(['up-down', 'id' => $model->id, 'param' => 0]);

                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                        return Html::a($icon, $url, $options);
                    },
                    'down' => function ($url, $model, $key) {
                        $iconName = "arrow-down";

                        //Текст в title ссылки, что виден при наведении
                        $title = \Yii::t('yii', 'Info');

                        $id = 'info-' . $key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'id' => $id
                        ];

                        $url = Url::to(['up-down', 'id' => $model->id, 'param' => 1]);

                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                        return Html::a($icon, $url, $options);
                    },
                ],
            ]
        ],
    ]); ?>
