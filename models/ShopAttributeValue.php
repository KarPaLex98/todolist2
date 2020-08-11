<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%shop_attribute_value}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $attribute_id
 * @property string $value
 *
 * @property ShopAttribute $shop_attribute
 */
class ShopAttributeValue extends \yii\db\ActiveRecord
{
    /**
     * @var mixed|null
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_attribute_value}}';
    }

    /* Связь с моделью ShopAttribute*/
    public function getShop_attribute()
    {
        return $this->hasOne(ShopAttribute::className(), ['id' => 'attribute_id']);
    }

    public function getAttribute_title() {
        return $this->shop_attribute->title;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_title'], 'safe'],
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
            'attribute_title' => 'Attribute Title',
        ];
    }

    public function getFreeAttributes(){
        $attributes_values = [];
        $raw_data = self::find()->andWhere(['=', 'product_id', $id])->all();
        foreach ($raw_data as $element) {
            $attributes_values[] = ["attribute" => $element->attribute_id, "value" => $element->value];
        }
        return $attributes_values;
    }

    public static function getDP_ValuesByProductId($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => self::find()->andWhere(['=', 'product_id', $id]),
        ]);
        return $dataProvider;
    }

    public static function get_ValuesByProductId($id)
    {
        $raw_data = self::find()->andWhere(['=', 'product_id', $id])->all();
        foreach ($raw_data as $element) {
            $attributes_values[] = ["attribute" => $element->attribute_id, "value" => $element->value];
        }
        return $attributes_values;
    }

}
