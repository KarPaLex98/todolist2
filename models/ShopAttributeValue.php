<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shop_attribute_value}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $attribute_id
 * @property string $value
 *
 * @property ShopAttribute $attribute0
 */
class ShopAttributeValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'attribute_id', 'value'], 'required'],
            [['product_id', 'attribute_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'attribute_id' => 'Attribute ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute0()
    {
        return $this->hasOne(ShopAttribute::className(), ['id' => 'attribute_id']);
    }
}
