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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-value', 'delete-value',],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['watchAdminsPages'],
                    ],
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
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ShopAttributeValueSearch();
        $dataProvider = $searchModel->searchById(Yii::$app->request->queryParams, $id);
        return $this->render('view', [
            'product_model' => $this->findModel($id),
            'value_dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model_product = new Product();

        if (!empty($post = Yii::$app->request->post('Product'))) {
            $model_product->name = $post['name'];
            $model_product->description = $post['description'];
            $model_product->save();
            $product_id = Yii::$app->db->getLastInsertID();
            if (isset($post['attributes_values']) && ($post['attributes_values']) !== '') {
                $attributes_values = $post['attributes_values'];
                foreach ($attributes_values as $elem) {
                    $model_value = new ShopAttributeValue();
                    $model_value->product_id = $product_id;
                    $model_value->attribute_id = $elem['attribute'];
                    $model_value->value = $elem['value'];
                    $model_value->save();
                }
            }
            return $this->redirect(['view', 'id' => $model_product->id]);
        }

        $model_value = new ShopAttributeValue();
        $attributes = ShopAttribute::getModelsAttributes();

        return $this->render('create', [
            'model_product' => $model_product,
            'attributes' => $attributes,
            '$attributes_values' => null,
        ]);
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

        if (!empty($post = Yii::$app->request->post('Product'))) {
            $model_product->name = $post['name'];
            $model_product->description = $post['description'];
            $model_product->save();
            $product_id = $model_product->id;
            if (isset($post['attributes_values']) && ($post['attributes_values']) !== '') {
                $attributes_values = $post['attributes_values'];
                foreach ($attributes_values as $elem) {
                    $model_value = new ShopAttributeValue();
                    $model_value->product_id = $product_id;
                    $model_value->attribute_id = $elem['attribute'];
                    $model_value->value = $elem['value'];
                    $model_value->save();
                }
            }
            return $this->redirect(['view', 'id' => $model_product->id]);
        }

        if (!empty($post = Yii::$app->request->post('id'))) {
            $attributes_values = ShopAttributeValue::get_ValuesByProductId($post);
            return json_encode($attributes_values);
        }

        $searchModel = new ShopAttributeValueSearch();
        $dataProvider = $searchModel->searchById(Yii::$app->request->queryParams, $id);

        $attributes = $model_product->getFreeAttributes();

        return $this->render('update', [
            'model_product' => $model_product,
            'attributes' => $attributes,
            'attributes_values' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Updates an existing ShopAttributeValue model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateValue($product_id, $value_id)
    {
        $model = ShopAttributeValue::findOne($value_id);

        $product = Product::findOne($product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $product->id]);
        }

        return $this->render('shop-attribute-value/update', [
            'model' => $model,
            'product_id' => $product->id,
            'product_name' => $product->name,
        ]);
    }

    /**
     * Deletes an existing ShopAttributeValue model.
     * If deletion is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteValue($product_id, $value_id)
    {
        ShopAttributeValue::findOne($value_id)->delete();

        return $this->redirect(['update', 'id' => $product_id]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
