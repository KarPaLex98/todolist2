<?php

namespace app\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimeStampBehavior::className(),
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'tree' => Yii::t('app', 'Tree'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Get parent's ID
     * @return \yii\db\ActiveQuery
     */
    public function getParentId()
    {
        $parent = $this->parent;
        return $parent ? $parent->id : null;
    }

    /**
     * Get parent's node
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->parents(1)->one();
    }

    /**
     * Get a full tree as a list, except the node and its children
     * @param integer $node_id node's ID
     * @return array array of node
     */
    public static function getTree($node_id = 0)
    {
        // don't include children and the node
        $children = [];

        if (!empty($node_id))
            $children = array_merge(
                self::findOne($node_id)->children()->column(),
                [$node_id]
            );

        $rows = self::find()->
        select('id, name, depth')->
        where(['NOT IN', 'id', $children])->
        orderBy('tree, lft')->
        all();

        $return = [];
        foreach ($rows as $row)
            $return[$row->id] = str_repeat('-', $row->depth) . ' ' . $row->name;

        return $return;
    }

    public function getLeftNeighbor()
    {
        // Если узел корневой
        if (is_null($parent = $this->getParent())) {
            return self::find()
                ->andWhere(['<', 'tree', $this->tree])
                ->andWhere(['=', 'depth', 0])
                ->orderBy(['tree' => SORT_DESC])
                ->one();
        }
        //Обычный узел
        return self::find()
            ->andWhere(['=', 'tree', $parent->tree])
            ->andWhere(['=', 'depth', $parent->depth + 1])
            ->andWhere(['>', 'lft', $parent->lft])
            ->andWhere(['<', 'rgt', $parent->rgt])
            ->andWhere(['<', 'lft', $this->lft])
            ->orderBy(['lft' => SORT_DESC])
            ->one();
    }

    public function getRightNeighbor()
    {
        // Если узел корневой
        if (is_null($parent = $this->getParent())) {
            return self::find()
                ->andWhere(['>', 'tree', $this->tree])
                ->andWhere(['=', 'depth', 0])
                ->orderBy(['tree' => SORT_ASC])
                ->one();
        }
        //Обычный узел
        return self::find()
            ->andWhere(['=', 'tree', $parent->tree])
            ->andWhere(['=', 'depth', $parent->depth + 1])
            ->andWhere(['>', 'lft', $parent->lft])
            ->andWhere(['<', 'rgt', $parent->rgt])
            ->andWhere(['>', 'rgt', $this->rgt])
            ->orderBy(['rgt' => SORT_ASC])
            ->one();
    }
}
