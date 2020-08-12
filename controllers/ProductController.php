<?php

namespace app\controllers;

use app\models\ProductSearch;
use app\models\ShopAttributeValueSearch;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use app\httpclient\Client;
use app\models\Product;
use app\models\ShopAttributeValue;
use app\models\ShopAttribute;

use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->can('watchProducts')) {
            $searchModel = new ProductSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->can('watchProducts')) {
            $searchModel = new ShopAttributeValueSearch();
            $dataProvider = $searchModel->searchById(Yii::$app->request->queryParams, $id);
            return $this->render('view', [
                'product_model' => $this->findModel($id),
                'value_dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (\Yii::$app->user->can('addProduct')) {
            $model_product = new Product();

            if ($model_product->load(Yii::$app->request->post()) && $model_product->save()) {
                return $this->redirect(['view', 'id' => $model_product->id]);
            }

            $attributes = ShopAttribute::getModelsAttributes();

            return $this->render('create', [
                'model_product' => $model_product,
                'attributes' => $attributes,
                '$attributes_values' => null,
            ]);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $count_val = 0)
    {
        $model_product = $this->findModel($id);
        if (\Yii::$app->user->can('updateProduct', ['Product' => $model_product])) {
            $model_product = $this->findModel($id);
            if (\Yii::$app->user->can('updateOwnProduct', ['Product' => $model_product])) {

                if ($model_product->load(Yii::$app->request->post()) && $model_product->save()) {
                    return $this->redirect(['view', 'id' => $model_product->id]);
                }

                $searchModel = new ShopAttributeValueSearch();
                $dataProvider = $searchModel->searchById(Yii::$app->request->queryParams, $id);
                $attributes = ShopAttribute::getModelsAttributes();

                return $this->render('update', [
                    'model_product' => $model_product,
                    'attributes' => $attributes,
                    'attributes_values' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->user->can('deleteProduct')) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
