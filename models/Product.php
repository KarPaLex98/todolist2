<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
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


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
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
        ];
    }

    public function getFreeAttributes(){
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
}
