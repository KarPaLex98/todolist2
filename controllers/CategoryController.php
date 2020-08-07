<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\BaseVarDumper;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
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

    public function actions()
    {
        return [
            'nodeMove' => [
                'class' => 'klisl\nestable\NodeMoveAction',
                'modelName' => Category::className(),
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['depth' => 0])
            ->orderBy('tree');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays childrens.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */

    public function actionChildrens($id)
    {
        $parent = Category::findOne($id);

        if (!is_null($parent)) {
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->search_in_one_depth(Yii::$app->request->queryParams, $parent);

            $breadcrumbs = $parent
                ->parents()
                ->all();
            $breadcrumbs[] = $parent;

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'breadcrumbs' => $breadcrumbs,
            ]);
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Move node up/down.
     * @param int $id
     * @param int $param
     * @return mixed
     */

    public function actionUpDown($id, $param=0)
    {
        $current_node = Category::findOne($id);

        $neighbor = ($param == 0) ? $current_node->getLeftNeighbor() : $current_node->getRightNeighbor();

        // Если узел корневой
        if (is_null($current_node->getParentId())) {

            if (is_null($neighbor)) {
                return $this->redirect(['index']);
            }

            // Swap tree's indices
            Category::updateAll(['tree' => -1], ['=', 'tree', $current_node->tree]);
            Category::updateAll(['tree' => $current_node->tree], ['=', 'tree', $neighbor->tree]);
            Category::updateAll(['tree' => $neighbor->tree], ['=', 'tree', -1]);

            return $this->redirect(['index']);
        }
        //Обычный узел
        if (!is_null($neighbor)) {
            if ($param == 0) {
                $current_node->insertBefore($neighbor);
            }else{
                $neighbor->insertBefore($current_node);
            }
        }
        return $this->redirect(['childrens', 'id' => $current_node->getParentId()]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category_id = 0)
    {
        $model = new Category([
            '' => $category_id
        ]);

        if (!empty($post = Yii::$app->request->post('Category'))) {
            $model->name = $post['name'];
            $parent_id = $post['parentId'];

            if (empty($parent_id)) {
                $model->makeRoot();
            } else {
                $parent = Category::findOne($parent_id);
                $model->appendTo($parent);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'category_id' => $category_id,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!empty(Yii::$app->request->post('Category'))) {
            $post = Yii::$app->request->post('Category');

            $model->name = $post['name'];
            $parent_id = $post['parentId'];

            if ($model->save()) {
                if (empty($parent_id)) {
                    if (!$model->isRoot()) {
                        $model->makeRoot();
                    }
                } else if ($model->id != $parent_id) {
                    $parent = Category::findOne($parent_id);
                    $model->appendTo($parent);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->isRoot()) {
            $model->deleteWithChildren();
        } else {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
