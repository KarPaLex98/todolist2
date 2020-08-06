<?php


namespace app\models;


use Yii;

/**
 * This is the model class for table "{{%shop_attribute}}".
 *
 * @property int $id
 * @property int $type_id
 * @property int $sort
 * @property string $alias
 * @property string $title
 *
 * @property ShopAttributeValue[] $shopAttributeValues
 */
class ShopAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_attribute}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'sort'], 'required'],
            [['type_id', 'sort'], 'integer'],
            [['alias', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'sort' => 'Sort',
            'alias' => 'Alias',
            'title' => 'Title',
        ];
    }

    public static function getModelsAttributes()
    {
        return self::find()->indexBy('id')->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopAttributeValues()
    {
        return $this->hasMany(ShopAttributeValue::className(), ['attribute_id' => 'id']);
    }
}
