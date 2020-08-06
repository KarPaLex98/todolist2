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
     */

    public function actionChildrens($id)
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $parent = Category::findOne($id);
        $breadcrumbs = $parent
            ->parents()
            ->all();
        array_push($all_parents, $parent);
        $breadcrumbs = [];
        $breadcrumbs[] = $parent;
        foreach ($all_parents as $node) {
            array_push($breadcrumbs, [$node->name, $node->id]);
        }
        $dataProvider->query
            ->andWhere(['=', 'depth', $parent->depth + 1])
            ->andWhere(['>', 'lft', $parent->lft])
            ->andWhere(['<', 'rgt', $parent->rgt])
            ->andWhere(['=', 'tree', $parent->tree]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Move node up.
     * @param integer $id
     * @return mixed
     */

    public function actionUp($id)
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $current_node = Category::findOne($id);
        // Если узел корневой
        if (is_null($current_node->getParentId())) {
            $neighbor = Category::find()
                ->andWhere(['<', 'tree', $current_node->tree])
                ->andWhere(['=', 'depth', 0])
                ->orderBy(['tree' => SORT_DESC])
                ->one();
            if (is_null($neighbor)) {
                return $this->redirect(['index']);
            }
//            $tree_n = Category::find()->andWhere(['=', 'tree', $neighbor->tree])->all();
//            $tree_c = Category::find()->andWhere(['=', 'tree', $current_node->tree])->all();
//            foreach ($tree_n as $elem) {
//                $elem->tree = $current_node->tree;
//                $elem->save();
//            }
            Category::updateAll(
                ['tree' => $neighbor->tree],
                ['tree' => $current_node->tree],
            );
            return $this->redirect(['index']);
        }
        //Обычный узел
        $neighbors = $current_node->getParent()->children(1)->all();
        if (count($neighbors) != 0) {
            foreach ($neighbors as $neighbor) {
                if ($current_node->lft - 1 == $neighbor->rgt) {
                    $current_node->insertBefore($neighbor);
                    break;
                }
            }
        }
        return $this->redirect(['childrens', 'id' => $current_node->getParentId()]);
    }

    /**
     * Move node down.
     * @param integer $id
     * @return mixed
     */

    public function actionDown($id)
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $current_node = Category::findOne($id);
        // Если узел корневой
        if (is_null($current_node->getParentId())) {
            $neighbor = Category::find()->andWhere(['>', 'tree', $current_node->tree])
                ->andWhere(['=', 'depth', 0])
                ->orderBy(['tree' => SORT_ASC])
                ->one();
            if (is_null($neighbor)) {
                return $this->redirect(['index']);
            }
            $tree_n = Category::find()->andWhere(['=', 'tree', $neighbor->tree])->all();
            $tree_c = Category::find()->andWhere(['=', 'tree', $current_node->tree])->all();
            foreach ($tree_n as $elem) {
                $elem->tree = $current_node->tree;
                $elem->save();
            }
            foreach ($tree_c as $elem) {
                $elem->tree = $neighbor->tree;
                $elem->save();
            }
            return $this->redirect(['index']);
        }
        //Обычный узел
        $neighbors = $current_node->getParent()->children(1)->all();
        if (count($neighbors) != 0) {
            foreach ($neighbors as $neighbor) {
                if ($current_node->rgt + 1 == $neighbor->lft) {
                    $neighbor->insertBefore($current_node);
                    break;
                }
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
    public function actionCreate()
    {
        $model = new Category();

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
                } else // move node to other root
                {
                    if ($model->id != $parent_id) {
                        $parent = Category::findOne($parent_id);
                        $model->appendTo($parent);
                    }
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
