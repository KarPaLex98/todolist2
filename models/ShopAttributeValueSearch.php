<?php

namespace app\models;

use app\models\ShopAttributeValue;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShopAttributeValueSearch represents the model behind the search form of `app\models\ShopAttributeValue`.
 */
class ShopAttributeValueSearch extends ShopAttributeValue
{

    /* вычисляемый атрибут */
    public $attribute_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'attribute_id'], 'integer'],
            [['value', 'attribute_title'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ShopAttributeValue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'product_id',
                'attribute_title' => [
                    'asc' => ['shop_attribute.title' => SORT_ASC],
                    'desc' => ['shop_attribute.title' => SORT_DESC],
                    'label' => 'Attribute Title'
                ],
                'value',
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'attribute_id' => $this->attribute_id,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);


        $query->joinWith(['shop_attribute' => function ($q) {
            $q->where('shop_attribute.title LIKE "%' . $this->attribute_title . '%"');
        }]);

        return $dataProvider;
    }

    public function searchById($params, $id)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['=', 'product_id', $id]);

        return $dataProvider;
    }


}
