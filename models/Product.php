<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $created_by
 * @property mixed|null attributes_values
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /* Связь с моделью ShopAttributeValue*/
    public function getAttributes_values()
    {
        return $this->hasMany(ShopAttributeValue::className(), ['product_id' => 'id']);
    }

    public function setAttributes_values($value)
    {
        if ($this->isNewRecord){
            $this->save();
        }
        $attribute_ids = [];
        $attribute_value_model = ShopAttributeValue::find()
            ->andWhere(['product_id' => $this->id])
            ->indexBy('attribute_id')
            ->all();
        foreach ($value as $elem){
            $attribute_ids[] = $elem['attribute_id'];
            if (isset($attribute_value_model[$elem['attribute_id']])){
                $attribute_value_model[$elem['attribute_id']]->value = $elem['value'];
                $attribute_value_model[$elem['attribute_id']]->save();
            } else {
                $attribute_value_model = new ShopAttributeValue();
                $attribute_value_model->product_id = $this->id;
                $attribute_value_model->value = $elem['value'];
                $attribute_value_model->attribute_id = $elem['attribute_id'];
                $attribute_value_model->save();
            }
        }
        if (!$this->isNewRecord) {
            ShopAttributeValue::deleteAll([
                'AND',
                'product_id' => $this->id,
                ['not in', 'attribute_id', $attribute_ids],
            ]);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['created_by'], 'integer'],
            [['attributes_values'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'attributes_values' => 'Shop attribute values'
        ];
    }

    public function getFreeAttributes()
    {
        $attributes_values = [];
        $raw_data = ShopAttributeValue::find()->andWhere(['=', 'product_id', $this->id])->all();
        $attr_i = [];

        foreach ($raw_data as $element) {
            $attr_i[] = $element->attribute_id;
        }

        $raw_data = ShopAttribute::find()->where(['not in', 'id', $attr_i])->all();

        $attributes = [];
        $ids = [];
        $titles = [];
        foreach ($raw_data as $attribute) {
            $ids[] = $attribute->id;
            $titles[] = $attribute->title;
        }
        $attributes = array_combine($ids, $titles);
        return $attributes;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_by = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }
}
